<?php

require_once(MODEL.'/post.php');

class PostsController
{
    public function index()
    {
        $posts = Post::all();
        require_once(VIEW.'/posts/index.html');
    }

    public function article()
    {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            return self::error();
        }

        $post = Post::select($_GET['id']);
        require_once(VIEW.'/posts/article.html');
    }

    public function error()
    {
        require_once(VIEW.'/pages/error.html');
    }
}
