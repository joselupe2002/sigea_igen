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
		<link rel="stylesheet" href="<?php echo $nivel; ?>css/sigea.css" />

        

		<style type="text/css">
		       table.dataTable tbody tr.selected {color: blue; font-weight:bold;}
			   table.dataTable tbody td {padding:4px;}
               th, td { white-space: nowrap; }        
        </style>
	</head>


	<body id="grid_<?php echo $_GET['modulo']; ?>" style="background-color: white; width:98%;">
	   
	    
	<div class="widget-box widget-color-green" id="principal">
			  <div class="widget-header widget-header-small" style="padding:0px;">
			      <div class="row" >	
				         <div id="losciclos" class="col-sm-1">
						</div> 	
						<div id="losciclossel" class="col-sm-2" >
						</div>    
						<div id="loscortes" class="col-sm-2">
						</div>                    				
						<div id="losdeptos" class="col-sm-3">
						</div>       			 
					
						<div class="col-sm-4" style="padding-top:14px;">
						    <button title="Buscar Registros" onclick="cargarInformacion();" class="btn btn-white btn-info btn-round" value="Agregar"> 
								<i class="ace-icon green fa fa-search bigger-140"></i><span class="btn-small"></span>            
							</button>
							<button title="Calcular los Porcentajes de reprobación" onclick="calcularPorcentajes();" 
							    class="btn btn-white btn-success btn-round" > 
								<i class="ace-icon blue fa fa-strikethrough bigger-140"></i> Calcular<span class="btn-small"></span>            
							</button>
							<button title="Buscar Registros" onclick="convertirDataTable('tabInformacion');" 
							    class="btn btn-white btn-success btn-round" value="Agregar"> 
								<i class="ace-icon blue fa fa-wrench bigger-140"></i><span class="btn-small"></span>            
							</button>																							 									
						</div>
		            </div> 
		      </div>

              <div class="widget-body">
				   <div class="widget-main">
				       <div id="opcionestabInformacion" class="row hide" >
					        <div class="col-sm-1"></div>
						    <div class="col-sm-3">
								<div class="pull-left tableTools-container" id="botonestabInformacion"></div>
							</div>
							<div class="col-sm-3">
								<input type="text" id="buscartabInformacion" placeholder="Filtrar...">	
							</div>
				       </div>
				       <div class="row">							   
					       <div id="informacion" class="col-sm-12 sigeaPrin" style="overflow-x: auto; height:350px;" >    
						   </div>
                       </div>
                    </div>
			   </div>
			   <div class="widget-header">
			   			<button title="Grabar porcentajes de reprobación a tabla de indiadores" onclick="grabarPorcentajes();" 
							    class="btn btn-white btn-success btn-round" > 
								<i class="ace-icon green fa fa-save bigger-140"></i> Grabar Porcentajes<span class="btn-small"></span>            
						</button>
			   </div>
		</div>

<!-- ============================================================================================================-->			
		 							
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

<!-- -------------------Exportación de tabla a excel----------------------->
<script src="<?php echo $nivel; ?>js/FileSaver.min.js"></script>
<script src="<?php echo $nivel; ?>js/tableexport.js"></script>


<script src="repCorte.js?v=<?php echo date('YmdHis'); ?>"></script>
<script type="text/javascript">
 
</script>



</body>
<?php } else {header("Location: index.php");}?>
</html>


