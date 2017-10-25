<?php

require_once(INC.'Connection.php');

class Register
{
    public $id;
    public $title;
    public $message;
    public $author;

    public function __construct($id, $title, $message, $author)
    {
        $this->id = $id;
        $this->title = $title;
        $this->message = $message;
        $this->author = $author;
    }

    public static function memberExist(String $cell, String $value)
    {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM membres WHERE $cell = :$cell");
        $req->bindValue(":$cell", $value);
        $req->execute();

        return $req->fetchColumn();
    }

    public static function tokenExist($id, $token) {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM membres WHERE id = :id AND token = :token");
        $req->bindValue(":id", $id);
        $req->bindValue(":token", $token);
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

    public static function insert(Member $member)
    {
        $db = Db::getInstance();
        $req = $db->prepare("INSERT INTO membres (pseudo, password, email, inscrit, token, avatar) VALUES (:pseudo, :password, :email, NOW(), :token, :avatar)");
        $req->bindValue(':pseudo', $member->pseudo(), PDO::PARAM_STR);
        $req->bindValue(':password', $member->password(), PDO::PARAM_STR);
        $req->bindValue(':email', $member->email(), PDO::PARAM_STR);
        $req->bindValue(':token', $member->token(), PDO::PARAM_STR);
        $req->bindValue(':avatar', $member->avatar(), PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

    public static function sendEmail(Member $member)
    {
        $title = 'Inscription sur le Site du Savoir';
        $message = "Cliquez ou copier le lien dans votre navigateur http://".$_SERVER['SERVER_NAME']."/register/confirm/".$member->id()."/".$member->token();
        $header = 'sitedusavoir.com';

        if (PROD) {
            mail($member->email(), $title, $message, $header);
        }
    }
}
