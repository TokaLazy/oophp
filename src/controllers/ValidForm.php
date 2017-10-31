<?php

require_once(INC.'Session.php');

class ValidForm
{
    public static function init($data)
    {
        if (isset($data['pseudo'])) {
            self::validPseudo(trim($data['pseudo']));
        }

        if (isset($data['email'])) {
            self::validEmail(trim($data['email']));
        }

        if (isset($data['confirmPassword'])) {
            self::validPassword($data['password'], $data['confirmPassword']);
        }

        if (isset($data['avatar']) && !empty($data['avatar']['name'])) {
            self::validAvatar($data['avatar'], $data['pseudo']);
        }

        if (isset($data['localisation'])) {
            self::validLocalisation(trim($data['localisation']));
        }

        if (isset($data['siteweb'])) {
            self::validSiteweb(trim($data['siteweb']));
        }

        if (isset($data['signature'])) {
            self::validSignature(trim($data['signature']));
        }
    }

    private static function validPseudo($pseudo)
    {
        if (empty($pseudo)) {
            Session::setFlash('danger', 'Le pseudo est obligatoire');
        } elseif (strlen($pseudo) < 3) {
            Session::setFlash('danger', 'Votre pseudo est trop petit.');
        } elseif (strlen($pseudo) > 15) {
            Session::setFlash('danger', 'Votre pseudo est trop grand.');
        } elseif (!preg_match('#^[\w_-]{3,15}$#', $pseudo)) {
            Session::setFlash('danger', 'Votre pseudo ne respecte pas les caracteres autorisées.');
        }
    }

    private static function validEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::setFlash('danger', FORM_ERR_EMAIL);
        }
    }

    private static function validPassword($password, $confirm)
    {
        if ($password !== $confirm) {
            Session::setFlash('danger', FORM_ERR_PASSWORD);
        }
    }

    private static function validAvatar($avatar, $pseudo)
    {
        if ($avatar['error'] > 0) {
            Session::setFlash('danger', 'Erreur lors du transfert de l\'avatar');
        }

        $maxsize = 65536;
        if ($avatar['size'] > $maxsize) {
            Session::setFlash('danger', 'Le fichier est trop gros :(<strong>'.$avatar['size'].' Octets</strong> contre <strong>'.$maxsize.' Octets</strong>)');
        }

            $dimensions = 500;
            $imageSizes = getimagesize($avatar['tmp_name']);
        if ($imageSizes[0] > $dimensions || $imageSizes[1] > $dimensions) {
            Session::setFlash('danger', "Image trop large ou trop longue : (<strong>$imageSizes[0]x$imageSizes[1]</strong> contre <strong>$dimensions$dimensions</strong>)");
        }

            $extension = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
            $extensionValid = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        if (!in_array($extension, $extensionValid)) {
            Session::setFlash('danger', FORM_ERR_AVATAR_FORMAT);
        }
    }

    private static function validLocalisation($localisation)
    {
        if (strlen($localisation) < 2 && !empty($localisation)) {
            Session::setFlash('danger', 'Votre localisation semble étrange.');
        }
    }

    private static function validSiteweb($siteweb)
    {
        if (!filter_var($siteweb, FILTER_VALIDATE_URL) && !empty($siteweb)) {
            Session::setFlash('danger', 'Votre site web ne semble pas être valide.');
        }
    }

    private static function validSignature($signature)
    {
        if (strlen($signature) < 3 && !empty($signature)) {
            Session::setFlash('danger', 'Votre signature est trop petite.');
        }
    }
}
