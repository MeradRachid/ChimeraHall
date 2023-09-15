<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\EventManager;
    use Model\Managers\UserManager;
    use Model\Managers\RallyManager;
    use Model\Managers\PostManager;
    use Model\Managers\TypeManager;
    use Model\Managers\FormatManager;

    class HomeController extends AbstractController implements ControllerInterface
    {
        public function index()
        {
            $typeManager = new TypeManager();
            $formatManager = new FormatManager();
            $eventManager = new EventManager();
            $events = $eventManager->findAll(['dateTime', 'DESC']);
            $types = $typeManager->findAll();                        
            $formats = $formatManager->findAll();

            $rallyPoint = $eventManager->rallyCount();
            $rallyUser = $eventManager->userCount();
            $totalEvent = $eventManager->totalEventCount();
            $currentEvent = $eventManager->currentEventCount();
            $typeEvent = $typeManager->typeEventCount();
            $formatEvent = $formatManager->formatEventCount();
            $crewmate = $eventManager->crewMateEventCount();

            // var_dump($typeEvent, $formatEvent);
            // die();

            $lestats = 
            [
                "RallyPoint" => $rallyPoint["RallyPoint"],
                "Planeswalker" => $rallyUser["Planeswalker"],
                "TotalEvent" => $totalEvent["TotalEvent"],
                "CurrentEvent" => $currentEvent["CurrentEvent"],
                "TypeEvent" => $typeEvent["TypeEvent"],
                "FormatEvent" => $formatEvent["FormatEvent"],
                "Crewmate" => $crewmate['Crewmate']
            ];

            // var_dump($lestats);
            // die();

            return 
            [
                "view" => VIEW_DIR . "homePage.php",

                // var_dump($lestats),

                "data" => 
                [
                    "events" => $events,
                    "types" => $types,
                    "formats" => $formats,
                    "lestats" => $lestats
                ]
            ];
        }
  


        // public function users()
        // {
        //     $this->restrictTo("ROLE_USER");

        //     $manager = new UserManager();
        //     $users = $manager->findAll(['registered', 'DESC']);

        //     return 
        //     [
        //         "view" => VIEW_DIR."security/users.php",
        //         "data" => 
        //         [
        //             "users" => $users
        //         ]
        //     ];
        // }



        public function privacyPolicy()
        {
            return 
            [
                "view" => VIEW_DIR."privacyPolicy.php"
            ];
        }



        public function forumRules()
        {
            return 
            [
                "view" => VIEW_DIR."rules.php"
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

        public function searchEvents()
        {
            if(empty($_POST['eventSearch']) || $_POST['eventSearch'] == null)
            {
                // Si l'input submit n'a pas été renseigné, alors redirige vers listEvents 
                SESSION::addFlash("success", "Voici la liste complète des events");

                $this->redirectTo("forum", "listEvents");
                // exit();

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
             else
            { 
                $name = $_POST['eventSearch'];

                $name = filter_input(INPUT_POST, "eventSearch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // var_dump('val = '.$name);
                // die();
            }

            if($name)
            {
                $eventManager = new EventManager();

                $events = $eventManager->findEventsBySearch($name);
                // var_dump($events);
                // die();
            }

            // Si la recherche ne trouve pas de résultats, affichera : "Aucune correspondance trouvée. Pas de résultats possible"
            if(empty($events))
            {
                return
                [
                    "view" => VIEW_DIR."forum/searchResult.php"
                ];
            }
             else
            {
                return
                [
                    "view" => VIEW_DIR."forum/searchResult.php",
    
                    "data" =>
                    [
                        "search" => 
                        [
                            "events" => $events
                        ]
                    ]
                ];
            }
        }

        public function searchUsers()
        {
            if(empty($_POST['eventSearch']) || $_POST['eventSearch'] == null)
            {
                // Si l'input submit n'a pas été renseigné, alors redirige vers listEvents 
                SESSION::addFlash("success", "Voici la liste complète des users");

                $this->redirectTo("forum", "listUsers");
                exit();
            }
             else
            { 
                $name = $_POST['eventSearch'];
                $name = filter_input(INPUT_POST, "eventSearch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            if($name)
            {
                $userManager = new UserManager();
                $users = $userManager->findUserBySearch($name);
            }

            // Si la recherche ne trouve pas de résultats, affichera : "Aucune correspondance trouvée. Pas de résultats possible"
            if(empty($users))
                {
                    return
                    [
                        "view" => VIEW_DIR."forum/searchResult.php"
                    ];
                }

            return
            [
                "view" => VIEW_DIR."forum/searchResult.php",

                "data" =>
                [
                    // "events" => $events,
                    "search" =>
                    [
                        "users" => $users
                    ] 
                ]
            ];
            // $nb = $_GET['nb'];
            // $nb++;
            // include(VIEW_DIR."ajax.php");
        }

        public function searchRallyPoints()
        {
            if(empty($_POST['eventSearch']) || $_POST['eventSearch'] == null)
            {
                // Si l'input submit n'a pas été renseigné, alors redirige vers listEvents 
                SESSION::addFlash("success", "Voici la liste complète des points de ralliements");

                $this->redirectTo("event", "listRallyPoints");
                // exit();
            }
             else
            { 
                $name = $_POST['eventSearch'];

                $name = filter_input(INPUT_POST, "eventSearch", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }

            if($name)
            {
                
                $rallyManager = new RallyManager();
                
                $rallypoints = $rallyManager->findRallyBySearch($name);
                
            }
            
            // var_dump($rallypoints);
            // die();


            // Si la recherche ne trouve pas de résultats, affichera : "Aucune correspondance trouvée. Pas de résultats possible"
            if(empty($rallypoints))
                {
                    return
                    [
                        "view" => VIEW_DIR."forum/searchResult.php"
                    ];
                }

            return
            [
                "view" => VIEW_DIR."forum/searchResult.php",

                "data" =>
                [
                    // "events" => $events,
                    "search" =>
                    [
                        "rallyPoints" => $rallypoints
                    ] 
                ]
            ];
            // $nb = $_GET['nb'];
            // $nb++;
            // include(VIEW_DIR."ajax.php");
        }
    }

?>