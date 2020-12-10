<?php

namespace App\Controller;

use App\Database\Reaction;
use App\Database\User;

class IndexController extends AppController
{

    public $errors = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            $this->render('index.wall');
        } else {
            $this->render('index.connexion');
        }
    }

    public function inscription(){
        $this->render('index.inscription');
    }

    public function insertUser(){
        if(isset($_POST['mail']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['birthday']) && isset($_POST['password']) && isset($_POST['confirmation_password'])) {

            $mail = $_POST['mail'];
            $prenom = $_POST['first_name'];
            $nom = $_POST['last_name'];
            $birthday = $_POST['birthday'];
            $password = $_POST['password'];
            $conf_pw = $_POST['confirmation_password'];

            $user = new User;

            if ($user->isExist($mail) == true) {
                if($user->isSamepassword($password, $conf_pw) == true) {
                    $user->inscription($mail, $prenom, $nom, $birthday, $password);
                    echo 'Success';
                } else {
                    array_push($this->errors, 'Les mots de passe ne sont pas identiques');
                }
            } else {
                array_push($this->errors, 'Login déjà existant');
            }

        } else {
            array_push($this->errors, 'Informations incomplètes');
        }
        $erreurs_str = implode(",", $this->errors);
        echo $erreurs_str;
    }

    public function seConnecter(){
        if(isset($_POST['mail']) && isset($_POST['password'])) {
            $mail = $_POST['mail'];
            $password = $_POST['password'];

            $user = new User;

            if($user->isExist($mail) == false){
                if($user->isGoodpassword($mail, $password) == true){
                    $return = $user->connexion($mail, $password);

                    if($return[0] == 'connecté') {
                        $user_session = json_encode($return);

                        echo $user_session;
                    } else {
                        array_push($this->errors, 'Vous n\'etes pas connecté');
                    }
                } else {
                    array_push($this->errors, 'Le mot de passe entré ne correspond pas à nos données');
                }
            } else {
                array_push($this->errors, 'Cet email est inconnu');
            }

        } else {
            array_push($this->errors, 'Informations incomplètes');
        }
    }

    public function getEmoji()
        {
            if(isset($_POST['action']) && $_POST['action']=='getEmoji')
                {
                    $reaction = new Reaction;
                    echo $reaction->getEmoji();
                }
        }
    public function insertEmoji()
        {            
            $id_user = 2; //A modifier par l'id de l'utilisateur
            if(isset($_POST['action']) && $_POST['action']=='insertEmoji')
                {
                    $id_react = $_POST['id_react'];
                    $id_bloc = $_POST['id_bloc'];
                    $bloc = $_POST['bloc'];
                    $reaction = new Reaction;
                    $reaction->insertEmoji($id_user, $id_react, $id_bloc, $bloc);
                }
        }
    public function search()
        {            
            if(isset($_POST['action']) && $_POST['action']=='search')
                {
                    $auto = new User;
                    echo $auto->search();
                }
        }
}


