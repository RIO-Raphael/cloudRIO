<!DOCTYPE html>
<html lang="fr">
    <?php
        $path=$_SERVER['DOCUMENT_ROOT'];
        include $path.'/head.php';
    ?>
    <script src=j_new_account.js></script>
    <link rel="stylesheet" href="p_new.css">
    <body>
        <header>
            <div id='contain'>
                <p>
                    Inscription au CloudRIO
                </p>
            </div>
        </header>
        <main>
            <div id='contain_input'>
                <input required id='uid' type='text' label="Pseudo ou adresse mail" placeholder="Pseudo ou adresse e-mail"/>
                <input id='mdp' type='password' label="Mot de passe" placeholder="Mot de passe"/>
                <input id='mdp2' type='password' label="Mot de passe2" placeholder="Retapez votre mot de passe"/>
                <input id='mail' type='text' label="mail" placeholder="E-mail"/>
                <input id='nom' type='text' label="nom" placeholder="Nom"/>
                <input id='prenom' type='text' label="prenom" placeholder="Prénom"/>
                <p id=info></p>
                <button id='validation'>Valider</button>
            </div>
            <div id='loading'></div>
        </main>
    </body>
</html>