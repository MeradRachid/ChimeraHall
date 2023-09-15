<?php
    $pageName = "Modifier Avatar";
    $csrfToken = $_SESSION['csrf_token'];

?>

<section>
    <div class="d-flex justify-content-center">
        <form action="index.php?ctrl=security&action=modifyAvatar" method="POST" class="m-3">
            <label for="file">Sélectionnez un fichier :</label>
            <input type="file" id="file" name="file">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <button type="submit">Envoyer</button>

            <a href="index.php?ctrl=security&action=profileUser" class="btn btn-secondary m-3">Annuler</a>
        </form>
    </div>
</section>

