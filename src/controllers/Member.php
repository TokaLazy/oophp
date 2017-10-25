<?php

class Member
{
    // ID unique du membre
    protected $id;

    // Pseudo du membre
    protected $pseudo;

    // Password du membre
    protected $password;

    // Email du membre
    protected $email;

    // Lieu d'habitation du membre
    protected $localisation;

    // Site du membre
    protected $siteweb;

    // Dernière visite du membre
    protected $visite;

    // Token du membre
    protected $token;

    // Avatar du membre
    protected $avatar;

    // Signature / Description du membre
    protected $signature;

    // Date d'inscription du membre
    protected $inscrit;

    // Niveau des droits du membre
    protected $rang;

    // Nombre de posts publié par le membre
    protected $posts;

    protected $reset;
    protected $reset_at;
    protected $cookiee;


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

    public function generateToken(Int $size = 60)
    {
        $code = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $token = substr(str_shuffle(str_repeat($code, $size)), 0, $size);

        $this->setToken($token);
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

    public function setPseudo($value)
    {
        $this->pseudo = trim($value);
    }

    public function setEmail($value)
    {
        $this->email = trim($value);
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function setToken($value)
    {
        $this->token = $value;
    }

    public function setAvatar($value)
    {
        $this->avatar = $value;
    }


    /**
     * Getters
     */

    public function id()
    {
        return $this->id;
    }

    public function pseudo()
    {
        return $this->pseudo;
    }

    public function email()
    {
        return $this->email;
    }

    public function password()
    {
        return $this->password;
    }

    public function token()
    {
        return $this->token;
    }

    public function avatar()
    {
        return $this->avatar;
    }
}
