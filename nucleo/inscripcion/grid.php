<?php session_start(); if (($_SESSION['inicio']==1)) {
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
        <link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/jquery.gritter.min.css" />
        <link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/chosen.min.css" />

		<style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }</style>
		<style type="text/css">
		       table.dataTable tbody tr.selected {color: blue; font-weight:bold;}
			   table.dataTable tbody td {padding:4px;}
               th, td { white-space: nowrap; }        
        </style>
	</head>


	<body id="grid_<?php echo $_GET['modulo']; ?>" style="background-color: white;">
       
	      <div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>	      
                
		  <div class="widget-box widget-color-blue" id="principal">
			  <div class="widget-header widget-header-small" style="padding:0px;">
			      <div class="row" >		                    		
						<div id="losciclos" class="col-sm-2" >	

						</div>			
						<div id="lascarreras" class="col-sm-3">
						</div>       			 
						<div id="losgrupos" class="col-sm-4" >
						</div>
						<div class="col-sm-3" style="padding-top:14px;">		
						    		  
						   												
							<button title="Inscribe a todos los alumnos con las asignaturas cursadas" onclick="inscribir();" class="btn btn-white btn-purple  btn-round"> 
								<i class="ace-icon purple fa fa-save bigger-140"></i>Inscribir<span class="btn-small"></span>            
							</button>
							
						</div>
		            </div> 
		      </div>

              <div class="widget-body">
				   <div class="widget-main">
				       <div class="row">	
					       <div id="losalumnos" class="col-sm-6" style="overflow-x: scroll; height:320px;" >    
						   </div>
					       <div id="loshorarios" class="col-sm-6" style="overflow-x: scroll; height:320px;" >    
						   </div>
                       </div>
					</div>
					<div class="widget-footer bg-primary" style="padding-top:5px;">
					     <div class='row'>
						      <div class="col-sm-2">
								  <span class="text-white">No. Alumnos:  <span id="numAlum" class="badge badge-danger">0</span></span> <br/>								
								  <span class="text-white"><span id="elusuario" class="badge badge-warning">0</span></span> <br/>								
							  </div> 
							  <div class="col-sm-2">
							  </div> 

							  <div class="col-sm-2">
							        <div class="checkbox" style="padding:0px; margin: 0px;">
										 <label>
											   <input id="imprimirBoletaCheck" type="checkbox" class="ace ace-switch ace-switch-6" />
												<span class="lbl"> Imp. Bol.</span>
										</label>
									</div>
							  </div> 	
							  <div class="col-sm-3">							      
										<button title="Imprimir boleta de reinscripci??n" onclick="imprimeBoleta();" class="btn btn-white btn-purple  btn-round"> 
											<i class="ace-icon text-warning fa fa-clipboard bigger-140"></i>Boleta<span class="btn-small"></span>            
										</button>
							  </div>		

							   <div class="col-sm-3">							      
										<button title="Eliminar a los alumnos seleccionados Reinscripci??n" onclick="eliminar();" class="btn btn-white btn-purple btn-danger btn-round"> 
								           <i class="ace-icon red fa fa-trash-o bigger-140"></i>Eliminar<span class="btn-small"></span>            
							            </button>									
							  </div>				  
						</div>
					</div>
			   </div>
		</div>
	
<!-- ===============================VENTANA DE INFORMACION=================================================================-->
<div id="infoReins" class="modal fade" role="dialog" >
     <div class="modal-dialog modal-sm">
		   <div class="modal-content">
		        <div class="modal-header bg-primary">	
						 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
								 <span class="white">&times;</span>
						 </button>
						<span class="lead text-white">Informaci??n General</span>
				 </div>
				 <div class="modal-body">
				
	                     <div class="row">
						      <div class="col-sm-12">
						           <i class="ace-icon fa fa-user icon-animated-hand-pointer blue bigger-160"></i>	
								   <strong><span class="lighter text-success " id="elnombre"></span> </strong> 							       
							   </div>
						 </div>
						 <div class="row">
	                           <div class="col-sm-12">  
							       <i class="ace-icon fa fa-book icon-animated-hand-pointer green bigger-160"></i>	
								   <strong><span class="lighter text-info " id="lacarrerainfo"></span> </strong> 		                               
                               </div>	
						 </div>   
						 <div class="row">
						      <div class="col-sm-12">
						           <i class="ace-icon fa fa-file icon-animated-hand-pointer purple bigger-160"></i>	
								   <strong><span class="lighter text-success " id="elmapa"></span> </strong> 							       
							   </div>
						 </div>
						 <div class="row">
						      <div class="col-sm-12">
						           <i class="ace-icon fa fa-chevron-circle-right icon-animated-hand-pointer red bigger-160"></i>	
								   <strong><span class="lighter text-success " id="laespecialidad"></span> </strong> 
								   <strong><span class="lighter text-success " id="laespecialidadSIE"></span> </strong> 							       
							   </div>
						 </div>
						 <div class="row">   
							   <div class="col-sm-12" style="text-align: center;">
									<span class="btn btn-app btn-sm btn-light no-hover">
										 <span id="prom_cr" class="line-height-1 bigger-170 blue"></span><br />
										  <span class="line-height-1 smaller-90"> Prom.Rep. </span>
									</span>
									<span class="btn btn-app btn-sm btn-yellow no-hover">
									      <span id="prom_sr" class="line-height-1 bigger-170">  </span><br />
										  <span class="line-height-1 smaller-90"> Promedio </span>
									</span>										
															
							   </div>
						 </div>
						 <div class="row">   
							   <div class="col-sm-12" style="text-align: center;">
							        <span class="btn btn-app btn-sm btn-pink no-hover">
									      <span id="loscreditost"  class="line-height-1 bigger-170">  </span><br />
										  <span class="line-height-1 smaller-90"> Cr&eacute;ditos </span>
									</span>
									<span class="btn btn-app btn-sm btn-success  no-hover">
									      <span id="loscreditos" class="line-height-1 bigger-170">  </span><br />
										  <span class="line-height-1 smaller-90"> Cursados </span>
									</span>
							   </div>
						</div>
	                           
	                    <div class="space-10"></div>  
						<div class="row">   
						        <div class="col-sm-6">
										<span class="text-primary"><strong>Cred. Max.:</strong></span>
										<span class="pull-right badge badge-success" id="CMA">50</span>
						        </div>		
								<div class="col-sm-6">
										<span class="text-primary"><strong>Cred. Min.:</strong></span>
									    <span class="pull-right badge badge-info" id="CMI">50</span>
								</div>
						</div>
						<div class="space-10"></div>  
						<div class="row"> 
								<div class="col-sm-6">
										<span class="text-primary"><strong>Cred. 1 Rep.:</strong></span>
										<span class="pull-right badge badge-purple" id="C1R">30</span>
								</div>		
								<div class="col-sm-6">
										<span class=" text-primary"><strong>Cred. +1:</strong></span>
										<span class="pull-right badge badge-danger" id="CM1">20</span>							    
						        </div>
						</div>
						                 
                 </div><!-- /.modal-body -->
		   </div><!-- /.modal-content -->         
	 </div><!-- /.modal-dialog -->
</div>
			

		 							
<!-- -------------------Primero ----------------------->
<script src="<?php echo $nivel; ?>assets/js/jquery-2.1.4.min.js"></script>
<script type="<?php echo $nivel; ?>text/javascript"> if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");</script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap.min.js"></script>

<!-- -------------------Segundo ----------------------->
<script src="<?php echo $nivel; ?>assets/js/jquery-ui.custom.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/chosen.jquery.min.js"></script>

<!-- -------------------Medios ----------------------->
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
<script src="<?php echo $nivel; ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-timepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/moment.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/daterangepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-colorpicker.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.knob.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/autosize.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.inputlimiter.min.js"></script>
<script src="<?php echo $nivel; ?>js/mask.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-tag.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootstrap-select.js"></script>

<!-- -------------------ultimos ----------------------->
<script src="<?php echo $nivel; ?>assets/js/ace-elements.min.js"></script>
<script type="text/javascript" src="<?php echo $nivel; ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo $nivel; ?>js/subirArchivos.js"></script>
<script src="<?php echo $nivel; ?>js/utilerias.js?v=<?php echo date('YmdHis'); ?>"></script>
<script src="<?php echo $nivel; ?>assets/js/jquery.jqGrid.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/grid.locale-en.js"></script>
<script src="<?php echo $nivel; ?>assets/js/bootbox.js"></script>

<script src="<?php echo $nivel; ?>assets/js/jquery.gritter.min.js"></script>

<script src="<?php echo $nivel; ?>assets/js/jquery.easypiechart.min.js"></script>
<script src="<?php echo $nivel; ?>nucleo/inscripcion/inscripcion.js?v=<?php echo date('YmdHis'); ?>"></script>

</body>
<?php } else {header("Location: index.php");}?>
</html>


