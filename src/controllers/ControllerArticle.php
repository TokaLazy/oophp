<?php

require_once('Controller.php');
require_once(MODEL.'ModelArticle.php');

class ArticleController extends Controller
{
    protected $folder = 'article';
    protected $title = 'Page Article';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        $posts = Article::all();

        require_once(VIEW.'layout.html');
    }

    public function show($id)
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        $post = Article::select($id);

        require_once(VIEW.'layout.html');
    }
}
