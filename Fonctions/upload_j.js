$(document).ready(function(){
    var $time=800; //0.8s

    $('#b_upload').on('click',function(){
        $('#upload').show();
        $('#upload *').show();
        $('#select_files').trigger('focus');
        $('#select_files').trigger('click');
        $('#upload').hide();
        $('#select_files')[0].onchange=function(){
            Upload_envoi($('#select_files')[0].files);
        };
    });

    function Upload_envoi($files){
        $nb=$files.length;
        $d_id=null;
        if (history.state!==null){
            $d_id=history.state.d_id;
        }
        for ($i=0;$i<$nb;$i++){
            Upload_file($files[$i],$i,$nb);
        }
    }

    function Upload_file($file,$i,$total){
        Process_GET();
        var xhRequest=new XMLHttpRequest();
        //On initialise une requete
        xhRequest.open('POST','/Fonctions/upload.php');
        //On crée un 'form'
        var form = new FormData(); 
        //on ajoute les fichiers dans notre formulaire
        form.append('file',$file);
        if ($_GET['d_id']!==undefined){
            form.append('d_id',$_GET['d_id']);
        }
        //On envoie la requete
        xhRequest.send(form);
        setProgressBar(xhRequest,$i,$total);
        //On affiche la réponse
        xhRequest.onreadystatechange = function() {
            if (xhRequest.readyState === 4) {
                console.log(xhRequest.response);
                if (xhRequest.response=='0'){
                    window.location.reload();
                }else{
                    document.write(xhRequest.response);
                }
            }
        };
    }

    function setProgressBar(xhRequest,$i,$total){
        //Gestion de la barre de progression
        xhRequest.upload.onprogress=function(e){
            $load=(e.loaded/e.total)*100*($i/$total);
            $('#progress_bar').css({right:'calc(100% - '+$load+')'});
        }
        //Gestion de la barre de progression quand la requete est finie
        xhRequest.onload=function(){
            $('#progress_bar').css({right:'0%','border-radius':'0%'});
            setTimeout(function(){
                $('#progress_bar').css({opacity:'0'});
                setTimeout(function(){
                    $('#progress_bar').css({right:'100%'});
                    $('#progress_bar').css({'border-radius-top-right':'var(--margin)','border-radius-bottom-right':'var(--margin)'});
                    setTimeout(function(){
                        $('#progress_bar').css({opacity:'1'});
                    },$time)
                },$time);
            },$time);
        }

    }
})

/*Exemple code: 
function upload(file) {
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'reception.php');
    initBar();
    xhr.upload.onprogress = function (e) {
        var loaded = e.loaded / e.total * 100;
        setProgressBar(loaded);
    };
    xhr.onload = function (e) {   
        endBar();
    };  
        
    var form = new FormData(); 
    form.append('file', file);
    xhr.send(form);
}

var fileInput = document.querySelector('#file');
fileInput.onchange = function() {
    upload(fileInput.files[0]);
};*/