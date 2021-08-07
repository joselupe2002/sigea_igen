<!-- ====================================================================================
       GRID MODIFICADO DE CUSUARIOS, SE LE A�ADE UNA VENTA MODAL PARA LOS PERMISOS DE USUARIO 
  ====================================================================================
   -->


<?php session_start(); if (($_SESSION['inicio']==1)  && (strpos($_SESSION['permisos'],$_GET["modulo"])) ){ 
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";  
	$nivel="../../";
?> 
<!DOCTYPE html>
<html lang="es">
	<head>
	    <link rel="icon" type="image/gif" href="imagenes/login/sigea.ico">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="<?php echo $_SESSION['encode'];?>" />
		<title><?php echo $_SESSION["titApli"];?></title>
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/fonts.googleapis.com.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ace-rtl.min.css" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="<?php echo $nivel; ?>estilos/preloader.css" type="text/css" media="screen">         
        <link href="imagenes/login/sigea.png" rel="image_src" />
        <link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/ui.jqgrid.min.css" />

        <style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }</style>
	</head>


	<body id="grid_<?php echo $_GET['modulo']; ?>" style="background-color: white;">
	     <div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>

        <div class="main-content"  style="margin-left: 10px; margin-right: 10px; width: 98%;">
          <div class="row">
		     <div class="col-xs-12"> 
                <div class="row"> 
                     <div class="col-sm-4"> 
                            <div class="clearfix">
					            <div class="pull-left tableTools-container"></div>
					        </div>
				     </div>	
				     <div class="col-sm-1"><span class="text-success">**</span></div>
				     <div class="col-sm-1"> 
					        <div class=""> 
					        <?php 
					            $proc=$miUtil->getProcesos($_SESSION['usuario'],$_SESSION['super'],$_GET['modulo']);  
					            $tieneProc='N';
					            if (!(empty($proc[0]))) {
					            	$tieneProc='S';
					            	echo "<button data-toggle=\"dropdown\" class=\"btn btn-info btn-white dropdown-toggle\">
								          Procesos <span class=\"ace-icon fa fa-caret-down icon-only\"></span>
								          </button>
                                          <ul class=\"dropdown-menu dropdown-info dropdown-menu-right\">";
					            	
					                foreach ($proc as $row) {
					                	echo "<li><a onclick=\"".$row["proc_proceso"]."('".$_GET['modulo']."','"
				                                                                         .$_SESSION['usuario']."','"
				                                                                         .$_SESSION['super']."');\">".$row["proc_descrip"]."</a></li>";
					                }
					                echo "</ul>";
					            }
					            
					        ?> 
 
					        </div>					  
				     </div>	
				     
				 </div>	
				 <div id="tablaind"> 
                    
                </div>			   
	                   
           </div> <!--  De la tabla  -->
        </div> <!--  De la columna  -->
    </div> <!--  De la fila  -->
  </div> <!--  Del contenedor principal  -->
  
  <?php 		
			 $botones=$miUtil->getPermisos($_SESSION['usuario'],$_SESSION['super'],$_GET['modulo']);  	
			 
			 $laTablaGraba=$miUtil->getTablaModuloGraba($_GET['modulo']);			 
			 $laTabla=$miUtil->getTablaModulo($_GET['modulo']);
			 $campoLlave=$miUtil->getCampoLlave($laTabla); 

			    		        
   ?>

<!-- DIALOGO PARA LOS PERMISOS DE USUARIOS -->     
<div class="modal fade" id="modalPermisos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
    <div class="modal-dialog modal-lg"  role="document">
	     <div class="modal-content">
	           <div class="modal-header">
	              <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
	                   <span aria-hidden="true">&times;</span>
	              </button>
                  <button title="Guardar todos los cambios" type="button" class="btn btn-white btn-warning btn-bold" onclick="guardarPermisos();">Guardar Cambios</button>	              
	           </div>
              <div id="frmdocumentos" class="modal-body"  style="height:380px;">
                   <div class="widget-box widget-color-green2">
		              <div class="widget-header"><h4 class="widget-title lighter smaller">Permisos</h4></div>
                      <div class="widget-body" style=" max-height: calc(100vh - 210px); overflow-y: auto;">
					          <div class="main-container ace-save-state" id="main-container">
			                       <div id="sidebar" style="width:100%;" class="sidebar  ace-save-state">			
				                       <ul  style="width: 100%" id="miMenu" class="nav nav-list" ></ul>
			                       </div>
	                          </div>
		               </div>
	              </div>
                  
		   	              
             </div>
       </div>
  </div>
</div>



<!-- DIALOGO DE ESPERA -->     
<div id="dlgproceso" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 50%;">
     <div class="modal-content" style="vertical-align: middle;">
         <div class="modal-header" style="text-align: center;"> <p style="font-size: 16px; color: green; font-weight: bold;"> Procesando espere por favor..</p></div>
         <div class="modal-body" style="text-align: center;">
              <img src="../../imagenes/menu/esperar.gif" style="background: transparent; width: 100px; height: 80px"/>	
         </div>     
     </div>
     </div>
</div>

 
        
<script src="<?php echo $nivel; ?>assets/js/jquery-2.1.4.min.js"></script>
<script type="<?php echo $nivel; ?>text/javascript"> if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");</script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery-ui.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.dataTables.bootstrap.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/dataTables.buttons.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.flash.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.html5.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.print.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/buttons.colVis.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/dataTables.select.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.jqGrid.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/grid.locale-en.js"></script>



<!-- -------------------Medios ----------------------->


<script src="<?php echo $nivel; ?>assets/js/jquery.inputlimiter.min.js"></script>
<script src="<?php echo $nivel; ?>js/mask.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-tag.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.jqGrid.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/grid.locale-en.js"></script>


<!-- -------------------ultimos ----------------------->
<script src="<?php echo $nivel; ?>assets/js/ace-elements.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace.min.js"></script>
<script type="text/javascript" src="<?php echo $nivel; ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo $nivel; ?>js/sha/sha512.js"></script>
<script src="<?php echo $nivel; ?>js/utilerias.js"></script>


<?php if ($tieneProc=='S') {?>
    <script src="<?php echo $nivel; ?>nucleo/<?php echo $_GET["modulo"];?>/proc_<?php echo $_GET["modulo"];?>.js"></script>
<?php }?>

<script type="text/javascript">
        var loscamposf=""; var losdatosf=""; 
        <?php $loscamposf=""; $losdatosf=""; ?>

		$(document).ready(function($) { var Body = $('body'); Body.addClass('preloader-site');});
		$(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});


		jQuery(function($) { 
			
			   $(".input-mask-horario").mask("99:99-99:99",{placeholder:"hh:mm-hh:mm"});
			
			
			   var colsql="";
		       var columnas;
		       var col=[];
		       var obj;



		    mostrarEspera("esperaDatos","grid_CUSUARIOS","Cargando la información solicitada");
			 $.ajax({
	               type: "GET",
	               url: "../base/ind_getCampos.php?tabla=<?php echo $laTabla;?>",
	               success: function(data){  
		                
	                    col=[];
	                    columnas=JSON.parse(data);
	                    var i=0;


	                    $("#tablaind").empty();
	                    $("#botones").empty();
	                    $("#tablaind").append("<select class=\"form-control input-sm\" id=\"id_campo\">");
	                   

						
	                    $("#id_campo").empty();
	                    $("#id_campo").append("<option value=\"-1\">Todos</option>");
	                    jQuery.each(columnas, function(clave, valor){	
	                         colsql+=valor.colum_name+",";   
	                         obj={"title":valor.comments}
	                         $("#id_campo").append("<option value=\""+i+"\">"+valor.colum_name+"</option>");
	                         col[i]=obj;
	                         i++;                      
	                        });              
	                    colsql=colsql.substring(0,colsql.length-1);

	                    //$('#mitabla').DataTable().destroy();
	                    
	                    $("#tablaind").append("<table id=\"G_<?php echo $_GET["modulo"]; ?>\" class=\"table table-condensed table-striped table-sm table-bordered table-hover\">"); 
	                   

	            	
	                    //console.log("../base/getdatossqlSeg.php?sql=SELECT "+colsql+" FROM "+$("#indicador").val());
						
						parametros={
							modulo:"<?php echo $_GET['modulo'];?>",
							bd:"<?php echo $_GET['bd'];?>",
							//loscamposf:loscamposf,
							losdatosf:losdatosf,
							limitar:"<?php echo $_GET['limitar'];?>",
							dato:sessionStorage.co						
						}

	                    $.ajax({
							type: "POST",
							data:parametros,
	                        url:  "../base/getdatossqlfiltro.php",
	                        success: function(data){ 
								
	                        	 losdatos=JSON.parse(data);

	                        	 

	                        	 var myTable = $('#G_<?php echo $_GET["modulo"]; ?>').DataTable( {
	                     			bAutoWidth: false,  
	                     			 data: losdatos,
	                                 columns:col,          		
	                     			"aaSorting": [],
	                     			"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
	                     				             
	                                 },				
	                     			select: {style: 'single'},
	                     			"language": {
	                     				"emptyTable":			"No hay datos disponibles en la tabla.",
	                     				"info":		   			"Del _START_ al _END_ de _TOTAL_ ",
	                     				"infoEmpty":			"Mostrando 0 registros de un total de 0.",
	                     				"infoFiltered":			"(filtrados de un total de _MAX_ registros)",
	                     				"infoPostFix":			"(actualizados)",
	                     				"lengthMenu":			"Mostrar _MENU_ registros",
	                     				"loadingRecords":		"Cargando...",
	                     				"processing":			"Procesando...",
	                     				"search":				"Buscar:",
	                     				"searchPlaceholder":	"Dato para buscar",
	                     				"zeroRecords":			"No se han encontrado coincidencias.",
	                     				"paginate": {
	                     					"first":			"Primera",
	                     					"last":				"�ltima",
	                     					"next":				"Siguiente",
	                     					"previous":			"Anterior"
	                     				},
	                     				"aria": {
	                     					"sortAscending":	"Ordenaci�n ascendente",
	                     					"sortDescending":	"Ordenaci�n descendente"
	                     				}
	                     			},

	                     	    } );

	                        	 $.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';

	                             
	                 			new $.fn.dataTable.Buttons( myTable, {
	                 				buttons: [	                 				
	                 					  
	                 					{
	                 						"extend": "colvis",
	                 						"text": "<i title='Columnas' class='fa fa-search bigger-110 blue'></i>",
	                 						"className": "btn btn-white btn-primary btn-bold",
	                 						 columns: ':not(:first):not(:last)'
	                 					  },
	                 					{
	                 					    "extend": "copy",
	                 						"text": "<i title='Copiar Datos' class='fa fa-copy bigger-110 pink'></i>",
	                 						"className": "btn btn-white btn-primary btn-bold"
	                 				     },
	                 					{
	                 				        "extend": "csv",
	                 				        "charset": "UTF-8",
	                 						"text": "<i title='Exportar a Excel' class='glyphicon glyphicon-export bigger-110 orange'></i>",
	                 						"className": "btn btn-white btn-primary btn-bold"
	                 					},

	                 					<?php if (($botones[0]=='S') && ($_GET["automatico"]=='S')) {?>		   					 			
	                 					  {
	                 							"extend": "",
	                 							"text": "<i title='Insertar Registro' class='fa fa-plus-circle blue bigger-110 red'></i>",
	                 							"className": "btn btn-white btn-primary btn-bold",
	                 							"action": function ( e, dt, node, config ) {
	                 								  insertar();
	                 				                }
	                 				      },
	                 				      <?php }?>
	                 				      <?php if (($botones[1]=='S') && ($_GET["automatico"]=='S')) {?>	
	                 				      {
	                 							"extend": "",
	                 							"text": "<i title='Editar Registro' class='fa fa-edit  green bigger-110 red'></i>",
	                 							"className": "btn btn-white btn-primary btn-bold",
	                 							"action": function ( e, dt, node, config ) {
	                 								  modificar();
	                 				                }
	                 				      },
	                 				      <?php }?>
	                 				      <?php if (($botones[2]=='S') && ($_GET["automatico"]=='S')) {?>
	                 				      {
	                 							"extend": "",
	                 							"text": "<i title='Eliminar Registro' class='fa fa-trash red bigger-110 red'></i>",
	                 							"className": "btn btn-white btn-primary btn-bold",
	                 							"action": function ( e, dt, node, config ) {
	                 								  eliminar();
	                 				                }
	                 				      },
	                 				      <?php }?>			    

		                 					
	                 					
	                 			      {
	                 			  	        "extend": "print",
	                 						"text": "<i title='Imprimir datos' class='fa fa-print bigger-110 grey'></i>",
	                 						"className": "btn btn-white btn-primary btn-bold",
	                 						autoPrint: false,
	                 						message: 'SIGEA'
	                 			      }	
	                 			  	
	                 					  	 
	                 				]
	                 			} );
	                 			myTable.buttons().container().appendTo( $('.tableTools-container') );

	                 			$(".dataTables_filter").append(id_campo);

	                			//Mensaje del copiado de datos del Grid
	                			var defaultCopyAction = myTable.button(1).action();
	                			myTable.button(1).action(function (e, dt, button, config) {
	                				defaultCopyAction(e, dt, button, config);
	                				$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
	                			});
	                			

	                			//Columnas que se desean visualizar en el grid 
	                			var defaultColvisAction = myTable.button(0).action();
	                			myTable.button(0).action(function (e, dt, button, config) {
	                				
	                				defaultColvisAction(e, dt, button, config);
	                				
	                				if($('.dt-button-collection > .dropdown-menu').length == 0) {
	                					$('.dt-button-collection')
	                					.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
	                					.find('a').attr('href', '#').wrap("<li />")
	                				}
	                				$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
	                			});

	                                       
	                			$('.dataTables_filter input').unbind().bind('keyup', function() {
	             				   var searchTerm = this.value.toLowerCase()
	             				   if (!searchTerm) {
	             				      myTable.draw()
	             				      return
	             				   }
	             				   $.fn.dataTable.ext.search.pop(); 
	             				   
	             				   opb=searchTerm.substr(0,2);
	             				   opa=searchTerm.substr(0,1);   
	             				 
	             				   $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {	
	             					   
	             					   if ($('#id_campo').prop('value')=="-1") {ini=0; fin=data.length-1;}
	             					   else  {ini=$('#id_campo').prop('value'); fin=$('#id_campo').prop('id');}				 
	             				       for (var i=ini;i<=fin;i++) {
	             					     if ((opa=="=") ||(opa==">") ||(opa=="<") ||  (opb==">=")|| (opb=="<=")||(opb=="<=")||(opb=="!=")) {						     
	             					    	 if ((opa=="=")) {if (data[i].toLowerCase() == searchTerm.substr(1,searchTerm.length)) return true}
	             					    	 if ((opb==">=")) {if (parseFloat(data[i].toLowerCase()) >= parseFloat(searchTerm.substr(2,searchTerm.length))) return true}
	             					    	 if ((opa==">")) {if (parseFloat(data[i].toLowerCase()) > parseFloat(searchTerm.substr(1,searchTerm.length))) return true}
	             					    	 if ((opa=="<")) {if (parseFloat(data[i].toLowerCase()) < parseFloat(searchTerm.substr(1,searchTerm.length))) return true}
	             					    	 
	             					    	 if ((opb=="<=")) {if (parseFloat(data[i].toLowerCase()) <= parseFloat(searchTerm.substr(2,searchTerm.length))) return true}
	             					    	 if ((opb=="!=")) {if (!(data[i].toLowerCase() == searchTerm.substr(2,searchTerm.length))) return true}
	             						     }
	             					     else
	             					    	 if (data[i].toLowerCase().indexOf(searchTerm)>=0) return true				        
	             				      }
	             				      return false
	             				   });
	             				   
	             				   myTable.draw();   
	             				   
	             				   
	             				});      
	 
								  $('#dlgproceso').modal("hide");   
								  ocultarEspera("esperaDatos");           
	                        },
	                         error: function(data) {
	                             $('#dlgproceso').modal("hide");      
	                             alert('ERROR: '+data);
	                         }
	                    });
	            },
	            error: function(data) {
	               $('#dlgproceso').modal("hide");      
	               alert('ERROR: '+data);
	            }
	          }); 	
	 
		});

		function modificar(){
			

			ruta="../base/editaReg.php?modulo=<?php echo $_GET["modulo"]?>&restr=<?php echo $_GET['restr']?>&bd=<?php echo $_GET['bd']?>&limitar=<?php echo "N";?>&automatico=<?php echo $_GET['automatico']?>&nombre=<?php echo $_GET["nombre"]?>&tablagraba=<?php echo $laTablaGraba;?>&tabla=<?php echo $laTabla;?>&campollave=<?php echo $campoLlave; ?>&gridpropio=S&loscamposf="+loscamposf+"&losdatosf="+losdatosf+"&valorllave=";//El valor llave se coloca m�s abajo este debe ser siempre el ultimo parametros
		   <?php                  
	             if (file_exists("../".$_GET["modulo"]."/editaReg.php")) {?>
	                 ruta="<?php echo "../".$_GET["modulo"]."/editaReg.php"?>?modulo=<?php echo $_GET["modulo"]?>&restr=<?php echo $_GET['restr']?>&bd=<?php echo $_GET['bd']?>&limitar=<?php echo "N";?>&automatico=<?php echo $_GET['automatico']?>&nombre=<?php echo $_GET["nombre"]?>&tablagraba=<?php echo $laTablaGraba;?>&tabla=<?php echo $laTabla;?>&campollave=<?php echo $campoLlave; ?>&gridpropio=S&loscamposf="+loscamposf+"&losdatosf="+losdatosf+"&valorllave=";  //El valor llave se coloca m�s abajo este debe ser siempre el ultimo parametros
	       <?php }?>
	              
			
			var table = $('#G_<?php echo $_GET['modulo']?>').DataTable();
			if (table.rows('.selected').data().length>0) {
				 $('#dlgproceso').modal({backdrop: 'static', keyboard: false});
				 location.href=ruta+table.rows('.selected').data()[0][0]; 
				}
			else {
				alert ("Debe seleccionar un registro");

				}
		}

		  function eliminar(){
			   
			    var table = $('#G_<?php echo $_GET['modulo']?>').DataTable();
			    if (table.rows('.selected').data().length>0) {
			    	  if(confirm("Seguro que desea eliminar el registro: "+table.rows('.selected').data()[0][0])) {
			    			$('#dlgproceso').modal({backdrop: 'static', keyboard: false});			    		
			    		        
			    		  var parametros = {
			    	                "tabla" : "<?php echo $laTablaGraba;?>",
			    	                "campollave" : "<?php echo $campoLlave;?>",
			    	                "bd":"<?php echo $_GET["bd"];?>",
			    	                "valorllave" : table.rows('.selected').data()[0][0]
			    	        };
			    	        
			    		  $.ajax({
				  	            data:  parametros,
				  	            url:   '../base/eliminar.php',
				  	            type:  'post',          
				  	            success:  function (response) {
				  	            	$('#dlgproceso').modal("hide");
				  	            	alert(response);
				  	            	location.href ="grid.php?modulo=<?php echo $_GET['modulo'];?>&bd=<?php echo $_GET['bd']?>&limitar=<?php echo "N";?>&automatico=<?php echo $_GET['automatico']?>&nombre=<?php echo $_GET['nombre'];?>";
			    		        }		
				  	       }); 
			    	  }    
					}
				else {
					alert ("Debe seleccionar un registro a eliminar");
					}
				}      

				

		function insertar() {
			  $('#dlgproceso').modal({backdrop: 'static', keyboard: false});	
               <?php                  
               $url="../base/nuevoReg.php?modulo=".$_GET["modulo"]."&restr=".$_GET['restr']."&bd=".$_GET["bd"]."&limitar=N"."&automatico=".$_GET["automatico"]."&nombre=".$_GET["nombre"]."&tablagraba=".$laTablaGraba."&tabla=".$laTabla."&loscamposf=".$loscamposf."&losdatosf=".$losdatosf."&gridpropio=S";
               if (file_exists("../".$_GET["modulo"]."/nuevoReg.php")) {$url="../".$_GET["modulo"]."/nuevoReg.php?modulo=".$_GET["modulo"]."&restr=".$_GET['restr']."&bd=".$_GET["bd"]."&limitar=N"."&automatico=".$_GET["automatico"]."&loscamposf=".$loscamposf."&losdatosf=".$losdatosf."gridpropio=S";}?>
                 location.href="<?php echo $url;?>";       
                       
			}


		</script>


	</body>
<?php } else {header("Location: index.php");}?>
</html>
