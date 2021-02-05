var c_aff_dos=0;
var $_GET=[];
var ok_select=0;

$(document).ready(function(){
    $('#create').on('click',function(){
        $.ajax({
            url : '/Fichier/input.php', // La ressource ciblée
            type : 'POST', // Le type de la requête HTTP
            //data: ,    
            dataType : 'html', // Le type de données à recevoir, ici, du HTML.

            success : function(code_html){ // success
                $('body').append(code_html);
                $('#entrer_texte p').text('Entrez le nom du nouveau dossier');
                $(document).on('keydown',function(e){
                    if (e.keyCode==13){
                        $('#entrer_texte #ok_create').trigger('click');
                    }
                    if (e.keyCode==27){
                        $('#entrer_texte').remove();
                        $(document).off('keydown');
                    }
                });
                $('#entrer_texte #ok_create').on('click',function(){
                    $(document).off('keydown');
                    Process_GET();
                    var $d_id=$_GET['d_id'];
                    if ($d_id===undefined){
                        $d_id=null;
                    }
                    var $d_nom=$('#entrer_texte input').val();
                    $('#entrer_texte').remove();
                    $.ajax({
                        url : '/Fonctions/creation_dossier.php', // La ressource ciblée
                        type : 'POST', // Le type de la requête HTTP
                        data: {d_nom:$d_nom,d_parent:$d_id},    
                        dataType : 'html', // Le type de données à recevoir, ici, du HTML.
                        success : function(code_html){ // success
                           window.location.reload();
                        },
                
                        error : function(code_html, statut, erreur){
                            document.write(code_html);
                        }
                    }); 
                })
            },
    
            error : function(resultat, statut, erreur){
                document.write(code_html);
            }
        });        
    })

    $('#MAJ').on('click',function(){
        $.ajax({
            url : '/Fonctions/Mise_a_jour.php', // La ressource ciblée
            type : 'POST', // Le type de la requête HTTP
            dataType : 'html', // Le type de données à recevoir, ici, du HTML.
            success : function(code_html){ // success
                document.write(code_html+'<p style="font-size:3rem">Cliquez n\'importe où pour passer</p>');
                $(document).on('click',function(){
                    window.location.reload();
                })              
            },
    
            error : function(code_html, statut, erreur){
                document.write(code_html);
            }
        });  
    })

    $('#select').on('click',function(){
        ok_select++;
        if (ok_select==1){
            Share_check();
            Event_select();
            Event_suppr();
            Event_share();               
        }else{
            ok_select=0;
            Off_event_select();
            Off_event_suppr();
            Off_event_share();
        }
    })

})

//################ SELECT ####################
function Event_select(){
    $('#select').css({'background-color':'var(--main-color)'});
    $('#logo').css({'background-color':'var(--select-color)'});
    off_event_click_dos();
    Off_event_fichiers();
    $('.CK').css({display:'block'});
    $('.CK').on('click',function(){
        $this=this;
        setTimeout(function(){
            $($this).parents('.fichier,.dossier').trigger('click');
        },10)
    });
    $('.fichier,.dossier').on('click',function(){
        $(this).children('.CK').prop('checked',!$(this).children('.CK').prop('checked'));
        if ($(this).children('.CK').prop('checked')){
            $(this).css({'border-width':'1rem','border-radius':'1rem'})
        }else{
            if ($(this).prop('style')['border-color']=='green'){
                $(this).css({'border-width':'0.3rem'})
            }else{
                $(this).css({'border-width':'0rem','border-radius':'0rem'})
            }
        }
    })
}

function Off_event_select(){
    ok_select=0;
    $('#select').css({'background-color':''});
    $('#logo').css({'background-color':''});
    $('.fichier,.dossier,.CK').off('click');
    Select_reset();
    Event_click_dos();
    Event_fichiers();
    $('.CK').css({display:'none'});
}

//################ SUPRR ####################
function Event_suppr(){
    $('#suppr').css({display:'block'});
    $('#suppr').on('click',function(){
        var $R=[];
        $R=make_list_checked();
        $.ajax({
            url : '/Fonctions/suppr.php', // La ressource ciblée
            type : 'POST', // Le type de la requête HTTP
            data: {R:$R},    
            dataType : 'html', // Le type de données à recevoir, ici, du HTML.
            success : function(code_html){ // success
                setTimeout(function(){
                    window.location.reload();
                },1000)
                document.write(code_html);
            },
    
            error : function(code_html, statut, erreur){
                document.write(code_html);
            }
        }); 
    })
}

function Off_event_suppr(){
    $('#suppr').css({display:'none'});
    $('#suppr').off('click');
}

function Select_reset(){
    $('.fichier,.dossier').css({border:'','border-radius':''});
    $('.CK').prop('checked',false);
}

//################ SHARE ####################
function Event_share(){
    $('#share').css({display:'block'});
    $('#share').on('click',function(){
        //On affiche le champ texte
        $.ajax({
            url : '/Fichier/input.php', // La ressource ciblée
            type : 'POST', // Le type de la requête HTTP
            //data: ,    
            dataType : 'html', // Le type de données à recevoir, ici, du HTML.
    
            success : function(code_html){ // success
                $('body').append(code_html);
                $('#entrer_texte p').text('Entrez le nom de la personne à qui vous voulez partager vos fichiers. Partagez à "all" si vous voulez le rendre visible à tous');
                $(document).on('keydown',function(e){
                    var $text=$('#entrer_texte input').val();
                    if ($text.length>1){
                        //On regarde le nom qui est rentré et les correspondances avec la BDD
                        $.ajax({
                            url : '/Fonctions/select_uid.php', // La ressource ciblée
                            type : 'POST', // Le type de la requête HTTP
                            data: {text:$text},    
                            dataType : 'JSON', // Le type de données à recevoir, ici, du HTML.
                            success : function(code_JSON){ // success
                                console.log(code_JSON);
                                Show_list_user(code_JSON);
                            },
                    
                            error : function(code_JSON, statut, erreur){
                                document.write(code_html);
                            }
                        });
                    } 
                    if (e.keyCode==13){
                        $('#entrer_texte #ok_create').trigger('click');
                    }
                    if (e.keyCode==27){
                        $('#entrer_texte').remove();
                        $(document).off('keydown');
                    }
                });

                $('#entrer_texte #ok_create').on('click',function(){
                    $(document).off('keydown');
                    Share();
                    setTimeout(function(){
                        $('#entrer_texte').remove();
                    },10);
                })
            },
    
            error : function(resultat, statut, erreur){
                document.write(code_html);
            }
        });
    })
}

function Off_event_share(){
    $('#share').css({display:'none'});
    $('#share').off('click');
}
