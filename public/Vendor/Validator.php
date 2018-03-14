<?php

namespace Vendor;

use Vendor\Session;
use Vendor\Form;

class Validator {

    private static $data;

    public static function check(array $data) {

        self::setData($data);

        foreach ($data as $key => $value) {

            $method = $key . 'IsValid';

            Form::setData($key, $value);

            if (method_exists(__CLASS__, $method)) {

                if (isset(self::$data[$key])) {

                    if (self::$method()) {

                        Session::setBanner('danger', self::$method());

                    }

                }

            }

        }

    }

    private static function setData(array $data) {

        self::$data = $data;

    }

    private function pseudoIsValid() {

        $pseudo = trim(self::$data['pseudo']);

        if (empty($pseudo)) {

            return 'Le pseudo est obligatoire.';

        }

        if (strlen($pseudo) < 3) {

            return 'Votre pseudo est trop petit.';

        }

        if (strlen($pseudo) > 15) {

            return 'Votre pseudo est trop grand.';

        }

        if (!preg_match('#^[\w_-]{3,15}$#', $pseudo)) {

            return 'Votre pseudo ne respecte pas les caracteres autorisées.';

        }

    }

    private function confirmPasswordIsvalid() {

        $password = trim(self::$data['password']);
        $confirm = trim(self::$data['confirmPassword']);

        if (empty($password)) {

            return 'Le mot de passe est obligatoire.';

        }

        if (empty($confirm)) {

            return 'Vous avez oublié de confirmer votre mot de passe.';

        }

        if ($password !== $confirm) {

            return FORM_ERR_PASSWORD;

        }

    }

    private function emailIsValid() {

        $email = trim(self::$data['email']);

        if (empty($email)) {

            return 'L\'e-mail est obligatoire.';

        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            return FORM_ERR_EMAIL;

        }

    }

    private function avatarIsValid() {

        $avatar = self::$data['avatar'];

        if (empty($avatar['name'])) {

            return;

        }

        if ($avatar['error'] > 0) {

            return 'Erreur lors du transfert de l\'avatar';

        }

        $maxsize = 65536;
        if ($avatar['size'] > $maxsize) {

            return 'Le fichier est trop gros :(<strong>'.$avatar['size'].' Octets</strong> contre <strong>'.$maxsize.' Octets</strong>)';

        }

        $dimensions = 500;
        $imageSizes = getimagesize($avatar['tmp_name']);
        if ($imageSizes[0] > $dimensions || $imageSizes[1] > $dimensions) {

            return "Image trop large ou trop longue : (<strong>$imageSizes[0]x$imageSizes[1]</strong> contre <strong>$dimensions$dimensions</strong>)";

        }

        $extension = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
        $extensionValid = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        if (!in_array($extension, $extensionValid)) {

            return FORM_ERR_AVATAR_FORMAT;

        }

    }

}
