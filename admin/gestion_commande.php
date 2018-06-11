<?php 

require_once('inc/header.php');

if(userAdmin())
{
    $resultat = $pdo->query("SELECT c.id_commande, c.id_membre, c.montant, c.date_enregistrement, c.etat, m.pseudo, m.adresse, m.ville, m.code_postal FROM commande AS c, membre AS m WHERE c.id_membre = m.id_membre");
    $commandes = $resultat->fetchAll();

    $ca = 0;

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
            //debug($commande['montant']);
            if($key == 'montant')
            {
                $ca = $ca + $value;
            }
            //si on traite la valeur de l'état, on insère un select
            if ($key == 'etat') 
            {
                $contenu .= "<td>";

                $contenu .= "<select name='etat' class='form-control' id='etat'><option value='preparation de la commande'>Préparation de la commande</option><option value='en cours de livraison'>En cours de livraison</option><option value='livre'>Livré</option></select>";
                
                $contenu .= "</td>";
            } 
            else
            {
                $contenu .= "<td>" . $value . "</td>";
            }
        }
        $contenu .= "</tr>";
    }
    $contenu .= "</tbody></table>";

}
else
{
    header('location:../index.php');
}


?>




<h1>Gestion des commandes</h1>

<?= $contenu ?>

<p>Total du chiffre d'affaires pour les commandes en cours: <?= $ca ?> €.</p>


<?php require_once('inc/footer.php'); ?>