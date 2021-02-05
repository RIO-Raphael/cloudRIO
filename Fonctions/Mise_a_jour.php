<?php
//Certaines fonctions sont dans fonctions.php
$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/Fonctions/fonctions.php');
include ($path_racine.'/fonct.php');
$path_racine=$_SERVER['DOCUMENT_ROOT'].'/Folders/';
session_start();

$bdd=BDD();
if (isset($_SESSION['login'])){
    $uid=$_SESSION['login'];
    //On regarde le dossier de départ nommé "Home" de l'utilisateur
    $sql='SELECT * FROM Dossiers WHERE (d_nom="Home") AND (proprietaire="'.$uid.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    //Chemin du dossier "Home"
    $path_dos_init=$result['d_chemin'];
    MAJ($bdd,$path_dos_init,$uid);
} else{
    echo"Veuillez vous connectez pour mettre à jour votre compte";
}

echo "<br><p style='font-size:2rem'>Tout a été mis à jour!</p><br>";

//arg=>chemin du dossier à mettre à jour
function MAJ($bdd,$path_dos,$uid){
    $P_dos=opendir($path_dos);
    /*Traverser le dossier. */
    $array=array();
    while (false !== ($entry = readdir($P_dos))) {
        if ($entry!='.' && $entry != '..'){
            $entry=$path_dos.'/'.$entry;
            $entry=str_replace('\\','/',$entry);
            array_push($array,$entry);
            if (is_file($entry)){
                $sql="SELECT * FROM Fichiers WHERE (f_chemin='$entry')";
                $result=$bdd->query($sql);
                $result=$result->fetch();
                if (empty($result)){
                    Add_entry_file($bdd,$entry);
                }
            }elseif (is_dir($entry)){
                //Attention au format du nom
                $sql="SELECT * FROM Dossiers WHERE (d_chemin='$entry')";
                $result=$bdd->query($sql);
                $result=$result->fetch();
                if (!(empty($result))){
                    MAJ($bdd,$entry,$uid);
                }else{
                    Add_entry_dos($bdd,$entry);
                    MAJ($bdd,$entry,$uid);                  
                }
            }
        }
    }
    /*echo "array=";
    print_r($array);
    echo"<br>";*/
    //On parcourt la table Dossiers
    $sql="SELECT * FROM Dossiers WHERE (proprietaire='$uid' AND dossier_parent=(SELECT iddossiers FROM Dossiers WHERE (d_chemin='$path_dos')))";
    $result=$bdd->query($sql);
    $result_tot=$result->fetchAll();
    //print_r($result_tot);
    $n=0;
    while (isset($result_tot[$n])){
        if ((array_search($result_tot[$n]['d_chemin'],$array)===false) && ($result_tot[$n]['d_chemin']!==$GLOBALS['path_dos_init'])){
            //echo"T suprr=".$result_tot[$n]['d_chemin']."<br>";
            Suppr_entry_dos($bdd,$result_tot[$n]['d_chemin']);
        }
        $n++;
    }


    //On parcourt la table Fichiers
    //Id du path_dos
    $sql="SELECT * FROM Dossiers WHERE (d_chemin='$path_dos')";
    $result=$bdd->query($sql);
    $result=$result->fetch();
    $d_id=$result['iddossiers'];
    //On regarde
    $sql="SELECT * FROM Fichiers WHERE (f_dossier_parent='$d_id')";
    $result=$bdd->query($sql);
    $result_tot=$result->fetchAll();
    $n=0;
    while (isset($result_tot[$n])){
        if (array_search($result_tot[$n]['f_chemin'],$array)===false){
            //echo"F suprr=".$result_tot[$n]['f_chemin']."<br>";
            Suppr_entry_file($bdd,$result_tot[$n]['f_chemin']);
        }
        $n++;
    }
}

function Suppr_entry_dos($bdd,$chemin){
    $sql='DELETE FROM Dossiers WHERE (d_chemin="'.$chemin.'")';
    if($bdd->query($sql)){
        echo"$sql   =>   0";
    }else{
        echo 'd5';
        exit();
    }
}

function Suppr_entry_file($bdd,$chemin){
    $sql='DELETE FROM Fichiers WHERE (f_chemin="'.$chemin.'")';
    if($bdd->query($sql)){
        echo"$sql   =>   0";
    }else{
        echo 'f5';
        exit();
    }
}


?>