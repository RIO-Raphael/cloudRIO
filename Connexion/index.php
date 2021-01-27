<!DOCTYPE html>
<html lang="fr">
    <?php
        $path=$_SERVER['DOCUMENT_ROOT'];
        include $path.'/head.php';
    ?>
    <script src=j_co.js></script>
    <link  rel="stylesheet" href="co.css">
    <body>
        <header>
            <div id='contain'>
                <p>
                    Connexion au CloudRIO
                </p>
            </div>
        </header>
        <main>
            <div id='contain_input'>
                <input id='uid' type='text' label="Pseudo ou adresse mail" placeholder="Pseudo ou adresse e-mail"/>
                <input id='mdp' type='password' label="Mot de passe" placeholder="Mot de passe"/>
                <div id='contain_button'>
                    <div id='contain_button_2'>
                        <button class='b_front_end' id='forgotpwd'>Mot de passe oublié?</button>
                        <button class='b_front_end'id='validation'>Valider</button>
                    </div>
                    <div id='contain_button_3'>
                        <a href='p_new_account.php'>
                            <button class='b_front_end' id='newC'>S'inscrire</button>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
