<?php
$pageName = "Profil Public";

    $user = $result["data"]['user'];
    $participations = $result['data']['participations'];
    $organisations = $result['data']['organisations'];
    $posts = $result['data']['posts'];

    // var_dump($posts);
?>
<section>


<h1>~ Bienvenue ~</h1>

<h2 class="text-center"> Détail du Profil</h2>

    <p> 
        <?=$user->getPseudo()?> <br>
        <em> <?=$user->getRegistered()?> </em>
        <!-- <span class="red"> < ?=$user->getRole()?> </span> -->
    </p>

    <!-- <p class="text-justify"> To do list : <br>
        -- Afficher la liste des Events &/Ou Posts -- <br>
         ~ Que l'user à créé d'une part..  
           et dans lesquels il a participé d'autre part ~ <br>
           => ("findEvents", "findParticipations" & "findPosts" ~ ByUser($id)) ? 
    </p> -->

    <table>
        <thead>
            <th>
                <!-- <div class="line"></div>  -->
                ~ Activités auxquels <?= $user->getPseudo() ?> a participé ~ 
                <!-- <div class="line"></div>'; -->
            </th>
        </thead>

        <tbody>
            <?php
            if(isset($participations))
            {
                foreach($participations as $participation)
                {
                    echo "<tr> <td>".$participation->getEvent()->getType()->getTitle()." : # ".$participation->getEvent()->getId()." ".$participation->getEvent()->getName()." ".$participation->getEvent()->getDateTime()->format('Y-m-d ~H:i')." </td> </tr>";                            
                }
            }
            else
            {
                echo "<tr><td> - Aucune participation à ce jour - </td></tr>";
            }
            ?>
        </tbody>
    </table>    
    
    <table>
        <thead>
            <th>
                <!-- <div class="line"></div>  -->
                ~ Activités organisées par <?= $user->getPseudo() ?> ~ 
                <!-- <div class="line"></div>'; -->
            </th>
        </thead>

        <tbody>
            <?php
                if(isset($organisations))
                {
                    foreach($organisations as $organisation)
                    {
                        echo "<tr> <td> # ".$organisation->getId()." ".$organisation->getName()." ".$organisation->getDateTime()->format('Y-m-d ~H:i')." </td> </tr>";                            
                    }
                }
                else
                {
                    echo "<tr><td> - Aucun event organisé à ce jour - </td></tr>";
                }
            ?>
        </tbody>
    </table>

    <table>
        <thead>
            <th>
                <!-- <div class="line"></div>  -->
                ~ Publications effectués par <?= $user->getPseudo() ?> ~ 
                <!-- <div class="line"></div>'; -->
            </th>
        </thead>

        <tbody>
            <?php
                if(isset($posts))
                {                    
                    foreach($posts as $post)
                    {
                        echo "<tr> <td> # ".$post->getId()." ".$post->getEvent()->getName()." <br> ".$post->getCreationDate()."</td> </tr>";                            
                    }
                }
                 else
                {
                    echo "<tr><td> - Aucune publication effectué à ce jour - </td></tr>";
                }
            ?>
        </tbody>
    </table>
</section>