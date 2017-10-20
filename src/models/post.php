<?php

class Post
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

    public static function all()
    {
        $db = Db::getInstance();
        $req = $db->query("SELECT * FROM posts");
        $list = [];

        foreach ($req->fetchAll() as $post) {
            $list[] = new Post($post['id'], $post['title'], $post['message'], $post['author']);
        }

        return $list;
    }

    public static function select($id)
    {
        $id = intval($id);

        $db = Db::getInstance();
        $req = $db->prepare("SELECT * FROM posts WHERE id = :id");
        $req->bindValue(':id', PDO::PARAM_INT);
        $req->execute();

        $post = $req->fetch(PDO::FETCH_ASSOC);

        return new Post($post['id'], $post['title'], $post['message'], $post['author']);
    }
}
