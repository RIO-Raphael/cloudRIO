$(document).ready(function(){
    $('body>*').not($('#recherche')).on('click',function(){
        if (clic_find!=0){
            $('#recherche').css({display:'none'});
            clic_find=0;
        }
    });
})

clic_find=0;

function Afficher_find(){
    $div=$('#recherche');
    $div.remove();
    $('body').append($div)
    $('#recherche').css({display:'block'});
    setTimeout(function(){
        clic_find++;
    },10)
}