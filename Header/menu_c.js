var mat_day=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
var mat_mois=["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"];

$(document).ready(function(){
    //ok
    //taille menu_c
    var taille_menu_c='calc(100% - var(--header))';

       MyFunctionTime();

    var nb_click_menu_c=0;
    $('body>*').not($('#menu_co *').parents()).on('click',function(){
        if (nb_click_menu_c!=0){
            setTimeout(function(){
                nb_click_menu_c=0;
            },20);
            $('#menu_co').css({'max-width':'','opacity':''});
        }
    });

    $('#b_menu_co').on('click',function(){
        setTimeout(function(){
            if (nb_click_menu_c==0){
                $('#menu_co').css({'max-width':'var(--t-menu)','opacity':'1'});
                nb_click_menu_c++;
            }    
        },10)
    })

    $('#retour_home').on('click',function(){
        if (window.location.href!=window.location.origin+'/'){
            window.location.href=window.location.origin;
        }
    })
});

function MyFunctionTime(){
    var now= new Date;
    var annee   = now.getFullYear();
    var mois    = now.getMonth() + 1;
    mois=mat_mois[mois];
    var jour_t=now.getDay();
    jour_t=mat_day[jour_t];
    var jour    = now.getDate();
    var heure   = now.getHours();
    var minute  = now.getMinutes();
     
    auj=jour_t+" "+jour+" "+mois+" "+annee;
    if (minute<10){
        time=+heure+":0"+minute;
    }else{
        time=+heure+":"+minute;
    }
    $('#date').text(auj);
    $('#time').text(time);
    setTimeout(function(){
        MyFunctionTime();
    },5000);//toute les 1000=1s
}