$(document).ready(function(){
    
    $(document).on('keypress',function(event){
        if (event.which==13){
            var Bool=!($('#validation').is(':focus')); //sinon on envoie 2 fois la requete ajax
            if (Bool){
                $('#info').text('Un mail vient de vous être envoyé. Veuillez vérifier que vous l\'avez bien reçu.');
                $('#validation').trigger('click');
            }
        }
    })
    
    $('#validation').on('click',function(){
        var $Bool=true;
        //On remet à la normale la page
        $('#info').text('');
        $('input').css({'border':''});
        //Critère + de 6 carac
        //On gère les cas où le formulaire est mal rempli
        if ($('#uid').val().length<3){
            $('#uid').css({'border':'solid red'});
            $('#uid').prop('placeholder','Ce pseudo est trop court');
            $('#uid').val('');
            $Bool=false;
        }
        if ($('#mail').val().length<3){
            $('#mail').css({'border':'solid red'});
            $('#mail').prop('placeholder','Ce mail est trop court');
            $('#mail').val('');
            $Bool=false;
        }
        if ($('#mail').val().length<6){
            $('#mail').css({'border':'solid red'});
            $('#mail').prop('placeholder','Ce mail n\'est pas valide');
            $('#mail').val('');
            $Bool=false;
        }
        if ($('#mail').val().indexOf('@')==-1){
            $('#mail').css({'border':'solid red'});
            $('#mail').prop('placeholder','Ce mail n\'est pas valide');
            $Bool=false;
        }
        if ($('#mdp').val().length<6){
            $('#mdp').css({'border':'solid red'});
            $('#info').append('Ce mot de passe est trop court<br>');
            $Bool=false;
        }
        if ($('#mdp').val()!==$('#mdp2').val()){
            $('#mdp').css({'border':'solid red'});
            $('#info').append('Les mots de passe ne correspondent pas<br>');
            $('#mdp2').css({'border':'solid red'});  
            $Bool=false;
        }
        
        
        
        if ($Bool){
            $('#loading').css({display:'block'});
            var $infos=[];
            var $nb;
            $nb=$('input').length;
            for (var $i=0;$i<$nb;$i++){
                //$infos[$i][0]=$($('input').get($i)).prop('id');
                $infos[$i]=[$($('input').get($i)).prop('id'),$($('input').get($i)).val()];
            }
            $.ajax({
                type:'POST',
                url:'/Connexion/f_new_co.php',
                data:{'infos':$infos},
                dataType:'html',
                
                success:function(code_html){
                    //document.write(code_html);
                    if (code_html=='1'){
                        $('#loading').css({display:'none'});
                        $('#uid').css({'border':'solid red'});
                        $('#uid').prop('placeholder','Ce pseudo est déjà utilisé');
                        $('#uid').val('');
                    }
                    if (code_html=='2'){
                        $('#loading').css({display:'none'});
                        $('#mail').css({'border':'solid red'});
                        $('#mail').prop('placeholder','Ce mail  n\'est pas valide');
                        $('#mail').val('');
                    }
                    if (code_html=='0'){
                        $('input').css({'border':'solid green'});
                        $('#info').text('Création de votre espace membre');
                        $.ajax({
                            type:'POST',
                            url:'/Fonctions/creation_dossier_home.php',
                            data:{'prop':$('#uid').val()},
                            dataType:'html',
                            success:function(code_html){
                                $('#loading').css({display:'none'});
                                $('#info').text('Votre compte a bien été enregistré! Bienvenue sur le CloudRIO!');
                                //document.write(code_html);
                                setTimeout(function(){
                                    window.location.href=window.location.origin;
                                },2500)        
                            },
                            error:function(code_html){
                                document.write(code_html);
                            },
                            complete:function(){
                                $('#loading').css({display:'none'});
                            }            
                        });           
                    }                    
                },                    
                error:function(code_html){
                    document.write(code_html);
                    $('#loading').css({display:'none'});
                },
                complete:function(){
                }
            });
        }
    });
});78