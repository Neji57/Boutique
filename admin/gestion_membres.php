<?php 

require_once('inc/header.php');

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
    $contenu .= "</tr>";
}
$contenu .= "</tbody></table>";

?>

<h1>Liste des produits</h1>

<?= $contenu ?>



<?php require_once('inc/footer.php'); ?>