<?php 

require_once('inc/header.php');

//$resultat = $pdo->query("SELECT * FROM commande AS c, membre AS m");
$resultat = $pdo->query("SELECT id_commande, c.id_membre, montant, date_enregistrement, etat, pseudo, adresse, ville, code_postal FROM commande AS c, membre AS m");
$commandes = $resultat->fetchAll();

$contenu .= "<table class='table'>";
$contenu .= "<thead><tr>";

for ($i = 0; $i < $resultat->columnCount(); $i++) {
    $champs = $resultat->getColumnMeta($i);
    $contenu .= "<th>" . $champs['name'] . "</th>";
}
$contenu .= "</tr></thead><tbody>";
$contenu .= "</tr></thead><tbody>";
foreach ($commandes as $commande) {
    $contenu .= "<tr>";
    foreach ($commande as $key => $value) {
        
            $contenu .= "<td>" . $value . "</td>";
        
    }
    
    $contenu .= "</tr>";
}
$contenu .= "</tbody></table>";

// debug($champs);

?>

<h1>Gestion des commandes</h1>

<?= $contenu ?>



<?php require_once('inc/footer.php'); ?>