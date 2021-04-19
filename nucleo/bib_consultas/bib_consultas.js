var id_unico="";
var estaseriando=false;
var matser="";
contAlum=1;
contMat=1;
numres=0;

var laconfig=[];
var losdias=[];

    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 
	

		$("#lascarreras").append("<span class=\"label label-warning\">Carrera</span>");		
		addSELECT("selSecciones","lascarreras","PROPIO", "SELECT ID, SECCION FROM bib_secciones ", "",""); 
	
		actualizaRes();

		elsql="SELECT * FROM bib_config where TIPO='LIBROS'";
			parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
			$.ajax({
				type: "POST",
				data:parametros,
				url:  "../base/getdatossqlSeg.php",
				success: function(data){   
					laconfig=JSON.parse(data);
				}
			});

	});
	

	function actualizaRes(){
		elsql="SELECT count(*) FROM bib_reservas where MATRICULA='"+usuario+"' and STR_TO_DATE(FECHARES,'%d/%m/%Y')=CURDATE()";
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){   
				numres=JSON.parse(data)[0][0];
				$("#numres").html(numres);
			}
		});
	}
	
		 
	function change_SELECT(elemento) {

    }

/*===========================================================POR MATERIAS ==============================================*/
function cargarInformacion(){
	script="";
	lapal="";

	$("#informacion").empty();
	$("#informacion").append(script);
	
	
	lascarr="";
    if ($("#selSecciones").val()!="0") { lascarr=" AND SECCION='"+$("#selSecciones").val()+"'";}
	lapal=$("#palabra").val().toUpperCase();
	cad="";
	if (lapal!='') { cad=" and (AUTOR LIKE '%"+lapal+"%' or TITULO LIKE '%"+lapal+"%')";}

	cadRes=" AND ID NOT IN (select IDARTICULO from bib_reservas a where STR_TO_DATE(FECHARES,'%d/%m/%Y')>=CURDATE())";
	if ($("#misreservas").prop("checked")) {
		lascarr="";cad=""; 
		cadRes= " AND ID IN (select IDARTICULO from bib_reservas a where STR_TO_DATE(FECHARES,'%d/%m/%Y')>=CURDATE())";
	}

	elsql="select * from vbib_ejemplares where  TIPO='LIBROS'  AND ACCESIBLE=3 "+lascarr+cad+
	" AND ID NOT IN (select IDARTICULO from bib_prestamos a where a.ENTREGADO='N') "+cadRes+
	" order by IDFICHA";


	mostrarEspera("esperahor","grid_bib_consultas","Cargando Datos...");
	parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
	$.ajax({
		   type: "POST",
		   data:parametros,
		   url:  "../base/getdatossqlSeg.php",
		   success: function(data){  				      			      
				generaTabla(JSON.parse(data));   													
				ocultarEspera("esperahor");  																											
		},
		error: function(dataMat) {	                  
				alert('ERROR: '+dataMat);
							}
});	      	      					  					  		
}

function generaTabla(grid_data){	
contAlum=1;
$("#contenido").empty();
$("#tabMaterias").append("<tbody id=\"cuerpoMaterias\">");

jQuery.each(grid_data, function(clave, valor) { 
	lafoto=valor.FOTO_LIBRO;
	if ((valor.FOTO_LIBRO ==null) ||(valor.FOTO_LIBRO =="")){lafoto="../../imagenes/menu/default.png";} 

	evento="onclick=\"reservar('"+valor.ID+"');\"";
	titulo="Reservar";
	if ($("#misreservas").prop("checked")) {evento="onclick=\"noreservar('"+valor.ID+"');\""; titulo="Eliminar Reserva"; }

	botonReserva="<div class=\"tools action-buttons\">"+
		"						<a title=\"Reservar libro\" "+evento+" style=\"cursor:pointer;\">"+
		"                            <i class=\"ace-icon fa fa-check-square-o red bigger-120\"> "+titulo+"</i>"+
		"                       </a>"+				
		"				</div>";
	if ((numres>=laconfig[0]["MAXRESERVA"]) && !($("#misreservas").prop("checked"))) {botonReserva="";}

	eventoLib="";
	if (valor.ENLACE!='') { eventoLib="onclick=\"window.open('"+valor.ENLACE+"','_blank');\"";}
	if (valor.PDF!='') {eventoLib="onclick=\"previewAdjunto('"+valor.PDF+"');\"";}

    $("#contenido").append(		
		"	<div class=\"itemdiv memberdiv\" id=\""+valor.ID+"\">"+
		"		<div class=\"ma_principal\" "+eventoLib+" >"+		
		"				<a href=\"#\"><img src=\""+lafoto+"\" class=\"ma_fotoRec\"  /></a><br/>"+		
		"			<div class=\"body\">"+
		"				<div class=\"name fontRoboto\">"+
		"                   <span class=\"fontRobotoB elname text-primary\" mipadre=\""+valor.ID+"\" style=\"font-size:11px; line-height: 1.2;\">"+valor.TITULO.substring(0,29)+
		"							<span class=\"hidden\">"+valor.TITULO+"</span>"+
		"							<span class=\"hidden\">"+valor.AUTOR+"</span>"+
		"							<span class=\"hidden\">"+valor.ID+"</span>"+
	    "					</span>"+
		"			</div>"+
		"		</div>"+
		"		<div class=\"popover ma_popover\">"+
		"			<div class=\"arrow\"></div>"+
		"			<div class=\"popover-content\">"+
		"				<div class=\"bolder\">"+valor.TITULO+"</div>"+
		"					<div class=\"time\"><span class=\"badge badge-success\"> No.: "+valor.ID+"</span></div>"+
		"					<div class=\"time\"><i class=\"ace-icon fa fa-user middle bigger-120 orange\"></i><span class=\"green\">"+valor.AUTOR+"</span></div>"+
		"					<div class=\"time\"><i class=\"ace-icon fa fa-columns  middle bigger-120 purple\"></i><span class=\"blue\"> ANAQUEL: "+valor.ANAQUEL+"</span></div>"+
		"					<div class=\"hr dotted hr-8\"></div>"+botonReserva+		
		"				</div>"+
		"			</div>"+
		"		</div>"+
		"	</div>");
		
	contAlum++;     
	
	$('.memberdiv').on('mouseenter touchstart', function(){
			
		var $this = $(this);
		var $parent = $this.closest('.tab-pane');

		var off1 = $parent.offset();
		var w1 = $parent.width();

		var off2 = $this.offset();
		var w2 = $this.width();

		var place = 'left';
		if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) place = 'right';
		
		$this.find('.popover').removeClass('right left').addClass(place);
	}).on('click', function(e) {
		e.preventDefault();
	});


});	
} 


function guardaRes(id,fechares, horares) {
	fecha=dameFecha("FECHAHORA");
	fechaent=dameFecha("FECHAHORA",2);
	fechasola=dameFecha("FECHA");
	hora=dameFecha("HORA");
	parametros={tabla:"bib_reservas",
					bd:"Mysql",
					MATRICULA:usuario,
					IDARTICULO:id,
					FECHARES:fechares, 
					HORARES:horares, 
					TIPO:"LIBROS",
					USER: usuario,
					FECHAUSER:fecha,
					_INSTITUCION: institucion, 
					_CAMPUS: campus	};
		$.ajax({
				type: "POST",
				url:"../base/inserta.php",
				data: parametros,
				success: function(data){ 
						numres++;
						cargarInformacion();
						actualizaRes();						
					}
				});			

}

function reservar(id){
	if (numres<laconfig[0]["MAXRESERVA"]) {
		mifecha=dameFecha("FECHA");
		mihora=dameFecha("HORA");
		var modal = 
		'<div class="modal fade">\
		<div class="modal-dialog">\
		<div class="modal-content">\
			<div class="modal-body">\
			<button type="button" class="close" data-dismiss="modal" style="margin-top:-10px;">&times;</button>\
			<form class="no-margin">\
					<div class=\"row\">\
						<div class="col-sm-4">\
								<label  class="fontRobotoB">Fecha</label>\
								<div class="input-group"><input readonly="true" class="form-control date-picker" id="fechares" value="' + mifecha + '"\
								type="text" autocomplete="off"  data-date-format="dd/mm/yyyy" /> \
								<span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span></div>\
							</div>\
							<div class="col-sm-3">\
								<label  class="fontRobotoB">Hora</label>\
								<input  id="horares" autocomplete="off" class= "small form-control input-mask-hora" value="'+ mihora + '"></input>\
							</div>\
						</div>\
			</form>\
			</div>\
			<div class="modal-footer">\
				<button type="button" class="btn btn-sm btn-primary" data-action="grabar"><i class="ace-icon fa fa-trash-o"></i> Reservar</button>\
				<button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> Cancel</button>\
			</div>\
		</div>\
		</div>\
		</div>';

		var modal = $(modal).appendTo('body');
		modal.find('form').on('submit', function(ev){
			ev.preventDefault();
			modal.modal("hide");
		});

		//$('.date-picker').datepicker({autoclose: true,todayHighlight: true}).next().on(ace.click_event, function(){$(this).prev().focus();});
		$(".input-mask-hora").mask("99:99");
			

		modal.find('button[data-action=grabar]').on('click', function() {
			guardaRes(id,$("#fechares").val(),$("#horares").val());
			modal.modal("hide");
		});
		
		modal.modal('show').on('hidden', function(){
			modal.remove();
		});
	}
	else {alert ("No puede reservar más de "+laconfig[0]["MAXRESERVA"]+" al día");}
	
}


function noreservar(id){
	lafecha=dameFecha("FECHA");
	if (confirm("¿Seguro que desea eliminar la reservación realizada?") ){
		parametros={
			tabla:"bib_reservas",
			campollave:"concat(IDARTICULO,FECHARES,MATRICULA)",
			bd:"Mysql",
			valorllave:id+lafecha+usuario
		};
		$.ajax({
			type: "POST",
			url:"../base/eliminar.php",
			data: parametros,
			success: function(data){
				numres--;
				cargarInformacion();
				actualizaRes();
				
			}					     
		});    	 
	}        
}


function filtrarMenu() {

	var input = $('#filtrar').val();
	var filter = input.toUpperCase();
	var contenidoMenu="";
	
	if (filter.length == 0) { // show all if filter is empty	
			$(".itemdiv").removeClass("hide");
		return;
	} else {														

		$(".itemdiv").addClass("hide");
		$(' .elname:contains("' + filter + '")').each(function() {				
		   $("#"+$(this).attr("mipadre")).removeClass("hide");
		});
		
	}
}
