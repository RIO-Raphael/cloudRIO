$(document).ready(function(){
    
});

//##################### Afficher dossier ###################################
function Afficher_fichier(){
    var $d_id=null;
    if (history.state!==null){
        $d_id=history.state.d_id;
        $f_id=history.state.f_id;
    };
    console.log('d_id_2='+$d_id);
    console.log('f_id_2='+$f_id);
    Appel_aff_fichier($d_id);
}

//Gestion des évènements pour parcourir les dossiers
function Event_click_dos(){
}

//Fonction pour changer l'URL
function Change_URL_fichier($d_id,$f_id){
    var $infos={
        d_id : $d_id,
        f_id : $f_id,
    }
    history.pushState($infos,null,"?d_id="+$d_id+"&f_id="+$f_id);
}

//Fonction qui va appeler le script php pour l'affichage des sous_dossiers et afficher les DOM sur la page
function Appel_aff_fichier($d_id){
    $.ajax({
        type:'POST',
        url:'/Fonctions/afficher_fichier.php',
        data:{'d_id':$d_id},
        dataType:'html',
        
        success:function(code_html){
            //document.write(code_html);
            Success_f(code_html);
        },                    
        error:function(code_html){
            document.write(code_html);
        },
        complete:function(){
        }
    });    
}

//Fonction appeller si succès
function Success_f(code_html){
    $('.fichier').remove();
    if (code_html[0]!==undefined){
        $('#file_contain_0').css({display:'block'});
        $('#file_contain').append(code_html);
        Event_click_dos();
        Event_fichiers();
    }else{
        $('#file_contain_0').css({display:''});

    }
}

//############ Plein écran ########################
function on_click_aff_fichier_plein_ecran($f_id){
    Change_URL_fichier($('#'+$f_id).attr('data-d_id'),$f_id);
    Afficher_plein_ecran($f_id);
}

function Afficher_plein_ecran($id){
    if($id!==null && $id!==undefined){
        Plein_ecran($id);
    }
    //alert($id);
}

function Plein_ecran($id){
    $chemin=$('#'+$id).attr('data-chemin');
    $nom=$('#'+$id).attr('data-nom');
    $type=$('#'+$id).attr('data-type');
    $data='id='+$id;
    //alert($data);
    $.ajax({
        type:'POST',
        url:'/Fichier/Plein_ecran.php',
        data:$data,
        dataType:'html',
        
        success:function(code_html){
            Success_full_f(code_html);
            Event_plein_ecran();
        },                    
        error:function(code_html){
            document.write(code_html);
        },
        complete:function(){
        }
    });    
}

function Success_full_f(code_html){
    if (code_html[0]!==undefined){
        $('body').append(code_html);
    }else{
        $('body').css({'background-color':'red'});
        setTimeout(function(){
            $('body').css({'background-color':''});
        },500);
    }
}

$c_plein_ecran=0;
function Event_plein_ecran(){
    $(window).on('keydown',function(e){
        if (e.keyCode==27){
            fin_plein_ecran();
        }
    });
    $('#fermeture_plein_ecran').on('click',function(){
        fin_plein_ecran();
    })
    $('#plein_ecran').on('click',function(){
        $c_plein_ecran++;
        if ($c_plein_ecran==1){
            $('#plein_ecran span').css({display:'none'});
            $('#fermeture_plein_ecran').css({display:'none'});
        }else{
            $c_plein_ecran=0;
            $('#fermeture_plein_ecran').css({display:'block'});
            $('#plein_ecran span').css({display:'block'});
        }
    })
    $('#plein_ecran video').on('play',function(){
        $('#plein_ecran span').css({display:'none'});
        $('#fermeture_plein_ecran').css({display:'none'});
    })
    $('#plein_ecran video').on('pause',function(){
        $c_plein_ecran=0;
        $('#plein_ecran span').css({display:'block'});
        $('#fermeture_plein_ecran').css({display:'block'});
    })
}

function fin_plein_ecran(){
    if (history.state!==null && c_aff_dos>1){
        history.back();
    }else{
        Change_URL_dos(history.state.d_id,null);
    }

    $('#plein_ecran').remove();
    $c_plein_ecran=0;
}

function Event_fichiers(){
    $('.fichier').on('click',function(){
        on_click_aff_fichier_plein_ecran(this.id);
    })
}

function Off_event_fichiers(){
    $('.fichier').off('click');
}

//############### d_id et f_id ###################
//return [d_id,f_id]
function get_id($this){
    $f_id=$this.prop('id');
    $d_id=$this.attr("data-d_id");
    //console.log('d_id='+$d_id);
    //console.log('f_id='+$f_id);
    return [$d_id,$f_id];
}