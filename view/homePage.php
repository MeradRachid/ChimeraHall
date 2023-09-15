<?php

$pageName = "Accueil";

$csrfToken = $_SESSION['csrf_token'];

$data = $result['data'];

// $search = $result['data']['eventSearch'];
$lestats = $result["data"]["lestats"];
$events = $result["data"]["events"];
$types = $result["data"]['types'];
$formats = $result["data"]['formats'];
// $search = $result["data"]['search'];
// $req = $result['data']['users'];

$rallyCount = $result["data"]["lestats"]['RallyPoint'];
$userCount = $result["data"]["lestats"]['Planeswalker'];
$totalEvent = $result["data"]["lestats"]['TotalEvent'];
$currentEvent = $result["data"]["lestats"]['CurrentEvent'];
$typeEvent = $result["data"]["lestats"]['TypeEvent'];
$formatEvent = $result["data"]["lestats"]['FormatEvent'];
$crewmate = $result["data"]["lestats"]['Crewmate'];

// var_dump($types);
// var_dump($_SESSION['message']);

?>

<section id="home1">
    <div id="introduction">
        <h2 id="line"> Tu te trouves devant l'Antre de la Chimère : </h2> 

        <p id="startHere">
            Si tu lis ce message, c'est probablement parce que ton <em>"Étincelle de Planeswalker"</em> vient de s'embraser. <br><br>
            Tu dois te poser tout un tas de questions, et nous avons les réponses. <br><br>
            La <em>Chimère Néméride</em> est prête à t'accueillir dans son humble demeure. <br><br>
            Tu y trouveras tout un tas de compagnons prêt à t'aider dans ta quête : <br> <br>
            𓆩⫯𓆪 ~ Pour bien démarrer et te familiariser avec l'univers de <strong> Magic The Gathering </strong>... <br><br>
            𓆩⫯𓆪 ~ Mais aussi à repousser tes limites et progresser en créant toi-même par la suite. <br>
        </p>

    </div>
    <h2 id="status"> <span class="underline-text">ChimeraHall, l'Antre de la Chimère en quelques statistiques</span> :
    </h2>

    <div id="lestat">
        <div class="Lestats" id="statSpots">
            <i class="fa fas fa-flag"></i>
            <h5>
                <?= $rallyCount ?> <br> Points de ralliement
            </h5>
        </div>

        <div class="Lestats" id="statUnits">
            <i class="fa fas fa-users"></i>
            <h5>
                <?= $userCount ?> <br> Planeswalkers inscrits
            </h5>
        </div>

        <div class="Lestats" id="statEvents">
            <i class="fa far fa-calendar-check"></i>
            <h5>
                <?= $currentEvent ?> <br> Events en cours
            </h5>
        </div>

        <div class="Lestats" id="statTotalEvents">
            <i class="fa fas fa-award"></i>
            <h5>
                <?= $totalEvent ?> <br> Events créés à ce jour
            </h5>
        </div>

        <div class="Lestats" id="statTypes">
            <i class="fa fas fa-trophy"></i>
            <h5>
                <?= $typeEvent ?> <br> Types d'évents proposés
            </h5>
        </div>

        <div class="Lestats" id="statFormats">
            <i class="fa fas fa-book"></i>
            <h5>Réparti sur
                <?= $formatEvent ?> <br> Formats de Jeux différents
            </h5>
        </div>

        <div class="Lestats" id="statMates">
            <i class="fa fas fa-handshake"></i>
            <h5>
                <?= $crewmate ?> <br> Partenaires & Cie
            </h5>
        </div>
        <span id="swipop"><i class="fas fa-arrow-up"></i></span>
    </div>
</section>



<section id="home2">
    <div id="explore">

    <?php 
        if (isset($_SESSION['user'])) 
        {
            ?>
            <form action="index.php?ctrl=home&action=searchRallyPoints" method="post" class="SearchBar">
                <fieldset>
                <legend style="position: absolute; left: 20px; top: -30px;"> 𓆩⫯𓆪 Découvre les lieux : </legend>
                    <label id="labelRally" for="RallyPoints" style="position: absolute; left: -9999px;"> Découvre les lieux </label>
                    <input type="text" name="eventSearch" id="RallyPoints" class="Search" style="position: absolute; left: -30px;">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" class="SearchButton">Rechercher</button>
                </fieldset>
            </form>

            <form action="index.php?ctrl=home&action=searchUsers" method="post" class="SearchBar">
                <fieldset>
                <legend style="position: absolute; left: 20px; top: -30px;"> 𓆩⫯𓆪 Découvre les joueurs : </legend>
                    <label id="labelUser" for="PlanesWalkers" style="position: absolute; left: -9999px;"> Découvre les joueurs </label>
                    <input type="text" name="eventSearch" id="PlanesWalkers" class="Search" style="position: absolute; left: -30px;">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <button type="submit" class="SearchButton">Rechercher</button>
                </fieldset>
            </form>

        <form action="index.php?ctrl=home&action=searchEvents" method="post" class="SearchBar">
            <fieldset>
            <legend style="position: absolute; left: 20px; top: -30px;"> 𓆩⫯𓆪 Découvre les évents : </legend>
                <label id="labelEvent" for="searchEvent" style="position: absolute; left: -9999px;"> Découvre les jeux </label>
                <input type="text" name="eventSearch" id="searchEvent" class="Search" style="position: absolute; left: -30px;">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <button type="submit" class="SearchButton">Rechercher</button>
            </fieldset>
        </form>
            <?php
        }
         else
        {
            echo '<h3> Pour accéder à la fonction "Recherche", il faut être connecté. <h3>';
        }
    ?>

    </div>

    <div id="spotlight">
        <figure class="spotlight" id="rally">
            <figcaption style="position: relative; top: 30px;">
                Dernier point de ralliement créé :
            </figcaption>
            <img src="public/img/rallyPointWexim.png" alt="Last RallyPoint Spotlight">
        </figure>
        <figure class="spotlight" id="unite">
            <figcaption style="position: relative; top: 30px;">
                Dernier Planeswalker inscrit :
            </figcaption>
            <img src="public/img/yurikoh.png" alt="Last Registered Spotlight">
        </figure>
        <figure class="spotlight" id="spawn">
            <figcaption style="position: relative; top: 30px;">
                Dernier évènement en cours :
            </figcaption>
            <img src="public/img/lifeWorkCreate.png" alt="Last Event Spotlight">
        </figure>
        <span id="swipup"><i class="fas fa-arrow-up"></i></span>
    </div>
</section>



<section id="home3">
    <div class="container">

        <div class="column" id="fluxRSS">
             <h2 class="title">Flux d'actualités Magic The Gathering :</h2>
 
            <figure class="spotlight">
                <figcaption>Renseigne-toi sur le jeu :</figcaption>
                <a href="https://magic.wizards.com/en/news"><img src="public/img/news.png" alt="News 1 Spotlight"></a>
            </figure>
        </div>

        <div class="API">
            <h2 class="title">Focus Lumière sur la carte :</h2>
 
            <figure class="spotlight">
                <figcaption>Découvre de nouvelles cartes : </figcaption>
                <a href="index.php?ctrl=api&action=magiCard"><img src="public/img/MTGverso.png" alt="MTG Card Randomized"></a>
            </figure>
        </div>
        <span id="swipap"><i class="fas fa-arrow-up"></i></span>
    </div>
</section>

