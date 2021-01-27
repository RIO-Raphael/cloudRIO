$(document).ready(function(){
    //ok
    //taille du menu
    var taille_menu_app='calc(3.2*var(--button))';
    var nb_click_menu_app=0;
    $('body>*').not($('#menu_choix_appli *').parents()).on('click',function(){
        if (nb_click_menu_app!=0){
            setTimeout(function(){
                nb_click_menu_app=0;
            },20);
            $('#menu_choix_appli').css({'max-height':'','opacity':''});
            $('#b_menu_app').css({'background-color':'','border-radius':''});
        }
    })

    $('#b_menu_app').on('click',function(){
        setTimeout(function(){
            if (nb_click_menu_app==0){
                $('#menu_choix_appli').css({'max-height':taille_menu_app,'opacity':'1'});
                $('#b_menu_app').css({'background-color':'var(--second-color)','border-radius':'50%'});
                nb_click_menu_app++;
            }
        },10);
    })
});