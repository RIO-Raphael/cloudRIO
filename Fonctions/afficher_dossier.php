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
$ok_test_dos=true;

if (isset($_SESSION['login'])){
    $uid=$_SESSION['login'];
    //ok
    if (isset($_POST['d_id'])){
        $d_id_p=$_POST['d_id']; //Id du dossier que l'on regarde
    }elseif (isset($_SESSION['d_id'])){
        $d_id_p=$_SESSION['d_id']; //Id du dossier que l'on regarde
    }else{
        $sql='SELECT iddossiers FROM Dossiers WHERE (d_nom="Home") AND (proprietaire="'.$uid.'")';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        $d_id_p=$result['iddossiers']; //Id du dossier que l'on regarde
    }
}else{
    if (isset($_POST['d_id'])){
        $d_id_p=$_POST['d_id']; //Id du dossier que l'on regarde
    }elseif (isset($_GET['d_id'])){
        $d_id_p=$_GET['d_id']; //Id du dossier que l'on regarde
    }
    if (!(Test_fichier_dos($d_id_p))){
        $ok_test_dos=false;
        exit(10);
    }
}

if (isset($d_id_p) && $ok_test_dos){
    $_SESSION['d_id']=$d_id_p;
    Afficher($d_id_p);
}

function Afficher($d_id_p){
    Afficher_les_dossiers($d_id_p);
}

function Afficher_les_dossiers($d_id){
    $bdd=$GLOBALS['bdd'];
    $sql='SELECT * FROM Dossiers WHERE (dossier_parent="'.$d_id.'")';
    $result=$bdd->query($sql);
    $result=$result->fetchAll();
    $nb=count($result);
    for ($i=0;$i<$nb;$i++){
        if (isset($_SESSION['login'])){
            if (Test_fichier_dos($_SESSION['login'],$result[$i]['iddossiers'])!=-1){
                Div_dossier($result[$i]['iddossiers'],$result[$i]['d_nom'],$result[$i]['d_chemin']);
            }
        }else{
            if (Test_fichier_dos('all',$result[$i]['iddossiers'])!=-1){
                Div_dossier($result[$i]['iddossiers'],$result[$i]['d_nom'],$result[$i]['d_chemin']);
            }
        }
       
    }
}


?>
