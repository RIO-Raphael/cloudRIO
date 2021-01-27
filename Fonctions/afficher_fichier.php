<?php
//OK!

$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

// /!\ l'arguement à passer est iddossiers' sinon on affiche le dossier home du uid en cours
//Code de retour 
//div html avec les fichiers ou les dossiers présent dans le dossier que l'on regarde d_id_p

//Conexion BDD
$bdd=BDD();
$d_id_p=null;

if (isset($_SESSION['login'])){
    $uid=$_SESSION['login'];
}else{
    $uid=null;
}
//ok
if (isset($_GET['d_id'])){
    $d_id_p=$_GET['d_id']; //Id du dossier que l'on regarde
}
if (isset($_POST['d_id'])){
    $d_id_p=$_POST['d_id']; //Id du dossier que l'on regarde
}

if ($d_id_p==null && $uid!=null){
    $sql='SELECT iddossiers FROM Dossiers WHERE (d_nom="Home") AND (proprietaire="'.$uid.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    $d_id_p=$result['iddossiers']; //Id du dossier que l'on regarde
}

if ($d_id_p!=null){
    Afficher($d_id_p);
}

function Afficher($d_id_p){
    Afficher_les_fichiers($d_id_p);
}

function Afficher_les_fichiers($d_id){
    $bdd=$GLOBALS['bdd'];
    $sql='SELECT * FROM Fichiers WHERE (f_dossier_parent="'.$d_id.'")';
    $result=$bdd->query($sql);
    $result=$result->fetchAll();
    $nb=count($result);
    for ($j=0;$j<$nb;$j++){
        $chemin_r=substr($result[$j]['f_chemin'],strlen($_SERVER['DOCUMENT_ROOT']));
        if (Test_fichier_dos($result[$j]['f_dossier_parent'],$result[$j]['idfichiers'])){
            Div_fichier($result[$j]['idfichiers'],$result[$j]['f_nom'],$d_id,$result[$j]['f_type'],$chemin_r);
        }
    }
}
