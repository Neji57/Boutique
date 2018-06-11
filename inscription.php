

<?php require_once("inc/header.php");

$page = 'Inscription';

if ($_POST) {
  // debug($_POST, 2);


  //VERIFICATION PSEUDO
  if (!empty($_POST['pseudo'])) {
    $verif_pseudo = preg_match('#^[a-zA-Z0-9-._]{3,20}$#',
    $_POST['pseudo']); // la fonction preg_match() me permet de définir les caracteres autorisés dans une STR/VAR. Elle attend 2 arguments: REGEX ou Expression régulière + ma STR/VAR à checker. Elle renvoie un TRUE ou un FALSE

    if (!$verif_pseudo) {
      $msg_erreur .= "<div class='alert alert-danger'>Votre pseudo doit comporter entre 3 et 20 caractères (Majuscules, minuscules, chiffres et caractères '.', '_', '-' acceptés)";
    }
  }
  else {
    $msg_erreur .= "<div class='alert alert-danger'>Veuillez entrer un pseudo valide";
  }
  // Fin vérification pseudo


  //VERIFICATION MDP
  if (!empty($_POST['mdp'])) {
    $verif_mdp = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#', $_POST['mdp']); // le mdp doit contenir: 6 caractère min et 15 max + 1 MAJ + 1 MIN + 1 chiffre + 1 caractère spécial

    if (!$verif_mdp) {
      $msg_erreur .= "<div class='alert alert-danger'>Votre mot de passe doit comporter entre 6 et 15 caractères dont des majuscules, minuscules, chiffres et caractères spéciaux)";
    } 
  } else {
    $msg_erreur .= "<div class='alert alert-danger'>Veuillez entrer un mot de passe valide";
  }
  // Fin vérification mot de passe

  //VERIFICATION EMAIL
  if (!empty($_POST['email'])) {
    $verif_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); 
    // la fonction filter_var() nous permet de vérifier une STR (email, URL -> FILTER_VALIDATE_URL Elle prend 2 arguments: la STR + la methode. Elle retourne un BOOL )

    $dom_interdit = [
      'mailinator.com',
      'yopmail.com',
      'mail.com'
    ];

    $dom_email = explode('@', $_POST['email']);
    // La fonction explode() nous permet d'exploser une STR/VAR à partir de l'élément choisi en 1er argument

    // debug($dom_email);

    if (!$verif_email || in_array($dom_email[1], $dom_interdit)) {
      $msg_erreur .= "<div class='alert alert-danger'>Veuillez renseigner un email valide";
    } 
  } else 
  {
      $msg_erreur .= "<div class='alert alert-danger'>Veuillez renseigner un email";
    }
  // Fin vérification email

  // if (!isset($_POST['civilite']) || $_POST['civilite'] !== "m" || $_POST['civilite'] !== "f" || $_POST['civilite'] !== "o") {
  //   $msg_erreur .= "<div class='alert alert-danger'>Veuillez rentrer une civilité valide</div>";
  // }

  // AUTRES VÉRIFS POSSIBLES
  if (empty($msg_erreur)) {
    // Vérification si pseudo libre
    $resultat = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $resultat->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $resultat->execute();

            // Si on a une ligne de résultat dans la BDD
    if ($resultat->rowcount() > 0) {
      $msg_erreur .= "<div class='alert alert-danger'>Le pseudo " . $_POST['pseudo'] . " n'est malheureusement pas disponible. Veuillez en choisir un autre.</div>";
    }
    else // Pas de ligne en retour, je peux inscrire l'utilisateur
    {
      $resultat = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, 0)");

      $mdp_crypte = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
      /*
      La fonction password_hash() nous permet de sécuriser l'enregistrement du mdp en BDD.
      Elle prend 2 arguments : 
        - L'élement à hasher
        - La méthodologie d'hashage
       */

      $resultat->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
      $resultat->bindValue(':mdp', $mdp_crypte, PDO::PARAM_STR);
      $resultat->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
      $resultat->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
      $resultat->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
      $resultat->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
      $resultat->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
      $resultat->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
      $resultat->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);

      
      if ($resultat->execute()) {
        header('location:connexion.php');
      }

    }
  }
  

}


//traitement pour réaffivher les valeurs entrées en cas de rechargement de la page et erreur d'inscription

$pseudo = (isset($_POST['pseudo'])) ? $_POST['pseudo'] : '';
// ici nous mettons une condition: si on recoit du POST, alors ma variable contiendra la valeur envoyée sinon, la valeur sera vide
$nom = (isset($_POST['nom'])) ? $_POST['nom'] : '';
$prenom = (isset($_POST['prenom'])) ? $_POST['prenom'] : '';
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$ville = (isset($_POST['ville'])) ? $_POST['ville'] : '';
$code_postal = (isset($_POST['code_postal'])) ? $_POST['code_postal'] : '';
$adresse = (isset($_POST['adresse'])) ? $_POST['adresse'] : '';

?>

        
<div class="starter-template">
  <h1><?= $page ?></h1>
  <?= $msg_erreur ?>
</div>

<form method="post">
  <div class="form-group">
    
    <input name="pseudo" type="text" class="form-control" id="pseudo" value="<?= $pseudo ?>" 
    placeholder="Pseudo">
  </div>
  <div class="form-group">
    <input name="mdp" type="password" class="form-control" id="exampleInputPassword1" 
    placeholder="Mot de passe">
  </div>
  <div class="form-group">
    <input name="nom" type="text" class="form-control" id="nom" value="<?= $nom ?>"
    placeholder="Nom">
  </div>
  <div class="form-group">
    <input name="prenom" type="text" class="form-control" id="prenom" value="<?= $prenom ?>"
    placeholder="Prénom">
  </div>
  <div class="form-group">
    <input name="email" type="email" class="form-control" id="exampleInputEmail1" value="<?= $email ?>" 
    placeholder="Email">
    <small id="emailHelp" class="form-text text-muted">Nous ne communiquerons jamais vos coordonnées.</small>
  </div>
  <div class="form-group">
  <select name="civilite" class="form-control" id="exampleFormControlSelect1">
      <option value="f">Femme</option>
      <option value="m">Homme</option>
      <option value="o">Autre</option>
    </select>
  </div>
  <div class="form-group">
    <input name="ville" type="text" class="form-control" id="ville" placeholder="Ville" value="<?= $ville ?>">
  </div>
  <div class="form-group">
    <input name="code_postal" type="text" class="form-control" id="codepostal" value="<?= $code_postal ?>"
    placeholder="Code postal">
  </div>
  <div class="form-group">
    <input name="adresse" type="typetext" class="form-control" id="adresse" value="<?= $adresse ?>"
    placeholder="adresse">
  </div>

  
  <button type="submit" class="btn btn-info">Inscription</button>
</form>


   

<?php require_once("inc/footer.php"); ?>