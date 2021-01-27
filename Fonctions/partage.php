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
if (isset($R['noms'])){
    $N=$R['noms'];
}
if (isset($R['fichiers'])){
    $F=$R['fichiers'];
    $nb=count($F);
    for ($i=0;$i<$nb;$i++){
        if (isset($N)){        
            share_file($uid,$F[$i],$bdd,$N);
        }else{
            share_file($uid,$F[$i],$bdd);
        }
    }
}
if (isset($R['dossiers'])){
    $D=$R['dossiers'];
    $nb=count($D);
    for ($i=0;$i<$nb;$i++){
        if (isset($N)){        
            share_dos($uid,$D[$i],$bdd,$N);
        }else{
            share_dos($uid,$D[$i],$bdd);
        }
    }
}

function share_file($uid,$f_id,$bdd,$nom=["all"],$droit=3){
    $nb=count($nom);
    for ($i=0;$i<$nb;$i++){
        $sql='INSERT INTO f_Partage (idfichiers,share_person,droit) VALUES ('.$f_id.',"'.$nom[$i].'",'.$droit.')';
        if (!($bdd->query($sql))){
            echo"1";
        }else{
            echo"0";
        }
    }
}

function share_dos($uid,$d_id,$bdd,$nom=["all"],$droit=3){
    $nb=count($nom);
    for ($i=0;$i<$nb;$i++){
        $sql='INSERT INTO d_Partage (iddossiers, share_person, Droit) VALUES ("'.$d_id.'",'.$nom[$i].',"'.$droit.'"';
        if (!($bdd->query($sql))){
            echo'2';
            exit();
        }else{
            echo'0';
        }
    }
}
?>