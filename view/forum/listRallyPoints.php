<?php
$pageName = "ListRally";

$csrfToken = $_SESSION['csrf_token'];

$rally = $result["data"]['rallyPoints'];

?>

<h1>~ Bienvenue sur List Rally ~</h1>

<form action="index.php?ctrl=event&action=rallyForm" method="post" enctype="multipart">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
    <button type="submit" class="rounded p-1 m-3 border-success" >Créer un nouveau spot</button>
</form>
<div class="d-flex justify-content-center" id="listRallies">
    <?php
        foreach($rally as $rallyPoint)
        {
    ?>
    <div class="card border-warning m-1 text-center" style="max-width: 18em; background: darkslateblue;">
        <div class="card-header bg-transparent border-warning text-warning">
            Spot de Jeu N°<?=$rallyPoint->getId()?> 
            <br> <?=$rallyPoint->getCity()?> - <?=$rallyPoint->getZipCode()?>
        </div>
                    
        <div class="card-body text-secondary">
            <h5 class="card-title"><a href="index.php?ctrl=event&action=detailRally&id=<?=$rallyPoint->getId()?>" class="text-reset text-decoration-none">
            <?=$rallyPoint->getSpot()?> 
            <br>
            <?php if ($rallyPoint->getActive() == false) : ?>
                <!-- Afficher statut verrouillé -->
            <?= " <em>(Spot Fermé)</em>" ?>
            <?php else : ?>
                <!-- Afficher statut déverrouillé -->
            <?= "<em>(Spot Ouvert)</em>" ?>
            <?php endif; ?>
            </a></h5>
        </div>

        <div class="card-footer bg-transparent border-warning">
            <?php 
                if(App\Session::isAdmin() || $rallyPoint->getUser()->getId() == App\Session::getUser()->getId())
                {
                    if($rallyPoint->getActive() == false)
                    {
                        echo '<a href="index.php?ctrl=event&action=lockRally&id='.$rallyPoint->getId().'" class="m-1"><i class="fa fa-lock text-warning" aria-hidden="true"></i></a>';
                    }
                     else
                    {
                        echo '<a href="index.php?ctrl=event&action=updateRally&id='.$rallyPoint->getId().'" class="m-1"> <i class="fas fa-pencil-alt text-warning"></i></a> <a href="index.php?ctrl=event&action=lockRally&id='.$rallyPoint->getId().'" class="m-1"><span class="fas fa-lock-open text-warning mx-3"></span></a> <a href="index.php?ctrl=event&action=deleteRally&id='.$rallyPoint->getId().'" class="m-1"> <i class="fas fa-trash-alt text-warning" width="25%" height="25%" alt="penToSquare"></i></a>';
                    }
                }
                 elseif ($rallyPoint->getActive() == false)
                {
                    echo '<i class="fa fa-lock text-warning" aria-hidden="true"></i>';
                }
                 else
                {

                }
            ?>  
            <br> <a href="index.php?ctrl=forum&action=detailUser&id=<?=$rallyPoint->getUser()->getId()?>" class="text-decoration-none text-warning">By : <?= $rallyPoint->getUser()->getPseudo() ?> </a> 
        </div>
    </div>
        <?php
            }    
        ?>
</div>