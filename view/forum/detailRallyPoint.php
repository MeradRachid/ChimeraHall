<?php 

$pageName = "Point de Jeu";

    $rally = $result["data"]['rallyPoint'];

    $event = $result["data"]['event'];
?>
<section>

    <!-- <h1>Détail du point de ralliement</h1> -->
    <br>
    <div id="retroEvent"> 
        <div class="line"></div>
        <h2> Spot de Jeu : <?=$rally->getSpot()?> </h2>
        <div class="line"></div>
    </div> 
    <br>
    
    <!--  -->
    
    <table>
        <tr>
            <?= $rally->getAdress() ?>
        </tr>
        <table>            
</section>


