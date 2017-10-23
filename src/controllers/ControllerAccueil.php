<?php

require_once('Controller.php');

class AccueilController extends Controller
{
    protected $folder = 'accueil';
    protected $title = 'Page d\'accueil';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        $presentation = [
            [
                'title' => 'Forum',
                'url' => '/forum',
                'message' => "Le Forum est la partie la plus communataire. elle permet au membre du site d'y discuter, chercher de l'aide, proposer des modifications, signaler des bugs.Plusieurs forum y sont present: On en trouve un forum dedies pour les jeux videos, un forum general, et un forum dedie a l'informatique."
            ],
            [
                'title' => 'Tutos',
                'url' => '/tutoriel',
                'message' => "Venez nous apprendre des nouvelles choses, que vous soyez informaticien ou non, vous possedez peut etre un domaine que vous maitrisez, venez nous en faire profiter. Partager votre savoir et aider les autres a s'ameliorer."
            ],
            [
                'title' => 'Blog',
                'url' => '/blog',
                'message' => "Le blog est une autre partie du site tres importante. Elle permet de vous tenir au courant de l'activites du site. Entre autres les nouvelles fonctionnalites apporte, les propositions d'amelioration, et notament les sortis de s versions du sites."
            ]
        ];

        require_once(VIEW.'layout.html');
    }
}
