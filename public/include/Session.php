<?php

require_once(MODEL.'ModelMember.php');

class Session
{
    // public function __construct()
    // {
    //     session_start();
    // }

    // public static function destroy()
    // {
    //     setcookie('souvenir', null, time() - 1);
    //     unset($_COOKIE['souvenir']);
    //     Session::disable(['pseudo', 'avatar', 'rank', 'id']);
    // }

    // public static function existAttr($key)
    // {
    //     return isset($_SESSION[$key]) && !empty($_SESSION[$key]);
    // }

    public function setAttr($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // public static function setFlash($state, $value)
    // {
    //     $_SESSION['flash'][$state][] = $value;
    // }

    // public static function setAriane(Array $message)
    // {
    //     $_SESSION['ariane'] = $message;
    // }

    public static function getAttr($key)
    {
        if (Session::existAttr($key)) {
            return $_SESSION[$key];
        }

        throw new Exception("L'attribut '$key' est absent de la session");
    }

    public static function disable($key)
    {
        if (is_array($key)) {
            foreach ($key as $value) {
                unset($_SESSION[$value]);
            }
        } else {
            unset($_SESSION[$key]);
        }
    }

    public static function setUser(Member $member)
    {
        $_SESSION['pseudo'] = $member->pseudo();
        $_SESSION['avatar'] = $member->avatar();
        $_SESSION['rank'] = $member->rank();
        $_SESSION['id'] = $member->id();
        $_SESSION['flash']['success'][] = 'Bienvenue '.$member->pseudo().', vous êtes maintenant connecté !';
    }

    public function autoConnect()
    {
        if (isset($_COOKIE['souvenir']) && !$this->existAttr('id')) {
            $remind = explode('==', $_COOKIE['souvenir']);
            $userId = $remind[0];
            $cookie = $remind[1];

            if (Member::check('cookie', $cookie, $userId)) {
                $member = Member::connexion('id', $userId);
                $this->setUser($member);
            }
        }
    }
}
