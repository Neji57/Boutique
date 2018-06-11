<?php require_once('inc/header.php'); 

if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']))
// vérification si il existe le GET 'id' + il est rempli + c'est un chiffre
{
    $req = "SELECT * FROM membre WHERE id_membre = :id";
    $resultat = $pdo->prepare($req);
    $resultat->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $resultat->execute();

    if($resultat->rowCount() > 0)
    {
        $membre = $resultat->fetch();

        $req2 = "UPDATE membre SET statut = 0 WHERE id_membre = $membre[id_membre]";
        $resultat2 = $pdo->exec($req2);

        if($resultat !== FALSE) 
        {
            header('location:' . URL . 'admin/gestion_membres.php' );
        }

    }
    else
    {
        header('location:' . URL . 'admin/gestion_membres.php' );
    }
}
else
{
    header('location:' . URL . 'admin/gestion_membres.php' );
}