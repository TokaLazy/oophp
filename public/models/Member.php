<?php

namespace Models;

use PDO;
use Vendor\Session;
use Vendor\Database;

class Member extends Database {

    const COOKIE_CODE = 'MALNUX667';
    const COOKIE_TIME = 60 * 60 * 24 * 7 * 30;

    public $data = [];
    public $table = 'members';

    public function __construct(array $member) {

        return $this->hydrate($member);

    }

    public function hydrate(array $member) {

        foreach ($member as $key => $value) {

            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {

                $this->$method($value);

            }

        }

        return $this;

    }

    public static function init(array $data) {

        $member = new Member($data);

        $member->generateToken();

        if (!is_string($member->getAvatar())) {

            $member->generateAvatar($member);

        }

        return $member;

    }

    private function generateToken() {

        $token = $this->getRandomString(60);

        $this->setToken($token);

    }

    public function generateCookie($id) {

        $cookieCode = sha1( $this->getRandomString(60) . self::COOKIE_CODE);

        $this->setCookie($cookieCode);

        $this->update($this);

        setcookie('souvenir', $this->getId() . '==' . $cookieCode, time() + self::COOKIE_TIME);

    }

    public function generateAvatar(Member $member) {

        $blocks = 5;
        $size = 100;

        if (empty($member->getAvatar()['name'])) {

            $togenerate  = ceil($blocks / 2);

            $hashsize = $togenerate * $blocks;

            $hash = md5($member->getPseudo());

            $hash = str_pad($hash, $hashsize, $hash);

            $blockssize = $size / $blocks;

            $color = substr($hash, 0, 6);

            $image = imagecreate($size, $size);

            $background = imagecolorallocate($image, 255, 255, 255);

            $color = imagecolorallocate($image, hexdec(substr($color, 0, 2)), hexdec(substr($color, 2, 2)), hexdec(substr($color, 4, 2)));

            for ($x = 0; $x < $blocks; $x++) {

                for ($y = 0; $y < $blocks; $y++) {

                    if ($x < $togenerate) {

                        $pixel = hexdec($hash[$x * $blocks + $y]) % 2 == 0;

                    } else {

                        $pixel = hexdec($hash[($blocks - 1 - $x) * $blocks  + $y]) % 2 == 0;

                    }

                    $pixelcolor = $background;

                    if ($pixel) {

                        $pixelcolor = $color;

                    }

                    imagefilledrectangle($image, $x * $blockssize, $y*$blockssize, ($x+1)*$blockssize, ($y+1)*$blockssize, $pixelcolor);
                }
            }

            $avatarName = $member->getPseudo().'.png';

            imagepng($image, './'.IMG.'avatar/'.$avatarName);

            $this->setAvatar($avatarName);

        } else {

            $avatarName = $member->pseudo().'.'.strtolower(pathinfo($member->avatar()['name'], PATHINFO_EXTENSION));
            $targetFile = './'.IMG.'avatar/'.$avatarName;

            if (!move_uploaded_file($member->avatar()['tmp_name'], $targetFile)) {

                $errs[] = "Il y a eu une erreur lors du chargement du fichier.";

            }

            $this->setAvatar($avatarName);

        }

    }

    private function getRandomString(int $size) : string {

        $char = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $code = substr( str_shuffle( str_repeat($char, $size)), 0, $size);

        return $code;

    }

    public function insert() {

        $table = $this->table;

        $req = self::getInstance()
            ->prepare("INSERT INTO $table
            (pseudo, password, email, avatar, token)
            VALUES (:pseudo, :password, :email, :avatar, :token)
        ");

        $req->bindValue(':pseudo', $this->getPseudo(), PDO::PARAM_STR);
        $req->bindValue(':password', $this->getPassword(), PDO::PARAM_STR);
        $req->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $this->getAvatar(), PDO::PARAM_STR);
        $req->bindValue(':token', $this->getToken(), PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();

        $this->connexion($this);

    }

    public function update() {

        $table = $this->table;

        $req = self::getInstance()
            ->prepare("UPDATE $table
            SET pseudo = :pseudo,
                password = :password,
                email = :email,
                avatar = :avatar,
                website = :website,
                localisation = :localisation,
                signature = :signature,
                token = :token,
                cookie = :cookie,
                visite = NOW()
            WHERE id = :id
        ");
        $req->bindValue(':pseudo', $this->getPseudo(), PDO::PARAM_STR);
        $req->bindValue(':password', $this->getPassword(), PDO::PARAM_STR);
        $req->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $this->getAvatar(), PDO::PARAM_STR);
        $req->bindValue(':website', $this->getWebsite(), PDO::PARAM_STR);
        $req->bindValue(':localisation', $this->getLocalisation(), PDO::PARAM_STR);
        $req->bindValue(':signature', $this->getSignature(), PDO::PARAM_STR);
        $req->bindValue(':token', $this->getToken(), PDO::PARAM_STR);
        $req->bindValue(':cookie', $this->getCookie(), PDO::PARAM_STR);
        $req->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $req->execute();
        $req->closeCursor();

        return $this;

    }

    public function connexion() {

        $table = $this->table;

        if ($this->getId() !== null && !empty($this->getId())) {

            $cell = 'id';
            $value = $this->getId();

        } else {

            $cell = 'pseudo';
            $value = $this->getPseudo();

        }

        $req = self::getInstance()
            ->prepare("SELECT *
            FROM $table
            WHERE $cell = :$cell
        ");

        $req->bindValue(":$cell", $value);
        $req->execute();

        $data = $req->fetch(PDO::FETCH_ASSOC);

        $req->closeCursor();

        return $data ? $this->hydrate($data) : false;

    }

    public static function verify(string $cellFirst, string $valueFirst, string $cellSecond, string $valueSecond) {

        $table = self::$table;

        $req = self::getInstance()
            ->prepare("SELECT *
            FROM $table
            WHERE $cellFirst = :$cellFirst
            AND $cellSecond = :$cellSecond
        ");

        $req->bindValue(":$cellFirst", $valueFirst);
        $req->bindValue(":$cellSecond", $valueSecond);
        $req->execute();

        $data = $req->fetchColumn();

        $req->closeCursor();

        return $data;

    }

    public function setId($value) {

        $this->data['id'] = intval($value);

    }

    public function getId() {

        return $this->data['id'];

    }

    public function setRank($value) {

        $this->data['rank'] = $value;

    }

    public function getRank() {

        return $this->data['rank'];

    }

    public function setPseudo($value) {

        $this->data['pseudo'] = $value;

    }

    public function getPseudo() {

        return $this->data['pseudo'];

    }

    public function setPassword($value) {

        $this->data['password'] = trim($value);

    }

    public function getPassword() {

        return $this->data['password'];

    }

    public function setEmail($value) {

        $this->data['email'] = trim($value);

    }

    public function getEmail() {

        return $this->data['email'];

    }

    public function setAvatar($value) {

        $this->data['avatar'] = $value;

    }

    public function getAvatar() {

        return $this->data['avatar'];

    }

    public function setWebsite($value) {

        $this->data['website'] = trim($value);

    }

    public function getWebsite() {

        return $this->data['website'];

    }

    public function setLocalisation($value) {

        $this->data['localisation'] = trim($value);

    }

    public function getLocalisation() {

        return $this->data['localisation'];

    }

    public function setSignature($value) {

        $this->data['signature'] = trim($value);

    }

    public function getSignature() {

        return $this->data['signature'];

    }

    public function setRegistration($value) {

        $this->data['registration'] = trim($value);

    }

    public function getRegistration() {

        return $this->data['registration'];

    }

    public function setVisite($value) {

        $this->data['visite'] = trim($value);

    }

    public function getVisite() {

        return $this->data['visite'];

    }

    public function setToken($value) {

        $this->data['token'] = $value;

    }

    public function getToken() {

        return $this->data['token'];

    }

    public function setCookie($value) {

        $this->data['cookie'] = trim($value);

    }

    public function getCookie() {

        return $this->data['cookie'];

    }
}
