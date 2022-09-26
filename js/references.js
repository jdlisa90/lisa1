 $(document).ready(function(){
   $("body").on("mouseover",".info",function(){  //#but_upload : bouton 
   	txt=$(this).children(":first");
   	ref=$(this).attr("ref");
       var fd = new FormData();
       fd.append('ref',ref);					// transmet l'id de la référence

       $.ajax({		// instancie implicitement un objet XmlHttpRequest
          url:'ref_query.php',		// La ressource ciblée
          type:'post',
          data:fd,					// l'objet FormData
          dataType:'json',
          contentType: false,	// pour empêcher jQuery d'ajouter un header 
          processData: false,	// (boolean) pour empêcher jQuery de convertir l'objet FormData en string
          success:function(response){		// callback function à exécuter when Ajax request succeeds ; response est la donnée retournée par la page url
            if(response != ""){
					//alert(response.result);
					if(response.res==1){	
						txt.html(response.result);
   					txt.css('display','block');
					}
				   else{
                alert(response.result);
            	}
             }
             else{
                alert('Nada');
             }
           }		// fin de success
       });		// fin de $.ajax
   });		// fin de jquery
   
  	$("body").on("mouseout",".info",function(){
   	$(this).children(":first").css('display','none');
  	});
});