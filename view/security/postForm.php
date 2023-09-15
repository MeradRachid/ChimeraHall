<?php 
$pageName="Publication";

$csrfToken = $_SESSION['csrf_token'];

$event_id = $_POST['event_id']; 

// var_dump($topic_id); 
?>
<div class="d-flex justify-content-center">

    <form action="index.php?ctrl=forum&action=addPost" method="POST" class="m-3 d-flex flex-column justify-content-center">

        <input type="hidden" name="event_id" value="<?=$event_id?>">
        
        <textarea name="message" cols="30" rows="10" placeholder="Start your message here.." class="form-control rounded p-1 m-1 text-center" style="width : 18rem; height: 18rem; overflow-wrap: break-word;" required></textarea>
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
        <button type="submit" class="rounded p-1 m-1 border-success" style="max-width: 18rem;"> Post a New Message </button> <br>

    </form>

</div>
