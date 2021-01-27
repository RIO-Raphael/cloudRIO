<header>
    <script src='/Header/header.js'></script>
    <div id='contain'>
        <div class='contain_niv_1' id='contain_gauche'>
            <div data-text='Menu' id='logo'></div>
            <div data-text='Recherche' class='bouton' id='find' onclick="Afficher_find()"></div>
            <input id='recherche' type='text' placeholder='Recherche'/>
        </div>
        <div class='contain_niv_1' id='contain_droit'>
            <div id='b_contain'>
                <div class='bouton' data-text='Application' id=b_menu_app></div>
                <div class='bouton' data-text='Gestion compte' id=b_menu_co>
                    <?php
                        if (isset($_SESSION['data'])){
                            //On recupère les initales du bonhomme (en majuscule svp)
                            $init=$_SESSION['data']['nom'][0].$_SESSION['data']['prenom'][0];
                        }else{
                            $init='Part.';
                        }
                        $init=strtoupper($init);
                        echo"<p>$init</p>";
                    ?>
                </div>
            </div>
        </div>
    </div>
</header>