<?php
//Droit pas encore mis en place
//ARgument=> [fichiers,dossiers,noms] //Le nom des personnes avec qui on veut partager les fichiers/dossiers => par déf : all
//REtour
//1=>pblm insertion fichier
//2=>pblm insertion dos
//4=>pas d'arg

$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

if (isset($_POST['d_id'])){
    $d_id=$_POST['d_id'];
}
if (isset($_POST['f_id'])){
    $f_id=$_POST['f_id'];
}

$bdd=BDD();
if (isset($d_id) && isset($f_id)){
    chekc_share($bdd,$d_id,$f_id);
}

function chekc_share($bdd,$d_id,$f_id){
    $sql='SELECT * FROM f_Partage WHERE (idfichiers='.$f_id.')';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (!(empty($result))){
        echo"&$f_id=".$result['Droit'];
    }else{
        echo"&$f_id=-1";
    }
}
?>