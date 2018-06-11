<?php 

require_once('inc/header.php');

$resultat = $pdo->query("SELECT * FROM produit");
$produits = $resultat->fetchAll();

$contenu .= "<table class='table'>";
$contenu .= "<thead><tr>";

for ($i = 0; $i < $resultat->columnCount(); $i++) {
    $champs = $resultat->getColumnMeta($i);
    $contenu .= "<th>" . $champs['name'] . "</th>";
}
$contenu .= "</tr></thead><tbody>";
$contenu .= "</tr></thead><tbody>";
foreach ($produits as $produit) {
    $contenu .= "<tr>";
    foreach ($produit as $key => $value) {
        if ($key == 'photo') {
            $contenu .= '<td><img height="100" src="' . URL . 'assets/uploads/img/' . $produit['photo'] . '"/></td>';
        } else {
            $contenu .= "<td>" . $value . "</td>";
        }
    }
    $contenu .= "<td><a class='btn btn-outline-warning btn-sm' href='" . URL . "admin/gestion_produit.php?id=" . $produit['id_produit'] . "'>Modifier</a></td>";
    $contenu .= "<td><a class='btn btn-outline-danger btn-sm' href='" . URL . "admin/suppression_produit.php?id=" . $produit['id_produit'] . "'>Supprimer</a></td>";
    $contenu .= "</tr>";
}
$contenu .= "</tbody></table>";

debug($produit['id_produit']);

?>

<h1>Liste des produits</h1>

<?= $contenu ?>



<?php require_once('inc/footer.php'); ?>