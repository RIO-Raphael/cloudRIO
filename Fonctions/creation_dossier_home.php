<?php
//Utiliser que lors de la création d'un nouveau compte
//Code de retour 
//0=>ok
//1=>pblm mkdir
//2=>pblm insertion nvo utilisateur dans la bdd
//3=> pas de propriétaire set

//On spécifie proprietaire du dossier par $_Post['prop']
//se référer au dossier BDD_cloudRIO.sql pour la struture de la BDD
$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
$path_racine=$_SERVER['DOCUMENT_ROOT'].'/Folders/';

$bdd=BDD();
$nom='Home';
//nom proprio
if (isset($_POST['prop'])){
    $uid=$_POST['prop'];
}else{
    echo '3';
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

$path=$path_racine.$uid;
$sql='INSERT INTO Dossiers (d_chemin, iddossiers, d_nom, proprietaire) VALUES (\''.$path.'\',"'.$id.'","'.$nom.'","'.$uid.'")';
if (!($bdd->query($sql))){
    echo'2';
    exit();
}

if (mkdir($path_racine.$uid)){
    echo '0';
}else{
    echo '1';
}
?>