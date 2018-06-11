<?php
    require_once('inc/sidebar.php');

    if($_POST)
    {
        // debug($_POST);
        // debug($_FILES);

        if(!empty($_FILES['photo']['name'])) 
        { //signifie que si une photo est uploadée

            // 1) modif du nom de la photo pour éviter potentiel doublon
            $nom_photo = $_POST['reference'] . '_' . time() . '_' . rand(1, 999) . '_' . $_FILES['photo']['name'];

            // 2) On va créer une variable contenant le chemin ABSOLU et définitif de la photo 
            $chemin_photo = RACINE_SITE . 'uploads/img/' . $nom_photo;

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
        elseif(isset($_POST['photo_actuelle'])) { // si je suis en train de modifier un produit alors photo_actuelle existe et je prend sa valeur pour la mettre dans nom_photo afin qu'elle soit enregistrée dans la BDD
            $nom_photo = $_POST['photo_actuelle'];
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

            $resultat -> bindValue(':reference', $_POST['reference'], PDO::PARAM_STR);
            $resultat -> bindValue(':categorie', $_POST['categorie'], PDO::PARAM_STR);
            $resultat -> bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
            $resultat -> bindValue(':description', $_POST['description'], PDO::PARAM_STR);
            $resultat -> bindValue(':couleur', $_POST['couleur'], PDO::PARAM_STR);
            $resultat -> bindValue(':taille', $_POST['taille'], PDO::PARAM_STR);
            $resultat -> bindValue(':public', $_POST['public'], PDO::PARAM_STR);

            $resultat -> bindValue(':photo', $nom_photo, PDO::PARAM_STR);

            $resultat -> bindValue(':prix', $_POST['prix'], PDO::PARAM_STR); // FLOAT et DOUBLE uniquement via STR
            $resultat -> bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);

            if($resultat -> execute()) { //si requête = ok
                // 1) on enregistre le fichier photo dans son emplacement définitif
                if(!empty($_FILES['photo']['name'])) {
                    copy($_FILES['photo']['tmp_name'], $chemin_photo);
                    //copy permet de copier-coller un fichier. Ici on copie fichier photo de son emplacement temporaire vers emplacement définitif
                }

                // on redirige vers la page liste_produit.php
                header('location:liste_produit.php');
            }
        }
    }
    
    //traitement pour récupérer les infos du produits à modifier
    if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
        $req = "SELECT * FROM produit WHERE id_produit = :id";
        $resultat = $pdo -> prepare($req);
        $resultat -> bindValue(":id", $_GET['id'], PDO::PARAM_INT);
        $resultat -> execute();
            
        if($resultat -> rowCount() > 0) {
            $produit_actuel = $resultat -> fetch();

            // debug($produit_actuel);
        }
    }

    $reference = (isset($produit_actuel)) ? $produit_actuel['reference'] : ''; // ? = if; : = else
    $categorie = (isset($produit_actuel)) ? $produit_actuel['categorie'] : '';
    $titre = (isset($produit_actuel)) ? $produit_actuel['titre'] : '';
    $description = (isset($produit_actuel)) ? $produit_actuel['description'] : '';
    $couleur = (isset($produit_actuel)) ? $produit_actuel['couleur'] : '';
    $taille = (isset($produit_actuel)) ? $produit_actuel['taille'] : '';
    $public = (isset($produit_actuel)) ? $produit_actuel['public'] : '';
    $photo = (isset($produit_actuel)) ? $produit_actuel['photo'] : '';
    $prix = (isset($produit_actuel)) ? $produit_actuel['prix'] : '';
    $stock = (isset($produit_actuel)) ? $produit_actuel['stock'] : '';
    $id_produit = (isset($produit_actuel)) ? $produit_actuel['id_produit'] : '';

    $action = (isset($produit_actuel)) ? 'Modifier' : 'Ajouter';

    
?>

    <h3><?=$action?> un produit.</h3>

    <form action="" method="post" enctype="multipart/form-data">

        <input type="hidden" name="id_produit" value="<?= $id_produit ?>" /> <!-- ce champs caché permet de transmettre à notre requête REPLACE l'ID du produit en cours de modif -->

        <div class="form-group">
            <input type="text" class="form-control" name="reference" placeholder="Référence produit" value="<?=$reference?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="categorie" placeholder="Catégorie produit" value="<?=$categorie?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="titre" placeholder="Titre produit"  value="<?=$titre?>">
        </div>
        <div class="form-group">
            <textarea class="form-control" name="description" placeholder='Description produit' rows="3"><?=$description?></textarea>
        </div>
        <div class="form-group">
            <label for="couleur">Couleur</label>
            <select class="form-control" name="couleur" id='couleur'>
                <option <?php if($couleur == 'Noir'){echo 'selected';} ?>>Noir</option>
                <option <?php if($couleur == 'Blanc'){echo 'selected';} ?>>Blanc</option>
                <option <?php if($couleur == 'Rouge'){echo 'selected';} ?>>Rouge</option>
                <option <?php if($couleur == 'Bleu'){echo 'selected';} ?>>Bleu</option>
                <option <?php if($couleur == 'Orange'){echo 'selected';} ?>>Orange</option>
                <option <?php if($couleur == 'Vert'){echo 'selected';} ?>>Vert</option>
                <option <?php if($couleur == 'Turquoise'){echo 'selected';} ?>>Turquoise</option>
                <option <?php if($couleur == 'Jaune'){echo 'selected';} ?>>Jaune</option>
                <option <?php if($couleur == 'Moutarde'){echo 'selected';} ?>>Moutarde</option>
                <option <?php if($couleur == 'Saumon'){echo 'selected';} ?>>Saumon</option>
                <option <?php if($couleur == 'Violet'){echo 'selected';} ?>>Violet</option>
            </select>
        </div>
        <div class="form-group">
            <label for="taille">Taille</label>
            <select class="form-control" name="taille" id='taille'>
                <option <?php if($taille == 'XS'){echo 'selected';} ?>>XS</option>
                <option <?php if($taille == 'S'){echo 'selected';} ?>>S</option>
                <option <?php if($taille == 'M'){echo 'selected';} ?>>M</option>
                <option <?php if($taille == 'L'){echo 'selected';} ?>>L</option>
                <option <?php if($taille == 'XL'){echo 'selected';} ?>>XL</option>
            </select>
        </div>
        <div class="form-group">
            <label for="public">Public</label>
            <select class="form-control" name="public" id='public'>
                <option <?php if($public == 'm'){echo 'selected';} ?> value="m">Homme</option>
                <option <?php if($public == 'f'){echo 'selected';} ?> value="f">Femme</option>
                <option <?php if($public == 'mixte'){echo 'selected';} ?> value="mixte">Mixte</option>
            </select>
        </div>
        <div class="form-group">
            <label for="photo">Photo produit</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
            <?php
			if(isset($produit_actuel))
            { // si nous sommes en train de mofifier un produit
				echo '<input type="hidden" name="photo_actuelle" value="' . $photo . '"/>';
				echo '<img src="' . URL . 'uploads/img/' . $photo . '"/>';
				echo "</div>";
			}
		    ?>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="prix" placeholder="Prix produit" value="<?=$prix?>">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" name="stock" placeholder="Stock produit" value="<?=$stock?>">
        </div>
        <input type="submit" class="btn btn-primary" value="<?= $action ?>">
    </form>
            
<?php
    require_once('inc/footer.php');
?>