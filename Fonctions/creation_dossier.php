<?php
//ok!
// /!\ les arguments à passer sont propriétaire, d_nom, d_parent (iddossiers)
//Code de retour 
//0=>ok
//1=>pblm mkdir
//2=>pblm insertion nvo utilisateur dans la bdd (un dossier du meme nom existe surement à cet endroit)
//4=> pas de nom de dossier rentré
//5=> pas de dossier parent rentré => utiliser le scrip creation_dossier_home
//6=> le dossier parent n'existe pas...

//On spécifie proprietaire du dossier par $_Post['prop']
//se référer au dossier BDD_cloudRIO.sql pour la struture de la BDD
$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
$path_racine=$_SERVER['DOCUMENT_ROOT'].'/Folders/';
session_start();
//chemin depuis le path racine dépend de iddossiers (=>mt_rand())

$bdd=BDD();

if (isset($_POST['prop'])){
    $uid=$_POST['prop'];
}else{
    $uid=$_SESSION['login'];
}
if (isset($_POST['d_nom'])){
    $d_nom=$_POST['d_nom'];
    $d_nom=Nom_ok($d_nom);
}else{
    echo '4';
    exit();
}
if (isset($_POST['d_parent'])){
    $d_parent=$_POST['d_parent'];
    if ($d_parent==null && $uid!=null){
        $sql='SELECT iddossiers FROM Dossiers WHERE (d_nom="Home") AND (proprietaire="'.$uid.'")';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        $d_parent=$result['iddossiers'];
    }
}else{
    echo '5';
    exit();
}



//Génération aléatoire de iddossiers avec mt_rand()
//ensuite on check si ça existe dans la table
$ok=false;
while (!($ok)){
    $id= mt_rand();
    $sql='SELECT * FROM Dossiers WHERE (iddossiers="'.$id.'")';
    $result=$bdd->query($sql);
    $result=$result->fetchAll();
    if (empty($result)){
        $ok=true;
    }
}

//On vérifie que le parent existe
$sql='SELECT * FROM Dossiers WHERE (iddossiers="'.$d_parent.'")';
$result=$bdd->query($sql);
$result=$result->fetch();
$chemin_parent=$result['d_chemin'];//On rajoute le / pour les séparer du nom qu'on va lui concaténer
if (empty($result)){
    echo'6';
    exit();
}

$chemin="$chemin_parent/$d_nom";
$sql="INSERT INTO Dossiers (d_chemin, iddossiers, d_nom, proprietaire, dossier_parent) VALUES ('$chemin','$id','$d_nom','$uid','$d_parent')";
echo"$sql<br>";
if (!($bdd->query($sql))){
    echo'2';
    exit();
}


if (mkdir($chemin)){
    echo '0';
}else{
    echo '1';
}
?>
