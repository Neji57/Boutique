<?php 

require_once('inc/header.php');

if(userAdmin())
{
    $tri = 'c.date_enregistrement';

    if($_POST)
    {
        if($_POST['tri'] == 'date')
        {
            $tri = 'c.date_enregistrement';
        }
        if($_POST['tri'] == 'etat')
        {
            $tri = 'c.etat';
        }
        if($_POST['tri'] == 'montant')
        {
            $tri = 'c.montant';
        }
    }

    $resultat = $pdo->query("SELECT c.id_commande, c.id_membre, c.montant, c.date_enregistrement, c.etat, p.reference, d.quantite, p.titre, p.couleur, p.taille, p.photo, m.pseudo, m.adresse, m.ville, m.code_postal FROM commande AS c, membre AS m, details_commande AS d, produit AS p WHERE c.id_membre = m.id_membre AND c.id_commande = d.id_commande AND p.id_produit = d.id_produit ORDER BY $tri");
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
            if ($key == 'photo') 
            {
            $contenu .= '<td><img height="100" src="' . URL . 'assets/uploads/img/' . $commande['photo'] . '"/></td>';
            }
            //debug($commande['montant']);
            if($key == 'montant')
            {
                $ca = $ca + $value*$commande['quantite'];
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

<form method="post">
    <div class="row my-3">
        <div class="col-md-8">
            <select name="tri" class="form-control" id="tri">
                <option value="date">Date</option>
                <option value="etat">Etat</option>
                <option value="montant">Montant</option>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-info">Trier</button>
        </div>
    </div>
</form>

<?= $contenu ?>

<p>Total du chiffre d'affaires pour les commandes en cours: <?= $ca ?> €.</p>


<?php require_once('inc/footer.php'); ?>