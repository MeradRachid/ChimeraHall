<?php
    $pageName = "Évènements";

    $csrfToken = $_SESSION['csrf_token'];

    $events = $result["data"]['events'];

    // var_dump($event); 
?>


<section>
        <h1>~ Liste des Évènements ~</h1>
    <form action="index.php?ctrl=event&action=eventForm" method="post" enctype="multipart">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="rounded p-1 m-3 border-success" >Créer un évènement</button>
    </form>
    <div class="listElements" id="listEvents">
        <?php
            foreach($events as $event)
            {
        ?>
        <div class="card border-warning m-1 text-center" style="max-width: 18em; background: darkslateblue;">
            <div class="card-header bg-transparent border-warning text-warning">
                <?=$event->getType()->getTitle()?> - <?=$event->getFormat()->getLabel()?> : 
                <br> <?=$event->getDateTime()->format('d-m-Y ~H:i')?>
            </div>
                        
            <div class="card-body text-secondary bg-warning" style="background: goldenrod !important ;">
                <h5 class="card-title"><a href="index.php?ctrl=event&action=detailEvent&id=<?=$event->getId()?>" class="text-reset text-decoration-none">
                <?=$event->getName()?> 
                <br>
                <?php if ($event->getEventLocked() == true) : ?>
                    <!-- Afficher statut verrouillé -->
                <?= " <em>(Fermé)</em>" ?>
                <?php else : ?>
                    <!-- Afficher statut déverrouillé -->
                <?= "<em>(Ouvert)</em>" ?>
                <?php endif; ?>
                </a></h5>
            </div>

            <div class="card-footer bg-transparent border-warning">
                <?php 
                    if(App\Session::isAdmin() || $event->getUser()->getId() == App\Session::getUser()->getId())
                    {
                        if($event->getEventLocked() == true)
                        {
                            echo '<a href="index.php?ctrl=event&action=lockEvent&id='.$event->getId().'" class="m-1"><i class="fa fa-lock text-warning" aria-hidden="true"></i></a>';
                        }
                        else
                        {
                            echo '<a href="index.php?ctrl=event&action=updateForm&id='.$event->getId().'" class="m-1"> <i class="fas fa-pencil-alt text-warning"></i></a> <a href="index.php?ctrl=event&action=lockEvent&id='.$event->getId().'" class="m-1"><span class="fas fa-lock-open text-warning mx-3"></span></a> <a href="index.php?ctrl=event&action=deleteEvent&id='.$event->getId().'" class="m-1"> <i class="fas fa-trash-alt text-warning" width="25%" height="25%" alt="penToSquare"></i></a>';
                        }
                    }
                    elseif ($event->getEventLocked() == true)
                    {
                        echo '<i class="fa fa-lock text-warning" aria-hidden="true"></i>';
                    }
                    else
                    {

                    }
                ?>  
                <br> <a href="index.php?ctrl=forum&action=detailUser&id=<?=$event->getUser()->getId()?>" class="text-decoration-none text-warning">Cc : <?= $event->getUser()->getPseudo() ?> </a> 
            </div>
        </div>
            <?php
                }    
            ?>
    </div> 
</section>