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
            $(this).css({'border-width':'0rem','border-radius':'0rem'})
        }
    })
}

function Off_event_select(){
    ok_select=0;
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
                $('#entrer_texte p').text('Entrez le nom de la personne à qui vous voulez partager vos fichiers');
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

function Share(){
    var $R=[];
    $R=make_list_checked();
    $U=make_list_checked(1); //On met à 1 pour slectionner les users
    $.ajax({
        url : '/Fonctions/partage.php', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP
        data: {U:$U,R:$R},    
        dataType : 'html', // Le type de données à recevoir, ici, du HTML.
        success : function(code_html){ // success
            document.write(code_html);
        },

        error : function(code_html, statut, erreur){
            document.write(code_html);
        }
    });
}

function Share_check(){
    var $R=make_list();
    $.ajax({
        url : '/Fonctions/partage_check.php', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP
        data: {R:$R},    
        dataType : 'html', // Le type de données à recevoir, ici, du HTML.
        success : function(code_html){ // success
            console.log(code_html);
            var $retour=Process_arg(code_html);
            console.log($retour);
            for(var $i=0;$i<$retour.length;$i++){
                if ($retour[$i][1]!="-1"){
                    Style_ok_share($('#'+$retour[$i][0]));
                }
            }
        },

        error : function(code_html, statut, erreur){
            document.write(code_html);
        }
    });
}

function Style_ok_share($this){
    $this.css({border:'0.3rem solid green','border-radius':'1rem'});
}

function Off_event_share(){
    $('#share').css({display:'none'});
    $('#share').off('click');
}

function Show_list_user($json){
    //uid=>,nom=>,prenom=>,email=>
    if ($('#contain_user').length==0){
        $('#contain_user').remove();
        $html="<div style='color:white' id='contain_user'></div>";
        $('#entrer_texte').append($html);
    }else{
        var $i=0;
        var $count=$('#contain_user').children().length;
        while($i<$count){
            if (!($('.user').eq($i).children('.CK_user').prop('checked'))){
                $('.user').eq($i).remove();
                $i=0;
                $count=$('#contain_user').children().length;
                console.log($i+"<"+$count+" "+$('.user').eq($i).children('.CK_user').prop('checked'));
            }else{
                $i++;
            }
        }
    }
    if ($json!=undefined){
        var $p=0;
        $html='';
        while($json[$p]!=undefined){
            $html+=Bloc_user($json[$p]['uid'],$json[$p]['nom'],$json[$p]['prenom'],$json[$p]['email']);
            $p++;
        }
        $('#contain_user').append($html);
    }   
}

function Bloc_user($uid,$nom,$prenom,$email){
    $html="<div class='user' id='"+$uid+"'>";
    if ($uid!=$email){
        $html+="<span>pseudo="+$uid+" | nom="+$nom+" | prenom="+$prenom+" | email="+$email+"</span>";
    }else{
        $html+="<span>nom="+$nom+" | $prenom="+$prenom+" | email="+$email+"</span>";
    }
    $html+="<input class='CK_user' type='checkbox'></input>";
    $html+="</div>";
    return $html;
}

function Event_click_block_user(){
    $('.user').on('click',function(){

    })
}

