<?php

require_once('Controller.php');
require_once(INC.'Session.php');
require_once(MODEL.'ModelMember.php');
require_once(CONTROLLER.'ValidForm.php');

class ProfilController extends Controller
{
    protected $folder = 'profil';
    protected $title = 'Profil';

    public function parametres()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        $member = Member::connexion('id', $_SESSION['id']);

        $pseudo = $member->pseudo();
        $email = $member->email();
        $localisation = $member->localisation();
        $siteweb = $member->siteweb();
        $signature = $member->signature();

        if (isset($_POST['submit'])) {
            $post = array_merge($_POST, $_FILES);
            ValidForm::init($post);

            $pseudo = trim($post['pseudo']);
            $email = trim($post['email']);
            $localisation = trim($post['localisation']);
            $siteweb = trim($post['siteweb']);
            $signature = trim($post['signature']);

            if (!Session::existAttr('flash')) {
                if (!empty($post['password'])) {
                    $post['password'] = PASSWORD_HASH($post['password'], PASSWORD_BCRYPT);
                    $member->setPassword($post['password']);
                }

                if (!empty($post['avatar']['name'])) {
                    $member->setAvatar($post['avatar']);
                }

                $member->setPseudo($post['pseudo']);
                $member->setEmail($post['email']);
                $member->setLocalisation($post['localisation']);
                $member->setSiteweb($post['siteweb']);
                $member->setSignature($post['signature']);

                $member->update($member);
                Session::setUser($member);
            }
        }

        require_once(VIEW.'layout.html');
    }
}
