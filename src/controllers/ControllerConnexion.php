<?php

require_once('Controller.php');
require_once(INC.'Session.php');
require_once(MODEL.'ModelMember.php');
require_once(CONTROLLER.'ValidForm.php');

class ConnexionController extends Controller
{
    protected $folder = 'connexion';
    protected $title = 'Connexion';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane([
            'Accueil' => '/',
            'Connexion' => '/connexion'
        ]);

        $pseudo = '';

        if (isset($_POST['submit'])) {
            $validator = new ValidForm($_POST);
            $errors = $validator->getErrors();

            $pseudo = trim($_POST['pseudo']);

            if (!count($errors)) {
                $login = Member::init($_POST);

                if (!Member::exist('pseudo', $login->pseudo())) {
                    $errors[] = 'Le compte n\'existe pas.';
                } else {
                    $member = Member::connexion('pseudo', $login->pseudo());

                    if (!empty($member->token())) {
                        Session::setFlash('warning', 'Vous avez reçu un e-mail pour valider votre inscription ou pour réinitialiser votre mot de passe');
                    } else {
                        if (!PASSWORD_VERIFY($login->password(), $member->password())) {
                            $errors[] = 'Mot de passe ou pseudo incorrecte. Veuillez rééssayer';
                        } else {
                            if (!$member->rang()) {
                                $errors[] = 'Vous avez été banni du site, impossible de vous connecter sur ce site.';
                            } else {
                                if (isset($_POST['souvenir'])) {
                                    $member->generateCookie($member->id());
                                }

                                Session::setUser($member);

                                redirect();
                            }
                        }
                    }
                }
            }
        }

        require_once(VIEW.'layout.html');
    }
}
