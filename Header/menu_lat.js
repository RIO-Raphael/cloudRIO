$(document).ready(function(){
    var $a=0;
    $('#logo').on('click',function(){
        if ($a==0){
            $a++;
            $('#menu_lat').css({display:'block'});
            $('main').css({left:'var(--taille_menu_lat)'});
            $('#logo').css({transform:'rotate(0.25turn)'});
            setTimeout(function(){
                $('#menu_lat').trigger('click');
            },1000);
        }else{
            $a=0;
            $('#menu_lat').css({display:'none'});
            $('main').css({left:'0'});
            $('#logo').css({transform:'rotate(0turn)'});
            setTimeout(function(){
                $('#menu_lat').trigger('click');
            },1000);
        }
    })
});