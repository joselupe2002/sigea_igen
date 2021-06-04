
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
        <link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/bootstrap-editable.min.css" />
        <link rel="stylesheet" href="<?php echo $nivel; ?>assets/css/chosen.min.css" />

        <style type="text/css">table.dataTable tbody tr.selected {color: blue; font-weight:bold; }</style>
	</head>


	<body id="grid_<?php echo $_GET['modulo']; ?>" style="background-color: white;">
	    <div class="preloader-wrapper"><div class="preloader"><img src="<?php echo $nivel; ?>imagenes/menu/preloader.gif"></div></div>
	    
    <div class="main-content"  style="margin-left: 10px; margin-right: 10px; width: 98%;">
		 <div id="user-profile-1" class="user-profile row">
		      <div class="col-xs-12 col-sm-3 center">
				   <div>
					   <span class="profile-picture">
					        <img id="img_ALUM_FOTO"  style="width: 150px; height: 170px;" class="editable img-responsive" src=""/>
					   </span>
    				   <div class="space-4"></div>
                      
 	    		       <input class="fileSigea" type="file" id="file_ALUM_FOTO" name="file_ALUM_FOTO" 
 	    		             onchange="subirArchivoDriveName('file_ALUM_FOTO','ALUM_FOTO','img_ALUM_FOTO','ALUM_FOTO','jpeg|png|JPG|jpg','S','<?php echo $_SESSION["usuario"];?>')">
 	    		       
                        <input type="hidden" value=""  name="ALUM_FOTO" id="ALUM_FOTO" placeholder="" />   	
						<button  onclick="guardarCampo('ALUM_FOTO',true,'La Foto fue asignada correctamente');" class="btn btn-white btn-info btn-bold">
				                 <i class="ace-icon green fa fa-camera bigger-160"></i><span class=" fontRobot bigger-100">Asignar Foto</span>            
				        </button>

					</div>

					<div class="space-6"></div>
                       
														  
							  <div class="hr hr12 dotted"></div>
                                   <div class="clearfix">
                                        
								   </div>
							       <div class="hr hr16 dotted"></div>
								   <div class="clearfix">
								        <button  onclick="mikardex();" class="btn btn-white btn-info btn-bold">
				                            <i class="ace-icon green fa fa-book bigger-160"></i><span class=" fontRobot btn-lg">Mi Kardex</span>            
				                        </button>
								   </div>
							   </div>

							   <div class="col-xs-12 col-sm-9">
								    <div class="center">
								         <span class="btn btn-app btn-sm btn-light no-hover">
								             <span id="periodos" class="line-height-1 bigger-170 blue"></span>
								             <br />
										     <span class="line-height-1 smaller-90"> Periodo </span>
										 </span>

										 <span class="btn btn-app btn-sm btn-yellow no-hover">
											   <span class="line-height-1 bigger-170" id="ALUM_CICLOINS"> </span><br />
											   <span class="line-height-1 smaller-90"> Ingreso </span>
										 </span>

										 <span onclick="verMaterias('A');" class="btn btn-app btn-sm btn-purple no-hover">
											   <span class="line-height-1 bigger-170" id="promedio"> </span><br />
											   <span class="line-height-1 smaller-90"> Promedio </span>
										 </span>

										 <span onclick="verMaterias('R');" class="btn btn-app btn-sm btn-pink no-hover">
											   <span class="line-height-1 bigger-170" id="reprobadas"> </span><br />
											   <span class="line-height-1 smaller-90"> # Rep </span>
										 </span>


									  </div>

									<div class="space-12"></div>
										<div class="tabbable">		
											<ul class="nav nav-tabs" id="myTab">
													<li class="active">
														<a data-toggle="tab" href="#generales">
															<i class="green ace-icon fa fa-user bigger-120"></i>
															Generales
														</a>
													</li>

													<li>
														<a data-toggle="tab" href="#pesdireccion">
														<i class="blue ace-icon fa fa-map-marker bigger-120"></i>
															Dirección													
														</a>
													</li>	


													<li>
														<a data-toggle="tab" href="#laboral">
														<i class="blue ace-icon fa fa-truck bigger-120"></i>
															Laboral													
														</a>
													</li>	

													<li>
														<a data-toggle="tab" href="#pesotros">
														<i class="pink ace-icon fa fa-group bigger-120"></i>
															Padres													
														</a>
													</li>	

													<li>
														<a data-toggle="tab" href="#pestutor">
														<i class="red ace-icon fa fa-male bigger-120"></i>
															Tutor													
														</a>
													</li>	

											</ul>	
											
											<div class="tab-content">
												<div id="generales" class="tab-pane fade in active">
													<div class="profile-user-info profile-user-info-striped">
																								
														<div class="profile-info-row"><div class="profile-info-name">Matricula</div>
															<div class="profile-info-value"><i class="fa fa-user light-orange bigger-110"></i>
																<span id="matricula"></span>
															</div>
														</div>
														
														<div class="profile-info-row"><div class="profile-info-name">Nombre</div>
															<div class="profile-info-value"><i class="fa fa-user light-orange bigger-110"></i>
																<span id="nombreal">Nombre de la persona</span>
															</div>
														</div>


														<div class="profile-info-row"><div class="profile-info-name">Estado Nac.</div>
															<div class="input-group" id="elestadonac">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Municipio Nac.</div>
															<div class="input-group" id="elmuninac">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>
														
														

														<div class="profile-info-row"><div class="profile-info-name">e-mail</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="correo" id="correo" type="text" />
															</div>
														</div>
																										
													
														
														<div class="profile-info-row"><div class="profile-info-name">Carrera </div>
															<div class="profile-info-value"><i class="fa fa-gears light-orange bigger-110"></i>
																<span class="editable" id="carrera"></span>
															</div>
														</div>

														<div title="Esta contraseña solo será usada la primera vez que ingrese a la plataform Moodle" class="profile-info-row"><div class="profile-info-name">Moodle </div>
															<div class="profile-info-value"><i class="fa fa-key light-red bigger-110"></i>
																<span class="text text-success" id="moodle"><b>Temporal2020</b></span>
															</div>
														</div>														
													</div>
												</div> <!--  Del contenido del primer tab -->

												<div id="pesdireccion" class="tab-pane fade">
													<div class="profile-user-info profile-user-info-striped">
														<div class="profile-info-row"><div class="profile-info-name">Estado Res.</div>
															<div class="input-group" id="elestadores">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Municipio Res.</div>
															<div class="input-group" id="elmunires">
																	<span title="Municipio donde resides" class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Localidad Res.</div>
															<div class="input-group" id="lalocalidadres">
																	<span title="" class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Colonia</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_COLONIA" id="ALUM_COLONIA" type="text" />
															</div>
														</div>



														<div class="profile-info-row"><div class="profile-info-name">Direcci&oacute;n</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="direccion" id="direccion" type="text" />
															</div>
														</div>


														<div class="profile-info-row"><div class="profile-info-name">Tel&eacute;fono</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control input-mask-phone" name="telefono" id="telefono" type="text" id="form-field-mask-2" />
															</div>
														</div>		
													</div>																								
												</div><!--  Del contenido del segundo tab -->


												<div id="pesotros" class="tab-pane fade">
													<div class="profile-user-info profile-user-info-striped">
														<div class="profile-info-row"><div class="profile-info-name">Gpo Indigena</div>
															<div class="input-group" id="elgrupo">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Lengua Indig</div>
															<div class="input-group" id="lalengua">
																	<span title="Municipio donde resides" class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														
														<div class="profile-info-row"><div class="profile-info-name">No. IMSS</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_NOSEGURO" id="ALUM_NOSEGURO" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">¿Padre Vive?</div>
															<div class="input-group" id="elpadre">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>															
														</div>

														<div class="profile-info-row"><div class="profile-info-name">¿Madre Vive?</div>
															<div class="input-group" id="lamadre">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>															
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Nombre Padre</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_PADRE" id="ALUM_PADRE" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Nombre Madre</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_MADRE" id="ALUM_MADRE" type="text" />
															</div>
														</div>

	
													</div>																								
												</div><!--  Del contenido del tercer tab -->


												<div id="pestutor" class="tab-pane fade">
													<div class="profile-user-info profile-user-info-striped">
														<div class="profile-info-row"><div class="profile-info-name">Nombre Tutor</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTOR" id="ALUM_TUTOR" type="text" />
															</div>
														</div>
													
														<div class="profile-info-row"><div class="profile-info-name">Estado Res.</div>
															<div class="input-group" id="elestadotut">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Municipio Res.</div>
															<div class="input-group" id="elmunitut">
																	<span title="Municipio donde resides" class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Localidad Res.</div>
															<div class="input-group" id="lalocalidadtut">
																	<span title="" class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Dirección</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTORDIR" id="ALUM_TUTORDIR" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Cod. Postal</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTORCP" id="ALUM_TUTORCP" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Colonia</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTORCOL" id="ALUM_TUTORCOL" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Teléfono</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTORTEL" id="ALUM_TUTORTEL" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Correo</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTORCORREO" id="ALUM_TUTORCORREO" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Centro Trabajo</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="ALUM_TUTORTRABAJO" id="ALUM_TUTORTRABAJO" type="text" />
															</div>
														</div>

	
													</div>																								
												</div><!--  Del contenido del CUARTO tab -->


												<div id="laboral" class="tab-pane fade">
													<div class="profile-user-info profile-user-info-striped">		

														<div class="profile-info-row"><div class="profile-info-name">Trabajo</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="trabajo" id="trabajo" type="text" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Tel. Trabajo</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>																	
																	<input class="form-control input-mask-phone" name="teltrabajo" id="teltrabajo" type="text" id="form-field-mask-2" />
															</div>
														</div>

														<div class="profile-info-row"><div class="profile-info-name">Dirección</div>
															<div class="input-group">
																	<span class="input-group-addon"><i class="ace-icon fa fa-pencil red"></i></span>
																	<input class="form-control" name="dirtrabajo" id="dirtrabajo" type="text" />
															</div>
														</div>
														
													</div>
												</div>
											</div> <!--  Deltab content -->

										 <div class="space-20"></div>
										 
										 <div style="text-align: center;">
				                               <button  onclick="guardar();" class="btn  btn-bold btn-danger" value="Agregar" >
				                               <i class="ace-icon white fa fa-save bigger-200"></i><span class="btn-lg">Guardar Cambios Realizados</span>            
				                               </button>
				                         </div>
											
										 

			                   </div> <!--  De la segunda columna del row  -->
	    </div><!--  Del profile  -->
    </div> <!--  Del contenedor principal  -->
  
 
 
 <div class="modal fade" id="modalDocument" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> 
      <div class="modal-dialog modal-lg "  role="document">
		   <div class="modal-content">
		         <div class="modal-header  widget-header  widget-color-green">	
		                <span class="label label-lg arrowed arrowed-right" id="leyendap" >Historial de Asignaturas <span id="leyenda"></span></span> 
		                	   
		                <button type="button" class="close" data-dismiss="modal" aria-label="Cancelar">
		                     <span aria-hidden="true">&times;</span>
		                </button>	
		          </div>
                  <div id="frmdocumentos" class="modal-body" style="overflow-y: auto; height:350px;" class="modal-body">                  
		                  <div class="timeline-container">
								    <div class="timeline-label"><span class="label label-primary arrowed-in-right label-lg"><b>Historial</b></span></div>															                        
			                        <div id="lositems" class="timeline-items">									     									     
										
									 </div> <!-- time contenedor de los items -->										 
						   </div> <!-- time contenedor principal  de los items -->                       
		          </div> <!-- del modal-body -->     
		     </div>
      </div>
</div> 
 
 

 
<script src="<?php echo $nivel; ?>js/subirArchivos.js?v=<?php echo date('YmdHis'); ?>"></script>       
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
<script src="<?php echo $nivel; ?>assets/js/chosen.jquery.min.js"></script>



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
<script src="<?php echo $nivel; ?>assets/js/bootstrap-editable.min.js"></script>
<script src="<?php echo $nivel; ?>assets/js/ace-editable.min.js"></script>


<script src="<?php echo $nivel; ?>js/utilerias.js?v=<?php echo date('YmdHis'); ?>"></script>


<script type="text/javascript">
   $(document).ready(function($) { var Body = $('body'); Body.addClass('preloader-site');});
   $(window).load(function() {$('.preloader-wrapper').fadeOut();$('body').removeClass('preloader-site');});



   function guardarCampo(campo,conmsj,mensaje) {
	if (campo=='ALUM_FOTO') {parametros={ALUM_FOTO:$("#ALUM_FOTO").val(),tabla:"falumnos",campollave:"ALUM_MATRICULA",valorllave:"<?php echo $_SESSION['usuario'];?>",bd:"Mysql"};}
	if (campo=='ALUM_CORREO') {parametros={ALUM_CORREO:$("#correo").val(),tabla:"falumnos",campollave:"ALUM_MATRICULA",valorllave:"<?php echo $_SESSION['usuario'];?>",bd:"Mysql"};}
	if (campo=='ALUM_TELEFONO') {parametros={ALUM_TELEFONO:$("#telefono").val(),tabla:"falumnos",campollave:"ALUM_MATRICULA",valorllave:"<?php echo $_SESSION['usuario'];?>",bd:"Mysql"};}
	if (campo=='ALUM_DIRECCION') {parametros={ALUM_DIRECCION:$("#direccion").val(),tabla:"falumnos",campollave:"ALUM_MATRICULA",valorllave:"<?php echo $_SESSION['usuario'];?>",bd:"Mysql"};}
	

	$('#dlgproceso').modal({backdrop: 'static', keyboard: false});	         
	   $.ajax({
		        type: "POST",
		        url:"../base/actualiza.php",
		    	data: parametros,
		    	success: function(data){		    				
		    		$('#dlgproceso').modal("hide");  			                                	                      
		    		if (!(data.substring(0,1)=="0")){ if (conmsj){alert (mensaje);}}	
		    		else {alert ("OCURRIO EL SIGUIENTE ERROR: "+data);}          					           
		           }					     
		      });          
}
   
   $(document).ready(function($) {

		$('.input-mask-phone').mask('(999)9999999');

	   $('.fileSigea').ace_file_input({
			no_file:'Sin archivo ...',
			btn_choose:'Buscar',
			btn_change:'Cambiar',
			droppable:false,
			onchange:null,
			thumbnail:false, //| true | large
			whitelist:'png|jpg|JPG|jpeg',
			blacklist:'exe|php'
			//onchange:''
			//
		});
		

	 //editables on first profile page
		$.fn.editable.defaults.mode = 'inline';
		$.fn.editableform.loading = "<div class='editableform-loading'><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
	    $.fn.editableform.buttons = '<button class="btn btn-info editable-submit"><i class="ace-icon fa fa-check"></i></button>'+
	                                '<button class="btn editable-cancel"><i class="ace-icon fa fa-times"></i></button>';    
		
		//editables 
	
	

		elsql="SELECT alum_matricula, getPeriodos(ALUM_MATRICULA,getciclo()) as PERIODOS, alum_foto,concat(alum_nombre,' ',alum_apepat,' ',alum_apemat) as alum_nombrec,alum_direccion, alum_telefono, alum_correo, "+
		             " ALUM_EDONAC, ALUM_ESTADO, ALUM_MUNINAC, ALUM_MUNICIPIO, ALUM_LOCALIDAD, ALUM_PADRE, ALUM_MADRE, ALUM_PADREVIVE, ALUM_MADREVIVE,"+
					 "ALUM_TUTOR, ALUM_TUTORESTADO, ALUM_TUTORMUNICIPIO, ALUM_TUTORDIR, ALUM_TUTORLOC, ALUM_TUTORCP, ALUM_TUTORCOL, ALUM_TUTORDIR, ALUM_TUTORTRABAJO, ALUM_TUTORTEL, ALUM_TUTORCORREO,"+
					 " ALUM_COLONIA, ALUM_NOSEGURO, LENIND, GPOIND, CARR_DESCRIP AS alum_carreraregd, alum_cicloins, getcuatrialum(alum_matricula, getciclo()) AS CUAT,"+
                     " alum_correo AS CORREO,alum_telefono AS TEL, alum_tutor AS TUTOR, ALUM_TRABAJO, ALUM_TELTRABAJO, ALUM_DIRTRABAJO "+
					 " FROM falumnos, ccarreras  WHERE alum_matricula='<?php echo $_SESSION['usuario'];?>' and ALUM_CARRERAREG=CARR_CLAVE";
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		   $.ajax({
			   type: "POST",
			   data:parametros,
		       url:  "../base/getdatossqlSeg.php",
		       success: function(data){  
		    	      losdatos=JSON.parse(data);		    	      		    	    		    	        
		    	      jQuery.each(losdatos, function(clave, valor) { 
		    	    	
		    	    	//text editable		    	  	   
		    	  	    $('#nombreal').html(valor.alum_nombrec);		    	  	  
		    	  	    $('#ALUM_CICLOINS').html(valor.alum_cicloins);

		    	  	    $('#promedio').html(valor.PROM_SR);
						$('#periodos').html(valor.PERIODOS);
		    	  	    $('#reprobadas').html(valor.NUMREP);
		    	  	   		    	
						  
						$('#direccion').val(valor.alum_direccion);
		    	  	    $('#telefono').val(valor.alum_telefono);
						$('#correo').val(valor.alum_correo);
						  
					
						$('#trabajo').val(valor.ALUM_TRABAJO);	
						$('#teltrabajo').val(valor.ALUM_TELTRABAJO);
						$('#dirtrabajo').val(valor.ALUM_DIRTRABAJO);
						
		    	  	     $('#matricula').html(valor.alum_matricula);
						  $('#carrera').html(valor.alum_carreraregd);					
						  $('#img_ALUM_FOTO').attr("src",valor.alum_foto);
						  $('#ALUM_FOTO').val(valor.alum_foto);

						  elsql="SELECT id_estado, estado from cat_estado order by estado";
						  addSELECT_CONVALOR("ALUM_EDONAC","elestadonac","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_EDONAC);  	

						  elsql="SELECT id_municipio, municipio from cat_municipio where id_estado='"+valor.ALUM_EDONAC+"' order by municipio";
						  addSELECT_CONVALOR("ALUM_MUNINAC","elmuninac","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_MUNINAC); 

						  elsql="SELECT id_estado, estado from cat_estado order by estado";
						  addSELECT_CONVALOR("ALUM_ESTADO","elestadores","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_ESTADO);  	

						  elsql="SELECT id_municipio, municipio from cat_municipio where id_estado='"+valor.ALUM_ESTADO+"' order by municipio";
						  addSELECT_CONVALOR("ALUM_MUNICIPIO","elmunires","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_MUNICIPIO); 

						  elsql="SELECT id_localidad, localidad from cat_localidad where id_estado='"+valor.ALUM_ESTADO+"' and id_municipio='"+valor.ALUM_MUNICIPIO+"' order by localidad";
						  addSELECT_CONVALOR("ALUM_LOCALIDAD","lalocalidadres","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_LOCALIDAD); 

						  elsql="SELECT idgrupo, descrip from grupoindigena  order by idgrupo";
						  addSELECT_CONVALOR("GPOIND","elgrupo","PROPIO",elsql, "","BUSQUEDA",valor.GPOIND); 

						  elsql="SELECT idlengua, descrip from lenguaindigena  order by idlengua";
						  addSELECT_CONVALOR("LENIND","lalengua","PROPIO",elsql, "","BUSQUEDA",valor.LENIND); 
		    	    	 
						  $('#ALUM_NOSEGURO').val(valor.ALUM_NOSEGURO);
						  $('#ALUM_COLONIA').val(valor.ALUM_COLONIA);

						  elsql="SELECT 'S', 'S' FROM DUAL UNION SELECT 'N','N' FROM DUAL ";
						  addSELECT_CONVALOR("ALUM_PADREVIVE","elpadre","PROPIO",elsql, "","",valor.ALUM_PADREVIVE); 

				
						  elsql="SELECT 'S', 'S' FROM DUAL UNION SELECT 'N','N' FROM DUAL ";
						  addSELECT_CONVALOR("ALUM_MADREVIVE","lamadre","PROPIO",elsql, "","",valor.ALUM_MADREVIVE);
						  $('#ALUM_PADRE').val(valor.ALUM_PADRE);
						  $('#ALUM_MADRE').val(valor.ALUM_MADRE);

						  elsql="SELECT id_estado, estado from cat_estado order by estado";
						  addSELECT_CONVALOR("ALUM_TUTORESTADO","elestadotut","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_TUTORESTADO);  	

						  elsql="SELECT id_municipio, municipio from cat_municipio where id_estado='"+valor.ALUM_ESTADO+"' order by municipio";
						  addSELECT_CONVALOR("ALUM_TUTORMUNICIPIO","elmunitut","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_TUTORMUNICIPIO); 

						  elsql="SELECT id_localidad, localidad from cat_localidad where id_estado='"+valor.ALUM_TUTORESTADO+"' and id_municipio='"+valor.ALUM_TUTORMUNICIPIO+"' order by localidad";
						  addSELECT_CONVALOR("ALUM_TUTORLOC","lalocalidadtut","PROPIO",elsql, "","BUSQUEDA",valor.ALUM_TUTORLOC); 


						  $('#ALUM_TUTOR').val(valor.ALUM_TUTOR);
						  $('#ALUM_TUTORDIR').val(valor.ALUM_TUTORDIR);
						  $('#ALUM_TUTORCP').val(valor.ALUM_TUTORCP);
						  $('#ALUM_TUTORCOL').val(valor.ALUM_TUTORCOL);
						  $('#ALUM_TUTORTEL').val(valor.ALUM_TUTORTEL);
						  $('#ALUM_TUTORCORREO').val(valor.ALUM_TUTORCORREO);
						  $('#ALUM_TUTORTRABAJO').val(valor.ALUM_TUTORTRABAJO);

		    	      });		    
		
		
		             },
		       error: function(data) {	                  
		                  alert('ERROR: '+data);
		              }
		});


	  //Cargando el promedio 
	      $('#promedio').html("<img width=\"25px\" height=\"25px\" src=\"../../imagenes/menu/preloader.gif\">");
		  $('#reprobadas').html("<img width=\"25px\" height=\"25px\" src=\"../../imagenes/menu/preloader.gif\">");
		  elsql="SELECT "+
		             " getpromedio (alum_matricula,'N') as PROM_SR, "+
		             " getNumVecRep (alum_matricula) as NUMREP "+		                      
					 " FROM pvalumnos_cb  WHERE alum_matricula='<?php echo $_SESSION['usuario'];?>'";
		   parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		   $.ajax({
			   type: "POST",
			   data:parametros,
		       url:  "../base/getdatossqlSeg.php",
		       success: function(data){  
		    	      losdatos=JSON.parse(data);
		    	      jQuery.each(losdatos, function(clave, valor) { 
		    	    	
		    	  	    $('#promedio').html(valor.PROM_SR);
		    	  	    $('#reprobadas').html(valor.NUMREP);
		    	    	 
		    	      });		    
		             },
		       error: function(data) {	                  
		                  alert('ERROR: '+data);
		              }
		});//fin del promedio
   });// Fin del ready 



function change_SELECT(elemento) {
	if (elemento=='ALUM_EDONAC') {
		elsql="SELECT id_municipio, municipio from cat_municipio where id_estado='"+$("#ALUM_EDONAC").val()+"' order by municipio";
		actualizaSelect("ALUM_MUNINAC",elsql,"BUSQUEDA","");}

	if (elemento=='ALUM_ESTADO') {
		elsql="SELECT id_municipio, municipio from cat_municipio where id_estado='"+$("#ALUM_ESTADO").val()+"' order by municipio";
		actualizaSelect("ALUM_MUNICIPIO",elsql,"BUSQUEDA","");}
	
	if (elemento=='ALUM_LOCALIDAD') {
		elsql="SELECT id_localidad, localidad from cat_localidad where id_estado='"+valor.ALUM_ESTADO+"' and id_municipio='"+valor.ALUM_MUNICIPIO+"' order by localidad";
		actualizaSelect("ALUM_LOCALIDAD",elsql,"BUSQUEDA","");}	
}  

function guardar(){

	
     parametros={
		    	tabla:"falumnos",
		    	campollave:"ALUM_MATRICULA",
		    	valorllave:"<?php echo $_SESSION['usuario'];?>",
		    	bd:"Mysql",
		    	ALUM_DIRECCION:$("#direccion").val(),
		    	ALUM_TELEFONO:$("#telefono").val(),
		    	ALUM_CORREO:$("#correo").val(),
				ALUM_FOTO:$("#ALUM_FOTO").val(),
				ALUM_TRABAJO:$("#trabajo").val(),
				ALUM_TELTRABAJO:$("#teltrabajo").val(),
				ALUM_DIRTRABAJO:$("#dirtrabajo").val(),
				ALUM_EDONAC:$("#ALUM_EDONAC").val(),
				ALUM_MUNINAC:$("#ALUM_MUNINAC").val(),
				ALUM_ESTADO:$("#ALUM_ESTADO").val(),
				ALUM_MUNICIPIO:$("#ALUM_MUNICIPIO").val(),
				ALUM_LOCALIDAD:$("#ALUM_LOCALIDAD").val(),
				ALUM_COLONIA:$("#ALUM_COLONIA").val(),
				
				GPOIND:$("#GPOIND").val(),
				LENIND:$("#LENIND").val(),
				ALUM_NOSEGURO:$("#ALUM_NOSEGURO").val(),
				ALUM_SEGUROORD:$("#ALUM_NOSEGURO").val(),
				ALUM_PADRE:$("#ALUM_PADRE").val(),
				ALUM_MADRE:$("#ALUM_MADRE").val(),
				ALUM_PADREVIVE:$("#ALUM_PADREVIVE").val(),
				ALUM_MADREVIVE:$("#ALUM_MADREVIVE").val(),
			
		    	ALUM_TUTOR:$("#ALUM_TUTOR").val(),
				ALUM_TUTORESTADO:$("#ALUM_TUTORESTADO").val(),
				ALUM_TUTORMUNICIPIO:$("#ALUM_TUTORMUNICIPIO").val(),
				ALUM_TUTORLOC:$("#ALUM_TUTORLOC").val(),
				ALUM_TUTORDIR:$("#ALUM_TUTORDIR").val(),
				ALUM_TUTORCP:$("#ALUM_TUTORCP").val(),
				ALUM_TUTORCOL:$("#ALUM_TUTORCOL").val(),
				ALUM_TUTORTEL:$("#ALUM_TUTORTEL").val(),
				ALUM_TUTORCORREO:$("#ALUM_TUTORCORREO").val(),
				ALUM_TUTORTRABAJO:$("#ALUM_TUTORTRABAJO").val(),
		    	
		      };
		    		
       $('#dlgproceso').modal({backdrop: 'static', keyboard: false});	         
	   $.ajax({
		        type: "POST",
		        url:"../base/actualiza.php",
		    	data: parametros,
		    	success: function(data){		    				
		    		$('#dlgproceso').modal("hide");  			                                	                      
		    		if (!(data.substring(0,1)=="0")){	alert (data);}	
		    		else {alert ("OCURRIO EL SIGUIENTE ERROR: "+data);}          					           
		           }					     
		      });               
	      } 

		    		  

function verMaterias(tipo){
	   $('#lositems').empty();
	   if (tipo=='A') {
		               eltipo=" and a.LISCAL>=70";
		               $('#leyendap').addClass("label-success"); 
		               $('#leyenda').html("Aprobadas");
		               coloric="blue"; } 
       else {
                       eltipo=" and a.LISCAL<70"; 
                       $('#leyendap').addClass("label-danger"); 
                       $('#leyenda').html("Reprobadas");
                       coloric="red";
					   } 
	   elsql="SELECT a.MATCVE, b.MATE_DESCRIP, a.PDOCVE,a.LISCAL, a.PDOCVE, CONCAT(c.EMPL_NOMBRE,' ',c.EMPL_APEPAT,' ',c.EMPL_APEMAT) as PROFESORD "+
                " FROM dlista a, cmaterias b, pempleados c "+
     		   " where IFNULL(MATE_TIPO,'0') NOT IN ('T','I','AC') AND a.MATCVE=b.MATE_CLAVE and  a.LISTC15=c.EMPL_NUMERO and a.ALUCTR='<?php echo $_SESSION["usuario"];?>'"+eltipo+
				" order by a.PDOCVE";
	   
       parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	   $.ajax({
		type: "POST",
		data:parametros,
        url:  "../base/getdatossqlSeg.php",
        success: function(data){  
            losdatos=JSON.parse(data);                          
            jQuery.each(losdatos, function(clave, valor) { 

         	     colorCic="label-info";
         	     
         	     if (valor.LISCAL<70) {colorCic="label-danger";}
         	     if ((valor.LISCAL>=70) && (valor.LISCAL<80)) {coloric="red";}
         	     if ((valor.LISCAL>=80) && (valor.LISCAL<90)) {coloric="purple";}
         	     if ((valor.LISCAL>=90) && (valor.LISCAL<100)) {coloric="green";}
                  $('#lositems').append("<div class=\"timeline-item clearfix\"> "+
					                           "<div class=\"timeline-info\">"+
						                           "<span class=\"label "+colorCic+" label-sm\">"+valor.PDOCVE+"</span>"+
					                           "</div>"+
					                           "<div class=\"widget-box transparent\">"+
						                           "<div class=\"widget-header widget-header-small\">"+
								                        "<h5 class=\"widget-title smaller\">"+valor.MATE_DESCRIP+"   </h5>"+
								                              "<span class=\"grey\">"+valor.PROFESORD+"</span>"+								                        
								                        "<span class=\"widget-toolbar no-border\"><i class=\"ace-icon "+coloric+" fa fa-star-o bigger-110\"></i>"+valor.LISCAL+"</span> "+                                           
							                       "</div>"+
					                           "</div>"+
				                             "</div>");
				 
         	     $('#modalDocument').modal({show:true, backdrop: 'static'});
	               });
        }
	 });
		 
	  
	   }


	function mikardex(){
		enlace="nucleo/pa_datosgen/kardex.php?matricula=<?php echo $_SESSION["usuario"];?>";
		var content = '<iframe frameborder="0" id="FRNoti" src="'+enlace+'" style="overflow-x:hidden;width:100%;height:100%;"></iframe></div>';	
		$('#parentPrice', window.parent.document).html();
		window.parent.$("#myTab").tabs('add',{
				    	    title:'Mi Kardex',				    	    
				    	    content:content,
				    	    closable:true		    
				    	});
	   //window.open(enlace, '_blank'); 
      }

   
</script>



	</body>
<?php } else {header("Location: index.php");}?>
</html>
