<!DOCTYPE html>
<html lang="fr">
    <?php include ($_SERVER['DOCUMENT_ROOT'].'/head.php'); ?>
    <?php include ($_SERVER['DOCUMENT_ROOT'].'/fonct.php'); ?>
    <link rel="stylesheet" href="/index2.css">
    <?php
    session_start();
    if (!(Test_co())){
        echo'<meta http-equiv="Refresh" content="0; URL=/Connexion/"/>';
        exit(10);
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
        include ($path.'/Header/header.php');
        include ($path.'/Header/Progress_bar.php');
        ?>
        <?php include ($path.'/Header/menu.php');
        if (Test_droit_dos()){
            include ($path.'/Fonctions/upload_p.php');
        }?>

        <?php 
        if (Test_droit_dos()){
            include ($path.'/Header/menu_lat.php'); 
        }
        ?>
        <link rel="stylesheet" href="/Fichier/Plein_ecran.css"/>

        <main>
            <?php 
                include ($path.'/Fichier/dos_file.php');
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
