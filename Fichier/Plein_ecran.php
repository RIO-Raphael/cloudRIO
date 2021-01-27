<?php
$path_racine=$_SERVER['DOCUMENT_ROOT'];
include ($path_racine.'/fonct.php');
session_start();

//Conexion BDD
$bdd=BDD();

if (isset($_POST['id'])){
    $id=$_POST['id']; //Id du fichier que l'on regarde
}else{
    echo "erreur dans la transmission de l'id";
    exit();
}
$sql='SELECT * FROM Fichiers WHERE (idfichiers='.$id.')';
$result=$bdd->query($sql);
$result=$result->fetch();
$d_id=$result['f_dossier_parent']; //Id du dossier que l'on regarde dans lequel est le fichier
$nom=$result['f_nom'];
$chemin=$result['f_chemin'];
$chemin=substr($chemin,strlen($path_racine));
$type=$result['f_type'];

echo '<div id=plein_ecran>
    <div id="fermeture_plein_ecran"></div>
';
$ok=false;
if (strpos($type,"image")!==false){
    $ok=true;
    echo '<img type="'.$type.'" src="'.$chemin.'"/>
        <span class="f_nom_plein_ecran">'.$nom.'</span>
    </div>';
    
}

if (strpos($type,"video")!==false){
    $ok=true;
    echo '<video controls type="'.$type.'" src="'.$chemin.'"></video>
        <span class="f_nom_plein_ecran">'.$nom.'</span>
    </div>';
}

if (strpos($type,"audio")!==false){
    $ok=true;
    echo '<audio controls type="'.$type.'" src="'.$chemin.'"></audio>
        <span class="f_nom_plein_ecran">'.$nom.'</span>
    </div>';
}

if (strpos($type,"pdf")!==false){
    $ok=true;
    echo '<object type="'.$type.'" data="'.$chemin.'"></object>
        <span style="display:none" class="f_nom_plein_ecran">'.$nom.'</span>
    </div>';
}
if ($ok===false){
    echo '<span class="f_nom_plein_ecran">Fichier non-pris en charge pour le visionnage en ligne</span>';
}
echo '</div>';
?>
