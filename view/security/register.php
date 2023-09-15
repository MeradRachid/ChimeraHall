<?php $pageName = "Inscription"; 

$csrfToken = $_SESSION['csrf_token'];

?>

<section>
<h1> Formulaire d'inscription </h1>
    <div class="d-flex justify-content-center">
        <form action="index.php?ctrl=security&action=register" method="post" enctype="multipart" class="text-center">

            <input type="text" name="pseudo" class="text-center" placeholder="Pseudo" required>
            <input type="email" name="email" placeholder="Email" class="text-center" required>
            <input type="text" name="zipCode" placeholder="Code Postal" class="text-center" required>
            <input type="password" name="password" placeholder="Password" class="text-center" required>
            <input type="password" name="confirmPassword" placeholder="Confirm Password" class="text-center" required>
<br>
            <label class="d-flex align-items-center justify-content-center m-3">
                <input type="checkbox" name="terms" required> &nbsp; Accepter les <a href="index.php?ctrl=home&action=privacyPolicy" style="text-decoration: none; color: gold"> &nbsp; Conditions Générales d'utilisation</a> 
            </label>
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <button type="submit" name="submit" class="p-1 ml-4"> Inscription </button>
        </form>
    </div>
</section>