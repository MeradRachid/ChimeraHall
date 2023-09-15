<?php $pageName = "Page de Profil";

$csrfToken = $_SESSION['csrf_token'];

$user = $result["data"]['user'];

// var_dump($user->getAvatar());
// $avatar = $user->getAvatar()
?>

<section>
        <h2 class="text-center">Panneau Privé Utilisateur</h2>
</section>

<section>
    <p> 
        <em> Date d'inscription >>  <?=$user->getRegistered()?> </em>

        <table style="width:70%">
            <tr>
                <td> <?=$user->getPseudo()?> </td>

                <td>
                    <form action="index.php?ctrl=security&action=editPseudoForm" method="post">
                        <!-- <input type="text" name="newPseudo" placeholder=" .. Pseudo .. "> -->
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <button type="submit">Modifier Pseudo</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td> <?=$user->getEmail()?> </td>

                <td>
                    <form action="index.php?ctrl=security&action=editEmailForm" method="post"> 
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <button type="submit">Modifier Email</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td> <?=$user->getZipCode()?> </td>

                <td>
                    <form action="">
                        <button>Modifier Localité</button>
                    </form> 
                </td>
            </tr>
            <tr>
                <td>
                    <?php $avatar = $user->getAvatar() ?>
                    <img src="<?php echo $avatar ?>" alt="Profile Picture" style="width:10%">
                </td>

                <td> 
                    <form action="index.php?ctrl=security&action=editAvatar" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <button type="submit">Modifier Avatar</button>
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <form action="">
                        <button>Modifier Mot de Passe</button>
                    </form>
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                    <form action="index.php?ctrl=security&action=deleteAccountForm" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <button type="submit">Supprimer son Compte</button>
                    </form>
                </td>
            </tr>
        </table>

    </p>
</section>