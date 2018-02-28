<?php

require_once('Controller.php');
require_once(INC.'Session.php');
require_once(MODEL.'ModelMember.php');
require_once(CONTROLLER.'ValidForm.php');

class ForgetController extends Controller
{
    protected $folder = 'forget';
    protected $title = 'Mot de passe oublié';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane(['Accueil' => '/']);

        $email = '';

        if (isset($_POST['submit'])) {
            ValidForm::init($_POST);

            $email = trim($_POST['email']);

            if (!Session::existAttr('flash')) {
                $member = Member::init($_POST);

                if (!Member::exist('email', $email)) {
                    Session::setFlash('danger', 'Aucune correspondance avec cette adresse.');
                } else {
                    $member = Member::connexion('email', $_POST['email']);

                    if ($member->token() !== null) {
                        Session::setFlash('warning', 'Vous avez déjà reçu un e-mail pour valider votre inscription ou pour réinitialiser votre mot de passe');
                    } else {
                        $member->generateToken();
                        $member->update($member);

                        if (PROD) {
                            $member->sendMail("Cliquez sur le lien ou copier coller dans votre navigateur :\n\nhttp://".$_SERVER['SERVER_NAME']."/forget/confirm/".$member->id()."/".$member->token());
                        } else {
                            Session::setFlash('info', "http://".$_SERVER['SERVER_NAME']."/forget/confirm/".$member->id()."/".$member->token());
                        }

                        redirect();
                    }
                }
            }
        }

        require_once(VIEW.'layout.html');
    }

    public function confirm()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = 'Réinitialisation du mot de passe';

        Session::setAriane(['Accueil' => '/']);

        if (Member::exist('id', $_GET['id'])) {
            if (!Member::check('token', $_GET['token'], $_GET['id'])) {
                Session::setFlash('danger', "Votre token n'est pas valide.");

                redirect('/connexion');
            } else {
                if (isset($_POST['submit'])) {
                    ValidForm::init($_POST);

                    if (!Session::existAttr('flash')) {
                        $_POST['password'] = PASSWORD_HASH($_POST['password'], PASSWORD_BCRYPT);

                        $member = Member::connexion('id', $_GET['id']);
                        $member->setPassword($_POST['password']);
                        $member->setToken(null);
                        $member->update($member);

                        Session::setFlash('success', 'Mot de passe mis à jour, vous pouvez vous connecter à present');
                        redirect('/connexion');
                    }
                }
            }
        } else {
            Session::setFlash('danger', "Votre compte n'existe pas.");

            redirect('/connexion');
        }

        require_once(VIEW.'layout.html');
    }
}
