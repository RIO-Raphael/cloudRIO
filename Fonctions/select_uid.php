<?php
$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

$bdd=BDD();
//Arg élément du nom que l'in recherche arg: text
if (isset($_SESSION['login'])){
    $uid=$_SESSION['login'];
}else{
    $uid='';
}
if (isset($_POST['text'])){
    $text=$_POST['text'];
}else if (isset($_GET['text'])){
    $text=$_GET['text'];
}else{
    echo"pas de nom recontré";
    exit;
}

$text=strtolower($text);
$sql="SELECT uid,nom,prenom,email FROM `utilisateurs` WHERE (lower(uid) != '$uid') AND ((lower(uid) LIKE '$text%') OR (lower(nom) LIKE '%$text%') OR (lower(prenom) LIKE '%$text%') OR (lower(email) LIKE '%$text%'))";
$return=$bdd->query($sql);
$return=$return->fetchAll();

echo json_encode($return);

?>