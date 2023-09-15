<?php

use Model\Managers\ParticipationManager;

$pageName = "Évènement";
$csrfToken = $_SESSION['csrf_token'];

// $likes = $result["data"]["likes"];
$posts = $result["data"]['posts'];
$event = $result["data"]['event'];
$rally = $result["data"]['rallyPoint'];
$reservation = $result["data"]['reservations'];
$format_id = $result["data"]['format_id'];

// On récupère le nombre de participations pour cet événement
$participationManager = new ParticipationManager();
$nbParticipations = $participationManager->countParticipations($event->getId());

// var_dump($rally);
// die();
?>
<section>

    <h1>~ Bienvenue ~</h1>
    <form action="index.php?ctrl=event&action=listEvents" method="post">
    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="rounded p-1 border-success" style="max-width: 18rem;">Retour aux
            événements</button>
    </form>
    <h2 class="text-center"> Détail de l'event :
        <?= $event->getName() ?>
    </h2>
    <a class="d-flex align-center justify-center"></a>
    <div class="event-table">
        <table>
            <tr>
                <td>Numéro Identifiant Event :</td>
                <td> #
                    <?= $event->getId() ?>
                </td>
            </tr>
            <tr>
                <td>Prévue pour le :</td>
                <td>
                    <?= $event->getDateTime()->format('Y-m-d ~H:i') ?>
                </td>
            </tr>
            <tr>
                <td>Adresse :</td>
                <td>
                    <?= $event->getRally()->getAdress() ?>,
                    <?= $event->getRally()->getZipCode() ?>
                    <?= $event->getRally()->getCity() ?>
                </td>
            </tr>
            <tr>
                <td>Type d'évent :</td>
                <td> #
                    <?= $event->getType()->getId() ?> >
                    <?= $event->getType()->getTitle() ?>
                </td>
            </tr>
            <tr>
                <td>Format de jeu :</td>
                <td> #
                    <?= $format_id ?> >
                    <?= $event->getFormat()->getLabel() ?>
                </td>
            </tr>
            <tr>
                <td>Statut de l'évent :</td>
                <td> Actuellement
                    <?php if ($event->getEventLocked() == true): ?>
                        <!-- Afficher statut verrouillé -->
                        <?= "Terminé/Indisponible" ?>
                    <?php else: ?>
                        <!-- Afficher statut déverrouillé -->
                        <?= "Disponible/En Cours" ?>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <?php echo 'Nombre de Participants Actuel : ' . $nbParticipations . ' / ';
                    if ($event->getNbMaxPlayer() == 9999) {
                        echo 'Sans Limite';
                    } else {
                        echo $event->getNbMaxPlayer();
                    } ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"> Organisé par :
                    <?= $event->getUser()->getPseudo() ?>
                </td>
            </tr>
            <tr>
                <td>
                    <form action="index.php?ctrl=event&action=addParticipation&id=<?= $event->getId() ?>" method="post"
                        class="form-inline" style="max-width: 18rem;">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <?php
                        if ($nbParticipations == $event->getNbMaxPlayer()) {
                            echo '<button type="submit" class="rounded p-1 border-success" style="max-width: 18rem;" disabled>Évènement Complet</button>';
                        } else {
                            echo '<button type="submit" class="rounded p-1 border-success" style="max-width: 18rem;">Participer à cet évènement</button>';
                        }
                        ?>
                    </form>
                <td>
                    <form action="index.php?ctrl=event&action=deleteParticipation&id=<?= $event->getId() ?>"
                        method="post" class="form-inline" style="max-width: 18rem;">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <div class="d-inline-block"><button type="submit" class="rounded p-1 border-success"
                                style="max-width: 18rem;">Se retirer de cet événement</button></div>
                    </form>
                </td>
                </td>
            <tr>
                <td colspan="2">
                    <form action="#" method="post">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <button type="submit" class="rounded p-1 border-success" style="max-width: 18rem;">Gérer
                            l'événement</button>
                    </form>
                </td>
            </tr>
            </tr>
        </table>
    </div>
</section>

<section>
    <div id="retroEvent">
        <div class="line"></div>
        <h2> Rétrospective de l'évent : </h2>
        <div class="line"></div>
    </div>
    <br>
    <div id="testimonials">
        <!-- <form action="index.php?ctrl=forum&action=like&id=< ?=$event->getId()?>" method="post"> -->
        <?php
        // if (isset($likes) && !empty($likes)) 
        // {
        //     echo '<button type="submit" class="rounded p-1 border-info"> Liked (' . $likes . ')</button>';
        // } 
        // else 
        // {
        //     echo '<button type="submit" class="rounded p-1 border-info"> Like it !</button>';
        // }
        ?>
        <!-- </form> -->
        <?php
        if ($event->getEventLocked() == true) {
            if (App\Session::isAdmin() || $event->getUser()->getId() == App\Session::getUser()->getId()) {
                echo "L'event est terminé. Cependandant, vous avez les autorisations pour poster des messages et des photos à posteriori de l'event.";

                echo '<form action="index.php?ctrl=forum&action=postForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="event_id" value="' . $event->getId() . '">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <button type="submit" name="' . $event->getId() . '" class="rounded p-1 m-3 border-success" style="max-width: 18rem;">Nouveau Message</button>
                        </form>';

                echo "Envoi de fichiers : " .
                    '<form action="#" method="post" enctype="multipart/form-data">
                                <label for="file">Sélectionnez un fichier :</label>
                                <input type="file" id="file" name="file">
                                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                <button type="submit">Envoyer</button>
                              </form>';

                foreach ($posts as $post) {
                    ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <?= $post->getCreationDate() ?>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="table-group-divider">
                            <tr>
                                <td id="td">
                                    <?= $post->getMessage() ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em>
                                        <a href="index.php?ctrl=forum&action=detailUser&id=<?= $event->getUser()->getId() ?>"
                                            class="text-decoration-none">
                                            <?= $event->getUser()->getPseudo() ?> </a>,
                                        <?= $event->getName() ?>
                                    </em>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <?php
                }
            } else {
                echo " L'event est terminé, vous ne pouvez plus poster de messages. En cas de besoin, contacter l'organisateur de l'event. ";
            }
        } else {
            if (!empty($posts)) {
                echo '<form action="index.php?ctrl=forum&action=postForm" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="event_id" value="' . $event->getId() . '">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" name="' . $event->getId() . '" class="rounded p-1 m-3 border-success" style="max-width: 18rem;">Nouveau Message</button>
                                </form>';

                foreach ($posts as $post) {
                    ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <?= $post->getCreationDate() ?>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="table-group-divider">
                            <tr>
                                <td id="td">
                                    <?= $post->getMessage() ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <em>
                                        <a href="index.php?ctrl=forum&action=detailUser&id=<?= $event->getUser()->getId() ?>"
                                            class="text-decoration-none">
                                            <?= $event->getUser()->getPseudo() ?> </a>,
                                        <?= $event->getName() ?>
                                    </em>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                    <?php
                }
            } else {
                echo '<label class="label-info text-center alert alert-warning" role="alert"> Aucun message posté à ce jour. Soyez le premier à en créer un : </label> <br>
                                <form action="index.php?ctrl=forum&action=postForm" method="post" enctype="multipart/form-data">
                                    <input type="hidden" name="event_id" id="event_id" value="' . $event->getId() . '">
                                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                                    <button type="submit" name="' . $event->getId() . '" class="rounded p-1 m-3 border-success" style="max-width: 18rem;">Nouveau Message</button>
                                </form>';
            }
        }
        ?>
    </div>
</section>