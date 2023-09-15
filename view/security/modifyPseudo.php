<?php
    $pageName = "Modifier Pseudo";
    $csrfToken = $_SESSION['csrf_token'];

?>
    <div class="d-flex justify-content-center">
        <form action="index.php?ctrl=security&action=modifyPseudo" method="POST" class="m-3">
            <input type="text" name="newPseudo" placeholder="Type new pseudo here" class="rounded p-1 m-1 text-center" required>
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <button type="submit" class="rounded m-2 p-1 border-success" style="max-width: 18rem;">Renommer User</button>

            <a href="index.php?ctrl=security&action=profileUser" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
