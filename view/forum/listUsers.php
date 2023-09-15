<?php
$pageName = "Arpenteurs inscrits";

    $users = $result["data"]['users'];
    
?>
<section>

    <h1 class="text-center">~ Bienvenue ~</h1>
    <h2 class="text-center"> Listes des Membres </h2>
    
    <div class="d-flex ">
        
        <?php
        foreach($users as $user)
        {
            ?>
            <div class="m-1">
                <a href="index.php?ctrl=forum&action=detailUser&id=<?=$user->getId()?>" aria-label="userList" class="text-decoration-none text-reset">
                    <div class="card text-white bg-transparent align-items-center border-0">
                        <img src="<?=$user->getAvatar()?>" class="card-img-center" alt="Users List">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?=$user->getPseudo()?></h5>
                            <p><small> Joined us in : <br> <?=$user->getRegistered()?></small></p>
                        </div>
                    </div>
                </a>
            </div>
            
            <?php
        }
        ?>

</div>
</section>
