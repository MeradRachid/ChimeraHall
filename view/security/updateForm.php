<?php

$csrfToken = $_SESSION['csrf_token'];

$id = $_GET['id'];
$types = $result["data"]['types'];
$formats = $result["data"]['formats'];

use Model\Managers\EventManager;
$eventManager = new EventManager;

// var_dump($result);
// $iDn; 
?>

<div class="d-flex justify-content-center">

    <form action="index.php?ctrl=event&action=updateEvent&id=<?= $id ?>" method="POST" class="d-flex flex-column m-3">

        <input type="text" name="name" placeholder=" Event Name " class="rounded p-1 m-3 text-center">

        <div class="input-group mb-3">
            <select name="type_id" class="form-select form-select-sm text-center" aria-label="form-select-sm example">
                <option selected> Event Type </option>
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

        <div class="input-group mb-3">
            <select name="format_id" class="form-select form-select-sm text-center" aria-label="form-select-sm example">
                <option selected> Event Format </option>
                <?php                
                    foreach($formats as $format)
                    { 
                        $label = $format->getLabel(); 
                        // var_dump($label);
                        // die();
                ?>
                     <option value="<?= $iDn = $eventManager->findIdByLabel($label) ?>"> # <?= $iDn = $eventManager->findIdByLabel($label) ?> : <?= $label ?> </option>
                <?php
                    // $iDn++; 
                    }
                ?>
            </select>
        </div>

        <input type="date" id="event_date" name="date" placeholder=" Newest Date " class="rounded p-1 m-1 text-center">
        <input type="time" id="event_time" name="Time" placeholder=" Newest Time " class="rounded p-1 m-1 text-center">
        <input type="text" name="adress" placeholder=" Newest Place " class="rounded p-1 m-1 text-center" >
        <input type="text" name="zipCode" placeholder=" Newest ZipCode " class="rounded p-1 m-1 text-center">
        <input type="text" name="city" placeholder="Newest City " class="rounded p-1 m-1 text-center">
        <input type="text" name="nbMaxPlayer" placeholder="Newest Number Max of Players " class="rounded p-1 m-1 text-center" >
        <textarea name="description" cols="60" rows="10" placeholder="Type description here.." class="form-control rounded p-1 m-1 text-center" style="width : 18rem; height: 18rem; overflow-wrap: break-word;"></textarea>
        <!-- <input type="hidden" name="_id" value="'.$_id.'"> token csrf xD'-->
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="rounded p-1 m-3 border-success" style="max-width: 18rem;"> Update Event </button>
    </form>

</div>
