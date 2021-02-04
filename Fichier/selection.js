function Event_selection(){
    $('.CK').css({display:'block'});
    $('.fichier, .dossier').on('click',function(){
        $(this).children('.CK').prop('checked',(!($(this).children('.CK').prop('checked'))));
        get_id($(this));
        Update_style($(this));
    })
}

function Off_event_selection(){
    $('.CK').css({display:'none'});
    $('.fichier,.dossier').off('click');
    $('.fichier,.dossier').children('.CK').prop('checked',false);
    $('.fichier,.dossier').css({transform:'',border:'solid var(--main-color) 0rem','border-radius':''});
}

function Update_style($this){
    if ($this.children('.CK').prop('checked')){
        set_style($this);
    }else{
        reset_style($this);
    }
}

function set_style($this){
    $this.css({transform:'scale:0.6',border:'solid var(--main-color) 1rem','border-radius':'1rem'})
}

function reset_style($this){
    $this.css({transform:'',border:'solid var(--main-color) 0rem','border-radius':''})
}

//retourne les id des fichiers et dossiers sélectionnés
function make_list_checked($user=0){
    if ($user==0){
        var F=[];
        var D=[];
        var n=0;
        while ($('.fichier').eq(n).prop('id')!==undefined){
            if ($('.fichier').eq(n).children('.CK').prop('checked')){
                F.push($('.fichier').eq(n).prop('id')); //On prend que le f_id
            }
            n++;
        }
        n=0;
        while ($('.dossier').eq(n).prop('id')!==undefined){
            if ($('.dossier').eq(n).children('.CK').prop('checked')){
                D.push($('.dossier').eq(n).prop('id')); //On prend que le f_id
            }
            n++;
        }
        var R={'fichiers':F,'dossiers': D}
        console.log(R);
        return R;
    }else{
        var U=[];
        var n=0;
        while ($('.user').eq(n).prop('id')!==undefined){
            if ($('.user').eq(n).children('.CK_user').prop('checked')){
                U.push($('.user').eq(n).prop('id')); //On prend que le f_id
            }
            n++;
        }
        var R={'users':U};
        console.log(R);
        return R;
    }
}

function make_list(){
    var F=[];
    var D=[];
    var n=0;
    while ($('.fichier').eq(n).prop('id')!==undefined){
        F.push($('.fichier').eq(n).prop('id')); //On prend que le f_id
        n++;
    }
    n=0;
    while ($('.dossier').eq(n).prop('id')!==undefined){
        D.push($('.dossier').eq(n).prop('id')); //On prend que le f_id
        n++;
    }
    var R={'fichiers':F,'dossiers': D}
    console.log(R);
    return R;
}