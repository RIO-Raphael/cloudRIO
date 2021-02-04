<?php
    function BDD(){
        try
        {
            // On se connecte à MySQL
            // SERVEUR
            //$bdd = new PDO('mysql:host=localhost;dbname=cloudRIO;charset=utf8', 'rio', 'mySQL12455!');
            //Local SURFACE
            $bdd = new PDO('mysql:host=localhost;dbname=cloudRIO;charset=utf8', 'root', 'root');
        }
        catch(Exception $e)
        {
            // En cas d'erreur, on affiche un message et on arrête tout
            die('Erreur : '.$e->getMessage());
        }
        return $bdd;
    }
    
    function Test_droit_dos(){
        $bdd=BDD();
        $ok=false;
        if (isset($_SESSION['login'])){
            $uid=$_SESSION['login'];
        }else{
            $uid='all';
        }
        if (isset($_GET['d_id'])){
            $d_id=$_GET['d_id'];
        }else{
            $sql='SELECT * FROM Dossiers WHERE ((d_nom="Home") AND (proprietaire="'.$uid.'"))';
            $result=$bdd->query($sql);
            $result=$result->fetch();
            if (!(empty($result))){
                $d_id=$result['iddossiers'];
            }
        }
        if(isset($d_id)){
            $ok=Test_droit_dossier($bdd,$d_id,$uid);
        }
        return $ok;
    }

    function Test_co(){
        if (isset($_SESSION['login'])){
            return true;
        }else{
            $ok=false;
            $ok=Test_fichier_dos();
            return $ok;
        }
    }

    function Test_fichier_dos($d_id=null,$f_id=null){
        $bdd=BDD();
        $ok=false;
        if (isset($_SESSION['login'])){
            $uid=$_SESSION['login'];
        }else{
            $uid='all';
        }
        if (isset($_GET['f_id']) && isset($_GET['d_id'])){
            $d_id=$_GET['d_id'];
            $f_id=$_GET['f_id'];
            $ok=Test_droit_fichier($bdd,$d_id,$f_id,$uid);
        }elseif ($f_id!==null){
            $ok=Test_droit_fichier($bdd,$d_id,$f_id,$uid);
        }
        //On vérifie les précédent résulats
        if ($ok){
            return true;
        }

        //pour les dossiers
        if (isset($_GET['d_id'])){
            $d_id=$_GET['d_id'];
            $ok=Test_droit_dossier($bdd,$d_id,$uid);
        }elseif ($d_id!==null){
            $ok=Test_droit_dossier($bdd,$d_id,$uid);
        }
        //On vérifie les précédent résulats
        if ($ok){
            return true;
        }        
        return $ok;
    }

    function Test_droit_fichier($bdd,$d_id,$f_id,$uid){
        $sql='SELECT * FROM f_Partage WHERE (idfichiers="'.$f_id.'") AND (share_person="'.$uid.'")';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        //Test si le dossier appartient au prop
        $sql2='SELECT * FROM Dossiers WHERE ((iddossiers="'.$d_id.'") AND (proprietaire="'.$uid.'"))';
        $result2=$bdd->query($sql2);
        $result2=$result2->fetch();
        if (!(empty($result2))){
            return true;
        }
        //Si non on regarde les droits
        if (isset($result['Droit'])){
            if ($result['Droit']>0){
                return true;
            }
        }
        return false;
    }

    function Test_droit_dossier($bdd,$d_id,$uid){
        $sql='SELECT * FROM Dossiers WHERE ((iddossiers="'.$d_id.'") AND (proprietaire="'.$uid.'"))';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if (!(empty($result))){
            return true;
        }
        //On regarde si on a partager le fichier avec cette personne
        $sql='SELECT * FROM d_Partage WHERE ((iddossiers="'.$d_id.'") AND (share_person="'.$uid.'"))';
        $result=$bdd->query($sql);
        $result=$result->fetch();
        if (isset($result['Droit'])){
            if ($result['Droit']>0){
                return true;
            }
        }
        return false;
    }

    function Div_dossier($d_id,$d_nom,$d_chemin){
        echo '<div class="dossier" id="'.$d_id.'" data-d_nom="'.$d_nom.'" data-d_chemin="'.$d_chemin.'">
            <input type="checkbox" class="CK" name="'.$d_id.'">
            <div id="image_dos"></div>
            <span class="d_nom">'.$d_nom.'</span>
        </div>';
    }

    function Div_fichier($f_id,$f_nom,$d_id,$type,$chemin_r){
        $ok=false;
        if (strpos($type,"image")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <img type="'.$type.'" src="'.$chemin_r.'"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
            
        }
       
        if (strpos($type,"video")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <video type="'.$type.'" src="'.$chemin_r.'"></video>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }

        if (strpos($type,"audio")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <img src="./FD/audio.png"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }
       
        if (strpos($type,"pdf")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">    
                <object style="display:none" type="'.$type.'" data="'.$chemin_r.'"></object>
                <img src="./Fichier/FD/pdf.png"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }
      
        if (strpos($type,"word")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <img src="./Fichier/FD/word.png"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }
        
        if (strpos($type,"excel")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <img src="./Fichier/FD/excel.png"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }

        if (strpos($type,"powerpoint")!==false){
            $ok=true;
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <img src="./Fichier/FD/powerpoint.png"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }
        if ($ok===false){
            echo '<div class="fichier" id="'.$f_id.'" data-nom="'.$f_nom.'" data-d_id="'.$d_id.'" data-chemin="'.$chemin_r.'" data-type="'.$type.'">
                <input type="checkbox" class="CK" name="'.$f_id.'">
                <img src="./Fichier/FD/file.png"/>
                <span class="f_nom">'.$f_nom.'</span>
            </div>';
        }
    }

    //Retourne la taille d'un dossier en octet
    function Taille_dos($dir){
        $total=0;
        foreach (new RecursiveDirectoryIterator($dir) as $entry)
        {
            if ($entry->isFile()){
                $total += $entry->getSize();
            }
        }
        return $total;
    }

    function Chemin_relatif($string){
        $pos=strlen($_SERVER['DOCUMENT_ROOT']);
        $string=substr($string,$pos);
        return $string;
    }

    function Nom_ok($name){
        $name=strip_tags($name);
        //Normalisation de la chaine utf8 en mode caractère + accents
        $name_ok = $name;
        //Suppression des accents 
        $accents = Array("/é/", "/è/", "/ê/","/ë/", "/ç/", "/à/", "/â/","/á/","/ä/","/ã/","/å/", "/î/", "/ï/", "/í/", "/ì/", "/ù/", "/ô/", "/ò/", "/ó/", "/ö/","/µ/");
        $sans = Array("e", "e", "e", "e", "c", "a", "a","a", "a","a", "a", "i", "i", "i", "i", "u", "o", "o", "o", "o","u");
        $chiant= Array("/","{","}"," ","°","%","\"","'",":","<",">","\\","?","|","*","^","¨");
        
        $name_ok = preg_replace($accents, $sans,$name_ok); 
        $name_ok = str_replace($chiant,"_",$name_ok); 
        return $name_ok;
    }

    /*MAIL*/
    function Mail_ok($mail,$message=null,$titre=null){
        //Va essayer d'envoyer un mail
        if ($titre==null){
            $title='Nouveau compte CloudRIO';
        }
    
        $header="MIME-Version: 1.0\r\n";
        $header.='From:"CloudRIO"<auto.cloudRIO@gmail.com>'."\r\n";
        $header.='Content-Type:text/html; charset="uft-8"'."\r\n";
        $header.='Content-Transfer-Encoding: 8bit';
        if ($message==null){
            $message='
            <html>
                <body>
                    <div align="left">
                        Bienvenue sur notre service de nuages! Votre compte à bien été enregistré.
                        <br />
                        Rendez-vous sur <a href=https://'.$_SERVER['HTTP_HOST'].'> Lien vers le site</a>
                        <br />
                        A bientôt.
                    </div>
                    <br>
                        <p>Contact : raphael.06rio@gmail.com</p>
                </body>
            </html>
            ';
        }
        echo "envoi du mail à : ".$mail."<br>Avec le message suivant : <br>".$message."<br> header = ".$header."<br>";
        if (mail("$mail",$title,$message,$header)){
            echo "0";
            return TRUE;
        }else{
            echo "1";
            return FALSE;
        }
    }
?>