<?php

class ValidForm
{
    protected $errors = [];

    public function __construct($data)
    {
        if (isset($data['pseudo'])) {
            $this->validPseudo(trim($data['pseudo']));
        }

        if (isset($data['email'])) {
            $this->validEmail(trim($data['email']));
        }

        if (isset($data['confirmPassword'])) {
            $this->validPassword($data['password'], $data['confirmPassword']);
        }

        if (isset($data['avatar']) && !empty($data['avatar']['name'])) {
            $this->validAvatar($data['avatar'], $data['pseudo']);
        }

        if (isset($data['localisation'])) {
            $this->validLocalisation(trim($data['localisation']));
        }

        if (isset($data['siteweb'])) {
            $this->validSiteweb(trim($data['siteweb']));
        }

        if (isset($data['signature'])) {
            $this->validSignature(trim($data['signature']));
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validPseudo($pseudo)
    {
        if (empty($pseudo)) {
            $this->errors[] = 'Le pseudo est obligatoire';
        } elseif (strlen($pseudo) < 3) {
            $this->errors[] = 'Votre pseudo est trop petit.';
        } elseif (strlen($pseudo) > 15) {
            $this->errors[] = 'Votre pseudo est trop grand.';
        } elseif (!preg_match('#^[\w_-]{3,15}$#', $pseudo)) {
            $this->errors[] = 'Votre pseudo ne respecte pas les caracteres autorisées.';
        }
    }

    private function validEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = FORM_ERR_EMAIL;
        }
    }

    private function validPassword($password, $confirm)
    {
        if ($password !== $confirm) {
            $this->errors[] = FORM_ERR_PASSWORD;
        }
    }

    private function validAvatar($avatar, $pseudo)
    {
        if ($avatar['error'] > 0) {
            $this->errors[] = 'Erreur lors du transfert de l\'avatar';
        }

        $maxsize = 65536;
        if ($avatar['size'] > $maxsize) {
            $this->errors[] = 'Le fichier est trop gros :(<strong>'.$avatar['size'].' Octets</strong> contre <strong>'.$maxsize.' Octets</strong>)';
        }

            $dimensions = 500;
            $imageSizes = getimagesize($avatar['tmp_name']);
        if ($imageSizes[0] > $dimensions || $imageSizes[1] > $dimensions) {
            $this->errors[] = "Image trop large ou trop longue : (<strong>$imageSizes[0]x$imageSizes[1]</strong> contre <strong>$dimensions$dimensions</strong>)";
        }

            $extension = strtolower(pathinfo($avatar['name'], PATHINFO_EXTENSION));
            $extensionValid = ['jpg', 'jpeg', 'bmp', 'gif', 'png'];
        if (!in_array($extension, $extensionValid)) {
            $this->errors[] = FORM_ERR_AVATAR_FORMAT;
        }

        // $targetFile = IMG.'avatars/'.trim($pseudo).$extension;
        // if (file_exists($targetFile)) {
        //     $errors[] = FORM_ERR_AVATAR_EXIST . $pseudo.$extension . '.';
        // }
    }

    private function validLocalisation($localisation)
    {
        if (strlen($localisation) < 2 && !empty($localisation)) {
            $this->errors[] = 'Votre localisation semble étrange.';
        }
    }

    private function validSiteweb($siteweb)
    {
        if (!filter_var($siteweb, FILTER_VALIDATE_URL) && !empty($siteweb)) {
            $this->errors[] = 'Votre site web ne semble pas être valide.';
        }
    }

    private function validSignature($signature)
    {
        if (strlen($signature) < 3 && !empty($signature)) {
            $this->errors[] = 'Votre signature est trop petite.';
        }
    }
}
