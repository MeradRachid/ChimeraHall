<?php
$pageName = "Suppression";

$csrfToken = $_SESSION['csrf_token'];

$id = $_GET['id'];

$event = $result["data"]["event"];
// $dataSuppr = $result["data"]["dataSuppr"]; 

?>
<section>
    <h2>Confirmation de suppression</h2>
    <p>Êtes-vous sûr de vouloir supprimer cet élément ?</p>
    <p>ID :
        <?php echo '# ' . $id; ?>
    </p>
    <p>Nom :
        <?php echo $event->getName(); ?>
    </p>
    <form action="" method="post">
        <input type="hidden" name="confirm" value="oui">
        <input type="hidden" name="id" value="<?php $id ?>" />
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
        <a href="index.php?ctrl=forum&action=listEvents" class="btn btn-secondary">Annuler</a>
    </form>
</section>
<!-- - Code à adapter -  -->