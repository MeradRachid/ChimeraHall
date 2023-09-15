<?php

namespace Controller;

use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Entities\User;
use Model\Managers\CategoryManager;
use Model\Managers\TopicManager;
use Model\Managers\PostManager;
use Model\Managers\UserManager;

class SecurityController extends AbstractController implements ControllerInterface
{

    public function index()
    {

    }



    public function adminUsers()
    {
        $this->restrictTo("ROLE_ADMIN");
        $userManager = new UserManager();

        return
            [
                "view" => VIEW_DIR . "security/adminUsers.php",
                "data" =>
                [
                    "users" => $userManager->findAll(["registered", "ASC"])
                ]
            ];

    }

    public function listUsers()
    {
        $this->restrictTo("ROLE_USER");
        $userManager = new UserManager();

        return
            [
                "view" => VIEW_DIR . "forum/listUsers.php",
                "data" =>
                [
                    "users" => $userManager->findAll(["registered", "ASC"])
                ]
            ];

    }

    public function detailUser($id)
    {
        $userManager = new UserManager();

        return
            [
                "view" => VIEW_DIR . "forum/detailUser.php",
                "data" =>
                [
                    "user" => $userManager->findOneById($id)
                ]
            ];

    }

    public function profileUser()
    {
        $user = Session::getUser();

        return
            [
                "view" => VIEW_DIR . "security/userProfile.php",
                "data" =>
                [
                    "user" => $user
                ]
            ];

    }



    public function register()
    {
        if (!empty($_POST)) {
            $nickname = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $confirmPassword = filter_input(INPUT_POST, "confirmPassword", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_VALIDATE_EMAIL);
            $zipCode = filter_input(INPUT_POST, "zipCode", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            // Attention, il faut aussi filtrer les avatars dans le cas où vous les demandez. 
        }

        if (empty($avatar)) {
            $avatar = "public/img/wanderer.png";
        }

        if ($nickname && $password && $email && $avatar) {
            if (($password == $confirmPassword) && preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{12,}$/', $password)) {

                $manager = new UserManager();
                $user = $manager->findOneByPseudo($nickname);

                if (!$user) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    if (
                        $manager->add([
                            "pseudo" => $nickname,
                            "email" => $email,
                            "zipCode" => $zipCode,
                            "password" => $hash,
                            "role" => json_encode('ROLE_USER'),
                            "avatar" => $avatar,
                        ])
                    ) {
                        // Ajout d'un message de succès
                        SESSION::addFlash("success", "Bravo, votre compte à bien été créé !");
                    }
                }
            }
        }
        return
            [
                "view" => VIEW_DIR . "security/login.php",
            ];
    }

    /**
     * Le mot de passe est hashé pour ne plus pouvoir être affiché en clair.
     * On suit les recommandations de l'OWASP & de la CNIL par mesure de sécurité.      
     * On ne compare ni les mots de passes en clair, ni leurs hashs.. 
     * Mais l'empreinte numérique après hashage (Voir aussi le SALT par la suite...) 
     * Md5() & sha1() sont des fonctions obsolètes car facilement crackable via force brute. 
     * On parle désormais de mots de passes faibles.. 
     * En opposition aux mots de passes forts comme B-Crypt & Argon-2i. 
     * Empreinte numérique = Algo + Cost + Salt + Hash.
     *  
     **/

    public function registerForm()
    {
        return
            [
                "view" => VIEW_DIR . "security/register.php",
                "data" => null,
            ];
    }



    public function loginForm()
    {
        return
            [
                "view" => VIEW_DIR . "security/login.php",
            ];
    }

    public function login()
    {
        if (!empty($_POST)) {
            $pseudo = filter_input(INPUT_POST, "pseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        if ($pseudo && $password) {
            // Connexion à la base de données via le manager 
            $userManager = new UserManager();

            // Requête SQL pour récupérer l'utilisateur correspondant au pseudo saisi
            $user = $userManager->findOneByPseudo($pseudo);

            if ($user) {
                // On récupère le hash dans la base de données
                $hash = $user->getPassword();

                if (password_verify($password, $hash)) {

                    // Le mot de passe est correct, on met en session l'utilisateur connecté
                    // $_SESSION['user'] = $user;

                    SESSION::setUser($user);

                    // Ajout d'un message de connexion avec succès  
                    SESSION::addFlash("success", "Bravo " . $user->getPseudo() . ", vous êtes bien connecté!");

                    // Redirection vers la page d'accueil
                    $this->redirectTo("homePage");

                    exit();

                }

            } else {

                // Le mot de passe est incorrect, on affiche donc un message d'erreur
                SESSION::addFlash("error", "Saisie incorrecte, l'identifiant ou le mot de passe n'est pas reconnu. Vous n'êtes pas connecté.");

                $this->redirectTo("security", "loginForm");
            }

        } else {
            // L'utilisateur n'est pas reconnu : Ajout d'un message d'erreur
            SESSION::addFlash("error", "Saisie incorrecte, vous n'êtes pas connecté.");

            $this->redirectTo("security", "loginForm");
        }
    }

    public function logout()
    {

        // session_destroy(); // détruit la session en cours

        unset($_SESSION['user']);

        return
            [
                "view" => VIEW_DIR . "security/logout.php",
            ];
    }

    /**
     * Faille CSRF : CSRF vient de Cross-Site Request Forgery
     * Falsification de requête inter-sites en français.
     * 
     * Consiste à manipuler un utilisateur authentifié à son insu :
     * Par exemple en faisant passer un formulaire pirate pour un form legit. 
     * Ce qui donnera la possibilité d'agir sur le site.
     * La vigilance de l'utilisateur à ce moment là, étant proche du nul.
     * Il ne se méfiera pas et fera lui-meme l'action pirate.
     * 
     * Pour s'en prémunir, on utilisera des jetons 'Token' en session :
     * Qui seront hashé et modifié puis utilisé comme élément de comparaison.
     * ~ Le token ne sera pas visible (propriété hidden) et expire à la fin de la session.
     * Si le token en session n'est pas reconnu lors de l'envoi du formulaire : 
     * On arrête tout (déconnexion de l'user et retour à la case Home)
     * 
     **/



    public function modifyUserForm($id)
    {
        return
            [
                "view" => VIEW_DIR . "security/modifyUser.php",
                "data" =>
                [
                    'user_id' => $id
                ]
            ];
    }



    public function modifyPassword()
    {

    }

    /**
     * Regex password ~> Regex = Expression Régulière ~
     * Les frameworks ayant coutume de gérer les regexs.
     * On ne les fait généralement pas à la main.
     *   
     * Dans le filter input du password, au lieu de SANITIZE : 
     * 
     * FILTER_VALIDATE_REGEX, 
     * array("options" => array("regexp" => "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/" )); 
     * 
     * This regular expression cheks that a password :
     * At least one uppercase English letter. You can remove this condition by removing (?=.*?[A-Z])
     * At least one lowercase English letter.  You can remove this condition by removing (?=.*?[a-z])
     * At least one digit. You can remove this condition by removing (?=.*?[0-9])
     * At least one special character,  You can remove this condition by removing (?=.*?[#?!@$%^&*-])
     * Has minimum 8 characters in length. Adjust it by modifying {8,} 
     * 
     * Source : https://uibakery.io/regex-library/password-regex-php 
     * 
     * Imposer un maximum de 32 characters pour le password peut-être une bonne option.
     * 
     **/



    public function editPseudoForm()
    {
        return
            [
                "view" => VIEW_DIR . "security/modifyPseudo.php",
            ];
    }

    public function modifyPseudo()
    {
        // On Vérifie si l'utilisateur est connecté 
        $user = Session::getUser();

        if (empty($user) || (!Session::getUser())) {
            session_destroy();

            SESSION::addFlash("error", "Vous n'êtes pas autorisé à modifier cet élément. Veuillez-vous connecter avant de recommencer ! ");

            $this->redirectTo("security", "loginForm");
            exit();
        } else {
            if (!empty($_POST)) {
                $newPseudo = filter_input(INPUT_POST, "newPseudo", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $userManager = new UserManager();

                // var_dump($newPseudo);
                // die();
            }
            // $user->setPseudo($Pseudo);
            // var_dump($user);
            // die();
            $userManager->edit(["pseudo" => $newPseudo], $user->getId());
            SESSION::addFlash("success", "Pseudo modifié avec succès. La modification prendra effet à la prochaine connexion.");
            // $this->redirectTo("security", "userProfile");
            // exit();

            return
                [
                    "view" => VIEW_DIR . "security/userProfile.php",
                    "data" =>
                    [
                        "user" => $user
                    ]
                ];
        }
    }



    public function editEmailForm()
    {
        return
            [
                "view" => VIEW_DIR . "security/modifyEmail.php",
            ];
    }

    public function modifyEmail()
    {
        // On Vérifie si l'utilisateur est connecté 
        $user = Session::getUser();

        if (empty($user) || (!Session::getUser())) {
            session_destroy();

            SESSION::addFlash("error", "Vous n'êtes pas autorisé à modifier cet élément. Veuillez-vous connecter avant de recommencer ! ");

            $this->redirectTo("security", "loginForm");
            exit();
        } else {
            if (!empty($_POST["newEmail"])) {
                $newEmail = filter_input(INPUT_POST, "newEmail", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $userManager = new UserManager();
            } elseif (!empty($_POST["newEmailByAdmin"])) {
                $newEmail = filter_input(INPUT_POST, "newEmailByAdmin", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $userManager = new UserManager();
            }
            // var_dump($_POST);
            // die();
            // $user->setPseudo($Pseudo);
            // var_dump($user);
            // die();

            $userManager->edit(["email" => $newEmail], $id);
            SESSION::addFlash("success", "Email modifié avec succès. La modification prendra effet à la prochaine connexion.");

            // $this->redirectTo("security", "userProfile");
            // exit();

            return
                [
                    "view" => VIEW_DIR . "security/userProfile.php",
                    "data" =>
                    [
                        "user" => $user
                    ]
                ];
        }
    }



    public function editAvatar()
    {
        return
            [
                "view" => VIEW_DIR . "security/modifyAvatar.php",
            ];
    }

    public function modifyAvatar()
    {
        // On Vérifie si l'utilisateur est connecté 
        $user = Session::getUser();

        if (empty($user) || (!Session::getUser())) {
            session_destroy();

            SESSION::addFlash("error", "Vous n'êtes pas autorisé à modifier cet élément. Veuillez-vous connecter avant de recommencer ! ");

            $this->redirectTo("security", "loginForm");
            exit();
        } else {
            $userManager = new UserManager();
            $id_user = $user->getId();

            // var_dump($_FILES);
            // die();

            if (!empty($_POST)) {
                $file = filter_input(INPUT_POST, "file", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                //     var_dump($_FILES);
                //     die();

                //     if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) 
                //     {
                //         // Une erreur s'est produite lors du téléchargement ou du traitement du fichier.
                //         // Vous pouvez afficher un message d'erreur ou effectuer des actions de gestion des erreurs ici.
                //         var_dump($_FILES['file']['error']);
                //         die();
                //     }
                //      else
                //     {
                //         var_dump($_FILES['file']);
                //         die();
                //     }

                //     // $avatar = filter_input(INPUT_POST, "file", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                //     // var_dump($_FILES);
                //     // die();

                //     // si on dump $_FILES on obtient un tableau qui contient les infos dans le deuxieme [' '] on les places dans des variables
                //     if (isset($_FILES['file'])) 
                //     {
                //         // var_dump($_FILES['file']);
                //         // die();

                //         $tmpName = $_FILES['file']['tmp_name'];
                //         $name = $_FILES['file']['name'];

                //         $size = $_FILES['file']['size'];

                //         $error = $_FILES['file']['error'];

                // $tabExtension = explode('.', $name);
                // $extension = strtolower(end($tabExtension));

                //         //Taille max que l'on accepte 
                //         $maxSize = 400000;

                $uploadDirectory = "public/img/"; // Dossier de destination

                //         $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                //         $mime = finfo_file($fileInfo, $tmpName);

                //         //Tableau des extensions que l'on accepte 
                //         $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'application/pdf'];


                //         if (in_array($mime, $allowedMimes) && $size <= $maxSize && $error == 0) 
                //         {
                //             // Génère un identifiant unique 
                //             $uniqueID = uniqid('', true);

                //             // Construit le chemin complet du fichier de destination
                //             $file = $uploadDirectory . $uniqueID . '.' . $extension;

                //             // Déplace le fichier téléchargé vers le dossier de destination
                //             move_uploaded_file($tmpName, $file);

                //             // Il faut renommer le fichier avec l'identifiant unique et son extension
                //             rename($file, $uploadDirectory . $uniqueID . '.' . $extension);

                //             $avatar = $file; // $image contient maintenant le chemin du fichier de l'avatar

                //             // var_dump($_FILES);
                //             // die();
                //         } 
                //          else 
                //         {
                //             Session::addFlash('error', "<p> Mauvaise extension ou fichier trop volumineux ! <p>");
                //             $this->redirectTo("security", "userProfile");
                //             // header("location:index.php?action=homePage");
                //             exit();
                //         }

                $avatar = $uploadDirectory . $file;

                $userManager->edit(['avatar' => $avatar], $id_user); // Met à jour l'avatar de l'utilisateur avec le chemin du fichier

                Session::addFlash('success', "Avatar modifié avec succès, effectif dès la prochaine connexion");
                $this->redirectTo("security", "profileUser");
                //         // header("location:index.php?action=homePage");
                exit();
                //     }
            }
        }
    }



    public function lockUser($id)
    {
        // lockUser is pressed without input
        if (empty($_POST)) {
            $userManager = new UserManager();

            $user = $userManager->findOneById($id);

            //  var_dump($user->getBan());
            //  die();

            if ($user->getBan() == true) {

                $user->setBan(0);

                // On update la base de données
                $userLocked = $user->getBan();

                $userManager->edit(["ban" => $userLocked], $id);

                // Ajout d'un message de succès
                SESSION::addFlash("success", "L'user a été déverrouillé avec succès !");
                $this->redirectTo("security", "adminUsers");
                exit();
            } else {
                $user->setBan(1);

                // On update la base de données
                $userLocked = $user->getBan();

                $userManager->edit(["ban" => $userLocked], $id);

                // Ajout d'un message de succès
                SESSION::addFlash("success", "L'user a été verrouillé.");
                $this->redirectTo("security", "adminUsers");
                exit();
            }
        }
    }



    public function deleteUserForm($id)
    {
        $userManager = new UserManager();

        $user = $userManager->findOneById($id);

        // var_dump($user);
        // die();

        return
            [
                "view" => VIEW_DIR . "security/deleteUserForm.php",
                "data" =>
                [
                    "user" => $user
                ]

                // $id,
                // var_dump($id)
            ];
    }

    public function randomizeUser($id)
    {
        $userManager = new UserManager();

        if (!empty($_POST)) // On vérifie si $_POST a bien été submit 
        {
            $confirm = $_POST['confirm'] ?? ''; // On vérifie si la confirmation à eu lieu avant d'effecuté le reste

            if ($confirm === 'oui') {
                // Le triple égal compare la valeur et le type de données, y compris la casse des caractères. 
                // il est préférable d'utiliser une comparaison stricte pour s'assurer que la comparaison est correcte. 
                // La fonction strcasecmp peut comparer deux chaînes de caractères sans prendre en compte la casse si besoin.
                // Si l'on utilisait une comparaison avec True & False, il est possible que cela fonctionne dans ce cas-ci ; 
                // Mais cela pourrait ne pas être fiable dans d'autres situations similaires où des valeurs différentes de zéro sont interprétées comme True.

                $user = $userManager->findOneById($id);

                if ($user) {
                    // On Génère un nouveau pseudo avec un nombre aléatoire
                    $deletedName = "Random";
                    $maxIterations = 1000; // On limite le nombre d'itérations
                    $iteration = 0;

                    do {
                        $randomNumber = rand(100000, 999999); // Génère un nombre aléatoire à 6 chiffres
                        $newPseudo = $deletedName . $randomNumber; // Par exemple, "Random123456"
                        $iteration++;

                        if ($iteration >= $maxIterations) {
                            // Si le nombre maximal d'itérations est atteint : Prendre une mesure appropriée ici, comme générer une erreur et demander de contacter un admin
                            break;
                        }
                    }

                    while ($userManager->findOneByPseudo($newPseudo) !== null);

                    if ($iteration < $maxIterations) {
                        // Vous avez trouvé un pseudo unique à 6 chiffres
                        // $newPseudo contient bien un pseudo unique
                    } else {
                        // Tous les pseudos possibles sont utilisés, prendre une mesure appropriée ici
                    }

                    $pseudo = $newPseudo;
                    $userManager->edit(["pseudo" => $pseudo], $id);

                    $deletedRole = json_encode('DELETED_ACCOUNT');
                    $role = $deletedRole;
                    $userManager->edit(["role" => $role], $id);

                    $deletedMail = "deleted@ccount.mail";
                    $email = $deletedMail;
                    $userManager->edit(["email" => $email], $id);

                    $deletedZCode = "Aucun";
                    $zipCode = $deletedZCode;
                    $userManager->edit(["zipCode" => $zipCode], $id);

                    function generateRandomPassword($length = 32)
                    {
                        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*-';
                        $randomPassword = '';

                        for ($i = 0; $i < $length; $i++) {
                            $randomPassword .= $chars[random_int(0, strlen($chars) - 1)];
                        }

                        return $randomPassword;
                    }

                    // Utilisation de la fonction pour générer un mot de passe aléatoire de 32 caractères
                    $newPassword = generateRandomPassword();

                    // Vous pouvez maintenant stocker $newPassword dans la base de données

                    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

                    $password = $hash;
                    $userManager->edit(["password" => $password], $id);

                    $avatar = $user->getAvatar();
                    $deletedPP = "public/img/wanderer.png";
                    $avatar = $deletedPP;
                    $userManager->edit(["avatar" => $avatar], $id);

                    SESSION::addFlash("error", "Le compte a été supprimé.");

                    $this->redirectTo("forum", "detailUser", $id);
                    exit();

                    // var_dump($user);
                    // die();
                } else {
                    SESSION::addFlash("error", "Le compte que vous souhaitez supprimer n'existe pas.");
                }
            } else {
                SESSION::addFlash("info", "La suppression a été annulée.");
            }
        } else {
            return
                [
                    "view" => VIEW_DIR . "security/deleteUserForm.php",
                    "data" =>
                    [
                        "user" => $userManager->findOneById($id)
                    ]
                ];
        }

    }

    public function deleteAccountForm()
    {
        $user = Session::getUser();

        if (empty($user) || (!Session::getUser())) {
            session_destroy();
        } else {
            // var_dump($user);
            // die();

            return
                [
                    "view" => VIEW_DIR . "security/deleteUserForm.php",
                    "data" =>
                    [
                        "user" => $user,
                        "id" => $user->getId()
                    ]
                ];
        }
    }


    
}

?>