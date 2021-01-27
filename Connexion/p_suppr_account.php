<!DOCTYPE html>
<html lang="fr">
    <?php
        $path=$_SERVER['DOCUMENT_ROOT'];
        include $path.'/head.php';
    ?>
    <script src=j_suppr.js></script>
    <link rel="stylesheet" href="p_new.css">
    <body>
        <header>
            <div id='contain'>
                <p>
                    Suppression d'un compte du CloudRIO
                </p>
            </div>
        </header>
        <main>
            <div id='contain_input'>
                <input id='uid' type='text' label="Pseudo ou adresse mail" placeholder="Pseudo ou adresse e-mail"/>
                <input id='mdp' type='password' label="Mot de passe" placeholder="Mot de passe"/>

                <p id=info></p>
            </div>
            
            <button id='validation'>Valider</button>
            
        </main>
    </body>
</html>