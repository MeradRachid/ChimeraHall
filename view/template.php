<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
                
        <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script> -->
        <script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
        
        <link rel="stylesheet" href="public/css/style.css">
        
        <link rel="icon" type="image/png" href="public/img/moon.jpg">

        <?php 
            //starts, only used to rescan the page and load new items or when start is set to false
            // mtgtooltip.start(); 
        ?>

        <title>Chimera Hall : Accueil </title> <!--< ?= $PageName ? >-->
    </head>
    <body>
        <div id="BodyWrap">
            <header id="Header">
                <nav>
                    <div id="brand">
                        <a href="index.php?action=homePage">
                            <img src="public/img/moon.jpg" alt="ChimeraHall" id="logo">
                            <h1> ✧༺ ChimeraHall ༻✧ <br> Dans l'Antre de la Chimère </h1>
                        </a>
                    </div>

                    <div id="Navbar" class="no-bullet">

                        <form action="index.php?action=homePage" method="post">
                            <button type="submit" id="Homepage"> Accueil </button>
                        </form>

                        <div class="dropdown">
                            <button type="button" id="ShowHideButton" class="btn btn-warning dropdown-toggle"> Explore la zone </span> </button>
                            <div id="exploreContent" class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <a href="index.php?action=homePage#introduction">Introduction</a>
                                    <a href="index.php?action=homePage#explore">Ton aventure commence ici</a>
                                    <a href="index.php?action=homePage#fluxRSS">Développe ton potentiel</a>
                                    <a href="index.php?action=homePage#descriptionSite">Qui sommes-nous ?</a>
                            </div>
                        </div>

                        <?php
                            if(App\Session::isAdmin())
                            {
                                ?>
                                    <div id="AdminProfile">
                                        <form action="index.php?ctrl=security&action=adminUsers" method="post">
                                            <button type="submit"> PANNEAU ADMINS </button>
                                        </form>
                                    </div>
                                <?php
                            }
                        ?>

                        <?php
                            if(App\Session::getUser())
                            {
                                ?>
                                    <div id="UserProfile">
                                        <form action="index.php?ctrl=security&action=profileUser" method="post">
                                            <button type="submit">
                                                <i class="fas fa-user"> Profile User </i>&nbsp; <?php App\Session::getUser() ?>
                                            </button>
                                        </form>
                                    </div>

                                    <form action="index.php?ctrl=security&action=logout" method="post">
                                        <button type="submit">Déconnexion</button>
                                    </form>
                                <?php
                            }
                             else
                            { 
                                ?>
                                    <button id="Login"><a href="index.php?ctrl=security&action=login"> Connecte-toi </a></button>
                                    <button id="Register"><a href="index.php?ctrl=security&action=registerForm"> REJOINS-NOUS !!! </a></button>
                                <?php
                            }
                        ?>
                    </div>
                </nav>
                        <!-- c'est ici que les messages (erreur ou succès) s'affichent-->
                        <h3 class="message error" style="color: red"><? App\Session::getFlash("error") ?></h3>
                        <h3 class="message success" style="color: green"><? App\Session::getFlash("success") ?></h3>
            </header>

            <main>

                <?= $page ?> <!-- C'est ici qu'on applique la temporisation de sortie avec $page == $content( ou $contenu) -->

            </main>

            <footer>
                <div id="descriptionSite" class="footers">
                    <h3> ChimeraHall <br> Dans l'Antre de la Chimère : </h3>
                    <p>
                        Espace Communautaire pour les joueurs de Magic The Gathering dans la région GRAND EST : <br><br> 
                        Trouvez rapidement un partenaire pour découvrir le jeu, vous amuser ou vous entraîner sérieusement. <br><br>
                        Créez vous-même &/ou participez à de nombreux évènements locaux réguliers.
                    </p>
                </div>
                <div id="liensUtiles" class="footers">
                    <h3> Liens Utiles : </h3>

                    <div id="footLinks">

                        <p> Conditions Générales </p>
                        
                        <p> <a href="index.php?ctrl=home&action=privacyPolicy"> Politique de confidentialité </a> </p>
                        
                        <p> Mentions Légales </p>
                        
                    </div>
                    <em> © Chimera Hall - Dans l'Antre de la Chimère 2023 ® </em>
                </div>

                <div id="contact" class="footers">
                    <h3> Coordonées pour contacter la Chimère : </h3>

                    <p>
                        Email : contact@chimerahall.com 
                    </p>
                        <!-- <br> -->
                    <p>
                        Tél : 03 - 72 - 41 - 58 - 59
                    </p>

                    <p>
                        Suit la Chimère sur les réseaux sociaux : 
                    </p>
                    <div id="faSNS">
                        <i class="fab fa-facebook"></i>  
                        <i class="fab fa-twitter"></i>
                        <i class="fab fa-snapchat"></i>
                        <i class="fab fa-twitch"></i>
                        <i class="fab fa-youtube"></i>
                    </div>

                </div>
            </footer>
        </div>

        <!-- /* -------- On ajoute la Library Ajax et le nécessaire Pour la Barre de Recherche --------- */ -->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

        <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script> -->

        <!-- <script src='https://raw.githubusercontent.com/giventofly/MTG-Tooltip-Js/dist/mtgtooltip.js'></script> -->

        <script src="public/js/style.js"></script>

    </body>
</html>