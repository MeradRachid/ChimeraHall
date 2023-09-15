<?php

$pageName = "Organisation";

$csrfToken = $_SESSION['csrf_token'];

$types = $result["data"]['types'];
$formats = $result["data"]['formats'];
$rally = $result["data"]['rallyPoints'];

use Model\Managers\EventManager;
$eventManager = new EventManager;

?>
<section>


<div class="d-flex justify-content-center align-items-center">

    <form action="index.php?ctrl=event&action=addEvent" method="POST" class="d-flex flex-column m-3">
    <label class="text-center"> Nom de l'évènement : </label>
        <input type="text" name="name" placeholder=" Ex : Retour sur Ravnica... " class="rounded p-1 m-3 text-center" required>
        
        <label class="text-center"> Type d'évènement : </label>
        <div class="input-group mb-3">
            <select name="type_id" class="form-select form-select-sm text-center" aria-label="form-select-sm example" required>
                <?php                
                    foreach($types as $type)
                    { 
                ?>
                     <option value="<?=$type->getId()?>"> # <?=$type->getId()?> : <?= $type->getTitle() ?> </option>
                <?php
                    }
                ?>
            </select>
        </div>

        <label class="text-center"> Format de jeu : </label>
        <div class="input-group mb-3">
            <!-- <br> -->
            <select name="format_id" class="form-select form-select-sm text-center" aria-label="form-select-sm example" required>
                <?php                
                    foreach($formats as $format)
                    { 
                        // $label = $format->getLabel(); 
                ?>
                     <option value="<?= $format->getId() ?>"> # <?= $format->getId() ?> : <?= $format->getLabel() ?> </option>
                <?php

                    }
                ?>
            </select>
        </div>

        <label class="text-center"> Spot de jeu : </label>
        <div class="input-group mb-3">
            <select name="rally_id" class="form-select form-select-sm text-center" aria-label="form-select-sm example" required>
                <?php                
                    foreach($rally as $rallyPoint)
                    { 
                ?>
                     <option value="<?=$rallyPoint->getId()?>"> # <?=$rallyPoint->getId()?> : <?= $rallyPoint->getSpot() ?> </option>
                <?php
                    }
                ?>
            </select>
        </div>

        <label class="text-center"> Décrivez votre évènement : </label>
        <div class="input-group mb-3 justify-content-center">
            <textarea rows="3" cols="30" id="description" name="description" placeholder=" Ex : Avant-Première, Découverte de la nouvelle extension... " class="rounded p-1 m-1 text-center" required></textarea>
        </div>        

        <label class="text-center"> Jour de démarrage : </label>
        <div class="input-group mb-3 justify-content-center">
            <input type="date" id="event_date" name="date" placeholder=" Event Date " class="rounded p-1 m-1 text-center" required>
        </div>

        <label class="text-center"> Heure de démarrage : </label>
        <div class="input-group mb-3 justify-content-center">
            <input type="time" id="event_time" name="Time" placeholder=" Event Time " class="rounded p-1 m-1 text-center" required>
        </div>
        
        <label class="text-center"> Capacité maximale d'accueil : </label>
        <div class="input-group mb-3 justify-content-center">
            <input type="text" name="nbMaxPlayer" placeholder="De 0 à 1000" class="rounded p-1 m-1 text-center" onkeypress="return isNumber(event)" required>
        <!-- <input type="hidden" name="_id" value="'.$_id.'"> token csrf xD'-->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        </div>
        <button type="submit" class="rounded p-1 m-3 border-success" style="max-width: 18rem;"> Create Event </button>
    </form>

</div>

</section>