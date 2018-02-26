<?php

require_once('Controller.php');
require_once(INC.'Session.php');
require_once(MODEL.'ModelMember.php');
require_once(INC.'ValidForm.php');

class RegisterController extends Controller
{
    protected $folder = 'register';
    protected $title = 'Inscription';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane([
            'Accueil' => '/',
            'Inscription' => '/inscription'
        ]);

        $pseudo = $email = '';

        if (isset($_POST['submit'])) {
            $post = array_merge($_POST, $_FILES);
            ValidForm::init($post);

            $pseudo = trim($post['pseudo']);
            $email = trim($post['email']);

            if (!Session::existAttr('flash')) {
                $post['password'] = PASSWORD_HASH($post['password'], PASSWORD_BCRYPT);

                $member = Member::init($post);

                if (Member::exist('pseudo', $member->pseudo())) {
                    Session::setFlash('warning', 'Votre pseudo est déjà pris, nous sommes désolé.');
                }

                if (Member::exist('email', $member->email())) {
                    Session::setFlash('warning', 'Votre adresse e-mail est déjà prise.');
                }

                if (!Session::existAttr('flash')) {
                    Member::insert($member);

                    $member->setId(Member::getId($member)['id']);

                    if (PROD) {
                        $member->sendEmail("Cliquez ou copier le lien dans votre navigateur http://".$_SERVER['SERVER_NAME']."/register/confirm/".$this->id."/".$member->token);

                        Session::setFlash('success', 'Un e-mail de confirmation vous a été envoyé.');
                    } else {
                        Session::setFlash('info admin', '<a href="http://'.$_SERVER['SERVER_NAME'].'/register/confirm/'.$member->id().'/'.$member->token().'">cliquer</a>');
                    }

                    redirect();
                }
            }
        }

        require_once('./layout.php');
    }

    public function confirm()
    {
        if (Member::exist('id', $_GET['id'])) {
            if (!Member::check('token', $_GET['token'], $_GET['id'])) {
                Session::setFlash('danger', "Votre token n'est pas valide.");
            } else {
                $member = Member::connexion('id', $_GET['id']);
                $member->setToken(null);
                $member->update($member);
                Session::setUser($member);
            }
        } else {
            Session::setFlash('danger', "Votre compte n'existe pas.");
        }

        redirect();
    }
}
