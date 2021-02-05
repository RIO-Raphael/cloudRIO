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
        $(this).children('.CK_user').prop('checked',!$(this).children('.CK_user').prop('checked'));
    })
}