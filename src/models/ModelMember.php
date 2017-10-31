<?php

require_once(INC.'Connection.php');
require_once(INC.'Session.php');

class Member
{
    private $COOKIE_TIME = 60 * 60 * 24 * 7;
    private $COOKIE_CODE = 'MALNUX667';

    protected $id;
    protected $rang;
    protected $pseudo;
    protected $password;
    protected $email;
    protected $avatar;
    protected $siteweb;
    protected $localisation;
    protected $signature;
    protected $inscrit;
    protected $visite;
    protected $token;
    protected $cookie;

    protected $posts;
    protected $reset;
    protected $reset_at;


    public function __construct($member)
    {
        foreach ($member as $key => $value) {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public static function init(array $data)
    {
        $member = new Member($data);
        $member->generateToken();

        if (!is_string($member->avatar())) {
            $member->generateAvatar($member);
        }

        return $member;
    }

    public static function exist(String $cell, String $value)
    {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM membres WHERE $cell = :$cell");
        $req->bindValue(":$cell", $value);
        $req->execute();

        return $req->fetchColumn();
    }

    public static function check(String $cell, $value, $id)
    {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM membres WHERE id = :id AND $cell = :$cell");
        $req->bindValue(":id", $id);
        $req->bindValue(":$cell", $value);
        $req->execute();

        return $req->fetchColumn();
    }

    public static function getId(Member $member)
    {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT id FROM membres WHERE pseudo = :pseudo");
        $req->bindValue(":pseudo", $member->pseudo(), PDO::PARAM_STR);
        $req->execute();

        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public static function connexion(String $cell, $value)
    {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM membres WHERE $cell = :$cell");
        $req->bindValue(":$cell", $value);
        $req->execute();

        return new Member($req->fetch(PDO::FETCH_ASSOC));
    }

    public static function insert(Member $member)
    {
        $db = Db::getInstance();
        $req = $db->prepare("INSERT INTO membres
        (pseudo, password, email, avatar, token)
        VALUES (:pseudo, :password, :email, :avatar, :token)");
        $req->bindValue(':pseudo', $member->pseudo(), PDO::PARAM_STR);
        $req->bindValue(':password', $member->password(), PDO::PARAM_STR);
        $req->bindValue(':email', $member->email(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $member->avatar(), PDO::PARAM_STR);
        $req->bindValue(':token', $member->token(), PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

    public function update(Member $member)
    {
        $db = Db::getInstance();
        $req = $db->prepare("UPDATE membres
        SET pseudo = :pseudo, password = :password, email = :email, avatar = :avatar, siteweb = :siteweb, localisation = :localisation, signature = :signature, token = :token, cookie = :cookie, visite = NOW()
        WHERE id = :id");
        $req->bindValue(':pseudo', $member->pseudo(), PDO::PARAM_STR);
        $req->bindValue(':password', $member->password(), PDO::PARAM_STR);
        $req->bindValue(':email', $member->email(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $member->avatar(), PDO::PARAM_STR);
        $req->bindValue(':siteweb', $member->siteweb(), PDO::PARAM_STR);
        $req->bindValue(':localisation', $member->localisation(), PDO::PARAM_STR);
        $req->bindValue(':signature', $member->signature(), PDO::PARAM_STR);
        $req->bindValue(':token', $member->token(), PDO::PARAM_STR);
        $req->bindValue(':cookie', $member->cookie(), PDO::PARAM_STR);
        $req->bindValue(':id', $member->id(), PDO::PARAM_INT);
        $req->execute();
        $req->closeCursor();
    }

    public static function sendEmail(String $message)
    {
        $title = 'Inscription sur le Site du Savoir';
        $header = 'sitedusavoir.com';

        mail($member->email(), $title, $message, $header);
    }

    public function randomKey(Int $size = 60)
    {
        $baseCode = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $code = substr(str_shuffle(str_repeat($baseCode, $size)), 0, $size);

        return $code;
    }

    public function generateToken(Int $size = 60)
    {
        $token = $this->randomKey($size);

        $this->setToken($token);
    }

    public function generateCookie($id)
    {
        $cookieCode = sha1($this->randomKey(60).$this->COOKIE_CODE);

        $this->cookie = $cookieCode;
        $this->update($this);
        setcookie('souvenir', $this->id.'=='.$cookieCode, time() + $this->COOKIE_TIME);
    }

    public function generateAvatar($member, $blocks = 5, $size = 100)
    {
        if (empty($member->avatar()['name'])) {
            $togenerate  = ceil($blocks / 2);

            $hashsize = $togenerate * $blocks ;

            $hash = md5($member->pseudo());

            $hash = str_pad($hash, $hashsize, $hash);

            $blockssize = $size / $blocks ;

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

            $avatarName = $member->pseudo().'.png';

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


    /**
     * Setters
     */

    public function setId($value)
    {
        $this->id = intval($value);
    }

    public function setRang($value)
    {
        $this->rang = $value;
    }

    public function setPseudo($value)
    {
        $this->pseudo = trim($value);
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function setEmail($value)
    {
        $this->email = trim($value);
    }

    public function setAvatar($value)
    {
        $this->avatar = $value;

        if (is_array($value)) {
            $this->generateAvatar($this);
        }
    }

    public function setSiteweb($value)
    {
        $this->siteweb = $value;
    }

    public function setLocalisation($value)
    {
        $this->localisation = trim($value);
    }

    public function setSignature($value)
    {
        $this->signature = trim($value);
    }

    public function setToken($value)
    {
        $this->token = $value;
    }

    public function setCookie($value)
    {
        $this->cookie = $value;
    }


    /**
     * Getters
     */

    public function id()
    {
        return $this->id;
    }

    public function rang()
    {
        return $this->rang;
    }

    public function pseudo()
    {
        return $this->pseudo;
    }

    public function password()
    {
        return $this->password;
    }

    public function email()
    {
        return $this->email;
    }

    public function avatar()
    {
        return $this->avatar;
    }

    public function siteweb()
    {
        return $this->siteweb;
    }

    public function localisation()
    {
        return $this->localisation;
    }

    public function signature()
    {
        return $this->signature;
    }

    public function inscrit()
    {
        return $this->inscrit;
    }

    public function visite()
    {
        return $this->visite;
    }

    public function token()
    {
        return $this->token;
    }

    public function cookie()
    {
        return $this->cookie;
    }
}
