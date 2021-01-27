<?php
//Fonction suprr
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

?>