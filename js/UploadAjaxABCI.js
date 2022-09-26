/*  UploadAjaxABCI version 7.5c Copyright (c) 20014-2020 Alain Bontemps (abciweb.net) Licensed under the mit license (https://opensource.org/licenses/MIT)*/var UploadAjaxABCI=function(i,e,t){this.upload_serveur=i,this.id_form=e,this.content_result_tag=t&&""!=$.trim(t)?t:"",this.iteration_form=0,this.config={},this.info={},this.info.status={},this.info.recoveryBackupConfirm={},this.config.ajaxTimeOut=250,this.config.filesExtensions=[],this.config.filesExtensionsInput=[],this.config.fileSizeMax=null,this.config.fileSizeMaxInput=[],this.config.customFileSelect=null,this.config.customDragAndDrop=!0,this.config.inputFormAttrOnSubmit=!1,this.config.cssSubmitOn=null,this.config.cssFileSelectOn=null,this.config.imgPreviewStartAuto=!0,this.config.imgPreviewMaxSize=null,this.config.imgPreviewMaxSizeCancelAll=!1,this.config.imgPreviewMaxSizeTotal=null,this.config.imgPreviewMaxWidth=90,this.config.imgPreviewMaxHeight=60,this.config.imgPreviewUseFileReader=!1,this.config.RevokeObjectURL=!0,this.config.infosRefresh=1,this.config.remainingTimeCompute=10,this.config.remainingTimeDisplayAfter=30,this.config.recoveryBackupConfirm=!1,this.config.submitWithoutFile=!0,this.config.submitWithoutFileFuncFormEnd=!1,this.config.queryFormEnd=!1,this.config.BackupFormDependency=!0,this.config.cookiePath="/",this.config.fragmentSize=4194304,this.config.browserOutdeted="Navigateur obsolète incompatible",this.config.serverFatalErrorDisplay=!0,this.config.uniqidForm=this.SHA1(this.Uniqid("UploadAjaxABCI",!0)),this.info.status.ok="Téléchargement ok",this.info.status.inProgress="Téléchargement en cours",this.info.status.stop="Arrêt",this.info.status.errorSize="Ce fichier dépasse la taille maximale autorisée.",this.info.status.errorExtension="Extension non valide.",this.info.status.errorServer="Echec du téléchargement. ",this.info.remainingTimeComputeWaiting="calcul en cours",this.info.unitsSpacing="&nbsp;",this.info.recoveryBackupConfirm.name="Récupération du fichier : ",this.info.recoveryBackupConfirm.size="\nSauvegarde en cours : ",this.info.recoveryBackupConfirm.message='\n\nCliquez sur "OK" pour compléter ce fichier ou sur "Annuler" pour réinitialiser le téléchargement.',this.info.queryEndErrorServer="Echec dans la finalisation du traitement serveur. ",this.func_SubmitForm,this.func_ImgPreviewStartAll,this.config.func_ImgPreviewLoadEach=null,this.config.func_FileSelectAllBefore=null,this.config.func_FileSelectEach=null,this.config.func_FileSelectAll=null,this.config.func_onFormSubmit=null,this.config.func_FormSubmit=null,this.config.func_FileInProgressEach=null,this.config.func_FileEndEach=null,this.config.func_FormEnd=null,this.config.func_BrowserOutdeted=null,this.classes=$(["status","name","size","imgPreview","progressionG","progressionT","backup","percentage","duration","remainingTime","stop","inputFileName","inputInfosFileUnic"]),this.formulaire=$(),this.content_result=$(),this.tab_fichiers=[],this.tab_fichiers_change=[],this.data_files=$(),this.custom_select_file=$(),this.data_nofile=$(),this.data_nofile_serialize,this.data_nofile_CR=$(),this.data_nofile_CR_serialize,this.bouton_submit=$(),this.css_envoi_select_ini={},this.obj_ini={},this.obj_ini.html={},this.obj_ini.es={},this.count_files=0,this.infos_serveur=$(),this.stopAll=$(),this.requete=!0,this.win_url=window.URL||window.webkitURL,this.data_drop,this.stop_drop=!1,this.retour_infos_server,this.retour_mixte_server,this.query_end=!1,this.paramServer=function(i){var e={};return e.id_form=this.id_form,e.uniqid_form=this.config.uniqidForm,e.iteration_form=++this.iteration_form,i&&(e.input_name=i.fichier.upabciInputName,e.id_fich=i.id_fich,e.name=i.fichier.name,e.size=i.fichier.size,e.type=i.fichier.type,e.lastModified=i.fichier.lastModified,e.qte_save=i.qte_save,e.qte_upload=i.qte_upload,e.result=i.result,e.time_start=i.time_start,e.time_end=i.time_end,e.iteration=i.iteration),$.param(e,!1)},this.GetCssData=function(i){var e={},t=[];return $.each(i.split(";"),function(i,n){2==(t=n.split(":")).length&&(e[t[0]]=t[1])}),e},this.GetCssIni=function(i,e){var t={};return"object"==typeof i&&($.each(i,function(i,n){t[i]=null!=e.css(i)?e.css(i):"default"}),t)},this.GetTime=function(){return(new Date).getTime()},this.FormateTime=function(i,e,t){var n={};n.h=Math.floor(i/3600);var r=i%3600;n.m=Math.floor(r/60),0==n.h&&(n.s=Math.floor(r%60));var s="";return $.each(n,function(i,e){e>0&&(s+=e+t+i+t)}),s=""!=s?s.substring(0,s.length-t.length):e},this.FormateOctets=function(i,e){for(var t=["","K","M","G","T","P","E","Z","Y"],n=0,r=t.length-1;i>=1024&&n<r;)i/=1024,n++;return(i=n<3?i.toFixed(1):i.toFixed(3))+""+e+t[n]+(""==t[n]?"octets":"o")},this.ReturnOctets=function(i){var e=String(i),t=(e=(e=(e=e.replace(/,/,".")).replace(/\s/g,"")).replace(/[oO]/,"")).substr(e.length-1,1).toLowerCase();switch(e=parseFloat(e),t){case"t":e*=1024;case"g":e*=1024;case"m":e*=1024;case"k":e*=1024}return Math.ceil(e)},this.AfficheErreur=function(i){var e="";return $.each(i,function(i,t){switch(t){case"taille":e+=n.info.status.errorSize;break;case"extension":e+=n.info.status.errorExtension;break;case"preview":e+=n.info.status.erreur_preview}}),e},this.Pourcentage=function(i,e,t){return null!=e&&e>0?Math.round(i/e*100)+""+t+"%":"0"+t+"%"},this.FormatIni=function(i,e){var t=i.html(),n=$.trim(t).split(" "),r=parseInt(n[0]),s=n.length,a=s>1?" ":"";a=(n=s>1?[]:$.trim(t).split("&nbsp;")).length>1?"&nbsp;":a,a=isNaN(r)?this.info.unitsSpacing:a,this.obj_ini.html[e]=t,this.obj_ini.es[e]=a},this.QteSauvegarde=function(i){var e=this.docCookies.getItem(i),t=null!=e?e.split("|"):null;return null!=t&&null!=t[1]?parseInt(t[1]):0},this.StopDrop=function(i){(i=null!=i&&$(i).length?$(i):$(document)).on("drop dragover",function(i){i.preventDefault()})};var n=this;this.ImgPrevisualisation=function(i){function e(e,t){var r;switch(i.img_orientation=t,i.img_width=e.width,i.img_height=e.height,t){case 2:r="scaleX(-1)";break;case 3:r="rotate(180deg)";break;case 4:r="rotate(180deg) scaleX(-1)";break;case 5:r="rotate(270deg) scaleX(-1)",i.img_width=e.height,i.img_height=e.width;break;case 6:r="rotate(90deg)",i.img_width=e.height,i.img_height=e.width;break;case 7:r="rotate(90deg) scaleX(-1)",i.img_width=e.height,i.img_height=e.width;break;case 8:r="rotate(270deg)",i.img_width=e.height,i.img_height=e.width}var s=function(i,e){var t,r,s,a={};if(0!=n.config.imgPreviewMaxWidth&&0!=n.config.imgPreviewMaxHeight){var o=i/n.config.imgPreviewMaxWidth,c=e/n.config.imgPreviewMaxHeight,u=Math.max(o,c);r=e/u,s=i>(t=i/u)||e>r}else 0==n.config.imgPreviewMaxWidth&&0!=n.config.imgPreviewMaxHeight?(t=(r=n.config.imgPreviewMaxHeight)*(i/e),s=e>n.config.imgPreviewMaxHeight):0!=n.config.imgPreviewMaxWidth&&0==n.config.imgPreviewMaxHeight?(r=(t=n.config.imgPreviewMaxWidth)*(e/i),s=i>n.config.imgPreviewMaxWidth):(a.width=0,a.height=0);return s&&(a.width=t,a.height=r),a}(i.img_width,i.img_height),a=$();if(i.img_width==e.width&&i.img_height==e.height)$(e).css({width:s.width+"px",height:s.height+"px"}),r&&$(e).css("transform",r),i.upAbci.imgPreview.append($(e));else{a=$('<span style="position:relative;display:block;width:'+s.width+"px;height:"+s.height+'px"></span>');var o=Math.abs(s.width-s.height)/2;o=e.width>e.height?o:-o,$(e).css({width:s.height+"px",height:s.width+"px",position:"absolute",left:-o+"px",top:o+"px",transform:r}),i.upAbci.imgPreview.append(a.append($(e)))}"function"==typeof n.config.func_ImgPreviewLoadEach&&n.config.func_ImgPreviewLoadEach(a.length?a:$(e),i)}function t(i,e){var t=new FileReader;t.onload=function(i){var t=new DataView(i.target.result);if(65496!=t.getUint16(0,!1))return e(-2);for(var n=t.byteLength,r=2;r<n;){if(t.getUint16(r+2,!1)<=8)return e(-1);var s=t.getUint16(r,!1);if(r+=2,65505==s){if(1165519206!=t.getUint32(r+=2,!1))return e(-1);var a=18761==t.getUint16(r+=6,!1);r+=t.getUint32(r+4,a);var o=t.getUint16(r,a);r+=2;for(var c=0;c<o;c++)if(274==t.getUint16(r+12*c,a))return e(t.getUint16(r+12*c+8,a))}else{if(65280!=(65280&s))break;r+=t.getUint16(r,!1)}}return e(-1)},t.readAsArrayBuffer(i)}var r=new Image;n.config.imgPreviewUseFileReader?t(i.fichier,function(t){var n=new FileReader;n.onload=function(i){r.onload=function(){e(this,t)},r.src=n.result},n.readAsDataURL(i.fichier)}):t(i.fichier,function(t){r.src=n.win_url.createObjectURL(i.fichier),r.onload=function(){n.config.RevokeObjectURL&&n.win_url.revokeObjectURL(this.src),e(this,t)}})},this.func_ImgPreviewStartAll=function(){0==n.config.imgPreviewStartAuto&&$.each(n.tab_fichiers_change,function(i,e){1==e.img_prev_delayed&&n.ImgPrevisualisation(e)})},this.attrData=function(i,e){var t=i.attr("data-upabcicss-"+e);t&&i.css(this.GetCssData(t))},this.SetCssData=function(i,e){this.attrData(this.content_result,e),this.attrData(this.infos_serveur,e),this.attrData(i,e),i.find("*").each(function(){n.attrData($(this),e)})},this.SetCssDataAll=function(i){this.attrData(this.formulaire,i),this.formulaire.find("*").each(function(){n.attrData($(this),i)}),this.CR_not_include&&(this.attrData(this.content_result,i),this.content_result.find("*").each(function(){n.attrData($(this),i)}))},this.ArretFormate=function(i){null!=n.tab_fichiers_change[i.data.index]?n.tab_fichiers_change[i.data.index].stop=1:null!=n.tab_fichiers[i.data.index]&&(n.tab_fichiers[i.data.index].stop=1),i.data.qte_save>0?(n.SetCssData(i.data.infosFile,"backup"),n.SetCssData(i.data.infosFile,"result"),n.SetCssData(i.data.infosFile,"result-stop"),i.data.iteration>0&&n.SetCssData(i.data.infosFile,"result-partial")):(n.SetCssData(i.data.infosFile,"result"),n.SetCssData(i.data.infosFile,"result-stop")),null!=i.data.status&&0==i.data.errorUser&&i.data.status.html(n.info.status.stop);var e=!0;n.tab_fichiers_change.length&&($.each(n.tab_fichiers_change,function(i,t){if(0==t.stop)return e=!1,!1}),e&&n.SetCssDataAll("upload-end"))},this.ArreterToutAvantEnvoi=function(){n.stopAll.length&&n.tab_fichiers_change.length&&n.stopAll.one("click",function(){$.each(n.tab_fichiers_change,function(i,e){e.upAbci.stop&&e.upAbci.stop.off("click"),e.stop_all=1;var t={data:{}};t.data.infosFile=e.infosFile,t.data.status=e.upAbci.status,t.data.index=i,t.data.qte_save=n.QteSauvegarde(e.id_fich),t.data.errorUser=e.fichier.upabciErrorUser,t.data.iteration=e.iteration,n.ArretFormate(t)})})}};UploadAjaxABCI.prototype.Start=function(){if(this.formulaire=$(this.id_form),0==this.formulaire.length)return alert("Configuration UploadAjaxABCI : identifiant de formulaire non valide"),!1;if(""!=this.content_result_tag&&0==$(this.content_result_tag).length)return alert("UploadAjaxABCI : identifiant de renvoi des résultats non valide"),!1;if(void 0===window.FormData){var i=this.config.browserOutdeted,e=$(this.config.customFileSelect);return""!=$.trim(i)&&(this.formulaire.find('input[type="file"]').on("change",function(){return alert(i),!1}),this.formulaire.find('input[type="submit"]').on("click",function(){return alert(i),!1}),this.formulaire.find(e).on("click",function(){return alert(i),!1})),"function"==typeof this.config.func_BrowserOutdeted&&this.config.func_BrowserOutdeted(),!1}var t=this;this.data_files=this.formulaire.find('input[type="file"]'),this.custom_select_file=""!=$.trim(this.config.customFileSelect)?this.formulaire.find(this.config.customFileSelect):$(),this.content_result=$(this.content_result_tag),this.CR_not_include=!this.formulaire.find(this.content_result).length;var n=this.formulaire.find('input[name="UpAbci_uniqidForm"]');n=n.length?n.val():"",this.config.uniqidForm=""!=$.trim(n)?n:this.config.uniqidForm;var r=this.formulaire.find('input[name="UpAbci_fragmentSize"]');if(r=r.length?parseInt(r.val()):void 0,r=isNaN(r)||r<1048576?this.config.fragmentSize:r,this.config.fragmentSize=r-10240,"object"==typeof this.id_form&&(this.id_form=null!=this.id_form.attr("id")?this.id_form.attr("id"):this.id_form.attr("class")),this.id_form="string"==typeof this.id_form?this.id_form:"",1==this.config.BackupFormDependency&&""==this.id_form)return alert("Le formulaire ne comporte pas d'id ni de classe permettant de l'identifier.\n\nCette configuration n'est pas compatible avec l'option 'config.BackupFormDependency' qui vaut actuellement true.\n\nVoir le mode d'emploi avant de modifier cette option, ou ajouter une classe ou un id à votre formulaire."),!1;this.config.imgPreviewMaxSize=""!=$.trim(this.config.imgPreviewMaxSize)?1048576*parseInt(this.config.imgPreviewMaxSize):null,this.config.imgPreviewMaxSizeTotal=""!=$.trim(this.config.imgPreviewMaxSizeTotal)?1048576*parseInt(this.config.imgPreviewMaxSizeTotal):null,this.config.fileSizeMax=""!=$.trim(this.config.fileSizeMax)?this.ReturnOctets(this.config.fileSizeMax):null,this.config.filesExtensions=Array.isArray(this.config.filesExtensions)?$.map(this.config.filesExtensions,function(i){return(i=0===(i=$.trim(i)).indexOf(".")?i.substring(1):i).toLowerCase()}):[];var s,a=""!=$.trim(this.config.cssFileSelectOn)?this.GetCssData(this.config.cssFileSelectOn):{},o=Object.keys(a).length,c=""!=$.trim(this.config.cssSubmitOn)?this.GetCssData(this.config.cssSubmitOn):{},u=$(),f=[],l={},h=[];function p(i){i.stopPropagation(),i.preventDefault()}function m(i){var e=this.name;"function"==typeof t.config.func_FileSelectAllBefore&&t.config.func_FileSelectAllBefore(i,t.tab_fichiers_change);var n=i.data.index;p(i);var r=$(),s=document.createElement("div");t.content_result.length&&(t.content_result.empty().append(u),t.stopAll=t.content_result.find(".UpAbci_stopAll"),(r=t.content_result.find(".UpAbci_infosFile")).wrap(s),s=r.parent()),t.stopAll=t.stopAll.length?t.stopAll:t.formulaire.find(".UpAbci_stopAll"),s.empty(),h[n]=[];var a=!1;l[n]=0;var o=null!=t.data_drop?t.data_drop:this.files;$(o).each(function(i,r){r.upabciTypeImage=0,r.upabciErrorPreview=0,r.upabciErrorSize=0,r.upabciErrorExtension=0,r.upabciErrorUser=0,r.upabciInputName=e,r.type.match("image.*")&&(r.upabciTypeImage=1,null!=t.config.imgPreviewMaxSize&&r.size>parseInt(t.config.imgPreviewMaxSize)?(r.upabciErrorPreview=1,1==t.config.imgPreviewMaxSizeCancelAll&&(a=!0)):l[n]+=r.size),null!=t.config.fileSizeMax&&r.size>t.config.fileSizeMax&&(r.upabciErrorSize=1,r.upabciErrorUser++),null!=t.config.fileSizeMaxInput[e]&&1!=r.upabciErrorSize&&r.size>t.ReturnOctets(t.config.fileSizeMaxInput[e])&&(r.upabciErrorSize=1,r.upabciErrorUser++);var s=r.name.substr(r.name.lastIndexOf(".")+1).toLowerCase();if(t.config.filesExtensions.length&&-1==$.inArray(s,t.config.filesExtensions)&&(r.upabciErrorExtension=1,r.upabciErrorUser++),Array.isArray(t.config.filesExtensionsInput[e])&&t.config.filesExtensionsInput[e].length){var o=$.map(t.config.filesExtensionsInput[e],function(i){return(i=0===(i=$.trim(i)).indexOf(".")?i.substring(1):i).toLowerCase()});1!=r.upabciErrorExtension&&-1==$.inArray(s,o)&&(r.upabciErrorExtension=1,r.upabciErrorUser++)}h[n].push(r)});var c=0;$.each(l,function(i,e){i!=n&&(c+=e)}),c+=l[n];var f,m=[];$.each(t.tab_fichiers_change,function(i,t){(f=t.fichier.upabciInputName)!=e&&(m[f]=Array.isArray(m[f])?m[f]:[],m[f].push(t))}),t.tab_fichiers_change=[];var g=0,_={};return $.each(h,function(e,n){n&&$.each(n,function(e,n){var o,u,f,l={},h=$(),p=$(),d=$(),b=0,v=-1,A=0,S=[],F=1==t.config.BackupFormDependency?t.id_form+""+n.upabciInputName:"",C=t.SHA1(F.toString()+n.name.toString()+n.size.toString()),x=t.QteSauvegarde(C);Array.isArray(m[n.upabciInputName])&&$.each(m[n.upabciInputName],function(i,e){C==e.id_fich&&(b=e.stop)}),r.length&&(p=r.clone(!0),t.classes.each(function(i,e){if((d=p.find(".UpAbci_"+e)).length){switch(e){case"status":h=d;break;case"name":d.html(n.name);break;case"size":t.FormatIni(d,e),d.html(t.FormateOctets(n.size,t.obj_ini.es[e]));break;case"imgPreview":1==n.upabciTypeImage&&(a||null==t.win_url||(null==t.config.imgPreviewMaxSizeTotal||c<=t.config.imgPreviewMaxSizeTotal?0==n.upabciErrorPreview?(t.SetCssData(p,"image-preview"),0==t.config.imgPreviewStartAuto?v=1:(v=0,A=1)):t.SetCssData(p,"error-img-prev"):t.SetCssDataAll("error-img-prev-total")));break;case"progressionG":d.get(0).value=x,d.get(0).max=n.size;break;case"progressionT":t.FormatIni(d,e),x>0&&d.html(t.FormateOctets(x,t.obj_ini.es.progressionT));break;case"backup":t.FormatIni(d,e),x>0&&d.html(t.FormateOctets(x,t.obj_ini.es.backup));break;case"percentage":t.FormatIni(d,e),d.html(t.Pourcentage(x,n.size,t.obj_ini.es.percentage));break;case"duration":case"remainingTime":t.FormatIni(d,e);break;case"stop":0==n.upabciErrorUser&&$(d).one("click",{infosFile:p,status:h,index:g,qte_save:x,errorUser:n.upabciErrorUser,iteration:0},t.ArretFormate),1==b&&$(d).trigger("click");break;case"inputFileName":d.html(n.upabciInputName);break;case"inputInfosFileUnic":d.each(function(){null!=(u=$(this).attr("name"))&&(f=u.lastIndexOf("["),u=-1!=f?u.substr(0,f)+""+C+u.substr(f):u+""+C,$(this).attr("name",u))})}l[e]=d}}),n.upabciErrorUser>0&&(t.SetCssData(p,"error-user"),t.SetCssData(p,"result"),t.SetCssData(p,"result-error"),h.length&&(1==n.upabciErrorExtension&&S.push("extension"),1==n.upabciErrorSize&&S.push("taille"),o=t.AfficheErreur(S),h.html(o))),x>0&&t.SetCssData(p,"backup")),s.append(p),g++,_={fichier:n,upAbci:l,infosFile:p,id_fich:C,qte_save:x,qte_save_ini:x,qte_upload:0,time_start:0,time_end:0,result:"0_0",stop:b,stop_all:0,iteration:0,img_prev_delayed:v,img_width:0,img_height:0},1==A&&t.ImgPrevisualisation(_),t.tab_fichiers_change.push(_),"function"==typeof t.config.func_FileSelectEach&&t.config.func_FileSelectEach(i,_)})}),r.length&&t.content_result.find(".UpAbci_infosFile").unwrap(),t.ArreterToutAvantEnvoi(),t.tab_fichiers_change.length&&(t.SetCssDataAll("select-file"),"function"==typeof t.config.func_FileSelectAll&&t.config.func_FileSelectAll(i,t.tab_fichiers_change)),!1}function g(){t.data_files.each(function(i,e){$(this).on("change",{index:i},m)}),t.custom_select_file.length&&(1==t.config.customDragAndDrop&&t.StopDrop(),t.custom_select_file.each(function(i){$(this).off("click drop"),null!=t.data_files.eq(i)&&(1==t.config.customDragAndDrop&&$(this).on({drop:function(e){p(e);var n=e.originalEvent.dataTransfer.files;if(!n||0==n.length)return!1;0==t.stop_drop&&(t.data_drop=n,t.data_files.eq(i).change())},dragover:function(i){p(i)}}),$(this).on("click",function(e){p(e),t.data_drop=void 0,t.data_files.eq(i).click()}))}))}function _(i,e){var n;t.data_files.each(function(i,e){n=document.createElement("div"),$(this).wrap(n),n=$(this).parent(),$(this).remove(),n.append($(f[i])),n.find('input[type="file"]').unwrap()}),t.data_files=t.formulaire.find('input[type="file"]'),i&&(t.data_files.each(function(){o&&0==t.custom_select_file.length&&$(this).css(a),$(this).prop("disabled",!0).val("")}),"function"==typeof t.config.func_FormSubmit&&t.config.func_FormSubmit(e,t.tab_fichiers)),g()}function d(i){t.stop_drop=!0,t.requete=!0,t.iteration_form=0,h=[],l={},t.tab_fichiers=t.tab_fichiers_change,t.tab_fichiers_change=[],t.count_files=0,t.infos_serveur=t.formulaire.find(".UpAbci_infosServer"),t.infos_serveur=t.infos_serveur.length?t.infos_serveur:t.content_result.find(".UpAbci_infosServer"),t.bouton_submit=t.formulaire.find('input[type="submit"]');var e,n=t.content_result.find(":input");t.data_nofile=t.formulaire.find(":input").not(n),t.data_nofile_CR=n.not(t.content_result.find(".UpAbci_infosFile").find(":input")),t.data_nofile_serialize=t.data_nofile.serializeArray(),t.data_nofile_CR_serialize=t.data_nofile_CR.serializeArray(),$.each(t.tab_fichiers,function(i,n){e=n.infosFile.find(":input"),n.inputs_infosFile=e.serializeArray(),t.config.inputFormAttrOnSubmit&&e.each(function(){$(this).hasClass("UpAbci_stop")||$(this).attr(t.config.inputFormAttrOnSubmit,t.config.inputFormAttrOnSubmit).css("cursor","default")}),0==n.stop&&0==n.fichier.upabciErrorUser&&t.count_files++}),t.SetCssDataAll("submit"),t.count_files>0||t.config.submitWithoutFile?(t.config.inputFormAttrOnSubmit&&(t.data_nofile.each(function(){$(this).hasClass("UpAbci_stopAll")||$(this).attr(t.config.inputFormAttrOnSubmit,t.config.inputFormAttrOnSubmit)}),t.data_nofile_CR.each(function(){$(this).hasClass("UpAbci_stopAll")||$(this).attr(t.config.inputFormAttrOnSubmit,t.config.inputFormAttrOnSubmit).css("cursor","default")})),t.count_files>0&&t.SetCssDataAll("submit-file"),t.data_files.each(function(){o&&0==t.custom_select_file.length&&(t.css_envoi_select_ini.data_files=t.GetCssIni(a,$(this)))}),t.custom_select_file.each(function(){o&&t.custom_select_file.length&&(t.css_envoi_select_ini.custom_select_file=t.GetCssIni(a,$(this)),$(this).css(a)),$(this).prop("disabled",!0)}),t.bouton_submit.each(function(){Object.keys(c).length&&(t.css_envoi_select_ini.bouton_submit=t.GetCssIni(c,$(this)),$(this).css(c)),$(this).prop("disabled",!0)}),_(!0,i),t.Upload()):(_(!1),t.data_files.prop("disabled",!1).val(""),$.each(t.tab_fichiers,function(i,e){e.upAbci.stop&&e.upAbci.stop.off("click")}),t.stopAll.length&&t.stopAll.off("click"),t.SetCssDataAll("form-end"),0==t.config.submitWithoutFile&&1==t.config.submitWithoutFileFuncFormEnd&&"function"==typeof t.config.func_FormEnd&&t.config.func_FormEnd(t.tab_fichiers))}this.content_result.length&&(u=this.content_result.html()),t.data_files.each(function(){s=document.createElement("div"),$(this).wrap(s),s=$(this).parent(),f.push(s.html()),$(this).unwrap()}),g(),this.formulaire.on("submit",function(i){p(i),"function"==typeof t.config.func_onFormSubmit?(t.func_SubmitForm=function(i){d(i)},t.config.func_onFormSubmit(i,t.tab_fichiers_change)):d(i)})},UploadAjaxABCI.prototype.Upload=function(i,e,t){var n=null!=i?i:0,r=this.tab_fichiers[n],s=null!=e?e:0,a=null!=t?t:this.config.fragmentSize,o=0,c=null,u=0,f=0,l=this;if(null==r){if(!(0==this.count_files&&this.config.submitWithoutFile||1==this.config.queryFormEnd&&this.count_files>0)||!this.requete){if(this.stop_drop=!1,this.stopAll.length&&this.stopAll.off("click"),this.count_files>0){this.SetCssDataAll("upload-end");var h=!1,p=this;$.each(this.tab_fichiers,function(i,e){p.docCookies.hasItem(e.id_fich)&&(h=!0)}),h&&this.SetCssDataAll("backup-end")}return this.data_files.each(function(){$(this).prop("disabled",!1),null!=l.css_envoi_select_ini.data_files&&$(this).css(l.css_envoi_select_ini.data_files)}),this.bouton_submit.each(function(){$(this).prop("disabled",!1),null!=l.css_envoi_select_ini.bouton_submit&&$(this).css(l.css_envoi_select_ini.bouton_submit)}),this.custom_select_file.each(function(){$(this).prop("disabled",!1),null!=l.css_envoi_select_ini.custom_select_file&&$(this).css(l.css_envoi_select_ini.custom_select_file)}),l.config.inputFormAttrOnSubmit&&l.data_nofile.each(function(){$(this).prop(l.config.inputFormAttrOnSubmit,!1)}),this.SetCssDataAll("form-end"),"function"==typeof this.config.func_FormEnd&&this.config.func_FormEnd(this.tab_fichiers,this.retour_infos_server,this.retour_mixte_server),!1}this.requete=!1}else{if(0==s&&(1==r.stop||r.fichier.upabciErrorUser>0))return r.upAbci.stop&&r.upAbci.stop.off("click"),this.Upload(++n),!1;r.iteration++,c=r.id_fich,0==s&&0==r.stop?(r.infosFile&&this.SetCssData(r.infosFile,"in-progress"),r.upAbci.status&&r.upAbci.status.html(this.info.status.inProgress),(o=r.qte_save_ini)>0&&(!this.config.recoveryBackupConfirm||confirm(this.info.recoveryBackupConfirm.name+""+r.fichier.name+this.info.recoveryBackupConfirm.size+this.FormateOctets(o,this.obj_ini.es.backup)+this.info.recoveryBackupConfirm.message)?a=(s=o)+this.config.fragmentSize:(o=0,r.qte_save=0,r.qte_save_ini=0,this.docCookies.removeItem(c,this.config.cookiePath))),r.time_end=0,r.time_start=this.GetTime()):o=this.QteSauvegarde(c),r.qte_save=o,r.infosFile&&o>0&&this.SetCssData(r.infosFile,"backup"),a=a>r.fichier.size?r.fichier.size:a;var m,g=r.fichier.size>this.config.fragmentSize?1:0;m=o>0&&o==s&&s==a?1:1==g?r.fichier.slice(s,a):r.fichier;var _=a==r.fichier.size?1:0}function d(){var i=(l.GetTime()-r.time_start-(r.iteration-1)*l.config.ajaxTimeOut)/1e3,e=(r.qte_upload-r.qte_save_ini)/i;return a-r.qte_upload-e>0}function b(i){var e=null!=i?i:o;r.upAbci.backup.html(l.FormateOctets(e,l.obj_ini.es.backup))}function v(i){r.upAbci.percentage&&r.upAbci.percentage.html(l.Pourcentage(i,r.fichier.size,l.obj_ini.es.percentage))}function A(i,e){var t=(i-r.qte_save_ini)/e;return t>0?(r.fichier.size-i)/t:0}function S(i,e){var t,n=l.GetTime(),s=(n-r.time_start)/1e3,a=n/1e3;0==u&&(t=A(i,s))>l.config.remainingTimeDisplayAfter&&r.infosFile&&(l.SetCssData(r.infosFile,"remaining-time-after"),u=1),0==f&&s>l.config.remainingTimeCompute&&r.infosFile&&(l.SetCssData(r.infosFile,"remaning-time-compute"),f=1),(a-r.time_end>l.config.infosRefresh||null!=e)&&(r.time_end=a,r.upAbci.backup&&(null!=e?b(i):b()),v(i),r.upAbci.progressionT&&function(i){r.upAbci.progressionT.html(l.FormateOctets(i,l.obj_ini.es.progressionT))}(i),r.upAbci.duration&&r.time_start>0&&function(i){r.upAbci.duration.html(l.FormateTime(i,l.obj_ini.html.duration,l.obj_ini.es.duration))}(s),r.upAbci.remainingTime&&r.time_start>0&&function(i,e){var t=l.FormateTime(e,l.obj_ini.html.remainingTime,l.obj_ini.es.remainingTime);t=i>l.config.remainingTimeCompute?t:l.info.remainingTimeComputeWaiting,r.upAbci.remainingTime.html(t)}(s,t=null!=t?t:A(i,s)),"function"==typeof l.config.func_FileInProgressEach&&l.config.func_FileInProgressEach(r))}function F(i){r.upAbci.progressionG&&(r.upAbci.progressionG.get(0).value=i,r.upAbci.progressionG.get(0).max=r.fichier.size)}function C(i){var e=i.loaded+s;r&&(r.qte_upload=e,F(e),S(e))}var x=new FormData;return this.data_nofile.length&&$.each(this.data_nofile_serialize,function(i,e){"UpAbci_uniqidForm"!=e.name&&x.append(e.name,e.value)}),this.data_nofile_CR.length&&$.each(this.data_nofile_CR_serialize,function(i,e){"UpAbci_uniqidForm"!=e.name&&x.append(e.name,e.value)}),r?(null!=r.inputs_infosFile&&$.each(r.inputs_infosFile,function(i,e){"UpAbci_uniqidForm"!=e.name&&x.append(e.name,e.value)}),x.append("UpAbci_form",l.paramServer(r)),x.append("UpAbci_blobSlice",g),x.append("UpAbci_fileEnd",_),null!=r.join_file&&x.append("UpAbci_joinFile","string"==typeof r.join_file?r.join_file:""),x.append("UpAbci_fragment",m)):1==this.config.queryFormEnd?(this.query_end=!0,this.tab_fichiers.length?$.each(this.tab_fichiers,function(i,e){var t;(x.append("UpAbci_formEnd["+i+"]",l.paramServer(e)),null!=e.join_file&&x.append("UpAbci_joinFile["+i+"]","string"==typeof e.join_file?e.join_file:""),l.iteration_form--,null!=e.inputs_infosFile)&&$.each(e.inputs_infosFile,function(e,n){-1!=(t=n.name.lastIndexOf("[]"))?x.append("UpAbci_inputsInfosFile["+i+"]["+n.name.substr(0,t)+"][]",n.value):x.append("UpAbci_inputsInfosFile["+i+"]["+n.name+"]",n.value)})}):x.append("UpAbci_formEnd[0]",l.paramServer())):x.append("UpAbci_form",l.paramServer()),$.ajax({url:this.upload_serveur,type:"POST",data:x,xhr:function(){var i=$.ajaxSettings.xhr();return i.upload&&i.upload.addEventListener("progress",C,!1),i},processData:!1,contentType:!1,dataType:"json",beforeSend:function(i){!function(i){l.stopAll.length&&r&&1!=r.stop_all&&l.stopAll.off("click").one("click",function(){$.each(l.tab_fichiers,function(i,e){if(e.upAbci.stop&&e.upAbci.stop.off("click"),e.stop_all=1,i>=n){var t={data:{}};t.data.infosFile=e.infosFile,t.data.status=e.upAbci.status,t.data.index=i,t.data.qte_save=l.QteSauvegarde(e.id_fich),t.data.errorUser=e.fichier.upabciErrorUser,t.data.iteration=e.iteration,l.ArretFormate(t)}}),d()&&i.abort()})}(i),function(i){if(r){var e=r.upAbci.status?r.upAbci.status:$(),t=l.QteSauvegarde(c);1==r.stop?(S(t,"now"),r.upAbci.remainingTime&&r.upAbci.remainingTime.html(l.obj_ini.html.remainingTime),F(t),r.infosFile&&(t>0&&l.SetCssData(r.infosFile,"backup"),l.SetCssData(r.infosFile,"result"),l.SetCssData(r.infosFile,"result-stop"),t>0&&r.iteration>0&&l.SetCssData(r.infosFile,"result-partial")),r.upAbci.status&&0==r.fichier.upabciErrorUser&&r.upAbci.status.html(l.info.status.stop),r.upAbci.stop&&r.upAbci.stop.off("click"),i.abort()):r.upAbci.stop&&r.upAbci.stop.off("click").one("click",function(){if(r.stop=1,d()){var s={data:{}};s.data.infosFile=r.infosFile,s.data.status=e,s.data.index=n,s.data.qte_save=t,s.data.errorUser=r.fichier.upabciErrorUser,s.data.iteration=r.iteration,l.ArretFormate(s),i.abort()}})}}(i)}}).done(function(i){var e=null!=i.upabci_resultat?i.upabci_resultat:null,t=null!=i.upabci_erreur?i.upabci_erreur:"",o=null!=i.upabci_ok?i.upabci_ok:"";if(null!=i.upabci_infos_server?(l.SetCssDataAll("infos-server"),l.infos_serveur.length&&l.infos_serveur.html(i.upabci_infos_server),l.retour_infos_server=i.upabci_infos_server):l.retour_infos_server=void 0,null!=i.upabci_mixte_server?l.retour_mixte_server=i.upabci_mixte_server:l.retour_mixte_server=void 0,"continu"==e&&null==i.upabci_stop_form)s=a,a+=l.config.fragmentSize,setTimeout(function(){l.Upload(n,s,a)},l.config.ajaxTimeOut);else{if(r){if(r.upAbci.stop&&r.upAbci.stop.off("click"),r.upAbci.progressionT&&r.upAbci.progressionT.html(l.obj_ini.html.progressionT),r.upAbci.remainingTime&&r.upAbci.remainingTime.html(l.obj_ini.html.remainingTime),"upload_ok"==e)r.result="ok_done",r.qte_save=0,r.qte_upload=r.fichier.size,r.infosFile&&(l.SetCssData(r.infosFile,"result"),l.SetCssData(r.infosFile,"result-ok")),F(r.fichier.size),v(r.fichier.size),r.upAbci.status&&r.upAbci.status.html(l.info.status.ok+""+o),r.upAbci.backup&&r.upAbci.backup.html(l.obj_ini.html.backup);else if(l.docCookies.hasItem(c)){var u=l.QteSauvegarde(c);r.result="backup_done",r.qte_save=u,r.infosFile&&(l.SetCssData(r.infosFile,"backup"),l.SetCssData(r.infosFile,"result"),l.SetCssData(r.infosFile,"result-partial"),l.SetCssData(r.infosFile,"result-error")),r.upAbci.status&&r.upAbci.status.html(l.info.status.errorServer+""+t),S(u,"now"),r.upAbci.remainingTime&&r.upAbci.remainingTime.html(l.obj_ini.html.remainingTime),F(u)}else r.result="error_done",r.qte_save=0,r.infosFile&&(l.SetCssData(r.infosFile,"result"),l.SetCssData(r.infosFile,"result-error")),r.upAbci.status&&r.upAbci.status.html(l.info.status.errorServer+""+t),r.upAbci.backup&&r.upAbci.backup.html(l.obj_ini.html.backup),F(0),v(0);"function"==typeof l.config.func_FileEndEach&&l.config.func_FileEndEach(r,l.retour_infos_server,l.retour_mixte_server)}null!=i.upabci_stop_form?(0==i.upabci_stop_form&&(l.requete=!1),l.Upload(l.tab_fichiers.length)):setTimeout(function(){l.Upload(++n)},l.config.ajaxTimeOut)}}).fail(function(i,e,t){var s=null!=i.responseText&&!0===l.config.serverFatalErrorDisplay?i.responseText:"";if(r){if(r.upAbci.stop&&r.upAbci.stop.off("click"),r.upAbci.progressionT&&r.upAbci.progressionT.html(l.obj_ini.html.progressionT),r.upAbci.remainingTime&&r.upAbci.remainingTime.html(l.obj_ini.html.remainingTime),l.docCookies.hasItem(c)){var a=l.QteSauvegarde(c);r.result="backup_fail",r.qte_save=a,r.infosFile&&1!=r.stop&&(l.SetCssData(r.infosFile,"backup"),l.SetCssData(r.infosFile,"result"),l.SetCssData(r.infosFile,"result-partial"),l.SetCssData(r.infosFile,"result-error")),r.upAbci.status&&1!=r.stop&&r.upAbci.status.html(l.info.status.errorServer+""+s),S(a,"now"),r.upAbci.remainingTime&&r.upAbci.remainingTime.html(l.obj_ini.html.remainingTime),F(a)}else r.result="error_fail",r.qte_save=0,r.infosFile&&1!=r.stop&&(l.SetCssData(r.infosFile,"result"),l.SetCssData(r.infosFile,"result-error")),r.upAbci.backup&&1!=r.stop&&r.upAbci.backup.html(l.obj_ini.html.backup),r.upAbci.status&&1!=r.stop&&r.upAbci.status.html(l.info.status.errorServer+""+s);"function"==typeof l.config.func_FileEndEach&&l.config.func_FileEndEach(r,l.retour_infos_server,l.retour_mixte_server)}else 1==l.query_end&&(l.retour_infos_server=l.info.queryEndErrorServer+""+s,l.SetCssDataAll("infos-server"),l.infos_serveur.length&&l.infos_serveur.html(l.info.queryEndErrorServer+""+s));setTimeout(function(){l.Upload(++n)},l.config.ajaxTimeOut)}),!1},UploadAjaxABCI.prototype.Uniqid=function(i,e){var t;void 0===i&&(i="");var n=function(i,e){return e<(i=parseInt(i,10).toString(16)).length?i.slice(i.length-e):e>i.length?Array(e-i.length+1).join("0")+i:i};return this.php_js||(this.php_js={}),this.php_js.uniqidSeed||(this.php_js.uniqidSeed=Math.floor(123456789*Math.random())),this.php_js.uniqidSeed++,t=i,t+=n(parseInt((new Date).getTime()/1e3,10),8),t+=n(this.php_js.uniqidSeed,5),e&&(t+=(10*Math.random()).toFixed(8).toString()),t},UploadAjaxABCI.prototype.SHA1=function(i){function e(i,e){return i<<e|i>>>32-e}function t(i){var e,t="";for(e=7;e>=0;e--)t+=(i>>>4*e&15).toString(16);return t}var n,r,s,a,o,c,u,f,l,h=new Array(80),p=1732584193,m=4023233417,g=2562383102,_=271733878,d=3285377520,b=(i=function(i){i=i.replace(/\r\n/g,"\n");for(var e="",t=0;t<i.length;t++){var n=i.charCodeAt(t);n<128?e+=String.fromCharCode(n):n>127&&n<2048?(e+=String.fromCharCode(n>>6|192),e+=String.fromCharCode(63&n|128)):(e+=String.fromCharCode(n>>12|224),e+=String.fromCharCode(n>>6&63|128),e+=String.fromCharCode(63&n|128))}return e}(i)).length,v=new Array;for(r=0;r<b-3;r+=4)s=i.charCodeAt(r)<<24|i.charCodeAt(r+1)<<16|i.charCodeAt(r+2)<<8|i.charCodeAt(r+3),v.push(s);switch(b%4){case 0:r=2147483648;break;case 1:r=i.charCodeAt(b-1)<<24|8388608;break;case 2:r=i.charCodeAt(b-2)<<24|i.charCodeAt(b-1)<<16|32768;break;case 3:r=i.charCodeAt(b-3)<<24|i.charCodeAt(b-2)<<16|i.charCodeAt(b-1)<<8|128}for(v.push(r);v.length%16!=14;)v.push(0);for(v.push(b>>>29),v.push(b<<3&4294967295),n=0;n<v.length;n+=16){for(r=0;r<16;r++)h[r]=v[n+r];for(r=16;r<=79;r++)h[r]=e(h[r-3]^h[r-8]^h[r-14]^h[r-16],1);for(a=p,o=m,c=g,u=_,f=d,r=0;r<=19;r++)l=e(a,5)+(o&c|~o&u)+f+h[r]+1518500249&4294967295,f=u,u=c,c=e(o,30),o=a,a=l;for(r=20;r<=39;r++)l=e(a,5)+(o^c^u)+f+h[r]+1859775393&4294967295,f=u,u=c,c=e(o,30),o=a,a=l;for(r=40;r<=59;r++)l=e(a,5)+(o&c|o&u|c&u)+f+h[r]+2400959708&4294967295,f=u,u=c,c=e(o,30),o=a,a=l;for(r=60;r<=79;r++)l=e(a,5)+(o^c^u)+f+h[r]+3395469782&4294967295,f=u,u=c,c=e(o,30),o=a,a=l;p=p+a&4294967295,m=m+o&4294967295,g=g+c&4294967295,_=_+u&4294967295,d=d+f&4294967295}return(l=t(p)+t(m)+t(g)+t(_)+t(d)).toLowerCase()},UploadAjaxABCI.prototype.docCookies={getItem:function(i){return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*"+encodeURIComponent(i).replace(/[\-\.\+\*]/g,"\\$&")+"\\s*\\=\\s*([^;]*).*$)|^.*$"),"$1"))||null},setItem:function(i,e,t,n,r,s){if(!i||/^(?:expires|max\-age|path|domain|secure)$/i.test(i))return!1;var a="";if(t)switch(t.constructor){case Number:a=t===1/0?"; expires=Fri, 31 Dec 9999 23:59:59 GMT":"; max-age="+t;break;case String:a="; expires="+t;break;case Date:a="; expires="+t.toUTCString()}return document.cookie=encodeURIComponent(i)+"="+encodeURIComponent(e)+a+(r?"; domain="+r:"")+(n?"; path="+n:"")+(s?"; secure":""),!0},removeItem:function(i,e,t){return!(!i||!this.hasItem(i))&&(document.cookie=encodeURIComponent(i)+"=; expires=Thu, 01 Jan 1970 00:00:00 GMT"+(t?"; domain="+t:"")+(e?"; path="+e:""),!0)},hasItem:function(i){return new RegExp("(?:^|;\\s*)"+encodeURIComponent(i).replace(/[\-\.\+\*]/g,"\\$&")+"\\s*\\=").test(document.cookie)},keys:function(){for(var i=document.cookie.replace(/((?:^|\s*;)[^\=]+)(?=;|$)|^\s*|\s*(?:\=[^;]*)?(?:\1|$)/g,"").split(/\s*(?:\=[^;]*)?;\s*/),e=0;e<i.length;e++)i[e]=decodeURIComponent(i[e]);return i}};