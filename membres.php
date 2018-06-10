<?php
    require_once('inc/header.php');
    // debug($_POST);
    // debug($_FILE);
    if($_POST)
    {
        // Vérification des champs
        if(empty($msg_erreur))
        {
            // On enregistre la modification
            if(!empty($_POST))
            {
                $resultat = $pdo->prepare("REPLACE INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)");
                $resultat->bindValue(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
                // $nom_photo = (isset($_POST['photo'])) ? $_POST['photo'] : '' ;
            }
            $resultat->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
            $resultat->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
            $resultat->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
            $resultat->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
            $resultat->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
            $resultat->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
            $resultat->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
            $resultat->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
            $resultat->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        }
    }
    // Vérification si il existe le $_GET['id'] + il est rempli + c'est un chiffre
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
    {
        $req = "SELECT * FROM membre WHERE id_membre = :id";
        $resultat = $pdo->prepare($req);
        $resultat->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $resultat->execute();
        if($resultat->rowCount() > 0)
        {
            $modif = $resultat->fetch();
        }
    }
    $pseudo = (isset($modif)) ? $modif['pseudo'] : '';
    $mdp = (isset($modif)) ? $modif['mdp'] : '';
    $nom = (isset($modif)) ? $modif['nom'] : '';
    $prenom = (isset($modif)) ? $modif['prenom'] : '';
    $email = (isset($modif)) ? $modif['email'] : '';
    $civilite = (isset($modif)) ? $modif['civilite'] : '';
    $ville = (isset($modif)) ? $modif['ville'] : '';
    $code_postal = (isset($modif)) ? $modif['code_postal'] : '';
    $adresse = (isset($modif)) ? $modif['adresse'] : '';
?>

<h1>Modifier mes infos</h1>
<form action="" method="post" enctype="multipart/form-data" class="mb-4">
    <?= $msg_erreur ?>
    <div class="row">
        <div class="form-group col-12">
            <input type="hidden" class="form-control" name="id_membre" id="id_membre" aria-describedby="helpId" placeholder="Identifiant membre" value="<?= $id_membre ?>">
        </div>
    </div>
    

    <div class="row">
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="pseudo" id="pseudo" aria-describedby="helpId" placeholder="Pseudo" value="<?= $pseudo ?>">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="nom" id="nom" aria-describedby="helpId" placeholder="Votre Nom" value="<?= $nom ?>">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="prenom" id="prenom" aria-describedby="helpId" placeholder="Votre prénom" value="<?= $prenom ?>">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="mdp" id="mdp" aria-describedby="helpId" placeholder="Votre mot de passe" value="<?= $mdp ?>">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="email" id="email" aria-describedby="helpId" placeholder="Votre adresse mail" value="<?= $email ?>">
        </div>
        <div class="form-group col-md-4">
            <select name="civilite" class="form-control" id="civilite">
                <option <?php if(empty($civilite)){echo 'selected';}?> disabled>--Civilité--</option>
                <option <?php if($civilite == "m"){echo 'selected';}?> value="m">Homme</option>
                <option <?php if($civilite == "f"){echo 'selected';}?> value="f">Femme</option>
                <option <?php if($civilite == "o"){echo 'selected';}?> value="o">Autre</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-12">
            <input type="text" class="form-control" name="adresse" id="adresse" aria-describedby="helpId" placeholder="Votre adresse" value="<?= $adresse ?>">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="ville" id="ville" aria-describedby="helpId" placeholder="Ville" value="<?= $ville ?>">
        </div>
        <div class="form-group col-md-4">
            <input type="text" class="form-control" name="code_postal" id="code_postal" aria-describedby="helpId" placeholder="code postal" value="<?= $code_postal ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-block btn-success">Modifier</button>
        </div>
    </div>
</form>


<?php require_once('inc/footer.php'); ?>