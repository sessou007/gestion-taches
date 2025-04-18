<?php
include('config.php');
// Récupération des données

$poste_name = $_POST['s_poste_name'] ;
//contrôle de la saisie
if(empty($poste_name))
{
    //message d'erreur
    echo '<script>alert("Veuillez entrer les données")</script>';
    header('refresh:0.5 url=home.php');
}
else
{
    //faire contrôle doublon
    $requete1 = "SELECT * from postes where poste_name='$poste_name'";
    $reponse1 = $pdo->query($requete1);
    $donnees1 = $reponse1->fetchAll();
    if($donnees1)
    {
        echo '<script>alert("Ce code existe")</script>';
        header('refresh:0.5 url= home.php');
    }
    else
    {
        //preparer la req d'insection
        $requete="INSERT INTO postes value ('','$poste_name')";
        //test d'execution de la req
        if($pdo->query($requete)==true)
        {
            //message de succes
            echo '<script>alert("Enrégistrement effectué avec succes")</script>';
            header('refresh:0.5 url=listposte.php');
        }
    } 
}
 ?>