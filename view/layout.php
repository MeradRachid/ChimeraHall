<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://cdn.tiny.cloud/1/zg3mwraazn1b2ezih16je1tc6z7gwp5yd4pod06ae5uai8pa/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script src="public/tarteaucitron/tarteaucitron.js"></script>
    <link rel="stylesheet" href="public/tarteaucitron/css/tarteaucitron.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
        integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="icon" type="image/png" href="public/img/moon.jpg">

    <meta name="description"
        content="Espace Communautaire pour les joueurs de Magic The Gathering dans la région GRAND EST : Trouvez rapidement un partenaire pour découvrir le jeu, vous amuser ou vous entraîner sérieusement. Créez vous-même &/ou participez à de nombreux évènements locaux réguliers.">
    <title>
        CHIMERA HALL :
        <?php
        $csrfToken = $_SESSION['csrf_token'];

        echo $pageName;
        ?>
    </title>

</head>

<body>
    <div id="BodyWrap">
        <div id="mainpage">
            <header>
                <nav>
                    <div id="brand">
                        <a href="index.php?action=homePage">
                            <img src="public/img/moon.jpg" alt="LogoChimeraHall" id="logo">
                            <h1> ✧༺ ChimeraHall ༻✧ <br> L'Antre de la Chimère </h1>
                        </a>
                    </div>
                        <p>
                            <?php
                            if (App\Session::isAdmin()) {
                                ?>
                                <!-- <div id="AdminProfile"> -->
                            <form action="index.php?ctrl=security&action=adminUsers" method="post">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <button type="submit"> PANNEAU ADMINS </button>
                            </form>
                            <!-- </div> -->
                            <?php
                            }
                            ?>
                        </p>
                        <?php
                        if (App\Session::getUser()) {
                            ?>
                            <div id="UserProfile">
                                <form action="index.php?ctrl=security&action=profileUser" method="post">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit">
                                        <i class="fas fa-user"> Page de Profil </i>&nbsp;
                                        <?php App\Session::getUser() ?>
                                    </button>
                                </form>
                            </div>

                            <div class="nav-item dropdown">
                                <button class="nav-link active dropdown-toggle text-decoration-none text-reset winx p-1"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">L'aventure commence ici
                                </button>
                                <div class="dropdown-menu text-left">
                                    <a class="dropdown-item" href="index.php?action=homePage#introduction">Introduction</a>
                                    <a class="dropdown-item" href="index.php?action=homePage#explore">Explore la zone</a>
                                    <a class="dropdown-item" href="index.php?action=homePage#fluxRSS">Développe ton potentiel</a>
                                    <a class="dropdown-item" href="index.php?action=homePage#descriptionSite">Qui sommes-nous ?</a>
                                </div>
                            </div>

                            <form action="index.php?ctrl=security&action=logout" method="post">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <button type="submit">Déconnexion</button>
                            </form>
                            <?php
                        } else {
                            ?>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button id="Home"><a href="index.php?action=home"
                                        class="text-decoration-none text-reset">Accueil</a></button>
                                <button id="Login"><a href="index.php?ctrl=security&action=loginForm"
                                        class="text-decoration-none text-reset">Connecte-toi</a></button>
                                <button id="Register" class="winx"><a href="index.php?ctrl=security&action=registerForm"
                                        class="text-decoration-none text-reset"> REJOINS-NOUS !!! </a></button>
                            </div>
                            <?php
                        }
                        ?>
                </nav>
            </header>

            <!-- <div>
            <nav aria-label="breadcrumb">
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/categorie">Catégorie</a></li>
                    <li><a href="/categorie/sous-categorie">Sous-catégorie</a></li>
                    <li>Page actuelle</li>
                </ul>
            </nav> -->

            <!-- c'est ici que les messages (erreur ou succès) s'affichent-->
            <h5 class="message error"> <span class="highlight" style="color: red">
                    <?= App\Session::getFlash("error") ?>
                </span></h5>
            <h5 class="message success"> <span class="highlight" style="color: gold">
                    <?= App\Session::getFlash("success") ?>
                </span></h5>
        </div>

        <main id="forum" class="d-flex flex-column">

            <?= $page ?>
            <!-- C'est ici qu'on applique la temporisation de sortie avec $page == $content( ou $contenu) -->

        </main>

    </div>

    <footer class="text-center">
        <div id="descriptionSite" class="footers">
            <h3> ChimeraHall <br> Dans l'Antre de la Chimère : </h3>
            <p>
                Espace Communautaire pour les joueurs de Magic The Gathering situés dans le Haut-Rhin : <br><br>
                Trouvez rapidement un partenaire pour découvrir le jeu, vous amuser ou vous entraîner sérieusement.
                <br><br>
                Créez vous-même &/ou participez à de nombreux évènements locaux réguliers.
            </p>
        </div>
        <div id="liensUtiles" class="footers">
            <h3> Liens Utiles : </h3>

            <div id="footLinks">

                <p> <a href="index.php?ctrl=home&action=privacyPolicy"> Conditions Générales </a> </p>

                <p> <a href="index.php?ctrl=home&action=privacyPolicy"> Politique de confidentialité </a> </p>

                <p> <a href="index.php?ctrl=home&action=privacyPolicy"> Mentions Légales </a> </p>

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
                <a href="https://fr-fr.facebook.com/" aria-label="Site Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://twitter.com/" aria-label="Site Facebook"><i class="fab fa-twitter"></i></a>
                <a href="https://www.snapchat.com/" aria-label="Site Facebook"><i class="fab fa-snapchat"></i></a>
                <a href="https://www.twitch.tv/" aria-label="Site Facebook"><i class="fab fa-twitch"></i></a>
                <a href="https://www.youtube.com/" aria-label="Site Facebook"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>
    </div>
    <!-- 
   <script 
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous">
    </script>
    <script>

        $(document).ready(function(){
            $(".message").each(function(){
                if($(this).text().length > 0){
                    $(this).slideDown(500, function(){
                        $(this).delay(3000).slideUp(500)
                    })
                }
            })
            $(".delete-btn").on("click", function(){
                return confirm("Etes-vous sûr de vouloir supprimer?")
            })
            tinymce.init({
                selector: '.post',
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                toolbar: 'undo redo | formatselect | ' +
                'bold italic backcolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
                content_css: '//www.tiny.cloud/css/codepen.min.css'
            });
        })        

        /*
        $("#ajaxbtn").on("click", function(){
            $.get(
                "index.php?action=ajax",
                {
                    nb : $("#nbajax").text()
                },
                function(result){
                    $("#nbajax").html(result)
                }
            )
        })*/
    </script>        
-->

    <!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>

    <script src="public/js/style.js"></script>

    <script src="public/js/tarteaucitron.js"></script>
    <script src="public/tarteaucitron/advertising.js"></script>
    <script src="public/tarteaucitron/tarteaucitron.services.js"></script>
    <script src="public/tarteaucitron/lang/tarteaucitron.fr.js"></script>

</body>

</html>