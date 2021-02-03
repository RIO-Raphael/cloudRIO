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

function Afficher_Arbo_dos(){
    Process_GET();
    $.ajax({
        url : '/Fonctions/afficher_arbo.php', // La ressource ciblée
        type : 'POST', // Le type de la requête HTTP
        data: {d_id:$_GET['d_id']},    
        dataType : 'html', // Le type de données à recevoir, ici, du HTML.
        success : function(code_html){ // success
            document.write(code_html);
        },

        error : function(code_html, statut, erreur){
            document.write(code_html);
        }
    });
}