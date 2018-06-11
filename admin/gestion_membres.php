<?php 

require_once('inc/header.php');

if(userAdmin())
{
    $resultat = $pdo->query("SELECT id_membre, pseudo, nom, prenom, email, statut FROM membre");
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
        //Si le membre n'est pas admin
        if($membre['statut'] == 0)
        {
            //on ajoute un lien pour modifier le statut
            $contenu .= "<td><a class='btn btn-outline-success btn-sm' href='" . URL . "admin/set_membre_admin.php?id=" . $membre['id_membre'] . "'>Donner statut Admin</a></td>";
        }
        //Si le membre est admin
        if($membre['statut'] == 1)
        {
            //on ajoute un lien pour modifier le statut
            $contenu .= "<td><a class='btn btn-outline-danger btn-sm' href='" . URL . "admin/unset_membre_admin.php?id=" . $membre['id_membre'] . "'>Retirer statut Admin</a></td>";
        }
        
        $contenu .= "<td><a class='btn btn-outline-warning btn-sm' href='" . URL . "admin/suppression_membre.php?id=" . $membre['id_membre'] . "'>Supprimer</a></td>";
        $contenu .= "</tr>";
    }
    $contenu .= "</tbody></table>";
}
else
{
    header('location:../index.php');
}
//debug($membre['statut']);

?>

<h1>Gestion des membres</h1>

<?= $contenu ?>



<?php require_once('inc/footer.php'); ?>