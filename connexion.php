<?php require_once("inc/header.php");

$page = 'Connexion';


if($_POST) {
  
  // debug($_POST);
  

  $req = "SELECT * FROM membre WHERE pseudo = :pseudo";

  $resultat = $pdo->prepare($req);
  $resultat->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
  $resultat->execute();

  if($resultat->rowCount() > 0)// si on trouve un résultat pour le pseudo
  {
    $membre = $resultat->fetch();

    // debug($membre);

     /*
      La fonction password_verify() est liée à password_hash() et nous permet de vérifier la correspondance entre un mot de passe et un mot de passe hashé.
      Elle prend 2 paramètres :
          - Le MDP venant du formulaire
          - Le MDP en BDD
     */
    if(password_verify($_POST['mdp'], $membre['mdp']))
    {
      foreach($membre as $key => $value)
      {
        if($key != "mdp")
        {
          $_SESSION['membre'][$key] = $value;
          header('location:profil.php');
        }
      }
      // debug($_SESSION);

    }
    else {
      $msg_erreur .= "<div class='alert alert-danger'>Erreur d'identification Veuillez réessayer !</div>";
    }
  }
  else 
  {
      $msg_erreur .= "<div class='alert alert-danger'>Erreur d'identification. Veuillez réessayer !</div>";
  }
}

?>

        
<div class="starter-template">
  <h1><?= $page ?></h1>
  <p class="lead">Super e-commerce</p>
  <p class="mt-5">Si vous n'avez pas de compte ? Inscrivez-vous !</p>
</div>

<form method="post">
  <?= $msg_erreur ?>
  <div class="form-group">
    
    <input name="pseudo" type="text" class="form-control" id="pseudo"  
    placeholder="Pseudo">
  </div>
  <div class="form-group">
    <input name="mdp" type="password" class="form-control" id="exampleInputPassword1" 
    placeholder="Mot de passe">
  </div>
  
  <button type="submit" class="btn btn-info">Connexion</button>
</form>
   

<?php require_once("inc/footer.php"); ?>