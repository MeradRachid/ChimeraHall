<?php

    namespace Controller;

    use App\Session;
    use App\AbstractController;
    use App\ControllerInterface;
    use Model\Managers\EventManager;
    use Model\Managers\UserManager;
    use Model\Managers\TopicManager;
    use Model\Managers\PostManager;
    use Model\Managers\TypeManager;
    use Model\Managers\FormatManager;



    class ApiController extends AbstractController implements ControllerInterface
    {
        public function index()
        {

        }
        
        // public function getRandomCard()
        // {
        //     $curl = curl_init();
            
        //     curl_setopt_array($curl, array(
        //                         CURLOPT_URL => 'https://api.scryfall.com/cards/random',
        //                         CURLOPT_RETURNTRANSFER => true,
        //                         CURLOPT_ENCODING => '',
        //                         CURLOPT_MAXREDIRS => 10,
        //                         CURLOPT_TIMEOUT => 0,
        //                         CURLOPT_FOLLOWLOCATION => true,
        //                         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //                         CURLOPT_CUSTOMREQUEST => 'GET',
        //                     ));
            
        //     $response = curl_exec($curl);
            
        //     curl_close($curl);
            
        //     return
        //     [
        //         "view" => VIEW_DIR . "homePage.php",

        //         "data" =>
        //         [
        //             "randomCard" => $response
        //         ]

        //     ];
            
        // }

        public function magic()
        {
            return 
            [
                "view" => VIEW_DIR."listEvents.php"
            ];
        }

        public function magiCard()
        {
            $cards = "https://api.magicthegathering.io/v1/cards";
    
            // Send GET request to the API
            $cards = file_get_contents($cards);
    
            // Handle the response
            if ($cards) {
                // Convert JSON response to PHP array
                $cards = json_decode($cards, true);
    
                return
                    [
                        "view" => VIEW_DIR."forum/cardMagic.php",
                        "data" => 
                        [
                            'cards' => $cards
                        ]
                    ];    
            } 
             else 
            {
                // Handle error if request fails
                echo "API request failed.";
            }
        }

    }

?>