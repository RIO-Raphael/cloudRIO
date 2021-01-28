<?php
//OK!
    $path_racine=$_SERVER['DOCUMENT_ROOT'];
    include ($path_racine.'/fonct.php');
    session_start();
    //Voir la doc de HTMLElementInput type=file pour savoir comment les champs sont construits
    // /!\argument passer via $_FILES et $post pour l id du dossier
    //Code de retour 
    //0=>Ok
    //1=>caractère non-autorisés
    //2=>Prblm dans le dossier pour upload
    //3=>prblm dans le nom du fichier
    //4=>

    //Strcuture $file
    // files[$i]={lastModified, name, size, type ,[lastModifiedDate ]}

    //Conexion BDD
    $bdd=BDD();
    //UID
    if (isset($_SESSION['login'])){
        $uid=$_SESSION['login'];
    }else{
        echo "Connectez vous!";
        exit;
    }

    //On recupère l id du dossier où uploader les fichiers
    if (isset($_POST['d_id'])){
        $d_id=$_POST['d_id']; //Id du dossier que l'on regarde
    }else{
        $sql='SELECT iddossiers FROM Dossiers WHERE (d_nom="Home") AND (proprietaire="'.$uid.'")';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        $d_id=$result['iddossiers']; //Id du dossier que l'on regarde
    }

    $result=Path_dos($d_id);
    $path_dos=$result['d_chemin'];
    if ($path_dos===false){
        echo'2';
        exit();
    }

    $space_dispo=Taille_disponible($path_dos);

    //Si le fichier
    if (isset($_FILES['file'])){
        $file=$_FILES['file'];
        $name=htmlspecialchars($file['name']);
        /*schéma : Array ( 
        [name] => 7.png 
        [type] => image/png 
        [tmp_name] => C:\Users\rapha\AppData\Local\Temp\php3766.tmp 
        [error] => 0 
        [size] => 10126 
        ) */
        //Variable qui sert pour savoir si il y a un fichier qui a le même nom que l'upload.
        $enr=TRUE;
        //Testons que le fichier ne soit pas un exectuable
        if (!(is_executable($file['tmp_name']))){
            // Testons si le fichier a bien été envoyé et s'il n'y a pas d'erreur
            if ($file['error']==0){
                //On vérifie la taille du fichier
                if ($file['size']<=$space_dispo){
                    //Vérif du nom du fichier (non présent)
                    $enr=File_already_exist($name);
                    if (!($enr)){ 
                        //On vérifie que le nom ne soit pas chelou
                        $name=Nom_ok($name);
                        $pos=strcspn($name,'<>?/\\"\':');
                        if ($pos==strlen($name)){
                            //verif ok
                            //on l'insère dans la bdd
                            $f_path=Inser_BDD($path_dos,$name,$d_id,$file['tmp_name']);
                            if($f_path!==false){
                                //On enregistre le fichier de manière définitive.
                                if(move_uploaded_file($file['tmp_name'], $f_path)){
                                    echo'0';
                                }else{
                                    echo'9';
                                }        
                            }else{
                                echo'8';
                            }
                        }else{
                            echo"$name";
                            echo'3';
                        }
                    }else{
                        echo'4';
                    }
                }else{
                    echo'5';
                }
            }else{
                echo'6';
            }
        }else{
            echo'7';
        }
    }
    
    function Path_dos($d_id){
        $bdd=$GLOBALS['bdd'];
        $uid=$GLOBALS['uid'];
        $sql="SELECT * FROM Dossiers WHERE ((iddossiers='$d_id') AND (proprietaire='$uid'))";
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if (!(empty($result))){
            return $result;
        }else{
            echo'10';
            return false;
        }
    }
    
    function Taille_disponible($dir){
        $uid=$GLOBALS['uid'];
        $bdd=$GLOBALS['bdd'];
        $space_uti;
        //Espace dispo "virtuellement"
        $sql="SELECT * FROM Utilisateurs WHERE (uid='$uid')";
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if(!(empty($result))){
            $space_uti=$result['space_allow']*(2**30);
        }else{
            echo'20';
        }
        
        $space_disk=disk_free_space($dir);
        if ($space_uti<$space_disk){
            return $space_uti;
        }else{
            return $space_disk;
        }
    }
    
    function File_already_exist($name){
        $uid=$GLOBALS['uid'];
        $bdd=$GLOBALS['bdd'];
        $d_id=$GLOBALS['d_id'];
        $sql="SELECT * FROM Fichiers WHERE (f_nom='$name') AND (f_dossier_parent='$d_id')";
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if(!(empty($result))){
            //un fichier du même nom existe déjà
            echo "name=$name et dossier_parent=$d_id <br>";
            return true;
        }else{
            return false;
        }
    }

    //$path=futur emplacement du fichier, et $tmp l'emplacement provisiore actuel
    function Inser_BDD($path,$name,$d_id,$tmp){
        $size=filesize($tmp);
        $type=mime_content_type($tmp);
        $bdd=$GLOBALS['bdd'];
        //Génération aléatoire de iddossiers avec mt_rand()
        //ensuite on check si ça existe dans la table
        $ok=false;
        while (!($ok)){
            $id_p=mt_rand();
            $id= floor($id_p*microtime());
            $sql='SELECT * FROM Fichiers WHERE (idfichiers="'.$id.'")';
            $result=$bdd->query($sql);
            $result=$result->fetchAll();
            if (empty($result)){
                $ok=true;
            }
        }

        $path="$path/$name";

        //REquete SQL
        $sql="INSERT INTO Fichiers (idfichiers,f_nom,f_chemin,f_dossier_parent,f_type,taille) VALUES ('$id','$name','$path','$d_id','$type','$size')";
        if($bdd->query($sql)){
            return $path;
        }else{
            return false;
        }

    }

    function Get_extension($name){
        $ext=strrchr ($name,'.');
        return $ext;
    }
    
?>
    