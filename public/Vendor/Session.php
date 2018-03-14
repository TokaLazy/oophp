<?php

namespace Vendor;

use Models\Member;

class Session {

    public static $title;
    public static $folder;
    public static $page;

    public static function start() {

        session_start();

    }

    public static function automaticConnection() {

        if (isset($_COOKIE['souvenir']) && self::attrExists('user')) {

            $explode = explode('==', $_COOKIE['souvenir']);
            $userId = $explode[0];
            $cookie = $explode[1];

            if (Member::verify('id', $userId, 'cookie', $cookie)) {

                $member = Member::init(['id' => $userId])->connexion();

            }

        }

    }

    public static function destroy() {

        setcookie('souvenir', null, time() - 1);
        unset($_COOKIE['souvenir']);

        session_destroy();
        session_unset();

        if (!isset($_SESSION)) {

            return;

        }

        foreach ($_SESSION as $key => $value) {

            if (array_key_exists($key, $_SESSION)) {

                unset($_SESSION[$key]);

            }

        }

    }

    public static function unset(string $key) {

        if (array_key_exists($key, $_SESSION)) {

            unset($_SESSION[$key]);

        }

    }

    public static function attrExists(string $key) {

        return isset($_SESSION[$key]) && !empty($_SESSION[$key]);

    }

    public static function setBreadcrumb(array $crumb) {

        $_SESSION['breadcrumb'] = $crumb;

    }

    public static function setBanner(string $state, string $text) {

        $_SESSION['banner'][$state][] = $text;

    }

    public static function setUser(Member $member) {

        $_SESSION['user'] = [
            'pseudo' => $member->getPseudo(),
            'avatar' => $member->getAvatar(),
            'rank' => $member->getRank(),
            'id' => $member->getId(),
        ];

        self::setBanner('success', 'Bienvenue '.$member->getPseudo().', vous êtes maintenant connecté !');

    }

    public static function setPage($page) {

        self::$page = $page;

    }

    public static function setFolder($folder) {

        self::$folder = $folder;

    }

    public static function setTitle($title) {

        self::$title = $title;

    }

}
