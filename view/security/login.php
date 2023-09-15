<?php $pageName = "Connexion"; 

$csrfToken = $_SESSION['csrf_token'];

?>

<section>
<h1> Formulaire de Connexion </h1>

<div class="d-flex justify-content-center">

    <form action="index.php?ctrl=security&action=login" method="post" enctype="multipart">

        <input type="text" name="pseudo" placeholder="Pseudo" required>
        <input type="password" name="password" placeholder="Password" >
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" name="submit" class="p-1"> Connexion </button>
    </form>

</div>
</section>