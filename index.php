<!DOCTYPE html>
<html lang="fr">
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/head.php'); ?>
    <?php require_once ($_SERVER['DOCUMENT_ROOT'].'/fonct.php'); ?>
    <link rel="stylesheet" href="/index2.css">
    <?php
    session_start();
    $ok=Test_co();
    //exit;
    if ($ok==-1){
        if (!(isset($_SESSION['login']))){
            echo'<meta http-equiv="Refresh" content="0; URL=/Connexion/"/>';
            exit(10);
        }else{
            echo "Vous êtes connecté(e) en tant que : ";
            echo "".$_SESSION['data']['nom']." ".$_SESSION['data']['prenom']." | uid=".$_SESSION['login']."<br>";
            echo "<p style='font-size:3rem'><b>Vous n'avez pas les droits pour accéder à ce fichier ou dossier.</b></p>";
            exit(10);
        }
    }

    if (isset($_GET['d_id'])){
        $d_id=$_GET['d_id'];
        echo "<div hidden id='affichage_dos_infos' data-d_id='$d_id'></div>
        <script>
            var \$infos={d_id:'$d_id'};
            history.pushState(\$infos,null,null);
        </script>";
    }
    ?>
    <body>
        <?php
        $path=$_SERVER['DOCUMENT_ROOT'];
        require_once ($path.'/Header/header.php');
        require_once ($path.'/Header/Progress_bar.php');
        ?>
        <?php require_once ($path.'/Header/menu.php');
        if (isset($_SESSION['login'])){
            if (Test_fichier_dos($_SESSION['login'],$_SESSION['d_id'])!=-1){
                require_once ($path.'/Fonctions/upload_p.php');
            }
        }?>

        <?php 
        if (isset($_SESSION['login'])){
            if (Test_fichier_dos($_SESSION['login'],$_SESSION['d_id'])!=-1){
                require_once ($path.'/Header/menu_lat.php');
            }
        }
        ?>
        <link rel="stylesheet" href="/Fichier/Plein_ecran.css"/>

        <main>
            <?php 
                require_once ($path.'/Fichier/dos_file.php');
            ?>
            <div id='main_contain'>
                <div id='dossier_contain_0' >
                    <div class='top' id='dossier_top'>
                        <span id='dossier_top_txt'>Dossiers</span>
                    </div>
                    <div id='dossier_contain'></div>
                </div>
                <div id='file_contain_0'>
                    <div class='top' id='file_top'>
                        <span id='file_top_txt'>Files</span>
                    </div>
                    <div id='file_contain'></div>
                </div>

            </div>
        </main>
        <footer>
        </footer>
    </body>
</html>
