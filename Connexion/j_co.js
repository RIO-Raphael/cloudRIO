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
        Reset();
        var $infos=[];
        var $nb;
        $nb=$('input').length;
        //on rempli la variable $infos avec les champs input
        for (var $i=0;$i<$nb;$i++){
            $infos[$i]=[$($('input').get($i)).prop('id'),$($('input').get($i)).val()];
        }
        $.ajax({
            type:'POST',
            url:'/Connexion/f_co.php',
            data:{'infos':$infos},
            dataType:'html',
            
            success:function(code_html){
                //document.write(code_html);
                if (code_html=='1'){
                    $('#uid').css({'border':'solid red'});
                    $('#uid').prop('placeholder','Mauvais pseudo');
                    $('#uid').val('');
                }
                if (code_html=='2'){
                    $('#mdp').css({'border':'solid red var(--taille-ligne)'});
                }
                if (code_html=='0'){
                    $('input').css({'border':'solid green var(--taille-ligne)'});
                    setTimeout(function(){
                        window.location.href=window.location.origin;
                    },1500)
                }                    
           
            },                    
            error:function(code_html){
                document.write(code_html);
            },
            complete:function(){
            }
        });
    });

    function Reset(){
        $('#uid').css({'border':''});
        $('#mdp').css({'border':''});
    }
});