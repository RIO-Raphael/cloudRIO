<?php
//Fonction suprr et add_entry dans la bdd

function Suppr_fichier($uid,$f_id,$bdd){
    //On vérifie que le dossier existe et qu'il est au bon proprio
    $sql='SELECT * FROM Fichiers WHERE (idfichiers="'.$f_id.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (empty($result)){
        echo'6';
        exit();
    }else{
        $path_f=$result['f_chemin'];//On récup le path du dossier
    }

    //On vérifie qu'il appartienne à la bonne personne
    $d_id=$result['f_dossier_parent'];
    $sql='SELECT * FROM Dossiers WHERE (iddossiers="'.$d_id.'") AND (proprietaire="'.$uid.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (empty($result)){
        echo'9';
        exit();
    }

    if (is_file($path_f)){
        if(unlink($path_f)){
            $sql='DELETE FROM Fichiers WHERE (idfichiers="'.$f_id.'")';
            if($bdd->query($sql)){
                echo'0';
            }else{
                echo '5';
                exit();
            }
        } else{
            echo'2';
            exit();
        }
    }else{
        echo '1';
    }
}

function Suppr_dossier($uid,$d_id,$bdd){
    //On vérifie que le dossier existe et qu'il est au bon proprio
    $sql='SELECT * FROM Dossiers WHERE (iddossiers="'.$d_id.'") AND (proprietaire="'.$uid.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (empty($result)){
        echo'6';
        exit();
    }else{
        $path_dossier=$result['d_chemin'];//On récup le path du dossier
    }

    //On regarde s'il y a des fichiers à l'intérieur
    $sql='SELECT * FROM Fichiers WHERE (f_dossier_parent="'.$d_id.'")';
    $result=$bdd->query($sql);
    $result=$result->fetchAll();
    if (!(empty($result))){
        $nn=count($result);
        for ($i=0;$i<$nn;$i++){
            //On supprime les sous dossiers
            Suppr_fichier($uid,$result[$i]['idfichiers'],$bdd);
        }
    }


    $sql='SELECT * FROM Dossiers WHERE (dossier_parent="'.$d_id.'")';
    $result=$bdd->query($sql);
    $result=$result->fetchAll();
    //print_r($result);
    if (!(empty($result))){
        //print_r($result);
        for ($i=0;$i<count($result);$i++){
            //On supprime les sous dossiers
            Suppr_dossier($uid,$result[$i]['iddossiers'],$bdd);
        }
    }

    if (is_dir($path_dossier)){
        if(rmdir($path_dossier)){
            $sql='DELETE FROM Dossiers WHERE (iddossiers="'.$d_id.'")';
            if($bdd->query($sql)){
                echo'0';
            }else{
                echo '5';
                exit();
            }
        } else{
            echo'2';
            exit();
        }
    }else{
        echo '1';
    }
}

function Add_entry_dos($bdd,$chemin){
    //Dossier parent
    $chemin_p=substr($chemin,0,strripos($chemin,'/'));
    $d_nom=substr($chemin,strripos($chemin,'/')+1);
    $sql='SELECT * FROM Dossiers WHERE (d_chemin="'.$chemin_p.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (!(empty($result))){
        //print_r($result);
    }else{
        echo'2';
        exit;
    }
    $d_parent=$result['iddossiers'];
    $uid=$result['proprietaire'];

    //Génération aléatoire de iddossiers avec mt_rand()
    //ensuite on check si ça existe dans la table
    $ok=false;
   
    while (!($ok)){
        $d_id= mt_rand();
        $sql='SELECT * FROM Dossiers WHERE (iddossiers="'.$d_id.'")';
        $resultbis=$bdd->query($sql);
        $resultbis=$resultbis->fetchAll();
        if (empty($resultbis)){
            $ok=true;
        }
    }

    $sql="INSERT INTO Dossiers (d_chemin, iddossiers, d_nom, proprietaire,dossier_parent) VALUES ('$chemin','$d_id','$d_nom','$uid','$d_parent')";
    if (!($bdd->query($sql))){
        echo "3";
        exit;
    }else{
        echo"$sql   =>   0";
    }
}

function Add_entry_file($bdd,$chemin){
    //On regarde le dossier dans lequel est present le fichier
    $chemin_d=substr($chemin,0,strripos($chemin,'/'));
    $sql='SELECT * FROM Dossiers WHERE (d_chemin="'.$chemin_d.'")';
    $result=$bdd->query($sql);
    $result=$result->fetch();
    if (!(empty($result))){
        //print_r($result);
    }else{
        echo'2';
        exit;
    }

    $d_id=$result['iddossiers'];
    $f_nom=substr($chemin,strripos($chemin,'/')+1);
    $f_type=mime_content_type($chemin);
    $size=filesize($chemin);

    //Génération aléatoire de idfichiers avec mt_rand()
    //ensuite on check si ça existe dans la table
    $ok=false;
   
    while (!($ok)){
        $id= mt_rand();
        $sql='SELECT * FROM Fichiers WHERE (idfichiers="'.$id.'")';
        $resultbis=$bdd->query($sql);
        $resultbis=$resultbis->fetchAll();
        if (empty($resultbis)){
            $ok=true;
        }
    }

    $sql="INSERT INTO Fichiers (idfichiers,f_nom,f_chemin,f_dossier_parent,f_type,taille) VALUES ('$id','$f_nom','$chemin','$d_id','$f_type','$size')";
    if (!($bdd->query($sql))){
        echo "3";
        exit;
    }else{
        echo"$sql   =>   0";
    }
}

?>