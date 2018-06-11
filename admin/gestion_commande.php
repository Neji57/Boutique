<?php 

require_once('inc/header.php');

if(userAdmin())
{
    $resultat = $pdo->query("SELECT c.id_commande, c.id_membre, c.montant, c.date_enregistrement, c.etat, m.pseudo, m.adresse, m.ville, m.code_postal FROM commande AS c, membre AS m WHERE c.id_membre = m.id_membre");
    $commandes = $resultat->fetchAll();

    $ca = "<p>Total du chiffre d'affaires pour les commandes en cours: ";

    $contenu .= "<table class='table'>";
    $contenu .= "<thead><tr>";

    for ($i = 0; $i < $resultat->columnCount(); $i++) 
    {
        $champs = $resultat->getColumnMeta($i);
        $contenu .= "<th>" . $champs['name'] . "</th>";
    }
    $contenu .= "</tr></thead><tbody>";
    $contenu .= "</tr></thead><tbody>";
    foreach ($commandes as $commande) 
    {
        $contenu .= "<tr>";
        foreach ($commande as $key => $value) 
        {
            //si on traite la valeur de l'état, on insère un select
            if ($key == 'etat') 
            {
            $contenu .= "<td><select name='etat' class='form-control'><option value='" . $value . "'>" . $value . "</option></select></td>";
            } 
            else
            {
                $contenu .= "<td>" . $value . "</td>";
            }
        }
        $contenu .= "</tr>";
    }
    $contenu .= "</tbody></table>";



    // créer une boucle pour ajouter la somme des montants dans $ca

    $ca .= "</p>";

}
else
{
    header('location:../index.php');
}

?>




<h1>Gestion des commandes</h1>

<?= $contenu ?>

<?= $ca ?>


<?php require_once('inc/footer.php'); ?>