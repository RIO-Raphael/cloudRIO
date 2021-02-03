<?php
//Fichier pour afficher l'arborescence du dossier en cours.
//On passe en arg l'id du dossier

$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

$bdd=BDD();
if (isset($_SESSION['uid'])){
    $uid=$_SESSION['uid']; //Id du dossier que l'on regarde
}
if (isset($_POST['d_id'])){
    $d_id=$_POST['d_id']; //Id du dossier que l'on regarde
}elseif (isset($_SESSION['d_id'])){
    $d_id=$_SESSION['d_id']; //Id du dossier que l'on regarde
}elseif (isset($_GET['d_id'])){
    $d_id=$_GET['d_id']; //Id du dossier que l'on regarde
}else{
    if (isset($uid)){
        $sql='SELECT iddossiers FROM Dossiers WHERE (d_nom="Home") AND (proprietaire="'.$uid.'")';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        $d_id=$result['iddossiers']; //Id du dossier que l'on regarde
    }else{
        echo "pas de dossier défini et pas d'utilisateur connecté";
        exit;
    }
}