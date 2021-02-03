<?php
//Fichier pour afficher l'arborescence du dossier en cours.
//On passe en arg l'id du dossier

$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

$bdd=BDD();

if (isset($_SESSION['login'])){
    $uid=$_SESSION['login'];//Utilisateur
}else{
    print_r($_SESSION);
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

//div contain arbo CSS dans index2.css
echo "<div id='contain_arbo'>";

if (isset($d_id)){
    Aff_arbo($d_id);
}else{
    echo "<p hidden>pas de d_id donné</p>";
}

echo "<div id='contain_arbo'>";

function Aff_arbo($d_id){
    $bdd=$GLOBALS['bdd'];
    $sql="SELECT * FROM Dossiers WHERE (iddossiers='$d_id')";
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (!empty($result)){
        $nom=$result['d_nom'];
        if ($result['dossier_parent']!=null){
            Aff_arbo($result['dossier_parent']);
            echo "<p class='separe_arbo'> > </p>";
        }
        echo "<a class='arbo_dos' href='/?d_id=$d_id' title='$nom'><span>$nom</span></a>";
    }else{
        echo "Probleme BDD";
    }
}
?>