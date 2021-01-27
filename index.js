var c_aff_dos=0;
var $_GET=[];

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
        
    })

    $('#select').on('click',function(){
        
    })

    $('#share').on('click',function(){
        
    })
})
