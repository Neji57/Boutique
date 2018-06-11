<?php require_once('inc/header.php'); 

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
// vérification si il existe le GET 'id' + il est rempli + c'est un chiffre
{
    $req = "SELECT * FROM produit WHERE id_produit = :id";
    $resultat = $pdo->prepare($req);
    $resultat->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $resultat->execute();

    if($resultat->rowCount() > 0)
    {
        $produit = $resultat->fetch();

        $req2 = "DELETE FROM produit WHERE id_produit = $produit[id_produit]";
        $resultat2 = $pdo->exec($req2);

        if($resultat !== FALSE)
        {
            $chemin_photo_suppression = RACINE_SITE . 'assets/uploads/img/' . $produit[photo] ;

            if(file_exists($chemin_photo_suppression) && $produit['photo'] != 'default.png' ) // cette fonction permet de vérifier s'il existe bien un fichier dans notre dosier serveur
            {
                unlink($chemin_photo_suppression);
                //cette fonction permet de supprimer le fichier selectionné
            }

            header('location:' . URL . 'admin/liste_produit.php' );

        }

    }
    else
    {
        header('location:' . URL . 'admin/liste_produit.php' );
    }
}
else
{
    header('location:' . URL . 'admin/liste_produit.php' );
}

