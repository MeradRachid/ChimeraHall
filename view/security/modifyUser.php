<?php
$pageName = "Modifier Utilisateur";

$csrfToken = $_SESSION['csrf_token'];

$id = $result['data']['user_id'];


?>;

<div class="d-flex justify-content-center">
    <form action="index.php?ctrl=security&action=modifyPseudo" method="POST" class="m-3">
        <input type="text" name="newPseudoByAdmin" placeholder="Type new pseudo here"
            class="rounded p-1 m-1 text-center" required>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="rounded m-2 p-1 border-success" style="max-width: 18rem;">Renommer User</button>

        <?php if (App\Session::isAdmin()) {
            ?>
            <a href="index.php?ctrl=security&action=adminUsers" class="btn btn-secondary">Annuler</a>
            <?php
         } else {
            ?>
            <a href="index.php?ctrl=security&action=profileUser" class="btn btn-secondary">Annuler</a>
            <?php
         }
        ?>
    </form>
</div>

<div class="d-flex justify-content-center">
    <form action="index.php?ctrl=security&action=modifyEmail" method="POST" class="m-3">
        <input type="text" name="newEmailByAdmin" placeholder="Type new email here" class="rounded p-1 m-1 text-center"
            required>
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="rounded m-2 p-1 border-success" style="max-width: 18rem;">Modifier Email</button>

        <?php if (App\Session::isAdmin()) {
            ?>
            <a href="index.php?ctrl=security&action=adminUsers" class="btn btn-secondary">Annuler</a>
            <?php
         } else {
            ?>
            <a href="index.php?ctrl=security&action=profileUser" class="btn btn-secondary">Annuler</a>
            <?php
         }
        ?>
    </form>
</div>