<?php
 $path=$_SERVER['DOCUMENT_ROOT'];
 include $path.'/fonct.php';
 
if (isset($_POST["infos"])){
    $infos_entete=$_POST["infos"];
    $infos_val=$_POST["infos"];
    //On récupère les données dans le POST
    for ($j=0;$j<count($infos_entete);$j++){
        $infos_entete[$j]=htmlspecialchars($_POST["infos"][$j][0]);
        $infos_val[$j]=htmlspecialchars($_POST["infos"][$j][1]);
    }

    $bdd=BDD();
    // Structure BDD   uid VARCHAR(254), email VARCHAR(254), nom VARCHAR (254), prenom VARCHAR(254), 
    //mdp VARCHAR(254), /*haché*/ date_inscription DATE
    //On va traité tout les champs et vérifié qu'il n'eiste pas déjà, 
    //le nom des champs est dans p_new_account.php -> input ->id
    $uid='';
    $mdp='';
    $nom='';
    $prenom='';
    $mail='';
    $path=$_SERVER['DOCUMENT_ROOT'].'/Folders/';
    $nb=count($infos_entete);

    for ($i=0;$i<$nb;$i=$i+1){
        //echo ''.$infos_entete[$i].'='.$infos_val[$i].' et i='.$i.'/';
        if ($infos_entete[$i]=="uid"){
            $uid=$infos_val[$i];
            $sql='SELECT uid FROM Utilisateurs WHERE (uid="'.$uid.'")';
            $result=$bdd->query($sql);
            $result=$result->fetch();
            //print_r($result);
            //echo"<br>";
            if (!(empty($result[0]))){
                //Le pseudo existe déjà code 1
                echo"1";
                exit(1);
            }
        }
        if ($infos_entete[$i]=="mail"){
            $mail=$infos_val[$i];
            $sql='SELECT email FROM Utilisateurs WHERE (email="'.$mail.'")';
            //echo "$sql <br>";
            $result=$bdd->query($sql); //retourne false si vide
            $result=$result->fetch();
            if (!(empty($result))){
                //L'email existe déjà code 2
                echo"2";
                exit(2);
            }
            //Envoi mail
            if (!(Mail_ok($mail))){
                echo'2';
                exit();
                //Email invalide
            }
        }
        //Nom/prénom on s'en fout
        if ($infos_entete[$i]=="nom"){
            $nom=$infos_val[$i];
        }
        if ($infos_entete[$i]=="mdp"){
            $mdp=$infos_val[$i];
            $mdp=password_hash($mdp, PASSWORD_DEFAULT);
        }
        if ($infos_entete[$i]=="prenom"){
            $prenom=$infos_val[$i];
        }
    }
    //echo "uid=$r_uid;mdp=$r_mdp;nom=$r_nom;prenom=$r_prenom;mail=$r_mail";
    //On insère les valeurs dans la BDD
    $sql="INSERT INTO Utilisateurs (uid,email,nom,prenom,mdp,date_inscription,lien_dossier_init) 
    VALUES ('$uid','$mail','$nom','$prenom','$mdp',CURDATE(),'$path$uid')";
    $bdd->query($sql);


    echo"0";
    exit(0);
} else {
    return "Erreur -1, contactez l'admin";
}


?>