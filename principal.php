
<?php session_start(); if ($_SESSION['inicio']==1) { 
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
  
	include("./includes/Conexion.php");
	include("./includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="imagenes/login/sigea.png";  
?> 
<!DOCTYPE html>
<html lang="es">
	<head>
	    <link rel="icon" type="image/gif" href="imagenes/login/sigea.ico">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="<?php echo $_SESSION['encode'];?>" />
		<title>Sistema de Gesti&oacute;n Escolar Administrativa</title>
	     
	     <link rel="stylesheet" type="text/css" href="easyUI/themes/bootstrap/easyui.css">
         <link rel="stylesheet" type="text/css" href="easyUI/themes/icon.css">       
        
         
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
		<link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="assets/css/jquery-ui.custom.min.css" />
		<link rel="stylesheet" href="assets/css/jquery.gritter.min.css" />
        <link href="imagenes/login/sigea.png" rel="image_src" />
		<link rel="stylesheet" href="assets/css/chosen.min.css" />
        <link rel="stylesheet" href="css/sigea.css" type="text/css" media="screen">


         
    
        <?php $misMenus=$miUtil->getMenus($_SESSION['usuario'],$_SESSION['super'],true);?>
        <script type="text/javascript">            
             data=<?php echo json_encode($misMenus);?>
        </script>
         <?php $permisos=""; 
         foreach ($misMenus as $row) {$permisos.=$row["modu_modulo"]."|";}
                   $_SESSION["permisos"]=$permisos;
          
         
		  $miConex = new Conexion();
		  
		
          $resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT IFNULL(a.EMPL_FOTO,'imagenes/menu/default.png') FROM pempleados a where a.EMPL_NUMERO='".$_SESSION['usuario']."'".
          		                                           " UNION  SELECT IFNULL(b.ALUM_FOTO,'imagenes/menu/default.png') from falumnos b where b.ALUM_MATRICULA='".$_SESSION['usuario']."'" );
           foreach ($resultado as $row) {
				   $logouser= $row[0];
				   if ($logouser==''){
					$logouser='imagenes/menu/default.png';
				   }
            }
            
            $logouser=str_replace('../../','',$logouser);

					
			$resultado=$miConex->getConsulta("SQLite","SELECT * from INSTITUCIONES where inst_clave='".$_SESSION["INSTITUCION"]."'");
			foreach ($resultado as $row) {$lamision= $row["inst_aux1"]; }		


           
         ?>
	</head>


	<body id="sigea" class="no-skin sigeaPrin" style="overflow-x:hidden; ">
		<div id="navbar" class="navbar navbar-default ace-save-state" >
			<div class="navbar-container ace-save-state" id="navbar-container">	
			<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" name="menu-toggler" data-target="#sidebar">
					<span class="sr-only"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
		    </button>			
				<div>
					<a href="#" class="navbar-brand" style="padding:0px; margin:5px;">
					   <small class="fontRobotoB"><img class="iconoMenu" src="imagenes/login/sigea.png" style="width:30px; height:30px;"> SiGEA</small>
					</a>
				</div>
				
				<?php $misNoti=$miUtil->getNotificaciones($_SESSION['usuario'],$_SESSION['super'],true);
				      $noti=0;
				      foreach ($misNoti as $row) {$noti++;}
				  ?>
				
				<div class="pull-right">
					<ul class="nav ace-nav">
					
					   <li class="purple dropdown-modal">
							<a data-toggle="dropdown" class="dropdown-toggle" href="#">
								<i class="ace-icon fa fa-bell icon-animated-bell"></i>
								<span id="numNoti" class="badge badge-important"><?php echo $noti;?></span>
							</a>

							<ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
								<li class="dropdown-header"><i class="ace-icon fa fa-exclamation-triangle"></i><?php echo $noti;?> Notificaciones</li>
								<li class="dropdown-content">
									<ul class="dropdown-menu dropdown-navbar navbar-pink">									
									    <?php foreach ($misNoti as $row) 									          
									    {      $oc="";$st="";
									    if (strlen($row["ENOT_ENLACE"])>0) {$st="cursor:pointer;"; $oc="onClick=\"abrirenlace('".$row["ENOT_ENLACE"]."','".$row["ENOT_TIPO"]."');\"";}
									         	echo "<li id=\"not_".$row["ENOT_ID"]."\"><div class=\"row\">".											                 
											                 "<div class=\"col-md-12\"><p style=\"line-height:15px; vertical-align:center; 
                                                                                                         text-align:justify; font-size:11px; ".$st."\"><span ".$oc.">".
																					   $row["ENOT_DESCRIP"].
														     "</span><i style=\"cursor:pointer; padding:0px;\" onclick=\"eliminarNoti('".$row["ENOT_ID"]."')\" class=\"fa fa-trash-o red\"></i></p></div>".
											            "</div>".											            
                                                    "</li>";} ?>																													
									</ul>
								</li>
							</ul>
						</li>
					   
					   
						<li class="light-blue dropdown-modal">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img class="imgRedonda" src="<?php echo $logouser; ?>" title="<?php echo $_SESSION["nombre"];?>" />
								<span class="user-info"></span>
								<i class="ace-icon fa fa-caret-down"></i>
							</a>
							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<li onclick="generales();"> <a><i class="ace-icon fa fa-cog"></i>Generales</a> </li>
								<li onclick="cambioClave();"> <a><i class="ace-icon fa fa-key"></i>Contrase&ntilde;a</a></li>
								<li class="divider"></li>
                                <li> <a href="cierraSesion.php?pag=index.php"><i class="ace-icon fa fa-power-off"></i>Cerrar Sesi&oacute;n</a></li>
							</ul>
						</li>
					</ul>
				</div>
			</div><!-- /.navbar-container -->
		</div>



		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript"> try{ace.settings.loadState('main-container')}catch(e){} </script>
			<div id="sidebar" class="sidebar responsive ace-save-state">
				<script type="text/javascript"> try{ace.settings.loadState('sidebar')}catch(e){} </script>				
				<ul id="miMenu" class="nav nav-list" ></ul> <!--*********************** MENU PRINCIPAL *************************-->
				<div class="sidebar-toggle sidebar-collapse"  id="sidebar-collapse">
					<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>
			</div>
			
			<div class="main-content sigeaPrin">
				<div class="main-content-inner sigeaPrin"> 		
					<div id="myTab"  style="width:100%; height:700px; overflow-y: hidden;" class="easyui-tabs sigeaPrin">
					       <div title="Inicio" style="padding:20px;">	


						   			<div class="row" id="prorroga"> </div>
						            <div class="widget-main">
						            <div class="row">
						                     <div class="col-xs-12" style="text-align: center;">
						                           <h2 class="text-warning fontAmaranthB" id="lacarrera"></h2>
						                     </div>
						            </div>

						   			<div class="row">
										     <div class="col-xs-5" style="border-left: 5px solid #B07D06; text-align:justify;">
										          <h1 class="text-primary fontRobotoB"><strong>Misión</strong></h1>
												     <h5 class="fontRoboto"><?php echo $lamision;?></h5>												  												  
													 
											 </div>
											 <div class="col-xs-2" style="text-align: center;"></div>
											 <div class="col-xs-5" style="text-align: center;">
											       <img alt="" src="imagenes/empresa/logo2.png" width="70%" height="70%">											
											 </div>
									</div>	 

									
										 <div class="row">
										     <div class="col-xs-5" style="border-left: 5px solid #009DE1;">
										          <h1 id="etlamision" class="text-success"></h1>
												  <h5  class="fontRoboto" style="text-align: justify;" id="lamision"></h5>
											 </div>
											 
											  <div class="col-xs-2" ></div>
											  
											  <div class="col-xs-5" style="border-left: 5px solid #089E2A;">
										          <h1 id="etlavision" class="text-danger"></h1>
												  <h5  class="fontRoboto" style="text-align: justify;" id="lavision"></h5>
											 </div>											 
										 </div>

										 <div class="row">
										     <div class="col-xs-5" style="border-left: 5px solid #009DE1;">
										          <h1 id="etobjetivo" class="text-success"></h1>
												  <h5  class="fontRoboto" style="text-align: justify;" id="objetivo"></h5>
											 </div>
											 
											  <div class="col-xs-2" ></div>
											  
											  <div class="col-xs-5" style="border-left: 5px solid #089E2A;">
										          <h1 id="etatributos" class="text-danger"></h1>
												  <h5  class="fontRoboto" style="text-align: justify;" id="atributos"></h5>
											 </div>											 
										 </div>


										 <div class="vspace-6-sm"></div>
										 
									</div>
						    </div>						    
					</div>
                 </div>
             </div>
             
				
	     </div>
				
            </div>
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->


         
		<script src="assets/js/jquery-2.1.4.min.js"></script>
         <script type="text/javascript"> 
		     if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>		
		<script src="assets/js/bootstrap.min.js"></script>	
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>
		<script src="assets/js/jquery-ui.custom.min.js"></script>
        <script src="assets/js/jquery-ui.min.js"></script>        
        <script type="text/javascript" src="easyUI/jquery.min.js"></script>
        <script type="text/javascript" src="easyUI/jquery.easyui.min.js"></script>
        <script type="text/javascript" src="js/utilerias.js"></script>
		<script src="assets/js/chosen.jquery.min.js"></script>
		<script src="js/subirArchivos.js?v=<?php echo date('YmdHis'); ?>"></script>        
		<!-- -------------------ultimos ----------------------->
	
		<script type="text/javascript" src="assets/js/jquery.validate.min.js"></script>
		<script src="js/utilerias.js?v=<?php echo date('YmdHis'); ?>"></script>



<script type="text/javascript">

		var usuario="<?php echo $_SESSION["usuario"]?>";
		var laip="<?php echo $_SESSION["laip"]?>";

		$(document).ready(function(){					
			co=Math.round(Math.random() * (999999 - 111111) + 111111); 
			parametros={cose:co}; $.ajax({type: "POST",url:  "nucleo/base/iniciaPincipal.php", data:parametros, 
				                          success: function(data){

											//alert ("dta;"+data+"::"+co+"="+sessionStorage.co+"=<?php echo $_SESSION["idsesion"]?>");
											
											elsql="SELECT CARR_DESCRIP,MISION,VISION, OBJETIVO, ATRIBUTOSEGRE from dashboard, ccarreras where  CARR_CLAVE=CARRERA AND CARRERA in ('"+carreras+"')";
										
											parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
											$.ajax({
													type: "POST",
													data:parametros,
													url:  "nucleo/base/getdatossqlSeg.php",
												success: function(data){  	
														//alert (" ha ocurrido un error" +data);							   	             
														jQuery.each(JSON.parse(data), function(clave, valor) { 
															
															$("#lacarrera").html("<strong>"+utf8Decode(valor.CARR_DESCRIP)+"</strong>");
															
															$("#etlamision").html("<strong>Misi&oacute;n</strong>");
															$("#lamision").html(utf8Decode(valor.MISION));

															$("#etlavision").html("<strong>Visi&oacute;n</strong>");
															$("#lavision").html(utf8Decode(valor.VISION));

															$("#etobjetivo").html("<strong>Objetivo Educacional</strong>");
															$("#objetivo").html(utf8Decode(valor.OBJETIVO));


															$("#etatributos").html("<strong>Atributos de Egreso</strong>");
															$("#atributos").html(utf8Decode(valor.OBJETIVO));

														});
														
														},
												error: function(data) {	                  
															alert('ERROR: '+data);
														}
													});	
					 
										  }});
			sessionStorage.setItem("co",co);

			carreras="<?php echo $_SESSION["carrera"]?>";

			
			

					 $("#sidebar").append("<div class=\"space-8\"></div><div class=\"md-form active-purple active-purple-2 mb-3\">"+				    
                    "<input id=\"filtrar\"  onkeyup=\"filtrarMenu();\"class=\"form-control\" type=\"text\" placeholder=\"Filtrar...\" aria-label=\"Search\">"+
				   "</div>");
				 		 
			 jQuery.each(data, function(clave, valor){				   
				   cad=""; cadsm="";
				   
				   laimg="menu-icon fa fa-caret-right";
				   if (valor.modu_imaico.length>0){laimg=valor.modu_imaico;}
				   
				   if (valor.modu_ejecuta=="1") {
					    laClase_a="opExec"; 
				        elclick="onclick=\"abrirPagina('"+valor.modu_modulo+"','"+valor.modu_pagina+"','"+valor.modu_descrip+"','"+valor.modu_automatico+"','"+valor.modu_bd+"');\"";
						laClase_b="arrow";
						laClaseli="liejecutable";
				        submenu="";}
				   else {laClase_a="dropdown-toggle"; 
				         elclick="";
				         submenu=" <b class='arrow fa fa-angle-down'></b>"; 
						 cadsm="<ul class='submenufil submenu'  id='S_"+valor.modu_modulo+"'></ul>";
						 laClaseli="limenu";				        	      
				   }

				   if (valor.modu_pred==" ") {estilo="menu-text"; padre="style=\"font-weight:bold;\""} else {estilo=""; padre="";} 
				   
				           cad="<li descrip='"+valor.modu_descrip+"' id='"+valor.modu_modulo+"' class='"+laClaseli+"'>\n"+
				                    "<a class='"+laClase_a+"' "+elclick+" style='cursor: pointer;'>\n"+
				                         "<i class='"+laimg+"'></i> \n"+
                                         "<span class='"+estilo+"' "+padre+">"+valor.modu_descrip+"</span>"+
                                         submenu+"\n"+
                                    "</a>\n"+
                                    cadsm+
                                 "</li> \n";
   						    
                            if (valor.modu_pred==" ") {$("#miMenu").append(cad);}
                            else {$("#S_"+valor.modu_pred).append(cad);} 
                                					          
				});
				
				
		//Verificamos si el alumnos tiene prorrogas
		cadSql="select count(*) AS HAY  from vprorrogas a where MATRICULA='"+usuario+"' AND CICLO=getciclo() AND AUTORIZADA='S' AND PAGADA='N'";

		parametros={sql:cadSql,dato:sessionStorage.co,bd:"Mysql"}
		$("#informacion").empty();		
		$.ajax({
				type: "POST",
				data:parametros,
				url:  "nucleo/base/getdatossqlSeg.php",
				success: function(data2){  
					   if (JSON.parse(data2)[0]["HAY"]>0) {
							var alto=$(window).height()+"px";
							url="nucleo/pa_prorrogas/grid.php"	;   	      	
							var content = '<iframe frameborder="0" id="FR'+"pa_prorrroga"+'" src="'+url+'" style="overflow-x:hidden;width:100%;height:'+alto+';"></iframe></div>';	
							$('#myTab').tabs('add',{
									title:"Prorroga",
								// href:"nucleo/grid.php?modulo="+modulo,
								content:content,
									closable:true		    
								});
							}

				}
			});

			//Grabamos el log 
			var hoy= new Date();
			lafecha=dameFecha("FECHAHORA");

    		parametros={tabla:"log",
			    bd:"Mysql",
			    _INSTITUCION:"<?php echo $_SESSION["INSTITUCION"];?>",
			    _CAMPUS:"<?php echo $_SESSION["CAMPUS"];?>",
			    USUARIO:usuario,
				FECHA:lafecha,
				IP:laip,
				DISPOSITIVO:navigator.userAgent,
				FECHA:lafecha
			};
			    $.ajax({
			 		  type: "POST",
			 		  url:"nucleo/base/inserta.php",
			 	      data: parametros,
			 	      success: function(data){ 
						
					   }					
					});

		});
	
		jQuery.expr[':'].contains = function(a, i, m) {
           return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
        };

       function filtrarMenu() {
				var input = $('#filtrar').val();
				var filter = input.toUpperCase();
				var contenidoMenu="";
				
				if (filter.length == 0) { // show all if filter is empty	
					    $("#miMenuFil").remove();
					    $("#miMenu").show();
					return;
				} else {														
					$("#miMenu").hide();
					$("#miMenuFil").remove();
					$("#sidebar").append("<div id=\"miMenuFil\" style=\"background-color:#FFFFFF;\" ></div>");
					$('li .liejecutable:contains("' + filter + '")').each(function() {					
					   $("#miMenuFil").append($(this).html()+"<br/>");
					});
					
				}
		}


		function existeUrl(url) {
			   var http = new XMLHttpRequest();
			   http.open('HEAD', url, false);
			   http.send();
			   return http.status!=404;
			}
		


		function cambioClave(){	
			   var url="modClave.php";
			   var alto=$(window).height()+"px";
			   var content = '<iframe scrolling = "auto"  frameborder="0" id="cambioClave" src="'+url+'" style="overflow-x:hidden;width:100%;height:'+alto+';"></iframe></div>';
			     $('#myTab').tabs('add',{
			    	    title:"Clave",
			    	    content:content,
			    	    closable:true		    
			    	});
			     $('html, body').animate({scrollTop:0}, 'slow');
				}


		function generales(){	
			   var url="generales.php";
			   var alto=$(window).height()+"px";
			   var content = '<iframe scrolling = "auto"  frameborder="0" id="cambioClave" src="'+url+'" style="overflow-x:hidden;width:100%;height:'+alto+';"></iframe></div>';	
			     $('#myTab').tabs('add',{
			    	    title:"Generales",
			    	   content:content,
			    	    closable:true		    
			    	});
			     $('html, body').animate({scrollTop:0}, 'slow');
				}
		
		
		function abrirPagina(modulo, pagina, nombre,automatico, bd){
	       if ($('#myTab').tabs('exists',nombre)){
		        $('#myTab').tabs('select', nombre);
	       } else {
					  restr=pagina; if (restr=="null") { restr='';}
	    	          var alto=($(window).height()-100)+"px";
	    	      	   
	    	      	   var url="nucleo/base/grid.php?modulo="+modulo+"&nombre="+nombre+"&padre="+"SIGEA&limitar=S"+"&automatico="+automatico+"&bd="+bd+"&restr="+restr;	    	      	 
					  
	    	      	   if (existeUrl("nucleo/"+modulo+"/grid.php?modulo="+modulo)) {
	    	      		  url="nucleo/"+modulo+"/grid.php?modulo="+modulo+"&nombre="+nombre+"&padre="+"SIGEA&limitar=N"+"&automatico="+automatico+"&bd="+bd+"&restr="+restr;
	    	      	   }
	    	      	  		
	    	          var content = '<iframe scrolling = "auto"  frameborder="0" id="FR'+modulo+'" src="'+url+'" style="overflow-x:hidden;width:100%;height:'+alto+';"></iframe></div>';	
				     $('#myTab').tabs('add',{
				    	    title:nombre,
				    	   // href:"nucleo/grid.php?modulo="+modulo,
				    	   content:content,
				    	    closable:true		    
				    	});
				     $('html, body').animate({scrollTop:0}, 'slow');
	       }

		}



		function abrirenlace(enlace,tipo) {
			 var alto=$(window).height()+"px";
				
             if (tipo=="P") {
			         var content = '<iframe scrolling = "auto"  frameborder="0" id="FRNoti" src="'+enlace+'" style="overflow-x:hidden;width:100%;height:'+alto+';"></iframe></div>';	
				     $('#myTab').tabs('add',{
				    	    title:'Notificacion',
				    	   // href:"nucleo/grid.php?modulo="+modulo,
				    	   content:content,
				    	    closable:true		    
				    	});
				     $('html, body').animate({scrollTop:0}, 'slow');
             }
             if (tipo=="V") {
            	  window.open(enlace, '_blank');

                 }

			}

		
		jQuery(function($) { 
	
			$(".opExec").click(function() {			
				if('ontouchstart' in document.documentElement) {  			         
					$("#menu-toggler").trigger('tap');              
					}
				else
				{  					
				    $("#menu-toggler").trigger('click');
				}

			});
			
		});


		function eliminarNoti(idnot){
			lafecha=dameFecha("FECHA");
			parametros={tabla:"enotivistas",
								bd:"Mysql",
								_INSTITUCION:"<?php echo $_SESSION['INSTITUCION'];?>",
								_CAMPUS:"<?php echo $_SESSION['CAMPUS'];?>",
								IDNOT:idnot,
								USUARIO:"<?php echo $_SESSION['usuario'];?>",								
								FECHA:lafecha};     
			  		$.ajax({
							type: "POST",
							url:"nucleo/base/inserta.php",
							data: parametros,
							success: function(data){ 		
								$("#not_"+idnot).remove();
								$("#numNoti").html(parseInt($("#numNoti").html()-1));
							}
						});

		}


		</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
