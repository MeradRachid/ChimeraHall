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
use Model\Managers\RallyManager;
use Model\Managers\TypeManager;
use Model\Managers\FormatManager;
use Model\Managers\ParticipationManager;
use DateTime; // Import de la classe DateTime



class EventController extends AbstractController implements ControllerInterface
{
    public function index()
    {

        $likeManager = new LikeManager();

        $eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null; // Vérifiez que le paramètre event_id est bien défini

        $likes = 0; // Valeur par défaut si $eventId n'est pas défini 

        if (isset($eventId)) {
            $likes = $likeManager->countLikes($eventId);
            $_SESSION['likes'] = $likes;
        } elseif (isset($_SESSION['likes'])) {
            $likes = $_SESSION['likes'];
        }

        $eventManager = new EventManager();

        return
            [
                "view" => VIEW_DIR . "forum/listEvents.php",
                "data" =>
                [
                    "events" => $eventManager->findAll(["dateTime", "DESC"])
                ]
            ];
    }



    public function listEvents()
    {
        $eventManager = new EventManager();

        return
            [
                "view" => VIEW_DIR . "forum/listEvents.php",
                "data" =>
                [
                    "events" => $eventManager->findAll(["dateTime", "DESC"])
                ]
            ];
    }



    public function listRallypoints()
    {
        $rallyManager = new RallyManager();

        return
            [
                "view" => VIEW_DIR . "forum/listRallyPoints.php",
                "data" =>
                [
                    "rallyPoints" => $rallyManager->findAll()
                ]
            ];
    }



    public function detailRally($id)
    {
        $rallyManager = new RallyManager();
        $eventManager = new EventManager();

        $rallyPoint = $rallyManager->findOneById($id);
        $event = $eventManager->findOneById($id);
        return
            [
                "view" => VIEW_DIR . "forum/detailRallyPoint.php",
                "data" =>
                [
                    "event" => $event,

                    "rallyPoint" => $rallyPoint
                ]
            ];
    }



    public function detailEvent($id)
    {
        $postManager = new PostManager();
        $likeManager = new LikeManager();
        $eventManager = new EventManager();
        $rallyManager = new RallyManager();
        $participationManager = new ParticipationManager();

        $event = $eventManager->findOneById($id);
        $rallyPoint = $rallyManager->findOneById($id);
        $label = $event->getFormat()->getLabel();
        $user = Session::getUser()->getId();

        // Récupérer le nombre de likes à partir de la session, s'il existe
        $likes = isset($_SESSION['likes']) ? $_SESSION['likes'] : null;

        // Si le nombre de likes n'est pas enregistré en session, le récupérer de la base de données
        if (!$likes) {
            $likes = $likeManager->countLikes($id);

            // Enregistrer le nombre de likes en session
            $_SESSION['likes'] = $likes;
        }

        // Récupérer le nombre de reservations de $id à partir de la session, s'il existe
        $reservations = isset($_SESSION['reservations'][$id]) ? $_SESSION['reservations'][$id] : null;

        // Si le nombre de reservations n'est pas mis en session, on le récupère via la base de données
        if (!$reservations) {
            $likes = $participationManager->countParticipations($id);

            // Mettre à jour le tableau $_SESSION['reservations'] avec le nombre de reservations pour l'event correspondant
            $_SESSION['reservations'][$id] = $reservations;
        }
        // var_dump($rallyPoint);
        // die();
        return
            [
                "view" => VIEW_DIR . "forum/detailEvent.php",
                "data" =>
                [
                    "event" => $eventManager->findOneById($id),

                    "rallyPoint" => $rallyPoint,

                    // récupère le label pour renvoyer la clé correspondante
                    "format_id" => $eventManager->findIdByLabel($label),

                    "reservations" => $reservations,

                    "posts" => $postManager->findOneByEvent($id),

                    "likes" => $likes
                ]
            ];
    }



    public function eventForm()
    {
        $typeManager = new TypeManager();
        $formatManager = new FormatManager();
        $rallyManager = new RallyManager();

        return
            [
                "view" => VIEW_DIR . "security/eventForm.php",
                "data" =>
                [
                    "types" => $typeManager->findAll(),

                    "formats" => $formatManager->findAll(),

                    "rallyPoints" => $rallyManager->findAll()
                ]
            ];
    }



    public function addEvent()
    {
        // if submit is pressed
        if (!empty($_POST)) {
            // var_dump($_POST);
            // die();
            $token = $_POST['csrf_token'];

            // Vérifier le jeton CSRF
            if ($token === $_SESSION['csrf_token']) {
                // Le jeton CSRF est valide, traiter la demande

                // var_dump('Token Form : '.$token, 'Token Session : '.$_SESSION['csrf_token']);
                // die();
                $user_id = SESSION::getUser()->getId();

                $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $type_id = filter_input(INPUT_POST, "type_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $format_id = filter_input(INPUT_POST, "format_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $rally_id = filter_input(INPUT_POST, "rally_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $Time = filter_input(INPUT_POST, "Time", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $dateTimeString = $date . ' ' . $Time; // Concaténation de la Date au format 'Y-m-d' et de l'Heure au format 'H:i'
                $dateFormat = new DateTime($dateTimeString); // Création d'un objet DateTime à partir de la chaîne de caractères
                $dateTime = $dateFormat->format('Y-m-d H:i:s'); // Formatage de la date au format 'YYYY-mm-dd HH:ii:ss'

                $nbMaxPlayer = filter_input(INPUT_POST, "nbMaxPlayer", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                // Vérification si la valeur est numérique
                if (is_numeric($nbMaxPlayer)) {
                    // La valeur est un nombre, on peut continuer
                    if ($nbMaxPlayer < 1 || $nbMaxPlayer > 999) {
                        $nbMaxPlayer = 9999;
                    }
                    // var_dump($nbMaxPlayer);
                    // die();
                    if ($user_id && $name && $type_id && $format_id && $rally_id && $description && $dateTime && $nbMaxPlayer) {

                        $eventManager = new EventManager();

                        $eventManager->add(["user_id" => $user_id, "name" => $name, "type_id" => $type_id, "format_id" => $format_id, "rally_id" => $rally_id, "description" => $description, "dateTime" => $dateTime, "nbMaxPlayer" => $nbMaxPlayer], true);

                        // Ajout d'un message de succès
                        SESSION::addFlash("success", "Bravo, votre event à bien été créé !");

                        $this->redirectTo("event", "listEvents");
                    } else {
                        // Ajout d'un message d'erreur
                        SESSION::addFlash("error", "Une erreur s'est produite, votre event n'a pas été créé !");

                        // Redirection
                        $this->redirectTo("event", "eventForm");
                    }
                } else {
                    // Ajout d'un message d'erreur
                    SESSION::addFlash("error", "Capacité d'accueil incorrecte, votre event n'a pas été créé !");

                    // Redirection
                    $this->redirectTo("event", "eventForm");
                }
            } else {
                // Le jeton CSRF est invalide, rejeter la demande et effectuer d'autres actions de sécurité

                // var_dump('Token Form : '.$token, 'Token Session : '.$_SESSION['csrf_token']);
                // die();

                session_destroy();
                
                SESSION::addFlash("error", "Vous n'êtes pas autorisé à effectuer cette action. Veuillez-vous connecter avant de recommencer ! ");
                $this->redirectTo("security", "loginForm");
                exit();
            }
        } else {
            // Ajout d'un message d'erreur
            SESSION::addFlash("error", "Saisie incorrecte, votre event n'a pas été créé !");

            // Redirection
            $this->redirectTo("event", "eventForm");
        }
    }



    public function updateForm($id)
    {
        $typeManager = new TypeManager();
        $formatManager = new FormatManager();
        $eventManager = new EventManager();

        return
            [
                "view" => VIEW_DIR . "security/updateForm.php",
                "data" =>
                [
                    "types" => $typeManager->findAll(),

                    "formats" => $formatManager->findAll(),

                    "event" => $eventManager->findOneById($id)
                ],

                $id,
                // var_dump($id)
            ];
    }

    public function updateEvent($id)
    {
        // On Vérifie si l'utilisateur est connecté 
        $user = Session::getUser();

        // On instancie l'eventManager pour gérer les événements
        $eventManager = new EventManager();

        // On Récupère l'événement à modifier
        $event = $eventManager->findOneById($id);

        if (empty($user) || (!Session::isAdmin() && $event->getUser()->getId() !== $user->getId())) {
            // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
            $this->redirectTo("security", "loginForm");
        }

        if (!$event || ($event->getUser()->getId() !== $user->getId() && !$user->isAdmin())) {
            // Si l'utilisateur n'est pas autorisé à modifier l'événement, redirection vers la liste des événements
            $this->redirectTo("event", "listEvents");
        }

        if (!empty($_POST)) {
            $user_id = SESSION::getUser()->getId();

            /** 
             * On utilisera le new Update pour le CRUD => Edit !!  
             * Pour cela on va avoir besoin d'un tableau $data[]
             * */

            $data = [];

            $dateProvided = !empty($_POST['date']);
            $timeProvided = !empty($_POST['Time']);

            // Si l'heure &/Ou la date on été fournies 
            if ($dateProvided || $timeProvided) {
                // Si la date & l'heure ont été fournies
                if ($dateProvided && $timeProvided) {
                    $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $time = filter_input(INPUT_POST, "Time", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $dateTimeString = $date . ' ' . $time;

                    // Création d'un objet DateTime à partir de la chaîne de caractères
                    $dateFormat = new DateTime($dateTimeString);

                    // Formatage de la date au format 'YYYY-mm-dd HH:ii'
                    $dateTime = $dateFormat->format('Y-m-d H:i');

                    if (!empty($data)) {
                        // Vérification si un objet DateTime existe déjà dans $data
                        $existingDateTime = new DateTime($data['dateTime']);

                        if ($existingDateTime == $dateFormat) {
                            // Les deux objets DateTime sont identiques, pas besoin d'ajouter un nouvel objet
                            // $data['dateTime'] = $data['dateTime'];
                        } else {
                            // Les objets DateTime sont différents, on peut ajouter le nouvel objet DateTime au tableau de données
                            $data['dateTime'] = $dateTime;
                        }
                    } else {
                        // Aucun objet DateTime dans $data, on peut ajouter le nouvel objet DateTime au tableau de données
                        $data['dateTime'] = $dateTime;
                    }
                }
                // Quand la date seule a été fournie
                elseif ($dateProvided) {
                    $date = filter_input(INPUT_POST, "date", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    // On récupère le DateTime en base de données puis on le formate pour avoir le chaînon manquant

                    $existingDateTime = $event->getDateTime();

                    $existingTime = $existingDateTime->format('H:i');

                    $dateTimeString = $date . ' ' . $existingTime;
                    // Création d'un objet DateTime à partir de la chaîne de caractères
                    $dateFormat = new DateTime($dateTimeString);

                    // Formatage de la date au format 'YYYY-mm-dd HH:ii'
                    $dateTime = $dateFormat->format('Y-m-d H:i');

                    // Ajout du nouvel objet DateTime modifié au tableau de données
                    $data['dateTime'] = $dateTime;

                }
                // Quand l'heure seule a été fournie
                elseif ($timeProvided) {
                    $time = filter_input(INPUT_POST, "Time", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    // On récupère le DateTime en base de données puis on le formate pour avoir le chaînon manquant
                    $existingDateTime = $event->getDateTime();
                    $existingDate = $existingDateTime->format('Y-m-d');

                    $dateTimeString = $existingDate . ' ' . $time;
                    // Création d'un objet DateTime à partir de la chaîne de caractères
                    $dateFormat = new DateTime($dateTimeString);

                    // Formatage de la date au format 'YYYY-mm-dd HH:ii'
                    $dateTime = $dateFormat->format('Y-m-d H:i');

                    // Ajout du nouvel objet DateTime modifié au tableau de données
                    $data['dateTime'] = $dateTime;

                    // var_dump('$data = $dateTime : ', $dateTime);
                    // die();
                }
            } else {
                // Si Aucune date ni heure fournie dans le formulaire
                // $dateTime = $event->getDateTime();

                // Ajout du DateTime non modifié au tableau de données
                // $data['dateTime'] = $dateTime;
            }

            if (!empty($_POST['name'])) {
                $name = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['name'] = $name;
            }

            if (!empty($_POST['type_id'])) {
                $type_id = filter_input(INPUT_POST, "type_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['type_id'] = $type_id;
            }

            if (!empty($_POST['format_id'])) {
                $format_id = filter_input(INPUT_POST, "format_id", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['format_id'] = $format_id;
            }

            if (!empty($_POST['adress'])) {
                $adress = filter_input(INPUT_POST, "adress", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['adress'] = $adress;
            }

            if (!empty($_POST['zipCode'])) {
                $zipCode = filter_input(INPUT_POST, "zipCode", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['zipCode'] = $zipCode;
            }

            if (!empty($_POST['city'])) {
                $city = filter_input(INPUT_POST, "city", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['city'] = $city;
            }

            if (!empty($_POST['nbMaxPlayer'])) {
                $nbMaxPlayer = filter_input(INPUT_POST, "nbMaxPlayer", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['nbMaxPlayer'] = $nbMaxPlayer;
            }

            if (!empty($_POST['description'])) {
                $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                $data['description'] = $description;
            }

            // var_dump('$data = Tableau des données à edit : ', $data);
            // die();

            /**
             * Attention !!! Le EDIT ne fonctionne pas comme le UPDATE : 
             * Deux pistes à vérifier >> 
             * ~ La première = Il est probablement obligatoire de setter avant de pouvoir édit 
             * ~ La seconde = Il est possible qu'on ne puisse pas edit plus d'une chose à la fois 
             */

            // Mettre à jour l'événement dans la base de données avec l'edit grâce au tableau data[] 
            $eventManager->edit($data, $id);

            // Ajout d'un message de succès
            SESSION::addFlash("success", "L'événement a été modifié avec succès !");
            // die();
            // Redirection vers la liste des événements
            $this->redirectTo("event", "listEvents");
        }

        // Affiche le formulaire de modification de l'événement
        return
            [
                "view" => VIEW_DIR . "security/updateForm.php",
                "data" =>
                [
                    "event" => $eventManager->findOneById($id)
                ]
            ];
    }

    /** Prochaine étape : 
     * Il est recommandé d'ajouter un jeton CSRF à chaque formulaire, 
     * de vérifier ce jeton lors de la soumission du formulaire
     * et de le régénérer à chaque modification de l'état de l'utilisateur.        
     * */

    public function lockEvent($id)
    {
        // On Vérifie si l'utilisateur est connecté 
        $user = Session::getUser();

        // On instancie l'eventManager pour gérer les événements
        $eventManager = new EventManager();

        // On Récupère l'événement à modifier
        $event = $eventManager->findOneById($id);

        // récupère le label pour renvoyer la clé correspondante
        $label = $event->getFormat()->getLabel();

        // var_dump($format_id);
        // die();

        if (empty($user) || (!Session::isAdmin() && $event->getUser()->getId() !== $user->getId())) {
            // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
            $this->redirectTo("security", "loginForm");
            exit();
        } elseif (!$event || ($event->getUser()->getId() !== $user->getId() && !Session::isAdmin())) {
            // Si l'utilisateur n'est pas autorisé à modifier l'événement (ce qui inclu le lock ou la suppression), redirection vers la page de connexion
            session_destroy();
            SESSION::addFlash("error", "Vous n'êtes pas autorisé à modifier cet événement. Veuillez-vous connecter avant de recommencer ! "); // que vous souhaitez verrouiller n'existe pas.
            $this->redirectTo("security", "loginForm");
            exit();
        }

        // If submit is pressed without input
        if (empty($_POST)) {
            if ($event->getEventLocked() == true) {
                $event->setEventLocked(0);

                // On update la base de données
                $eventLocked = $event->getEventLocked();
                $eventManager->edit(["eventLocked" => $eventLocked], $id);

                // Ajout d'un message de succès
                SESSION::addFlash("success", "L'événement a été déverrouillé avec succès !");
                $this->redirectTo("event", "listEvents");
                exit();
            } else {
                $event->setEventLocked(1);
                // var_dump($event->getEventLocked());

                // On update la base de données
                $eventLocked = $event->getEventLocked();

                // var_dump($name, $type_id, $format_id, $dateTime, $adress, $zipCode, $city, $nbMaxPlayer, $eventLocked);
                // // die();

                $eventManager->edit(["eventLocked" => $eventLocked], $id);

                // Ajout d'un message de succès
                SESSION::addFlash("success", "L'événement a été verrouillé.");
                $this->redirectTo("event", "listEvents");
                exit();
            }
        }
    }



    public function editName($id)
    {
        $eventManager = new EventManager();
        $event = $eventManager->findOneById($id);
        $name = $event->getName();
        $newName = 'Nouvel intitulé';
        $name = $newName;
        // var_dump($name);
        // die();
        $eventManager->edit(["name" => $name], $id);
    }



    public function deleteEvent($id)
    {
        $eventManager = new EventManager();

        if (!empty($_POST)) {
            $confirm = $_POST['confirm'] ?? '';

            if ($confirm === 'oui') {
                // Le triple égal compare la valeur et le type de données, y compris la casse des caractères. 
                // il est préférable d'utiliser une comparaison stricte pour s'assurer que la comparaison est correcte. 
                // La fonction strcasecmp peut comparer deux chaînes de caractères sans prendre en compte la casse si besoin.
                // Si l'on utilisait une comparaison avec True & False, il est possible que cela fonctionne dans ce cas-ci ; 
                // Mais cela pourrait ne pas être fiable dans d'autres situations similaires où des valeurs différentes de zéro sont interprétées comme True.

                $event = $eventManager->findOneById($id);

                if ($event !== null) {
                    $eventManager->delete($id); // passage de l'identifiant de l'événement à supprimer
                    SESSION::addFlash("success", "L'événement a bien été supprimé.");
                    $this->redirectTo("event", "listEvents");
                    exit();
                } else {
                    SESSION::addFlash("error", "L'événement que vous souhaitez supprimer n'existe pas.");
                }
            } else {
                SESSION::addFlash("info", "La suppression a été annulée.");
            }
        } else {
            return
                [
                    "view" => VIEW_DIR . "security/deleteForm.php",
                    "data" =>
                    [
                        "event" => $eventManager->findOneById($id)
                    ]
                ];
        }
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

    public function deleteForm($id)
    {
        $eventManager = new EventManager();

        // $id = $_GET['id']; 
        return
            [
                "view" => VIEW_DIR . "security/deleteForm.php",
                "data" =>
                [
                    "event" => $eventManager->findOneById($id)
                ],

                $id,
                // var_dump($id)
            ];
    }



    public function search($name)
    {
        /*        if(empty($_POST) || $_POST['eventSearch'] == null)
                {
                    // Si l'input submit n'a pas été renseigné, alors redirige vers listEvents 
                    SESSION::addFlash("success", "Voici la liste complète des events");

                    $eventManager = new EventManager();

                    return
                    [
                        "view" => VIEW_DIR."forum/searchResult.php",
                        "data" =>
                        [
                            "events" => $eventManager->findAll(["dateTime", "DESC"]),
                        ]
                    ];
                }
                 else*/if (!empty($_POST['eventSearch'])) {
            $name = $_POST['eventSearch'];

            $name = filter_input(INPUT_POST, "eventSearch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }

        if ($name) {
            $eventManager = new EventManager();

            // $data = $_POST['eventSearch'];

            // $data = filter_input(INPUT_POST, "eventSearch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);            

            // var_dump($name);
            // die();
            $search = $eventManager->findEventsByName($name);

        }
        // var_dump($name);
        // die();



        return
            [
                "view" => VIEW_DIR . "forum/searchResult.php",
                "data" =>
                [
                    "events" => $eventManager->findAll(["dateTime", "DESC"]),
                    "eventSearch" => $search
                ]
            ];

        // $eventManager = new EventManager();
        // $events = $eventManager->findAll(['dateTime', 'DESC']);

        // $eventSearch = $eventManager->findEventsByName($name);

        // // var_dump($name);
        // // die();

        // return
        // [
        //     "view" => VIEW_DIR . "homePage.php",

        //     "data" =>
        //     [
        //         "events" => $events,

        //         "eventSearch" => $eventSearch
        //     ]
        // ];
    }



    public function addParticipation($id)
    {
        $participationManager = new ParticipationManager();
        $eventManager = new EventManager();

        $event = $eventManager->findOneById($id);
        $event_id = $id;

        if (!empty($_POST)) {
            $confirm = $_POST['confirm'] ?? '';

            if ($confirm === 'oui') {

                // Le triple égal compare la valeur et le type de données, y compris la casse des caractères. 
                // il est préférable d'utiliser une comparaison stricte pour s'assurer que la comparaison est correcte. 
                // La fonction strcasecmp peut comparer deux chaînes de caractères sans prendre en compte la casse si besoin.
                // Si l'on utilisait une comparaison avec True & False, il est possible que cela fonctionne dans ce cas-ci ; 
                // Mais cela pourrait ne pas être fiable dans d'autres situations similaires où des valeurs différentes de zéro sont interprétées comme True.

                $user_id = SESSION::getUser()->getId(); // On get l'user en session

                // On vérifie s'il fait déjà parti des participants  
                $participated = $participationManager->findOneByPseudo($user_id, $id);

                // S'il n'a fait aucune réservation : 
                if (!$participated) {
                    if ($user_id && $event_id) {
                        $participationManager->add(["event_id" => $event_id, "user_id" => $user_id,]); // passage de l'identifiant de l'événement et de l'user souhaité 
                        SESSION::addFlash("success", "Vous êtes bien inscrit à l'événement " . $event->getName() . ".");

                        // count the number of reservations 
                        $reservations = $participationManager->countParticipations($event_id);

                        // Mettre à jour le tableau $_SESSION['reservations'] avec le nombre de reservations pour l'event correspondant
                        $_SESSION['reservations'][$event_id] = $reservations;

                        $this->redirectTo("event", "listEvents");
                        exit();
                    }
                } else {
                    // S'il est déjà inscrit à cet événement : 
                    SESSION::addFlash("success", "Vous êtes déjà inscrit à l'événement " . $event->getName() . ".");

                    $this->redirectTo("event", "listEvents");
                    exit();
                }

            } else {
                SESSION::addFlash("error", "L'événement auquel vous souhaitez participer n'existe pas ou plus.");
            }
        } else {
            return
                [
                    "view" => VIEW_DIR . "security/participationForm.php",
                    "data" =>
                    [
                        // $id,
                        // "participation" => $participationManager->findParticipationByEvent($id),
                        "event" => $event,
                        // $user_id = SESSION::getUser()->getId(),

                        // var_dump($user_id, $id)
                    ]
                ];

        }

    }

    public function deleteParticipation($id)
    {
        $participationManager = new ParticipationManager();
        $eventManager = new EventManager();

        $event = $eventManager->findOneById($id);
        $event_id = $id;

        if (!empty($_POST)) {
            $confirm = $_POST['confirm'] ?? '';

            if ($confirm === 'oui') {
                // Le triple égal compare la valeur et le type de données, y compris la casse des caractères. 
                // il est préférable d'utiliser une comparaison stricte pour s'assurer que la comparaison est correcte. 
                // La fonction strcasecmp peut comparer deux chaînes de caractères sans prendre en compte la casse si besoin.
                // Si l'on utilisait une comparaison avec True & False, il est possible que cela fonctionne dans ce cas-ci ; 
                // Mais cela pourrait ne pas être fiable dans d'autres situations similaires où des valeurs différentes de zéro sont interprétées comme True.

                $user_id = SESSION::getUser()->getId(); // On get l'user en session

                // On vérifie s'il fait déjà parti des participants  
                $participated = $participationManager->findOneByPseudo($user_id, $event_id);


                // S'il est déjà inscrit à cet événement :
                if ($participated) {
                    // var_dump($user_id, $event_id);
                    // die();
                    if ($user_id && $event_id) {
                        $participationManager->retire($event_id, $user_id); // passage de l'identifiant de l'événement et de l'user souhaité 
                        SESSION::addFlash("success", "Vous n'êtes plus inscrit à l'événement " . $event->getName() . ".");

                        // count the number of reservations 
                        $reservations = $participationManager->countParticipations($event_id);

                        // Mettre à jour le tableau $_SESSION['reservations'] avec le nombre de reservations pour l'event correspondant
                        $_SESSION['reservations'][$event_id] = $reservations;

                        $this->redirectTo("event", "listEvents");
                        exit();
                    }
                } else {
                    // S'il n'a fait aucune réservation :                 
                    SESSION::addFlash("success", "Vous n'êtes pas inscrit à l'événement " . $event->getName() . ".");

                    $this->redirectTo("event", "listEvents");
                    exit();
                }
            } else {
                SESSION::addFlash("error", "L'événement auquel vous souhaitez vous rétracter n'existe pas ou plus.");
            }
        } else {
            return
                [
                    "view" => VIEW_DIR . "security/deleteParticipationForm.php",
                    "data" =>
                    [
                        // $id,
                        // "participation" => $participationManager->findParticipationByEvent($id),
                        "event" => $event,
                        // $user_id = SESSION::getUser()->getId(),

                        // var_dump($user_id, $id)
                    ]
                ];
        }
    }

    public function listParticipationsById($id)
    {
        $participationManager = new ParticipationManager();


        $event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null; // Vérifiez que le paramètre event_id est bien défini

        $reservations = $participationManager->countParticipations($id); // Valeur par défaut si $event_id n'est pas défini 

        if (isset($event_id)) {
            $reservations = $participationManager->countParticipations($event_id);

            $_SESSION['reservations'] = $reservations;
        } elseif (isset($_SESSION['reservations'])) {
            $reservations = $_SESSION['reservations'];
        }

        var_dump($reservations); // afficher le nombre de réservations
        die();

        // return
        //     [
        //         "view" => VIEW_DIR . "forum/detailEvent.php",
        //         "data3" =>
        //         [
        //             "reservations" => $participationManager->findParticipationsByEvent(["event_id", "DESC"], $id),
        //         ]
        //     ];

    }

    public function participationForm($id)
    {
        $eventManager = new EventManager();

        return
            [
                "view" => VIEW_DIR . "security/participationForm.php",
                "data" =>
                [
                    "event" => $eventManager->findOneById($id)
                ]

                // var_dump($id)
            ];
    }



    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['file'])) {
                $file = $_FILES['file'];

                // Vérifier s'il y a des erreurs lors du téléchargement
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $tmpFilePath = $file['tmp_name'];

                    // Déplacer le fichier téléchargé vers un emplacement permanent
                    $uploadDir = 'uploads/';
                    $filename = basename($file['name']);
                    $uploadPath = $uploadDir . $filename;

                    if (move_uploaded_file($tmpFilePath, $uploadPath)) {
                        echo 'Le fichier a été téléchargé avec succès.';
                    } else {
                        echo 'Une erreur est survenue lors du téléchargement du fichier.';
                    }
                } else {
                    echo 'Une erreur est survenue lors du téléchargement du fichier : ' . $file['error'];
                }
            }
        }
    }



    public function like($id)
    {
        // if button submit pressed
        if (empty($_POST)) {
            $likeManager = new LikeManager();

            // get the user in session 
            $user = SESSION::getUser()->getId();

            // get the id of the event 
            $event = $id;

            // look if there is a duplicate of the user and the event 
            $userLike = $likeManager->findOneByEvent($event);

            // if the user hasn't liked the event then 
            if (!$userLike) {
                $likeManager->add(["user_id" => $user, "event_id" => $event]);
                header("location:index.php?ctrl=event&action=detailEvent&id=" . $event);
            } else {
                // else if the user has already liked then delete the like from db 
                $likeManager->deleteLike($event, $user); // and redirect to the event page 
                header("location:index.php?ctrl=event&action=detailEvent&id=" . $event);
            }
            // count the number of like on a event 
            $likeManager->countLikes($event);
        }
    }

}