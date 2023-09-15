<?php
$pageName = "Panneau Admin";

$csrfToken = $_SESSION['csrf_token'];

$users = $result["data"]['users'];
?>
<section>
    <!-- <h1 class="text-center">~ Bienvenue ~</h1> -->
    <h2 class="text-center"> Panneau Admins </h2>
</section>
    
<div>

    <?php
        foreach($users as $user)
        {
            // echo '<p>'. var_dump($user) .'</p>';
    ?>
        <section>
                <div class="d-flex justify-content-center align-items-center m-3 p-1">
                    <a href="index.php?ctrl=forum&action=detailUser&id=<?=$user->getId()?>" aria-label="userList" class="d-flex justify-content-center text-decoration-none text-reset">
                        <img src="<?=$user->getAvatar()?>" alt="Users List">
                        <div>
                            <p><?=$user->getPseudo()?></p>
                        </div>
                    </a>
                </div>
                <div>
                    <?php 
                        if(App\Session::isAdmin() || $event->getUser()->getId() == App\Session::getUser()->getId())
                        {
                            echo '<p class="d-flex justify-content-center">';

                            if($user->getBan() == true)
                            {
                                echo '<a href="index.php?ctrl=security&action=lockUser&id='.$user->getId().'" class="m-1"> <i class="fas fa-lock text-warning mx-3"> Verrouiller </i> </a>';
                            }
                                else
                            {
                                echo '<a href="index.php?ctrl=security&action=modifyUserForm&id='.$user->getId().'" class="m-1"> <i class="fas fa-pencil-alt text-warning"> Modifier </i> </a>
                                        <a href="index.php?ctrl=security&action=lockUser&id='.$user->getId().'" class="m-1"> <i class="fas fa-lock-open text-warning mx-3"> Verrouiller </i> </a>'.                                      
                                        '<a href="index.php?ctrl=security&action=randomizeUser&id='.$user->getId().'" class="m-1"> <i class="fas fa-trash-alt text-warning" width="25%" height="25%" alt="penToSquare"> Supprimer </i> </a>
                                        </p>';
                            }
                        }
                    ?>
                </div>
        </section>
    <?php
        }
    ?>

</div>