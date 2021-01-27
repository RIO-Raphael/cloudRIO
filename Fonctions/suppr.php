<?php
//ok!
// /!\ les arguments à passer sont [fichiers,dossiers] et uid
//Code de retour 
//0=>ok
//1=>$path.$d_id/f_id n'est pas un dossier/ficheir
//2=>pblm rmdir/unlink
//4=> pas de [fichiers,dossiers] rentré
//5=>prblm lors de la suppr dans la BDD
//6,9=>le dossier n'existe pas ou n'appartient pas à ce proprio

//On include les fonctions principales
//On spécifie proprietaire du dossier par $_Post['prop']
//se référer au dossier BDD_cloudRIO.sql pour la struture de la BDD
$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
include ($path_racine.'/Fonctions/fonctions.php');
session_start();

if (isset($_POST['prop'])){
    $uid=$_POST['prop'];
}else{
    $uid=$_SESSION['login'];
}
if (isset($_POST['R'])){
    $R=$_POST['R'];
}else{
    echo '4';
    exit();
}

$bdd=BDD();
if (isset($R['fichiers'])){
    $F=$R['fichiers'];
    $nb=count($F);
    for ($i=0;$i<$nb;$i++){        
        Suppr_fichier($uid,$F[$i],$bdd);
    }
}
if (isset($R['dossiers'])){
    $D=$R['dossiers'];
    $nb=count($D);
    for ($i=0;$i<$nb;$i++){
        Suppr_dossier($uid,$D[$i],$bdd);
    }
}


?>
