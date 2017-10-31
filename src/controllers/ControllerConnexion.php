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

        $pseudo = '';

        if (isset($_POST['submit'])) {
            ValidForm::init($_POST);

            $pseudo = trim($_POST['pseudo']);

            if (!Session::existAttr('flash')) {
                $login = Member::init($_POST);

                if (!Member::exist('pseudo', $login->pseudo())) {
                    Session::setFlash('danger', 'Le compte n\'existe pas.');
                } else {
                    $member = Member::connexion('pseudo', $login->pseudo());

                    if (!PASSWORD_VERIFY($login->password(), $member->password())) {
                        Session::setFlash('danger', 'Mot de passe ou pseudo incorrecte. Veuillez rééssayer');
                    } else {
                        if (!empty($member->token())) {
                            Session::setFlash('warning', 'Vous avez reçu un e-mail pour valider votre inscription ou pour réinitialiser votre mot de passe');
                        } else {
                            if (!$member->rang()) {
                                Session::setFlash('warning', 'Vous avez été banni du site, impossible de vous connecter sur ce site.');
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
