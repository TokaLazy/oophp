<?php

use Vendor\Session;
use Vendor\Validator;
use controllers\Controller;

use Models\Member;

class ControllerRegister extends Controller {

    protected function index() {

        Session::setFolder('register');
        Session::setTitle('Inscription');

        Session::setBreadcrumb([
            'Accueil' => '/',
            'Inscription' => '/inscription'
        ]);

        $pseudo = $email = '';

        if (isset($_POST['submit'])) {

            if (Session::attrExists('banner')) {

                Session::unset('banner');

            }

            $data = array_merge($_POST, $_FILES);

            Validator::check($data);

            if (Session::attrExists('banner')) {

                return;

            }

            $data['password'] = PASSWORD_HASH($data['password'], PASSWORD_BCRYPT);

            $member = Member::init($data);

            if ($member->exists('pseudo', $member->getPseudo()) || $member->exists('email', $member->getEmail())) {

                Session::setBanner('warning', 'Pseudo ou adresse e-mail déjà utilisé, nous sommes désolé.');

            }

            if (!Session::attrExists('banner')) {

                $member->insert();

                if (PROD) {

                    $title = 'Inscription sur le Site du Savoir';
                    $message = "Cliquez ou copier le lien dans votre navigateur http://".$_SERVER['SERVER_NAME']."/register/confirm/".$member->getId()."/".$member->getToken();
                    $header = 'sitedusavoir.com';

                    mail($member->getEmail(), $title, $message, $header);

                    Session::setBanner('success', 'Un e-mail de confirmation vous a été envoyé.');

                } else {

                    Session::setBanner('info admin', '<a href="http://'.$_SERVER['SERVER_NAME'].'/register/confirm/'.$member->getId().'/'.$member->getToken().'">cliquer</a>');

                }

                $member = $member->connexion();
                Session::setUser($member);

                redirect();
            }

        }

    }

    protected function confirm() {

        $member = Member::init(['id' => $_GET['id']])->connexion();

        if (!$member) {

            Session::setBanner('danger', "Votre compte n'existe pas.");

        } else {

            if ($member->getToken() !== $_GET['token']) {

                Session::setBanner('danger', "Votre token n'est pas valide.");

            } else {

                $member->setToken(null);
                $member->update();

                Session::setUser($member);
            }

        }

        redirect();

    }
}
