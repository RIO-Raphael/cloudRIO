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
                            $(document).write(code_html);
                        }
                    }); 
                })
            },
    
            error : function(resultat, statut, erreur){
        
            }
        });        
    })

    $('#suppr').on('click',function(){
        make_list_checked();
    })

    $('#select').on('click',function(){
        ok_select++;
        if (ok_select==1){
            Event_select();               
        }else{
            ok_select=0;
            Off_event_select();
        }
    })

    $('#share').on('click',function(){
        
    })
})

function Event_select(){
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
            $(this).css({border:'1rem solid var(--main-color)','border-radius':'1rem'})
        }else{
            $(this).css({border:'0rem solid var(--main-color)','border-radius':'0rem'})
        }
    })
}

function Off_event_select(){
    $('.fichier,.dossier,.CK').off('click')
    Event_click_dos();
    Event_fichiers();
    $('.CK').css({display:'none'});
}
