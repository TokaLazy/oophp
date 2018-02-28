<?php

require_once('Controller.php');
require_once(INC.'Session.php');
require_once(MODEL.'ModelArticle.php');
require_once(INC.'ValidForm.php');

class BlogController extends Controller
{
    protected $folder = 'blog';
    protected $title = 'Blog';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane([
            'Accueil' => '/',
            'Blog' => '/blog'
        ]);

        $articles = Article::filter(2);

        require_once('./layout.php');
    }

    public function show()
    {
        $folder = $this->folder;
        $page = 'article';
        $title = $this->title;

        $article = Article::select('id', $_GET['action']);

        Session::setAriane([
            'Accueil' => '/',
            'Blog' => '/blog',
            ucfirst(strtolower($article->title())) => '/blog/'.$article->id()
        ]);

        require_once('./layout.php');
    }

    public function ecrire() {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        $titre = $introduction = $message = $conclusion = '';

        $categories = Article::getCategories();

        if (isset($_POST['submit'])) {
            $post = array_merge($_POST, $_FILES);

            ValidForm::init($post);

            $titre = trim($post['title']);
            $introduction = trim($post['introduction']);
            $message = trim($post['message']);
            $conclusion = trim($post['conclusion']);

            if (!Session::existAttr('flash')) {
                $article = Article::init($post);
                $article->setId_label(2);
                $article->setId_categorie($post['category']);

                logg($article);
                Article::insert($article);
            }
        }

        require_once('./layout.php');
    }
}
