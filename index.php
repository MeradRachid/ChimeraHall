<?php
    namespace App;

    define('DS', DIRECTORY_SEPARATOR); // le caractère séparateur de dossier (/ ou \) 
    // Pour une meilleure portabilité sur les différents systêmes.

    define('BASE_DIR', dirname(__FILE__).DS); // répertoire de base de l'application qui contient le fichier index.php
    define('BASE_URL', 'http://phpobjet.test/ChimeraHall_RM/ChimeraHall/'); // pour se simplifier la vie

    define('VIEW_DIR', BASE_DIR."view/");     //le chemin où se trouvent les vues
    define('PUBLIC_DIR', "/public");     //le chemin où se trouvent les fichiers publics (CSS, JS, IMG)

    define('DEFAULT_CTRL', 'Home');//nom du contrôleur par défaut
    define('ADMIN_MAIL', "admin@gmail.com");//mail de l'administrateur

    require("app/Autoloader.php");

    Autoloader::register();
    
    // On démarre une session ou on récupère la session actuelle
    session_start();

    // Puis on intègre la classe Session qui prend la main sur les messages en session
    use App\Session as Session;

    if(isset($_SESSION['csrf_token']))
    {
        
    }
     else
    {
        $csrfToken = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrfToken;
    }

    //---------REQUETE HTTP INTERCEPTEE-----------
    $ctrlname = DEFAULT_CTRL;//on prend le controller par défaut
    //ex : index.php?ctrl=home
    if(isset($_GET['ctrl']))
    {
        $ctrlname = $_GET['ctrl'];
    }

    // On construit le namespace de la classe Controller à appeller
    $ctrlNS = "controller\\".ucfirst($ctrlname)."Controller";

    // On vérifie que le namespace pointe vers une classe qui existe
    if(!class_exists($ctrlNS))
    {
        //si c'est pas le cas, on choisit le namespace du controller par défaut
        $ctrlNS = "controller\\".DEFAULT_CTRL."Controller";
    }
    $ctrl = new $ctrlNS();

    // Action par défaut de n'importe quel contrôleur
    $action = "index"; 

    // Si l'action est présente dans l'url ET que la méthode correspondante existe dans le ctrl
    if(isset($_GET['action']) && method_exists($ctrl, $_GET['action']))
    {
        // La méthode à appeller sera celle de l'url
        $action = $_GET['action'];
    }
    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
    }
    else $id = null;
    // exemple : HomeController->users(null)
    $result = $ctrl->$action($id);
    
    /*--------CHARGEMENT PAGE--------*/
    
    if($action == "ajax")
    { //si l'action était ajax
        // echo $result;  
        //on affiche directement le return du contrôleur (càd la réponse HTTP sera uniquement celle-ci)
    
        //   var_dump( $result['data']['search']); 
        // due to : warning array to string in conversion ligne 66. 
      foreach($result['data']['search'] as $user) 
      {
        // echo
        '<p> # Membre n° : '.$user->getId().
        " <br> > ".$user->getPseudo().
        // " : <br> ".$user->getRole().
        '</p>';
      } 
    }
     else
    {
        ob_start(); //démarre un buffer (tampon de sortie)

        /*la vue s'insère dans le buffer qui devra être vidé au milieu du layout*/
        include($result['view']);

        /* Je mets cet affichage dans une variable */
        $page = ob_get_contents();

        /* J'efface le tampon */
        ob_end_clean();

        /* J'affiche le template (ou le layout) principal */
        // include VIEW_DIR."template.php";
        include VIEW_DIR."layout.php";
    }

?>