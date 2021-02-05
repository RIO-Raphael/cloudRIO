<link rel="stylesheet" href="/Header/menu_app.css">
<link rel="stylesheet" href="/Header/menu_c.css">
<script src='/Header/menu_c.js'></script>
<script src='/Header/menu_app.js'></script>

<nav id='menu_choix_appli'>
    <div class='choix_nav' id='fichier'>
        <div class='img_nav' id='img_fichier'></div>
        <span class='txt_nav'>Fichiers</span>
    </div>
    <div class='choix_nav' id='musique'>
        <div class='img_nav' id='img_musique'></div>
        <span class='txt_nav'>Musiques</span>
    </div>
    <!--<a class='choix_nav' href="./map/"><span>Cartes</span></a>-->
    <div class='choix_nav' id='photo'>
        <div class='img_nav' id='img_photo'></div>
        <span class='txt_nav'>Galerie</span>
    </div>
</nav>
<nav id='menu_co'>
    <div id='retour_home'>Home</div>
    <p id='Nom'>
        <?php
        $bdd=BDD();
        if (isset($_SESSION['data'])){
            $nom=strtoupper($_SESSION['data']['prenom'][0]).substr($_SESSION['data']['prenom'],1)." ".strtoupper($_SESSION['data']['nom'][0]).substr($_SESSION['data']['nom'],1);
        }else{
            if (isset($_GET['d_id'])){
                $d_id=$_GET['d_id'];
                $sql='SELECT * FROM Dossiers WHERE (iddossiers='.$d_id.')';
                $result=$bdd->query($sql);
                $result=$result->fetch();
                $nom='Dossier de : '.strtoupper($result['proprietaire']); //proprio du dossier que l'on regarde
            }
        }
        echo"$nom";
        ?>
    </p>
    <div id=infos_date>
        <p class='infos_date' id='date'></p>
        <p class='infos_date' id='time'></p>
    </div>
    <p>Utilisation du cloud %</p>
    <p>Doc partagés</p>
    <p>Paramètres du compte</p>
    <a href="/Connexion/deconnexion.php">Déconnexion</a>
</nav>