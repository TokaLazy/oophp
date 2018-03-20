<?php

require_once('Controller.php');
require_once(INC.'Session.php');
require_once(MODEL.'ModelMember.php');
require_once(INC.'ValidForm.php');

class ProfilController extends Controller
{
    protected $folder = 'profil';
    protected $title = 'Profil';

    public function parametres()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane([
            'Accueil' => '/',
            'Parametres' => '/profil/parametres'
        ]);

        $member = Member::connexion('id', $_SESSION['id']);

        $pseudo = $member->pseudo();
        $email = $member->email();
        $localisation = $member->localisation();
        $website = $member->website();
        $signature = $member->signature();

        if (isset($_POST['submit'])) {
            $post = array_merge($_POST, $_FILES);
            ValidForm::init($post);

            $pseudo = trim($post['pseudo']);
            $email = trim($post['email']);
            $localisation = trim($post['localisation']);
            $website = trim($post['website']);
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
                $member->setWebsite($post['website']);
                $member->setSignature($post['signature']);

                $member->update($member);
                Session::setUser($member,false);
            }
        }

        require_once('./layout.php');
    }

    public function show()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane([
            'Accueil' => '/',
            'Profil' => '/profil/show'
        ]);

        $member = Member::connexion('id', $_SESSION['id']);
        require_once('./layout.php');
        
    }
}
