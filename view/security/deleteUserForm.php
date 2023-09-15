<?php

// $id = $_GET['id'];
$pageName = "Désinscription";

$csrfToken = $_SESSION['csrf_token'];

$user = $result["data"]["user"];
// $dataSuppr = $result["data"]["dataSuppr"]; 
?>
<section>

    <h2>Confirmation de suppression</h2>
    <p>Êtes-vous sûr de vouloir supprimer cet utilisateur ?</p>
    <p>
        ID :
        <?php echo '# ' . $id; ?> -> Nom d'utilisateur :
        <?php echo $user->getPseudo(); ?>

        <br> <br> <strong><em>~ Disclaimer ~</em></strong> <br> <br>

        Conformément à l'avis de "l'article 29" en date du 10 avril 2014 sur les Techniques d'anonymisation : <br>
        Et par soucis de préservation de l'intégrité structurelle de l'ensemble des données du site ; <br><br>
        Toutes informations personnelles qui permettraient de remonter jusqu'à vous seront supprimées. <br>
        Seuls resteront les données relatives aux événements et leurs publications, l'utilisateur restera inconnu.
    </p>
    <form action="" method="post">
        <input type="hidden" name="confirm" value="oui">
        <input type="hidden" name="id" value="<?php $id ?>" />
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="btn btn-danger">Supprimer</button>
        <?php 
            if(App\Session::isAdmin())
            {
                echo '<a href="index.php?ctrl=security&action=adminUsers" class="btn btn-secondary">Annuler</a>';
            }
             elseif(App\Session::getUser() == $user)
            {
                echo '<a href="index.php?ctrl=security&action=profileUser" class="btn btn-secondary">Annuler</a>';
            }
             else
            {
                session_destroy();
            }
        ?>
    </form>

</section>
