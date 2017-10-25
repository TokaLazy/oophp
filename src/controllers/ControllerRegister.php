<?php

require_once('Controller.php');
require_once(MODEL.'ModelRegister.php');
require_once(CONTROLLER.'Member.php');
require_once(CONTROLLER.'ValidForm.php');

class RegisterController extends Controller
{
    protected $folder = 'register';
    protected $title = 'Inscription';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        $pseudo = $email = '';

        if (isset($_POST['submit'])) {
            $post = array_merge($_POST, $_FILES);
            $validator = new ValidForm($post);
            $errors = $validator->getErrors();

            $pseudo = trim($post['pseudo']);
            $email = trim($post['email']);

            if (!count($errors)) {
                $post['password'] = PASSWORD_HASH($post['password'], PASSWORD_BCRYPT);

                $member = Member::init($post);

                if (Register::memberExist('pseudo', $member->pseudo())) {
                    $errors[] = "Votre pseudo est déjà pris, nous sommes désolé.";
                }

                if (Register::memberExist('email', $member->email())) {
                    $errors[] = "Votre adresse e-mail est déjà prise.";
                }

                if (!count($errors)) {
                    Register::insert($member);

                    $member->setId(Register::getId($member)['id']);

                    Register::sendEmail($member);

                    $_SESSION['flash']['success'][] = 'Un e-mail de confirmation vous a été envoyé.';
                    redirect();
                }
            }
        }

        require_once(VIEW.'layout.html');
    }

    public function confirm()
    {
        if (Register::memberExist('id', $_GET['id'])) {
            if (!Register::tokenExist($_GET['id'], $_GET['token'])) {
                $_SESSION['flash']['danger'][] = 'Votre token n\'est pas valide.';
            } else {
                $_SESSION['flash']['success'][] = 'Votre compte est validé.<br>Bienvenue sur le Site du Savoir';
                // NOTE Faire la connexion automatique ^^
            }
        } else {
            $_SESSION['flash']['danger'][] = 'Votre compte n\'existe pas valide.';
        }

        redirect();
    }
}
