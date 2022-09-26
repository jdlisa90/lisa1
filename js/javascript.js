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
    
    $("#aide_acte").dialog({
        width:700,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    
     $("#aide_debut").dialog({
        width:700,
        autoOpen: false,
        modal: true,
        buttons: {
            "OK":function() { $(this).dialog("close"); }
        }
    });
    
    $("#aide_individu").dialog({
        width:700,
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
    
    $("#btn_acte").click(function(){
        Foo.DoSomething("#aide_acte");
    });
    
    $("#btn_debut").click(function(){
        Foo.DoSomething("#aide_debut");
    });
    
    $("#btn_individu").click(function(){
        Foo.DoSomething("#aide_individu");
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
    
/*    $("#refresh_cap").click(function(){
    	alert("do");
		$("#captcha").attr("src","captcha.php");
    });*/
        
});

$(function () {
	$("#submit_contact").click(function () {
		valid=true;
		if($("#adresse_contact").val()==""){
			$("#adresse_contact").next(".error-message").fadeIn().text("Email svp");
			valid=false;
		}
		else if(!$("#adresse_contact").val().match(/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/)){
			$("#adresse_contact").next(".error-message").fadeIn().text("Email valide svp");
			valid=false;
		}		
		else {
			$("#adresse_contact").next(".error-message").fadeOut();
		}

		if ($("#message_contact").val().length<5) {
			valid=false;
			$("#message_contact").next(".error-message").fadeIn().text("Veuillez compléter ce champ");
		}
		else {
			$("#message_contact").next(".error-message").fadeOut();
		}
		return valid;
	});
});

/* <![CDATA[ */
/*
|-----------------------------------------------------------------------
|  jQuery Multiple Toggle Script by Matt - www.skyminds.net
|-----------------------------------------------------------------------
|
| Affiche et cache le contenu de blocs multiples. Bloc après le texte.
|
*/
jQuery(document).ready(function() {
	$(".more").hide();
	jQuery('.button-read-more').click(function () {
		$(this).closest('.less').addClass('active');
		$(this).closest(".less").next().stop(true).slideDown("1000");
	});
	jQuery('.button-read-less').click(function () {
		$(this).closest('.less').removeClass('active');
		$(this).closest(".less").next().stop(true).slideUp("1000");
	});
});

/* Autre fonction, plus simple, pour l'effet cacher-masquer l'élément suivant */ 

function toggle_disp(elt) {
  var x = elt.nextSibling;			// élément, premier à la suite du div elt
  var y=elt.firstChild;				// élément span, premier enfant du div elt
  //ATTENTION : ne rien intercaler entre les 2 blocs, même pas un saut de ligne !!!
  if (x.style.display === "none") {
    y.textContent="▼";
    x.style.display = "block";
  } 
  else {
    x.style.display = "none";
    y.textContent="▶";
  }
}

function toggle_disp2(elt) {
  var x = elt.nextSibling.nextSibling;			// élément, second à la suite du div elt (pour intercaler un élément restant visible)
  var y=elt.firstChild;				// élément span, premier enfant du div elt
  //ATTENTION : ne rien intercaler entre les 2 blocs, même pas un saut de ligne !!!
  if (x.style.display === "none") {
    y.textContent="▼";
    x.style.display = "block";
  } 
  else {
    x.style.display = "none";
    y.textContent="▶";
  }
}

function toggle_out(elt) {		// comme le précédent, mais appliqué sur un span, dont il faut ressortir
  var x = elt.parentNode.nextSibling;	// élément suivant le div parent
  var y=elt;						// élément span lui-même
  //ATTENTION : ne rien intercaler entre les 2 blocs, même pas un saut de ligne !!!
  if (x.style.display === "none") {
    y.textContent="▼";
    x.style.display = "block";
  } 
  else {
    x.style.display = "none";
    y.textContent="▶";
  }
}

/* ------------ */ 

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
newwindow=window.open("../affichage_actes/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,width=625,height="+hgt()+",scrollbars=yes,resizable=yes,location=no, menubar=no");
if (window.focus) {newwindow.focus()}
};

function affiche_work(arch,numA,ord)
{
arch=arch.trim();
newwindow=window.open("../affichage_work/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,width=625,height="+hgt()+",scrollbars=yes,resizable=yes,location=no, menubar=no");
if (window.focus) {newwindow.focus()}
};

function affiche_act_err1(arch,numA,ord)
{
arch=arch.trim();
numA=numA+1;
window.open("../affichage_actes/acte-"+numA+"-"+ord+"-"+arch+".html", "Acte","top=10,left=10,width=700,height="+hgt()+",scrollbars=yes,resizable=yes,location=no, menubar=no");
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

function valid(tf,c,l) {        // permet de vérifier 1 champ du formulaire.
	if (tf.elements[c].value){
		var y=tf.elements[c].value;
		if (y.length<l){
			alert ("Le champ doit comporter au moins "+l+" caractère(s) !");
			tf.elements[c].value="";
                        return false;
		}
                else return true;
	}
	else {
            alert ("Le champ doit comporter au moins "+l+" caractère(s) !!");
            return false;
        }
};

function valider(frm,old_mbr_mail){
    if(!frm.elements['login'].readOnly){// test seulement si c'est un nouveau compte ; pour préserver les comptes anciens ne respectant pas le RegExp
        var login=frm.elements['login'].value;
        var exp=new RegExp("^[a-zA-Z0-9]{3,50}$","g");// DE a à z, de A à Z, de 0 à 9 entre 3 et 50 caractères
        if (! exp.test(login)){	
            alert ("Le login "+login+" n'est pas valide !");
            login="";
            return false;
        }	
    }
    var pass=frm.elements['pass'].value;    // nouveau pass
    if(pass!="" || !frm.elements['login'].readOnly){   // on ne teste QUE SI qqch a été saisi comme nouveau pass OU si c'est un nouveau compte !
        var pass2=frm.elements['pass2'].value; // répétition nouveau pass
        if(pass!=pass2){
            alert("Les 2 mots de passe ne sont pas identiques !");
            pass="";
            return false;		
        }
        var exp=new RegExp("^[a-zA-Z0-9]{3,20}$","g");
        if (! exp.test(pass)){
            alert("Le mot de passe "+pass+" n'est pas valide !");
            pass="";
            return false;
        }        
    }
    var mail=frm.elements['mail'].value;
    if (mail=='') {return true;} 
    else{
        if ((mail.indexOf("@")>=0)&&(mail.indexOf(".")>=0)) {
	        if (old_mbr_mail!='' && mail!=old_mbr_mail){
	            var is_confirmed = confirm("L'adresse mail saisie diffère de celle qui figure dans vos \n\
	                    données de membre de LISA. \n Voulez-vous mettre à jour ces données ?");
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
    //alert(src);
    if (src=="0"){	
        alert ("Le choix d'une source est obligatoire");
        return false;
    }
    else{
    	setLoader();
    	return true;
    }
};

function valide_scr1(frm){
    var src=frm.elements['arch'].value;
    var initiale=frm.elements['initiale'].value;
    if (src=="0" || initiale==""){
    	if (src=="0"){
    		if (initiale==""){
    			mess="Veuillez sélectionner une source et une initiale";
    		}
    		else {
    			mess="Veuillez sélectionner une source";
    		}
    	}
    	else{
			mess="Veuillez sélectionner une initiale";
    	}
      alert (mess);
      return false;
    }
    else{
    	setLoader();
    	return true;
    }
};

function valide_travail(frm){//erreur si une variable contient autre chose que des lettres, 
    //sauf une apostrophe, un tiret ou des espaces. Il faut également que le tiret ou l'apostrophe ne soit pas placés en premier.
    var c=0;
    var titre=frm.elements['titre'].value.trim();
    if(titre!="") c++;
    for (i = 0; i < 9; i++) {
        var exp=new RegExp("^[a-zéèàùûêâôë]{1}[a-zéèàùûêâôë \'-]*[a-zéèàùûêâôë]$","gi");
        var txt=frm.elements['fam'+i].value;
        txt=txt.trim();
        if(txt!=""){
            if (! exp.test(txt)){	
                alert ("Un caractère du nom "+txt+" n'est pas valide !");
                txt=""; 
                return false;
            }
        c++;
        }
    }
    if(c==0){
        alert("Rien n'a été saisi !");
        return false;
    }
    return true;
};

//function suppr_accents(s, encod='utf-8') // pour les noms de lieux et de personnes POSE PROBLÈME ET FAIT ÉCHOUER TOUS LES JAVASCRIPTS SOUS IE !!!
//{
//    s = htmlentities(s, ENT_NOQUOTES, encod);
//    s = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', s);
//    s = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', s);
//    s = preg_replace('#&[^;]+;#', '', s);
//    return s;
//};

var nombreChamp = 1;        // utilisé pour ajouter des champs select communes dans la saisie d'un travail
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

function chState(element)		// utilisé pour les checkBox
{
    if(element.checked) 
        element.value='1'; 
   else
       element.value='0';
};

function copyToClipboard(elt,txt) {
  var $temp = $("<input>");
  var t=$(elt).html();
  $("body").append($temp);
  $temp.val(txt).select();
	if (document.execCommand('copy')) {		
		//$(elt).addClass('copied');		// ajout de la classe 'copied' au bouton 'copy'
		$(elt).html("<span style='color:blue;font-weight:bold;'>Source copiée !</span>");
		var temp = setInterval(function(){	// effet temporisé sur le bouton
			$(elt).html(t);
			clearInterval(temp);					
		}, 
		1200);
	}
	else {
		alert('erreur interne');
	}
  //document.execCommand("copy");
  $temp.remove();
}

$(document).ready(function () {
	$("body").on("mouseover",".info",function(){  // AFFICHAGE EN TOOLTIPS de certaines informations
		var fd = new FormData();
		var ref=$(this).attr("ref");
		var cont=$('span:first', this);	// le premier span enfant de this
		alert(cont);
	    fd.append('ref',ref);
		$.ajax({
           url:'ref_query.php',
           type:'post',
           data:fd,
           dataType:'json',
           contentType: false,
           processData: false,
           success:function(response){	
               if(response != ""){
						//alert(response.result);
						if(response.res==1){	
							//alert(response.result);
							cont.html(response.result);
						}
					   else{
                   alert(response.result);
               	}
               }
               else{
                   alert('Nada');
               }
               //alert('#close_'+close_but);
               $(close_but).get(0).click();
           }		// fin de success
       });		// fin de $.ajax*/
   });		// fin de jquery
   
});
