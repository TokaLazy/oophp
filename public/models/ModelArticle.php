<?php

require_once(INC.'Connection.php');
require_once(INC.'Session.php');

class Article
{
    static private $table = 'articles';

    protected $id;
    protected $id_label;
    protected $id_categorie;
    protected $thumbnail;
    protected $title;
    protected $introduction;
    protected $message;
    protected $conclusion;
    protected $publication;
    protected $online;
    protected $label;
    protected $categorie;
    protected $author;
    protected $avatar;


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
        $member = new Article($data);

        return $member;
    }

    public static function all()
    {
        $articles = [];
        $db = Db::getInstance();
        $req = $db->query("SELECT articles.*, labels.title AS label, categories.title AS categorie, members.pseudo AS author, members.avatar AS avatar
        FROM articles
        INNER JOIN labels ON articles.id_label = labels.id
        INNER JOIN categories ON articles.id_categorie = categories.id
        INNER JOIN authors ON articles.id = authors.id_article
        INNER JOIN members ON authors.id_member = members.id
        WHERE online = 1");

        foreach ($req->fetchAll() as $post) {
            $articles[] = new Article($post);
        }

        return $articles;
    }

    public static function filter($value)
    {
        $articles = [];
        $db = Db::getInstance();
        $req = $db->query("SELECT articles.*, labels.title AS label, categories.title AS categorie, members.pseudo AS author, members.avatar AS avatar
        FROM articles
        INNER JOIN labels ON articles.id_label = labels.id
        INNER JOIN categories ON articles.id_categorie = categories.id
        INNER JOIN authors ON articles.id = authors.id_article
        INNER JOIN members ON authors.id_member = members.id
        WHERE id_label = $value AND online = 1");

        foreach ($req->fetchAll() as $post) {
            $articles[] = new Article($post);
        }

        return $articles;
    }

    public static function select($cell, $value)
    {
        $db = Db::getInstance();
        $req = $db->prepare("SELECT articles.*, labels.title AS label, categories.title AS categorie, members.pseudo AS author, members.avatar AS avatar
        FROM articles
        INNER JOIN labels ON articles.id_label = labels.id
        INNER JOIN categories ON articles.id_categorie = categories.id
        INNER JOIN authors ON articles.id = authors.id_article
        INNER JOIN members ON authors.id_member = members.id
        WHERE articles.$cell = :$cell AND online = 1");
        $req->bindValue(":$cell", $value);
        $req->execute();

        return new Article($req->fetch(PDO::FETCH_ASSOC));
    }

    public static function insert(Article $article) {
        $db = Db::getInstance();
        $req = $db->prepare("INSERT INTO articles
        (id_label, id_categorie, thumbnail, title, introduction, message, conclusion)
        VALUES (:id_label, :id_categorie, :thumbnail, :title, :introduction, :message, :conclusion)");
        $req->bindValue(':id_label', $article->id_label(), PDO::PARAM_INT);
        $req->bindValue(':id_categorie', $article->id_categorie(), PDO::PARAM_INT);
        $req->bindValue(':thumbnail', $article->thumbnail(), PDO::PARAM_STR);
        $req->bindValue(':title', $article->title(), PDO::PARAM_STR);
        $req->bindValue(':introduction', $article->introduction(), PDO::PARAM_STR);
        $req->bindValue(':message', $article->message(), PDO::PARAM_STR);
        $req->bindValue(':conclusion', $article->conclusion(), PDO::PARAM_STR);
        $req->execute();
        $req->closeCursor();
    }

    public static function getCategories()
    {
        $list = [];
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM categories");

        foreach ($req->fetchAll() as $categorie) {
            $list[] = $categorie;
        }

        return $list;
    }

    public function generateThumbnail($article) {
        $fileName = $article->title().'.'.pathinfo($article->thumbnail()['name'], PATHINFO_EXTENSION);
        $targetFile = './'.IMG.'thumbnail/'.strtolower($fileName);

        if (!move_uploaded_file($article->thumbnail()['tmp_name'], $targetFile)) {
            Session::setFlash('danger', "Il y a eu une erreur lors du chargement du fichier.");
        }

        $this->setThumbnail($fileName);
    }


    public function setId($value)
    {
        $this->id = intval($value);
    }
    public function setId_label($value)
    {
        $this->id_label = intval($value);
    }
    public function setId_categorie($value)
    {
        $this->id_categorie = intval($value);
    }
    public function setThumbnail($value)
    {
        $this->thumbnail = $value;

        if (is_array($value)) {
            $this->generateThumbnail($this);
        }
    }
    public function setTitle($value)
    {
        $this->title = $value;
    }
    public function setIntroduction($value)
    {
        $this->introduction = $value;
    }
    public function setMessage($value)
    {
        $this->message = $value;
    }
    public function setConclusion($value)
    {
        $this->conclusion = $value;
    }
    public function setPublication($value)
    {
        $this->publication = $value;
    }
    public function setOnline($value)
    {
        $this->online = $value ? 1 : 0;
    }
    public function setLabel($value)
    {
        $this->label = $value;
    }
    public function setCategorie($value)
    {
        $this->categorie = $value;
    }
    public function setAuthor($value)
    {
        $this->author = $value;
    }
    public function setAvatar($value)
    {
        $this->avatar = $value;
    }


    public function id()
    {
        return $this->id;
    }
    public function id_label()
    {
        return $this->id_label;
    }
    public function id_categorie()
    {
        return $this->id_categorie;
    }
    public function thumbnail()
    {
        return $this->thumbnail;
    }
    public function title()
    {
        return $this->title;
    }
    public function introduction()
    {
        return $this->introduction;
    }
    public function message()
    {
        return $this->message;
    }
    public function conclusion()
    {
        return $this->conclusion;
    }
    public function publication()
    {
        return $this->publication;
    }
    public function online()
    {
        return $this->online;
    }
    public function label()
    {
        return $this->label;
    }
    public function categorie()
    {
        return $this->categorie;
    }
    public function author()
    {
        return $this->author;
    }
    public function avatar()
    {
        return $this->avatar;
    }
}
