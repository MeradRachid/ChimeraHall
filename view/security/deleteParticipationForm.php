
<?php 
$pageName="Reservation";

$csrfToken = $_SESSION['csrf_token'];

$event = $result["data"]["event"]; 
// var_dump($event);

?>

<section>
    <h2>Confirmation d'annulation</h2>
    <p>Êtes-vous sûr de vouloir vous retirer de cet évènement ?</p>
    <p>Numéro de l'évènement : <?= '# '.$id; ?></p>
    <p>Intitulé de l'évènement : <?= $event->getName(); ?></p>
    <p>Caractéristiques de l'évènement : <?=$event->getType()->getTitle()?> - Règles <?=$event->getFormat()->getLabel()?> </p>
    <form action="index.php?ctrl=event&action=deleteParticipation&id=<?=$event->getId()?>" method="post">
        <input type="hidden" name="confirm" value="oui">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="btn btn-warning">Je libère ma place !</button>
        <a href="index.php?ctrl=event&action=detailEvent&id=<?=$event->getId()?>" class="btn btn-secondary">Annuler</a>
    </form>    
</section>
