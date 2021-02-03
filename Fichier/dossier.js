$(document).ready(function(){
    //##################### Afficher dossier ###################################
    //Affichage de début
    Afficher_dossier();

    setTimeout(function(){
        $('#main_contain').css({opacity:'1'});
        //On change l'URL donc on réaffiche les dossiers
        window.onpopstate=function(){
            $('#plein_ecran').remove();
            Afficher_dossier();
        }
    },500);   
})

function Afficher_tot($d_id){
    Appel_aff_dos($d_id);
    Appel_aff_fichier($d_id);
    Afficher_Arbo_dos();
}

function Afficher_dossier(){
    c_aff_dos++;
    var ID=[];
    var $d_id=null;
    var $f_id=null;
    if (history.state!==null && c_aff_dos>1){
        $d_id=history.state.d_id;
        $f_id=history.state.f_id;
    }
    if ($d_id===null || $d_id===undefined){
        ID=Depart_history();
        $d_id=ID[0];
        $f_id=ID[1];
    }
    Afficher_tot($d_id);
    Afficher_plein_ecran($f_id);
}

//Gestion des évènements pour parcourir les dossiers
function Event_click_dos(){
    $('.dossier').off('click');
    $('.dossier').on('click',function(){
        $d_id=$(this).attr('id');
        Change_URL_dos($d_id);
        Afficher_tot($d_id);
    });
}

function off_event_click_dos(){
    $('.dossier').off('click');
}

//Fonction pour changer l'URL
//Dans Fichier.js
function Change_URL_dos($d_id){
    var $infos={
        d_id : $d_id,
        f_id : null,
    }
    history.pushState($infos,null,"?d_id="+$d_id);
}

//Fonction qui va appeler le script php pour l'affichage des sous_dossiers et afficher les DOM sur la page
function Appel_aff_dos($d_id){
    $('#main_contain').css({opacity:'0'});
    if ($d_id!==null && $d_id!==undefined){
        $.ajax({
            type:'POST',
            url:'/Fonctions/afficher_dossier.php',
            data:{'d_id':$d_id},
            dataType:'html',
            
            success:function(code_html){
                Success_sous_dos(code_html);
            },                    
            error:function(code_html){
                document.write(code_html);
            },
            complete:function(){
            }
        });    
    }else{
        $.ajax({
            type:'POST',
            url:'/Fonctions/afficher_dossier.php',
            dataType:'html',
            
            success:function(code_html){
                Success_sous_dos(code_html);
            },                    
            error:function(code_html){
                document.write(code_html);
            },
            complete:function(){
            }
        });

    }
}

//Fonction appeller si succès
function Success_sous_dos(code_html){
    $('.dossier').remove();
    if (code_html[0]!==undefined){
        $('#dossier_contain_0').css({display:'block'});
        $('#dossier_contain').append(code_html);
        Event_click_dos();
    }else{
        $('#dossier_contain_0').css({display:''});

    }
    $('#main_contain').css({opacity:'1'});
}


function Process_GET(){
    var parts = window.location.search.substr(1).split("&");
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
    }
    console.log($_GET);
}

function Process_arg($retour){
    var $A=[];
    var parts = $retour.substr(1).split("&");
    for (var i = 0; i < parts.length; i++) {
        var temp = parts[i].split("=");
        $A.push(temp);
    }
    //console.log($A);
    return $A;
}

function Depart_history(){
    Process_GET();
    if ($_GET['d_id']==undefined){
        $d_id=null;
        $f_id=null;
    }else{
        $d_id=$_GET['d_id'];
        if ($_GET['f_id']==undefined){
            $f_id=null;
        }else{
            $f_id=$_GET['f_id'];
        }
    }

    if ($f_id!==null){
        Change_URL_fichier($d_id,$f_id);
    }else{
        if ($d_id!==null){
            Change_URL_dos($d_id);
        }
    }
    return [$d_id,$f_id];
}

//############ Arbo ################
function Afficher_Arbo_dos(){
    Process_GET();
    $('#contain_arbo').remove();
    $.ajax({
        url : '/Fonctions/afficher_arbo.php', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP
        data: {d_id:$_GET['d_id']},    
        dataType : 'html', // Le type de données à recevoir, ici, du HTML.
        success : function(code_html){ // success
            $('main').prepend(code_html);
        },

        error : function(code_html, statut, erreur){
            document.write(code_html);
        }
    });
}
