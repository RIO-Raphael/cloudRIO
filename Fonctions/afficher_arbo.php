<?php
//Fichier pour afficher l'arborescence du dossier en cours.
//On passe en arg l'id du dossier

$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

$bdd=BDD();
if (isset($_SESSION['uid'])){
    $uid=$_SESSION['uid'];//Utilisateur
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

if (isset($d_id)){
    Aff_arbo($d_id);
}else{
    echo "<p hidden>pas de d_id donné</p>";
}

function Aff_arbo($d_id){
    $sql="SELECT iddossiers FROM Dossiers WHERE (iddossiers='$d_id')";
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (!empty($result)){
        $nom=$result['d_nom'];
        echo "<a class='arbo_dos' href='".$_SERVER['DOCUMENT_ROOT']."/?d_id=$d_id' title='$nom'>$nom</a>";
        if ($result['dossier_parent']!=null){
            Aff_arbo($result['dossier_parent']);
        }
    }else{
        echo "Probleme BDD";
    }

?>