$(document).ready(function(){
    
    $(document).on('keypress',function(event){
        if (event.which==13){
            var Bool=!($('#validation').is(':focus')); //sinon on envoie 2 fois la requete ajax
            if (Bool){
                $('#validation').trigger('click');
            }
        }
    });
    
    //Process de validation
    $('#validation').on('click',function(){
        var $uid=$('#uid').val();
        var $mdp=$('#mdp').val();
        $.ajax({
            type:'POST',
            url:'/Connexion/f_suppr.php',
            data:{'uid':$uid,'mdp':$mdp},
            dataType:'html',
            
            success:function(code_html){
                document.write(code_html);
                if (code_html=='1'){
                    $('#uid').css({'border':'solid red'});
                    $('#uid').prop('placeholder','Mauvais pseudo');
                    $('#uid').val('');
                }
                if (code_html=='2'){
                    $('#mdp').css({'border':'solid red'});
                    $('#info').text('Le mot de passe est incorrect.')
                }
                if (code_html=='0'){
                    $('input').css({'border':'solid green'});
                    $('#info').text('Votre compte a bien été supprimé. Au revoir!')
                }                    
           
            },                    
            error:function(code_html){
                document.write(code_html);
            },
            complete:function(){
            }
        });
    });
});