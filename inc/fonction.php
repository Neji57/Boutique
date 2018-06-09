<?php

    // création d'une fonction pour afficher les var_dump() et print_r

    function debug($var, $mode=1)
    {
        echo '<div class= "alert alert-warning">';

        $trace = debug_backtrace(); // permet de tracer l'endroit ou une fonction est appelée/exécutée=>MULTIDIMENSIONNEL
        $trace = array_shift($trace); // permet de casser le premier rang d'un array multipour renvoyer les premiers résultats
        
        echo "Le debug a été demandé dans le fichier $trace[file] à la ligne $trace[line] <hr>";

        echo '<pre>';
        

        switch ($mode) {
            case '1':
                var_dump($var);
                break;
            
            default:
                print_r($var);
                break;
        }

        echo '</pre>';

        echo '</div>';
    }

    //Fonction pour vérifier si un user est connecté
    function userConnect()
    {
        // if (isset($_SESSION['membre'])) {
        //     return TRUE;
        // }
        // else {
        //     return TRUE;
        // }
        
        if (isset($_SESSION['membre'])) return TRUE;
        else return FALSE;
        
    }

    function userAdmin()
    {
        if ( userConnect() && $_SESSION['membre']['statut'] == 1 ) 
        return TRUE;
        else return FALSE;
    }

?>