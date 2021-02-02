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

if (isset($_POST['R'])){
    $R=$_POST['R'];
}else{
    echo '4';
    exit();
}

$bdd=BDD();
if (isset($R['fichiers'])){
    $F=$R['fichiers'];
    $n=count($F);
    for ($i=0;$i<$n;$i++){
        chekc_share_f($bdd,null,$F[$i]);
    }
}
if (isset($R['dossiers'])){
    $D=$R['dossiers'];
    $n=count($D);
    for ($i=0;$i<$n;$i++){
        chekc_share_f($bdd,$D[$i]);
    }
}

function chekc_share_f($bdd,$d_id=null,$f_id=null){
    if ($f_id!=null){
        $sql='SELECT * FROM f_Partage WHERE (idfichiers='.$f_id.')';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if (!(empty($result))){
            echo"&$f_id=".$result['Droit'];
        }else{
            echo"&$f_id=-1";
        }
    }
    if ($d_id!=null){
        $sql='SELECT * FROM d_Partage WHERE (iddossiers='.$d_id.')';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if (!(empty($result))){
            echo"&$d_id=".$result['Droit'];
        }else{
            echo"&$d_id=-1";
        }
    }
}

?>