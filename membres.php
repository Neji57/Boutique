<?php
    require_once('inc/header.php');
    // debug($_POST);
    if($_POST)
    {
        //VERIFICATION PSEUDO
        if (!empty($_POST['pseudo'])) 
        {
            $verif_pseudo = preg_match('#^[a-zA-Z0-9-._]{3,20}$#', $_POST['pseudo']); // la fonction preg_match() me permet de définir les caracteres autorisés dans une STR/VAR. Elle attend 2 arguments: REGEX ou Expression régulière + ma STR/VAR à checker. Elle renvoie un TRUE ou un FALSE

            if (!$verif_pseudo) 
            {
                $msg_erreur .= "<div class='alert alert-danger'>Votre pseudo doit comporter entre 3 et 20 caractères (Majuscules, minuscules, chiffres et caractères '.', '_', '-' acceptés)";
            }
        }
        // Fin vérification pseudo

        //VERIFICATION MDP
        if (!empty($_POST['mdp'])) 
        {
            $verif_mdp = preg_match('#^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*\'\?$@%_])([-+!*\?$\'@%_\w]{6,15})$#', $_POST['mdp']); // le mdp doit contenir: 6 caractère min et 15 max + 1 MAJ + 1 MIN + 1 chiffre + 1 caractère spécial

            if (!$verif_mdp) 
            {
            $msg_erreur .= "<div class='alert alert-danger'>Votre mot de passe doit comporter entre 6 et 15 caractères dont des majuscules, minuscules, chiffres et caractères spéciaux)";
            }
        }
        // Fin vérification mot de passe

        //VERIFICATION EMAIL
        if (!empty($_POST['email'])) 
        {
            $verif_email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL); 
            // la fonction filter_var() nous permet de vérifier une STR (email, URL -> FILTER_VALIDATE_URL Elle prend 2 arguments: la STR + la methode. Elle retourne un BOOL )

            $dom_interdit = [
            'mailinator.com',
            'yopmail.com',
            'mail.com'
            ];

            $dom_email = explode('@', $_POST['email']);
            // La fonction explode() nous permet d'exploser une STR/VAR à partir de l'élément choisi en 1er argument

            if (!$verif_email || in_array($dom_email[1], $dom_interdit)) 
            {
                $msg_erreur .= "<div class='alert alert-danger'>Veuillez renseigner un email valide";
            }
        }
        //Fin vérification email

        // Vérification des champs
        if(empty($msg_erreur))
        {
            if(!empty($_POST))
            {
                //$resultat2 = $pdo->prepare("REPLACE INTO membre (id_membre, pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:id, :pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)");

                $resultat2 = $pdo->prepare("UPDATE membre SET id_membre = :id, pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, ville = :ville, code_postal = :code_postal, adresse = :adresse WHERE id_membre = :id");

                $resultat2->bindValue(':id', $_POST['id_membre'], PDO::PARAM_INT);
                
                //cryptage du mot de passe
                $mdp_crypte = password_hash($_POST['mdp'], PASSWORD_BCRYPT);

                $resultat2->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
                $resultat2->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $resultat2->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $resultat2->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
                $resultat2->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
                $resultat2->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
                $resultat2->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
                $resultat2->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);

                if(!empty($_POST['mdp']))
                {
                    $resultat2->bindValue(':mdp', $mdp_crypte, PDO::PARAM_STR);
                }
                $resultat2->execute();
            }

            
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
    $nom = (isset($modif)) ? $modif['nom'] : '';
    $prenom = (isset($modif)) ? $modif['prenom'] : '';
    $email = (isset($modif)) ? $modif['email'] : '';
    $civilite = (isset($modif)) ? $modif['civilite'] : '';
    $ville = (isset($modif)) ? $modif['ville'] : '';
    $code_postal = (isset($modif)) ? $modif['code_postal'] : '';
    $adresse = (isset($modif)) ? $modif['adresse'] : '';
    $id_membre = (isset($modif)) ? $modif['id_membre'] : '';

    if(!empty($_FILES['avatar']['name'])) 
        { //signifie que si une photo est uploadée

            // 1) modif du nom de la photo pour éviter potentiel doublon
            $nom_photo = $_POST['avatar'] . '_' . time() . '_' . rand(1, 999) . '_' . $_FILES['avatar']['name'];

            // 2) On va créer une variable contenant le chemin ABSOLU et définitif de la photo 
            $chemin_photo = RACINE_SITE . 'assets/uploads/img/' . $nom_photo;

            // 3) vérification de l'intégrité du fichier uploadé
            if($_FILES['photo']['size'] > 2000000) {
                $msg .= '<div class="erreur">Veuillez choisir un fichier de 2Mo maximum</div>';
            }

            $ext = array('image/jpeg', 'image/png', 'image/gif');
            if(!in_array($_FILES['photo']['type'], $ext)){
                $msg .= '<div class="erreur">Veuillez sélectionner une image JPG, JPEG, PNG ou GIF</div>';
            }

            // 4) Si tout est ok et pdt enregistré en BDD, copier image dans notre dossier photo => APRES VERIFICATION (sauvegarder espace serveur)

        } 
        else {
            // $msg .= '<div class="erreur">Veuillez sélectionner une photo</div>';
            $nom_photo = 'default.jpg';
        }

        // Vérifications sur toutes les autres champs : nbr de caractère, preg_match, valeur numérique (prix et stock), non vide ...

        // Si tout est ok dans notre formulaire on peut enregistrer le produit en BDD et la photo dans son emplacement définitif
        if(empty($msg)) {

            if(!empty($_POST['id_produit'])) {
                // on enregistre la modification
                $resultat = $pdo -> prepare("REPLACE INTO produit (id_produit, reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");

                $resultat -> bindValue(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
            }
            else {
                //enregistre en BDD
                $resultat = $pdo -> prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)");
            }
        }
    //debug($_FILES);
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
            <input type="text" class="form-control" name="mdp" id="mdp" aria-describedby="helpId" placeholder="Votre mot de passe">
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
        <div class="form-group col-md-4">
            <input type="file" class="form-control" name="avatar" id="avatar" aria-describedby="helpId" value="<?= $avatar ?>">
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-block btn-success">Modifier</button>
        </div>
    </div>
</form>

<?php require_once('inc/footer.php'); ?>