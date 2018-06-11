<?php require_once('inc/header.php'); 

$chemin_modif = URL . "membres.php?id=" . $_SESSION['membre']['id_membre'];
$delete = URL . "sp_membre.php?id=" .  $_SESSION['membre']['id_membre'];

// if (!isset($_SESSION['membre'])) {
//     header('location:connexion.php');
// }

if (!userConnect()) 
{
  header('location:connexion.php');
  exit(); 
} 
$page = 'Bienvenue ' . $_SESSION['membre']['pseudo'] . ' !';

debug($_SESSION['membre']['id_membre']);

?>

<div class="starter-template"> 
  <h1><?= $page ?></h1>
  <p class="lead">Voici vos informations</p>
  <ul class="list-group">
      <li class="list-group-item">Votre nom: <?= $_SESSION['membre']['nom'] ?></li>
      <li class="list-group-item">Votre prenom: <?= $_SESSION['membre']['prenom'] ?></li>
      <li class="list-group-item">Votre email: <?= $_SESSION['membre']['email'] ?></li>
  </ul>
  <a class="btn btn-primary my-4" href=" <?= $chemin_modif ?>">Modifier mes infos</a>
  <a class="btn btn-primary my-4" href=" <?= $delete ?>">Se desinsrire</a>
</div>



<?php require_once('inc/footer.php'); ?>