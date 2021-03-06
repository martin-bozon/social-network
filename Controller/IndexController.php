<?php

namespace App\Controller;

use App\Database\Reaction;
use App\Database\User;
use App\Database\Comment;
use App\Database\Post;

class IndexController extends AppController
{

    public $errors = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $posts = new Post;
        $infosUser = new User;
        $commentaires = new Comment;

        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {

            $id_user = $_SESSION['user']['id'];
            $posts = $posts->getAllPostsWall();
            $reacts = new Reaction;
            $commentaires = $commentaires->getAllCommentWall();
            $smileys = $reacts->getEmoji();

            foreach ($posts as $key => $post) {
                $posts[$key]['reactions'] = $reacts->getAllReactionsFromPost($post['id']);
            }

            $this->render('index.wall', compact('id_user', 'posts', 'reacts', 'commentaires', 'smileys'));
        } else {
            $this->render('index.connexion');
        }
    }

    public function inscription()
    {
        $this->render('index.inscription');
    }

    public function insertUser()
    {
        if (isset($_POST['mail']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['birthday']) && isset($_POST['password']) && isset($_POST['confirmation_password'])) {

            $mail = $_POST['mail'];
            $prenom = htmlspecialchars(ucfirst(strtolower($_POST['first_name'])));
            $nom = htmlspecialchars(ucfirst(strtolower($_POST['last_name'])));
            $birthday = $_POST['birthday'];
            $password = $_POST['password'];
            $conf_pw = $_POST['confirmation_password'];

            if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-z]{1,}\.+[a-z]{1,}@laplateforme.io$/', $mail)) {
                array_push($errors, "Le mail n'est pas au bon format (prenom.nom@laplateforme.io)");
            } else {
                if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,30}/', $password)) {
                    array_push($errors, "Le mot de passe n'est pas assez sécurisé");
                } else {
                    $user = new User;

                    if ($user->isExist($mail) == true) {
                        if ($user->isSamepassword($password, $conf_pw) == true) {
                            $user->inscription($mail, $prenom, $nom, $birthday, $password);
                            echo 'Success';
                        } else {
                            array_push($this->errors, 'Les mots de passe ne sont pas identiques');
                        }
                    } else {
                        array_push($this->errors, 'Login déjà existant');
                    }
                }
            }
        } else {
            array_push($this->errors, 'Informations incomplètes');
        }

        if (!empty($this->errors)) {
            $erreurs_str = implode(",", $this->errors);
            echo json_encode($erreurs_str);
        }
    }

    public function seConnecter()
    {
        if (isset($_POST['mail']) && !empty($_POST['mail']) && isset($_POST['password']) && !empty($_POST['password'])) {
            $mail = $_POST['mail'];
            $password = $_POST['password'];

            if (!filter_var($mail, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-z]{1,}\.+[a-z]{1,}@laplateforme.io$/', $mail)) {
                array_push($errors, "Le mail n'est pas au bon format (prenom.nom@laplateforme.io)");
            } else {
                if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,30}/', $password)) {
                    array_push($errors, "Le mot de passe n'est pas assez sécurisé");
                } else {
                    $user = new User;

                    if ($user->isExist($mail) == false) {
                        if ($user->isGoodpassword($mail, $password) == true) {
                            $return = $user->connexion($mail, $password);

                            if ($return[0] == 'connecté') {
                                $user_session = json_encode($return);

                                echo $user_session;
                            } else {
                                array_push($this->errors, 'Vous n\'etes pas connecté');
                            }
                        } else {
                            array_push($this->errors, 'Le mot de passe est incorrect');
                        }
                    } else {
                        array_push($this->errors, 'Cet email est inconnu');
                    }
                }
            }
        } else {
            array_push($this->errors, 'Entrer correctement vos informations');
        }

        if (!empty($this->errors)) {
            $erreurs_str = implode(",", $this->errors);
            echo json_encode($erreurs_str);
        }
    }

    public function getEmoji()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'getEmoji') {
            $reaction = new Reaction;
            echo json_encode($reaction->getEmoji());
        }
    }

    public function insertEmoji()
    {
        $id_user = $_SESSION['user']['id'];
        if (isset($_POST['action']) && $_POST['action'] == 'insertEmoji') {
            $id_react = $_POST['id_react'];
            $id_bloc = $_POST['id_bloc'];
            $bloc = $_POST['bloc'];
            $reaction = new Reaction;
            $reaction->insertEmoji($id_user, $id_react, $id_bloc, $bloc);
            $post = new Post;
            echo json_encode($post->getReacts($id_bloc));
        }
    }

    public function search()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'search') {
            $search = new User;
            echo json_encode($search->search());
        }
    }

    public function deco()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'deco') {
            $deco = new User;
            $deco->deco();
        }
    }

    public function redirectHeader()
    {
        if (isset($_POST['action']) && $_POST['action'] == '') {

        }
        if (isset($_POST['action']) && $_POST['action'] == '') {

        }
        if (isset($_POST['action']) && $_POST['action'] == '') {

        }
    }

    public function forgotPassword()
    {
        $this->render('index.reset');
    }

    public function resetPassword()
    {
        if (isset($_POST['mail'], $_POST['reset_password'], $_POST['conf_reset_password'])) {
            $mail = $_POST['mail'];
            $password = $_POST['reset_password'];
            $conf_password = $_POST['conf_reset_password'];

            $user = new User;
            if ($user->isExist($mail) == false) {
                if ($user->isSamepassword($password, $conf_password) == true) {
                    if ($user->passwordReset($mail, $password) == 'updaté') {
                        echo 'Success';
                    }
                } else {
                    array_push($this->errors, 'Les mots de passes ne sont pas identiques');
                }
            } else {
                array_push($this->errors, 'Identifiants inexistants');
            }

            if (!empty($this->errors)) {
                $erreurs_str = implode(",", $this->errors);
                echo json_encode($erreurs_str);
            }
        }
    }

    public function addPostFormWall()
    {
        // Si un formulaire a été envoyé, on ajoute la publication
        if (isset($_POST["post"]) && !empty($_POST['post'])) {
            $post = new Post;
            $post->addPost($_POST["post"], $_SESSION['user']['id']);
        }

    }

    public function ajouterCommentaireWall()
    {
        if (!empty($_POST["content"]) and !ctype_space($_POST["content"])) {
            $comment = new Comment;
            $response = $comment->addComment($_POST["content"], $_POST["id_post"], $_POST["id_user"]);
            return json_encode($response);
        } else die();
    }

    public function getPostEmoji()
    {
        $react = new Post;
        echo json_encode($react->getReactsWall());
    }

    public function addReactionPost($post_and_react_id, $idUser)
    {
        $reacts = new Reaction;
        $reaction = explode('.', $post_and_react_id);
        $post_id = $reaction[0];
        $react_id = $reaction[1];
        $reacts->insertEmoji($idUser, $react_id, $post_id, 'posts');
    }

    public function addReactionPostJS()
    {
        $this->addReactionPost($_POST['post_and_react_id'], $_POST['id_user']);
        $reaction = explode('.', $_POST['post_and_react_id']);
        $post_id = $reaction[0];
        $reacts = new Reaction;
        $post = new Post;
        echo json_encode([
            'reactions' => $reacts->getAllReactionsFromPost($post_id),
            'postInformations' => $post->getPost($post_id)
        ]);
    }


}
