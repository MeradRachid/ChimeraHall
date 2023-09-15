<section>
<?php
$pageName = "Recherche";

  if (!empty($result["data"]["search"])) 
  {
    $search = $result["data"]["search"];

    if (isset($search["users"])) 
    {
      $users = $search["users"];

      foreach ($users as $user) 
      {
        echo
        '<p> # Membre n° : ' . $user->getId() .
          " <br> > " . $user->getPseudo() .
          " <br> Rôle du Membre : ";
        if ($user->getRole() == "ROLE_ADMIN") 
        {
          echo "Administrateur";
        }
         else 
        {
          echo "Planeswalker";
        }
        echo " : <br> (Lien) ‘ Voir plus ’ (/Lien)" .
        '</p>';
      }
    } 
     elseif (isset($search["events"])) 
    {
      $events = $search["events"];

      foreach ($events as $event) 
      {
        echo 
        '<p> # Event n° : ' . $event->getId() .
          "<br> > " . $event->getName() .
          "<br> Adresse : ".$event->getRally()->getAdress() .
          "<br> Statut de l'évent : ";
        if ($event->getEventLocked() == true) 
        {
          echo "Terminé/Indisponible";
        }
          else 
        {
          echo "Disponible/En Cours";
        }
          echo ": <br> (Lien) ‘ Voir plus ’ (/Lien)" .
        '</p>';
      }
    }
    elseif (isset($search["rallyPoints"])) 
    {
      $rallypoints = $search["rallyPoints"];

      foreach ($rallypoints as $rallypoint) 
      {
        echo

        '<p> # Point de ralliement n° : '. $rallypoint->getId() .
        // var_dump('💘');
        // die();
          " <br> > ". $rallypoint->getAdress() .
          " <br> Code postal : ". $rallypoint->getZipCode() .
          " <br> Ville : ". $rallypoint->getCity();
        // if ($user->getRole() == "ROLE_ADMIN") 
        // {
        //   echo "Administrateur";
        // }
        //  else 
        // {
        //   echo "Planeswalker";
        // }
        echo " : <br> (Lien) ‘ Voir plus ’ (/Lien)" .
        '</p>';
      }
    }
  }
   else 
  {
    echo '<p>~ Aucune correspondance trouvée ~</p>';
  }

?>
</section>
