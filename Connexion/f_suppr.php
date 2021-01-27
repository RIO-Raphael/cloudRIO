<?php
//OK!
// /!\ les arguments à passer sont uid (ou mail), mdp
//Code de retour 
//0=>ok
//1=>pblm de transmission des arguments par JS
//2=>Aucun resultat pour l'indentifiant dans la table
//3=>Probleme dans la requete pour suppr le compte
//4=>mdp non valide

$uid='';
$mdp='';
//on récupère les données
if ((isset($_POST["uid"]))&&(isset($_POST["mdp"]))){
    $uid=$_POST['uid'];
    $mdp=$_POST['mdp'];
}else{
    echo'1';
    exit();
}

//On se connecte à la BDD
$path=$_SERVER['DOCUMENT_ROOT'];
include $path.'/fonct.php';
$bdd=BDD();

//On regarde si l'utilisateur n'a pas rentré son mail
if (!(strpos($uid,'@')===FALSE)){
    $mail=$uid;
    //Requete sur la table
    $sql_co="SELECT * FROM Utilisateurs WHERE (email='$mail')";
    $sql_suppr="DELETE FROM Utilisateurs WHERE (email='$mail')";
    Suppr_account($bdd,$sql_co,$sql_suppr,$mdp);
}else{
    //Requete sur la table
    $sql_co="SELECT * FROM Utilisateurs WHERE (uid='$uid')";
    $sql_suppr="DELETE FROM Utilisateurs WHERE (uid='$uid')";
    Suppr_account($bdd,$sql_co,$sql_suppr,$mdp);
}

function Suppr_account($bdd,$sql_co,$sql_suppr,$mdp){
    $result=$bdd->query($sql_co);
    $result=$result->fetch();
    if (empty($result[0])){
        //aucun resultat dans la table pour uid
        echo"2";
        exit();
    }else{
        //on a vérifié que le uid existait => maintenant regardons s'il correspond au mdp
        $B=password_verify($mdp,$result['mdp']);
        if ($B){
            //Le mot de passe est valide
            //On ferme la session
            session_write_close();
            //on supprime le compte
            if(!($bdd->query($sql_suppr))){
                echo'3';
                exit();
            }
            echo"0";
            exit();
        }else{
            //le mdp est non valide               
            echo"4";
            exit();
        }
    }   
}
?>