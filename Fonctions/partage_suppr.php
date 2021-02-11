<?php
//Pareil que partage sauf qu'on suppr les entrées de la table

$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

if (isset($_SESSION['login'])){
    $uid=$_SESSION['login'];
}else{
    echo '3';
    exit();
}

if (isset($_POST['R'])){
    $R=$_POST['R'];
}else{
    echo '4';
    exit();
}

$bdd=BDD();
if (isset($_POST['U']['users'])){
    if (!empty($_POST['U']['users'])){
        $N=$_POST['U']['users'];
    }
}
if (isset($R['fichiers'])){
    $F=$R['fichiers'];
    $nb=count($F);
    for ($i=0;$i<$nb;$i++){
        if (isset($N)){        
            suppr_share_file($uid,$F[$i],$bdd,$N);
        }else{
            suppr_share_file($uid,$F[$i],$bdd);
        }
    }
}
if (isset($R['dossiers'])){
    $D=$R['dossiers'];
    $nb=count($D);
    for ($i=0;$i<$nb;$i++){
        if (isset($N)){        
            suppr_share_dos($uid,$D[$i],$bdd,$N);
        }else{
            exit;
            //share_dos($uid,$D[$i],$bdd);
        }
    }
}

function suppr_share_file($uid,$f_id,$bdd,$nom=[]){
    $nb=count($nom);
    for ($i=0;$i<$nb;$i++){
        $sql='DELETE FROM f_Partage WHERE (idfichiers='.$f_id.' AND share_person="'.$nom[$i].'")';
        if (!($bdd->query($sql))){
            echo"1";
        }else{
            echo"0";
        }
    }
}

function suppr_share_dos($uid,$d_id,$bdd,$nom=[],$droit=3){
    $nb=count($nom);
    for ($i=0;$i<$nb;$i++){
        $sql='DELETE FROM d_Partage WHERE (iddossiers="'.$d_id.'",share_person="'.$nom[$i].'")';
        echo"$sql";
        if (!($bdd->query($sql))){
            echo'2';
            exit();
        }else{
            echo'0';
        }
    }
}
