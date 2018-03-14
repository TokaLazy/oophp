<?php

use Vendor\Session;
use Vendor\Validator;
use controllers\Controller;
use Models\Member;

class ControllerConnexion extends Controller {

    protected function index() {

        Session::setFolder('connexion');
        Session::setTitle('Connexion');

        Session::setBreadcrumb([
            'Accueil' => '/',
            'Connexion' => '/connexion'
        ]);

        $pseudo = '';

        if (isset($_POST['submit'])) {

            if (Session::attrExists('banner')) {

                Session::unset('banner');

            }

            Validator::check($_POST);

            if (Session::attrExists('banner')) {

                return;

            }

            $this->validForm($_POST);

        }

    }

    private function validForm(array $post) {

        $member = Member::init($post)->connexion();

        if (!$member) {

            return Session::setBanner('danger', "Le compte n'existe pas.");

        }

        if (!PASSWORD_VERIFY($post['password'], $member->getPassword())) {

            return Session::setBanner('danger', 'Mot de passe ou pseudo incorrecte. Veuillez rééssayer.');

        }

        if (!empty($member->getToken())) {

            return Session::setBanner('warning', 'Vous avez reçu un e-mail pour valider votre inscription ou pour réinitialiser votre mot de passe.');

        }

        if (!$member->getRank()) {

            return Session::setBanner('warning', 'Vous avez été banni du site, impossible de vous connecter sur ce site.');

        }

        if (isset($post['souvenir'])) {

            $member->generateCookie($member->getId());

        }

        Session::setUser($member);

        redirect();

    }

}
