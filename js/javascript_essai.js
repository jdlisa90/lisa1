$(document).ready(function () {
    $("#aide_list-names").dialog({
    		width:600,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    $("#aide_list-names-assoc").dialog({
    		width:600,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    $("#aide_list-fnames").dialog({
    		width:600,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    $("#aide_result").dialog({
    		width:600,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    
    $("#bloc_copy").dialog({
    		width:600,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    
    $("#aide_g").dialog({
        width:900,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    $("#aide_g").prev().addClass('ui-state-information');

    // Bind to the click event for my button and execute my function
    $("#bouton_list-names").click(function(){
        Foo.DoSomething("#aide_list-names");
    });
    $("#bouton_list-names-assoc").click(function(){
        Foo.DoSomething("#aide_list-names-assoc");
    });
    $("#bouton_list-fnames").click(function(){
        Foo.DoSomething("#aide_list-fnames");
    });
    $("#bouton_result").click(function(){
        Foo.DoSomething("#aide_result");
    });
    
    $("#Button2").click(function(){
        Foo.DoSomething("#bloc_dialog2");
    });
    
    $("#copy").click(function(){
        Foo.DoSomething("#bloc_copy");
    });

    $("#bouton_g").click(function(){
        Foo.DoSomething("#aide_g");
    });
       
    $("#bouton_2").click(function(){
        Foo.DoSomething("#aide_2");
    });
    
    $("#form_new").click(function(){
	$("#bloc_form_new").slideToggle("slow");
    });
    
    $("#form_old").click(function(){
	$("#bloc_form_old").slideToggle("slow"); 
    });
    
  	$("#form_sources").click(function(){
	$("#bloc_form_sources").slideToggle("slow");
    });
    
    $("#form_1").click(function(){
	$("#bloc_form_1").slideToggle("slow");
    });
    
    $("#message").click(function(){
	$("#bloc_message").slideToggle("slow");
    });
    
    var hauteur_fenetre = $(window).height();
    hauteur_fenetre =hauteur_fenetre -160;
    $("#affichage").css({minHeight: hauteur_fenetre});
    
    $("#retour").click(function(){
	location.href = "accueil.html";
    });
        
});

var Foo = {
    DoSomething: function(b){
        $(b).dialog("open");
    }
};

function hgt(){
var D = document;
return Math.max(
        Math.max(D.body.scrollHeight, D.documentElement.scrollHeight),
        Math.max(D.body.offsetHeight, D.documentElement.offsetHeight),
        Math.max(D.body.clientHeight, D.documentElement.clientHeight)
    )-30;
};

function affiche_acte(arch,numA,ord)
{
arch=arch.trim();
newwindow=window.open("../affichage_actes/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,width=700,height="+hgt()+",scrollbars=yes,resizable=yes,location=no, menubar=no");
if (window.focus) {newwindow.focus()}
};

function charger(lan,stat) {
        window.location='accueil.html';
};

function confirmLink(theLink, theSqlQuery)
{
    var confirmMsg  = 'Voulez-vous vraiment ';
	var is_confirmed = confirm(confirmMsg + '\n' + theSqlQuery);
    if (is_confirmed) {
        return true;
    }
    return false;
};

function valid(tf,c,l) {        // permet de v??rifier 1 champ du formulaire.
	if (tf.elements[c].value){
		var y=tf.elements[c].value;
		if (y.length<l){
			alert ("Le champ doit comporter au moins "+l+" caract??re(s) !");
			tf.elements[c].value="";
                        return false;
		}
                else return true;
	}
	else {
            alert ("Le champ doit comporter au moins "+l+" caract??re(s) !!");
            return false;
        }
};

function valider(frm,old_mbr_mail){
    if(!frm.elements['login'].readOnly){// test seulement si c'est un nouveau compte ; pour pr??server les comptes anciens ne respectant pas le RegExp
        var login=frm.elements['login'].value;
        var exp=new RegExp("^[a-zA-Z0-9]{3,50}$","g");// DE a ?? z, de A ?? Z, de 0 ?? 9 entre 3 et 50 caract??res
        if (! exp.test(login)){	
            alert ("Le login "+login+" n'est pas valide !");
            login="";
            return false;
        }	
    }
    var pass=frm.elements['pass'].value;    // nouveau pass
    if(pass!="" || !frm.elements['login'].readOnly){   // on ne teste QUE SI qqch a ??t?? saisi comme nouveau pass OU si c'est un nouveau compte !
        var pass2=frm.elements['pass2'].value; // r??p??tition nouveau pass
        if(pass!=pass2){
            alert("Les 2 mots de passe ne sont pas identiques !");
            pass="";
            return false;		
        }
        var exp=new RegExp("^[a-zA-Z0-9]{3,8}$","g");
        if (! exp.test(pass)){
            alert("Le mot de passe "+pass+" n'est pas valide !");
            pass="";
            return false;
        }        
    }
    var mail=frm.elements['mail'].value;
    if ((mail.indexOf("@")>=0)&&(mail.indexOf(".")>=0)) {
        if (old_mbr_mail!='' && mail!=old_mbr_mail){
            var is_confirmed = confirm("L'adresse mail saisie diff??re de celle qui figure dans vos \n\
                    donn??es de membre de LISA. \n Voulez-vous mettre ?? jour ces donn??es ?");
            if (is_confirmed) {
                frm.action=frm.action+"&change_mail_mbr=y";
            }            
        }
        return true;
    } 
    else {
        alert("L'adresse mail "+mail+" n'est pas valide !");
                mail="";
        return false;
    }
};

String.prototype.trim = function()
{ return this.replace(/(^\s*)|(\s*$)/g, ""); }

function valide_mail(frm){
    var txt=frm.elements['message'].value;
    txt=txt.trim();
    if (txt==""){	
        alert ("Votre message est vide !");
        return false;
    }	
    var mail=frm.elements['adresse'].value;
    if ((mail.indexOf("@")>=0)&&(mail.indexOf(".")>=0)) {
       return true;
    } 
    else {
        alert("L'adresse mail "+mail+" n'est pas valide !");
        mail="";
        return false;
    }
};

function valide_scr(frm){
    var src=frm.elements['arch'].value;
    if (src=="0"){	
        //alert ("Le choix d'une source est obligatoire");
        //return false;
    }
    return true;
};

function valide_scr1(frm){
    var src=frm.elements['arch'].value;
    var initiale=frm.elements['initiale'].value;
    if (src=="0" || initiale==""){
    	if (src=="0"){
    		if (initiale==""){
    			mess="Veuillez s??lectionner une source et une initiale";
    		}
    		else {
    			mess="Veuillez s??lectionner une source";
    		}
    	}
    	else{
			mess="Veuillez s??lectionner une initiale";
    	}
      alert (mess);
      return false;
    }
    return true;
};

function valide_travail(frm){//erreur si une variable contient autre chose que des lettres, 
    //sauf une apostrophe, un tiret ou des espaces. Il faut ??galement que le tiret ou l'apostrophe ne soit pas plac??s en premier.
    var c=0;
    var titre=frm.elements['titre'].value.trim();
    if(titre!="") c++;
    for (i = 0; i < 9; i++) {
        var exp=new RegExp("^[a-z??????????????????]{1}[a-z?????????????????? \'-]*[a-z??????????????????]$","gi");
        var txt=frm.elements['fam'+i].value;
        txt=txt.trim();
        if(txt!=""){
            if (! exp.test(txt)){	
                alert ("Un caract??re du nom "+txt+" n'est pas valide !");
                txt=""; 
                return false;
            }
        c++;
        }
    }
    if(c==0){
        alert("Rien n'a ??t?? saisi !");
        return false;
    }
    return true;
};

var nombreChamp = 1;        // utilis?? pour ajouter des champs select communes dans la saisie d'un travail
function test(what)
{
    if(what == 'plus' && nombreChamp <12)
    {
        var elem=document.getElementById("com").cloneNode(true);
        elem.setAttribute("name", "comm" + nombreChamp);
        elem.setAttribute("class", "cssinput");
        document.getElementById("bt").appendChild(elem);
        document.getElementById("bt").appendChild(document.createElement("BR" ));
        document.getElementById("bt").appendChild(document.createElement("BR" ));
        nombreChamp++;
        document.getElementById("nbcomm").value= nombreChamp;
    }
};
