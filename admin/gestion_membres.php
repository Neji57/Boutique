<?php 

require_once('inc/header.php');

if(userAdmin())
{
    $resultat = $pdo->query("SELECT id_membre, pseudo, nom, prenom, email FROM membre");
    $membres = $resultat->fetchAll();

    $contenu .= "<table class='table'>";
    $contenu .= "<thead><tr>";

    for ($i = 0; $i < $resultat->columnCount(); $i++) 
    {
        $champs = $resultat->getColumnMeta($i);
        $contenu .= "<th>" . $champs['name'] . "</th>";
    }
    $contenu .= "</tr></thead><tbody>";
    $contenu .= "</tr></thead><tbody>";
    foreach ($membres as $membre) 
    {
        $contenu .= "<tr>";
        foreach ($membre as $key => $value) 
        {
            
            $contenu .= "<td>" . $value . "</td>";
            
        }
        //$contenu .= "<td><a href='" . URL . "admin/set_membre_admin.php?id=" . $membre['id_membre'] . "'>Modifier</a></td>";
        $contenu .= "<td><a href='" . URL . "admin/suppression_membre.php?id=" . $membre['id_membre'] . "'>Supprimer</a></td>";
        $contenu .= "</tr>";
    }
    $contenu .= "</tbody></table>";
}
else
{
    header('location:../index.php');
}
debug($membre['id_membre']);

?>

<h1>Gestion des membres</h1>

<?= $contenu ?>



<?php require_once('inc/footer.php'); ?>