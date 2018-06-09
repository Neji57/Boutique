<?php require_once('inc/header.php'); 


// if (!isset($_SESSION['membre'])) {
//     header('location:connexion.php');
// }

if (!userConnect()) 
{
  header('location:connexion.php');
  exit(); 
}
$page = 'Bienvenue ' . $_SESSION['membre']['pseudo'] . ' !';

?>

<div class="starter-template">
  <h1><?= $page ?></h1>
  <p class="lead">Voici vos informations</p>
  <ul class="list-group">
      <li class="list-group-item">Votre nom: <?= $_SESSION['membre']['nom'] ?></li>
      <li class="list-group-item">Votre prenom: <?= $_SESSION['membre']['prenom'] ?></li>
      <li class="list-group-item">Votre email: <?= $_SESSION['membre']['email'] ?></li>
  </ul>
</div>

<?php require_once('inc/footer.php'); ?>