<?php

namespace Controller;

use App\DAO;
use App\Session;
use App\AbstractController;
use App\ControllerInterface;
use Model\Managers\PostManager;
use Model\Managers\UserManager;
use Model\Managers\LikeManager;
use Model\Managers\EventManager;
use Model\Managers\TypeManager;
use Model\Managers\FormatManager;
use Model\Managers\ParticipationManager;
use DateTime; // Import de la classe DateTime


class ForumController extends AbstractController implements ControllerInterface
{
    public function index()
    {
        
        $likeManager = new LikeManager();

        $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null; 
        // Vérifiez que le paramètre event_id est bien défini

        $likes = 0; // Valeur par défaut si $eventId n'est pas défini 

        if (isset($eventId)) 
        {
            $likes = $likeManager->countLikes($eventId);
            $_SESSION['likes'] = $likes;
        } 
         elseif (isset($_SESSION['likes'])) 
        {
            $likes = $_SESSION['likes'];
        }

        $eventManager = new EventManager();

        return
            [
                "view" => VIEW_DIR . "forum/listEvents.php",
                "data" =>
                [
                    "events" => $eventManager->findAll(["dateTime", "DESC"]),

                    // "likes" => $likes
                ]
            ];
    }



    public function postForm()
    {
        return
            [
                "view" => VIEW_DIR . "security/postForm.php",
                $event_id = $_POST['event_id'],
                // var_dump($topic_id) 
            ];
    }

    public function addPost()
    {
        // if submit is pressed
        if (!empty($_POST)) 
        {
            $event_id = filter_input(INPUT_POST, "event_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $message = filter_input(INPUT_POST, "message", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($message && $event_id) 
            {
                $user_id = SESSION::getUser()->getId();

                // var_dump($topic_id, $user_id, $message);
                // die();

                $postManager = new PostManager();

                $postManager->add(["event_id" => $event_id, "user_id" => $user_id, "message" => $message]);

                // Ajout d'un message de succès
                SESSION::addFlash("success", "Bravo, votre post à bien été créé !");

                $this->redirectTo("forum", "detailEvent", $event_id);
            } 
             else 
            {
                // Ajout d'un message d'erreur
                SESSION::addFlash("error", "Une erreur s'est produite, votre post n'a pas été créé !");

                // Redirection
                $this->redirectTo("forum", "detailEvent", $event_id);
            }
        } 
         else 
        {
            // Ajout d'un message d'erreur
            SESSION::addFlash("error", "Saisie incorrecte, votre post n'a pas été créé !");

            // Redirection
            $this->redirectTo("forum", "listEvents");
        }
    }



    // public function addPublication()
    // {
    //     if(isset($_FILES['image'])) 
    //     {
    //         // si on echo $_FILES on obtient un tableau qui contient les infos dans le deuxieme [' '] on les places dans des variables
    //         $tmpName = $_FILES['image']['tmp_name']; 
    //         $name = $_FILES['image']['name']; 
    //         $size = $_FILES['image']['size']; 
    //         $error = $_FILES['image']['error']; 
    //         $tabExtension = explode('.', $name); 
    //         $extension = strtolower(end($tabExtension)); 
    //         //Tableau des extensions que l'on accepte 
    //         $extensions = ['jpg', 'png', 'jpeg', 'gif']; 
    //         //Taille max que l'on accepte 
    //         $maxSize = 400000; 
    //         if(in_array($extension, $extensions) && $size <= $maxSize && $error == 0) 
    //         { 
    //             // Génère un identifiant unique 
    //             $uniqueID = uniqid('', true ); 
    //             $file = 'public/img/'.$uniqueID.'.'.$extension; 
    //             // vaut l'id générer.jpg par exemple 
            
    //             // Déplace un fichier téléchargé 
    //             move_uploaded_file($tmpName , './public/img/'.$name); 
            
    //             // il faut renomée le fichier avec l'uniqueiD et son extension car elle est enreigstrer en base de donnée de cet manière. 
    //             rename('./public/img/'.$name , './public/img/'.$uniqueID.'.'.$extension); $image = $file; 
    //         } 
    //          else
    //         {
    //              Session::addFlash('error', "<p>mauvaise extension ou fichier trop volumineux ! <p>") ; 
    //         };
    //          $userManager->edit(['image' => $image],$id); Session::addFlash('success', "Photo modifier avec succès") ; 
    //          $this->redirectTo("security","showProfileUser",$id); 
    //     }
    // }

 
    public function addPublication($id)
    {
        $eventManager = new EventManager();
        $postManager = new PostManager();

        if (!empty($_POST)) 
        {
            $publicationContenu = filter_input(INPUT_POST, "publicationContenu", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $publicationImage = filter_input(INPUT_POST, "file", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($publicationImage || $publicationContenu) 
            {
                if (isset($_FILES['file'])) 
                {
                    // si on echo $_FILES on obtient un tableau qui contient les infos dans le deuxieme [' '] on les places dans des variables
                    $tmpName = $_FILES['file']['tmp_name'];
                    $name = $_FILES['file']['name']; 
                    $size = $_FILES['file']['size']; 
                    $error = $_FILES['file']['error']; 
                    $tabExtension = explode('.', $name);
                }
            }

            $postManager->add(["message" => $publicationContenu, "event_id" => $id, "image" => $publicationImage, "user_id" => Session::getUser()->getId()]);
        
            return  
            [ 
                "view" => VIEW_DIR . "formPublication.php",
                "data" => 
                [
                    "event" => $eventManager->findOneById($id)
                ]
            ];
        }
    }



    public function listUsers()
    {
        $this->restrictTo("ROLE_ADMIN");
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
        $eventManager = new EventManager();
        $participationManager = new ParticipationManager();
        $postManager = new PostManager();

        $participations = $participationManager->findParticipationsByUser($id);
        $organisations = $eventManager->findEventsByUser($id);
        $posts = $postManager->findPostByUserId($id);
        
//     var_dump($organisations);
// die();
        return
            [
                "view" => VIEW_DIR . "forum/detailUser.php",
                "data" =>
                [
                    "user" => $userManager->findOneById($id),
                    "organisations" => $organisations,
                    "participations" => $participations,
                    "posts" => $posts
                    
                ]
            ];
    }



    // public function deleteById($id)
    // {
    //     $dataSuppr = $_POST['dataSuppr']; // on récupère en hidden l'entité de l'id à suppr, par exemple : User, Event, etc.  

    //     $manager = null; // Manager générique qui sera du type de l'entité qu'on souhaite lui attribuer
    //     $element = null; // les datas de l'élément à suppr. 

    //     switch ($dataSuppr) 
    //     {
    //         case "event":
    //             $manager = new EventManager();
    //             $element = $manager->findOneById($id);
    //             break;
    //         case "user":
    //             $manager = new UserManager();
    //             $element = $manager->findOneById($id);
    //             break;
    //         // On pourrait donc ajouter autant de cases à la suite que besoin

    //         // Si la clé est inconnue, renvoyer un message d'erreur à l'utilisateur
    //         default:
    //             SESSION::addFlash('error', 'Entité ou ID non reconnue');
    //     }

    //     if (!empty($_POST)) 
    //     {
    //         $confirm = $_POST['confirm'] ?? '';

    //         if ($confirm === 'oui') 
    //         {   
    //             /** Le triple égal compare la valeur et le type de données, ce qui implique aussi la casse des caractères.
    //              *  Il est préférable d'utiliser une comparaison stricte pour s'assurer que la comparaison est correcte.
    //              *  La fonction strcasecmp peut comparer deux chaînes de caractères sans prendre en compte la casse si besoin.
    //              *  Si l'on utilisait une comparaison avec True & False, il est possible que cela fonctionne dans ce cas-ci. 
    //              *  Mais cela pourrait ne pas être fiable dans d'autres situations similaires où des valeurs différentes de zéro seraient interprétées comme True.
    //             */

    //             if ($element !== null) 
    //             {
    //                 $manager->delete($element);
    //                 SESSION::addFlash("success", "L'élément a bien été supprimé.");
    //                 $this->redirectTo("homePage");
    //                 exit();
    //             } 
    //             else 
    //             {
    //                 SESSION::addFlash("error", "L'élément que vous souhaitez supprimer n'existe pas.");
    //             }
    //         } 
    //         else
    //         {
    //             SESSION::addFlash("info", "La suppression a été annulée.");
    //         }
    //     }
    //     else
    //     {
    //         return
    //         [
    //             "view" => VIEW_DIR . "security/deleteForm.php",
    //             "data" =>
    //             [
    //                 "dataSuppr" => $dataSuppr,
    //                 "element" => $element
    //             ]
    //         ];
    //     }
    // }

    // public function deleteForm($id)
    // {
    //     $eventManager = new EventManager();

    //     // $id = $_GET['id']; 
    //     return
    //         [
    //             "view" => VIEW_DIR . "security/deleteForm.php",
    //             "data" =>
    //                 [
    //                     "event" => $eventManager->findOneById($id)
    //                 ],

    //             $id,
    //             // var_dump($id)
    //         ];
    // }



    public function like($id)
    {
        // if button submit pressed
        if (empty($_POST)) 
        {
            $likeManager = new LikeManager();

            // get the user in session 
            $user = SESSION::getUser()->getId();

            // get the id of the event 
            $event = $id;

            // look if there is a duplicate of the user and the event 
            $userLike = $likeManager->findOneByEvent($event);

            // if the user hasn't liked the event then 
            if (!$userLike) 
            {
                $likeManager->add(["user_id" => $user, "event_id" => $event]);
                header("location:index.php?ctrl=forum&action=detailEvent&id=" . $event);
            } 
            else 
            {
                // else if the user has already liked then delete the like from db 
                $likeManager->deleteLike($event, $user); // and redirect to the event page 
                header("location:index.php?ctrl=forum&action=detailEvent&id=" . $event);
            }
            // count the number of like on a event 
            $likeManager->countLikes($event);
        }

    }


}

?>