<?php
//variable $_SESSION structure : [login]->uid / [data]->email | nom | prenom | date inscription

    if (isset($_POST["infos"])){
        $infos_entete=$_POST["infos"];
        $infos_val=$_POST["infos"];
        //On récupère les données dans le POST
        for ($j=0;$j<count($infos_entete);$j++){
            $infos_entete[$j]=htmlspecialchars($_POST["infos"][$j][0]);
            $infos_val[$j]=htmlspecialchars($_POST["infos"][$j][1]);
        }
        
        $path=$_SERVER['DOCUMENT_ROOT'];
        include $path.'/fonct.php';
        $bdd=BDD();

        //On va récupérer le uid et le mdp
        $uid='';
        $mail='';
        $mdp='';
        $Bool=true;
        $nb=count($infos_entete);
    
        for ($i=0;$i<$nb;$i=$i+1){
            if ($infos_entete[$i]=="uid"){
                $uid=$infos_val[$i];
            }
            if ($infos_entete[$i]=="mdp"){
                $mdp=$infos_val[$i];
            }
        }

        //On regarde si l'utilisateur n'a pas rentré son mail
        if (!(strpos($uid,'@')===FALSE)){
            $mail=$uid;
            //Requete sur la table
            $sql="SELECT * FROM Utilisateurs WHERE (email='$mail')";
            $result=$bdd->query($sql);
            $result=$result->fetch();
            if (empty($result[0])){
                //aucun resultat dans la table pour mail
                echo"1";
                exit();
            }else{
                //on a vérifié que le mail existait => maintenant regardons s'il correspond au mdp
                $B=password_verify($mdp,$result['mdp']);
                if ($B){
                    //Le mot de passe est valide
                    session_start();
                    $_SESSION['login']=$uid;
                    $_SESSION['data']=$result;
                    //$_SESSION['bdd']=$bdd;
                    echo"0";
                    exit();
                }else{
                    //le mdp est non valide               
                    echo"2";
                    exit();
                }
            }   
        }else{
        //Requete sur la table
            $sql="SELECT * FROM Utilisateurs WHERE (uid='$uid')";
            $result=$bdd->query($sql);
            $result=$result->fetch();
            if (empty($result[0])){
                //aucun resultat dans la table pour uid
                echo"1";
                exit();
            }else{
                //on a vérifié que le uid existait => maintenant regardons s'il correspond au mdp
                $B=password_verify($mdp,$result['mdp']);
                if ($B){
                    //Le mot de passe est valide
                    session_start();
                    $_SESSION['login']=$uid;
                    $_SESSION['data']=$result;
                    //$_SESSION['bdd']=$bdd;
                    echo"0";
                    exit();
                }else{
                    //le mdp est non valide               
                    echo"2";
                    exit();
                }
            }   
        } 
    }
?>