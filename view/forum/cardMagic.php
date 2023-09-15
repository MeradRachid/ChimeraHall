<section>
    <h2 class="text-center">API Cartes Magic</h2>
    <?php

$cards = $result['data']['cards'];
// var_dump($cards);

$pageName = "Le Gatherer";
// var_dump($cards);

foreach ($cards as $card) 
{
    // Accéder aux traductions en français
    // $foreignNames = $card['foreignNames'];

    // Parcourir les traductions pour trouver la version française
    // foreach ($foreignNames as $foreignName) 
    // {
    //     if ($foreignName['language'] === 'French') 
    //     {
    //         $cardNameFr = $foreignName['name'];
    //         $cardTextFr = $foreignName['text'];
    //         break;
    //     }
    // }
    
    $imageURL = $card[4]['imageUrl'];
    $cardName = $card[4]['name'];

    $imageURL1 = $card[6]['imageUrl'];
    $cardName1 = $card[6]['name'];

    $imageURL2 = $card[7]['imageUrl'];
    $cardName2 = $card[7]['name'];
    
    // var_dump($card['foreignNames']['language']);
    
    echo '<p>'. $cardName.
    '<br> <img src="' . $imageURL . '" alt="' . $cardName . '" width="280" height="360">';

    echo '<p>'. $cardName1.
    '<br> <img src="' . $imageURL1 . '" alt="' . $cardName1 . '" width="280" height="360">';

    echo '<p>'. $cardName2.
    '<br> <img src="' . $imageURL2 . '" alt="' . $cardName2 . '" width="280" height="360">';
    
}




?>
</section>