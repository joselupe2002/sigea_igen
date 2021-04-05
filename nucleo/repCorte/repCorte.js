var id_unico="";
var estaseriando=false;
var matser="";
contR=1;
contMat=1;


    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 

		
		$(".input-mask-hora").mask("99:99");
		$(".input-mask-horario").mask("99:99-99:99");
		$(".input-mask-numero").mask("99");

		$("#losdeptos").append("<span class=\"label label-warning\">Carrera</span>");
		 
		$.ajax({
			type: "GET",
			url:  "../base/getSesion.php?bd=Mysql&campo=depto",
			success: function(data){ 
				addSELECT("selDeptos","losdeptos","PROPIO", "SELECT URES_URES, URES_DESCRIP FROM fures "+
				" WHERE URES_URES IN ("+data+") ORDER BY URES_DESCRIP", "",""); 
				}
		   });
		
		$("#losciclossel").append("<span class=\"label label-danger\">Ciclo Escolar</span>");
		addSELECT("selCiclos","losciclossel","PROPIO", "SELECT CICL_CLAVE, concat(CICL_CLAVE,' ',CICL_DESCRIP) FROM ciclosesc order by cicl_clave desc", "","");  			      
	
		$("#loscortes").append("<span class=\"label label-danger\">Corte</span>");
		addSELECT("selCortes","loscortes","PROPIO", "SELECT * from ecortescal where ID=1", "","");  			      
	
		$("#losciclos").append("<i class=\" fa white fa-level-down bigger-180\"></i> ");
		$("#losciclos").append("<strong><span id=\"elciclo\" class=\"text-white bigger-40\"></span></strong>");
		colocarCiclo("elciclo","CLAVE");
		
	});
	
	
		 
	function change_SELECT(elemento) {
		if (elemento=='selCiclos') {	
			actualizaSelect("selCortes", "SELECT ID, DESCRIPCION FROM ecortescal where CLASIFICACION='CALIFICACION' AND CICLO='"+$("#selCiclos").val()+"'"+
			" ORDER BY STR_TO_DATE(INICIA,'%d/%m/%Y')", "PROPIO", "");
		}  
    }


    function cargarInformacion(){

		$("#opcionestabInformacion").addClass("hide");
		$("#botonestabInformacion").empty();
	
		cadUniTit="";
		for (i=1;i<=10;i++){cadUniTit+=" <th>APR_U"+i+"</th> ";}
		script="<table id=\"tabInformacion\" name=\"tabInformacion\" class= \" fontRoboto table table-condensed table-bordered table-hover\" "+
		        ">"+
	   	   "        <thead >  "+
		   "             <tr id=\"headMaterias\">"+
		   "                <th>No.</th> "+
		   "                <th>IDGRUPO.</th> "+
		   "                <th>Ciclo</th> "+
		   "                <th>Corte</th> "+	
		   "                <th>CveCarrera</th> "+	
		   "                <th>Carrera</th> "+	
		   "                <th>Rep</th> "+	
		   "                <th>Cve_prof</th> "+	
		   "                <th>Profesor</th> "+
		   "                <th>Cve_Materia</th>"+
		   "                <th>Materia</th>"+
		   "                <th>Sem</th>"+
		   "                <th>Grupo</th>"+
		   "                <th>Unidades</th>"+
		   "                <th>No.Uni</th>"+
		   "                <th>Alumnos</th> "+
		   "                <th>% Aprob.</th> "+
		   "                <th>% Reprob.</th> "+cadUniTit+
		   "              </tr>"+
		   "            </thead>" +
		   "         </table>";
		   $("#informacion").empty();
		   $("#informacion").append(script);


		   $("#cuerpoInformacion").empty();
		   $("#tabInformacion").append("<tbody id=\"cuerpoInformacion\">");

		   elsql="SELECT * FROM ecortescal where ID='"+$("#selCortes").val()+"'";
		   parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		   mostrarEspera("esperaInf","grid_repCortes","Cargando Datos...");
		   $.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(dataC){ 
					datCorte=JSON.parse(dataC);
					inicia=datCorte[0]["INICIA"];
					termina=datCorte[0]["TERMINA"];
					elcorte=datCorte[0]["TIPO"].substr(3,1);
					
					elsql="SELECT a.*, (SELECT COUNT(*) from dlista n where n.IDGRUPO=a.IDDETALLE and n.BAJA='N') as ALUMNOS"+
					" FROM vedgrupos a where CICLO='"+$("#selCiclos").val()+"' and DEPTO='"+$("#selDeptos").val()+"' order by PROFESOR";
					parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
					$.ajax({
						type: "POST",
						data:parametros,
						url:  "../base/getdatossqlSeg.php",
						success: function(dataH){ 
								contR=1;
								grid_data=JSON.parse(dataH);
								jQuery.each(grid_data, function(clave, valor) { 	
									
									
									btnRep="<button title=\"Reporte del Profesor\" onclick=\"verReporte('"+valor.PROFESOR+"');\""+ 
							            "class=\"btn btn-white btn-success btn-round bigger-100\">"+ 
								        "<i class=\"ace-icon blue fa fa-file-text bigger-100\"></i><span class=\"btn-small\"></span> "+           
							            "</button> ";
									
									$("#cuerpoInformacion").append("<tr id=\"rowM"+contR+"\">");
									$("#rowM"+contR).append("<td>"+contR+"</td>");
									$("#rowM"+contR).append("<td>"+valor.IDDETALLE+"</td>");
									$("#rowM"+contR).append("<td>"+valor.CICLO+"</td>");
									$("#rowM"+contR).append("<td>"+elcorte+"</td>");
									$("#rowM"+contR).append("<td>"+valor.CARRERA+"</td>");
									$("#rowM"+contR).append("<td>"+valor.CARRERAD+"</td>");
									$("#rowM"+contR).append("<td>"+btnRep+"</td>");
									$("#rowM"+contR).append("<td>"+valor.PROFESOR+"</td>");
									$("#rowM"+contR).append("<td>"+valor.PROFESORD+"</td>");
									$("#rowM"+contR).append("<td>"+valor.MATERIA+"</td>");								
									$("#rowM"+contR).append("<td>"+valor.MATERIAD+"</td>");
									$("#rowM"+contR).append("<td>"+valor.SEMESTRE+"</td>");
									$("#rowM"+contR).append("<td>"+valor.SIE+"</td>");
									$("#rowM"+contR).append("<td id=\"nu_"+valor.IDDETALLE+"\"></td>");
									$("#rowM"+contR).append("<td id=\"nu2_"+valor.IDDETALLE+"\"></td>");
									verboleta=" onclick=\"previewAdjunto('nucleo/pd_captcal/repUni.php?grupo="+valor.SIE+"&ciclo="+valor.CICLO+"&profesor="+valor.PROFESOR+"&materia="+
									valor.MATERIA+"&materiad="+valor.MATERIAD+"&id="+valor.IDDETALLE+"&semestre="+valor.SEMESTRE+"');\"";

									$("#rowM"+contR).append("<td><span "+verboleta+" id=\"ta_"+valor.IDDETALLE+"\" class=\"badge badge-info\" style=\"cursor:pointer;\">"+valor.ALUMNOS+"</span></td>");		
									$("#rowM"+contR).append("<td><span id=\"pa_"+valor.IDDETALLE+"\" class=\"badge badge-success\">0</span></td>");						
									$("#rowM"+contR).append("<td><span id=\"pr_"+valor.IDDETALLE+"\" class=\"badge badge-danger\">0</span></td>");																											
									for (i=1;i<=10;i++){$("#rowM"+contR).append("<td><span id=\"u"+i+"_"+valor.IDDETALLE+"\" class=\"badge badge-warning\"></span></td>");	}
									contR++; 
									elsql="SELECT GROUP_CONCAT(NUMUNIDAD) as UNIDADES, count(*) as NUNIDADES from eplaneacion where STR_TO_DATE(FECHA,'%d/%m/%Y') BETWEEN "+
									" STR_TO_DATE('"+inicia+"','%d/%m/%Y') AND "+
									" STR_TO_DATE('"+termina+"','%d/%m/%Y') AND MATERIA='"+valor.MATERIA+"' AND GRUPO='"+valor.SIE+"'";
														
									parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
									$.ajax({
										type: "POST",
										data:parametros,
										url:  "../base/getdatossqlSeg.php",
										success: function(datanu){											
											datnu=JSON.parse(datanu);
											$("#nu_"+valor.IDDETALLE).html(datnu[0]["UNIDADES"]);	
											$("#nu2_"+valor.IDDETALLE).html(datnu[0]["NUNIDADES"]);	
											if (datnu[0]["UNIDADES"]!='') {
													cadUnid=datnu[0]["UNIDADES"].split(",");
													cadsqlUni="";
													cadsqlUniR="";

												
													
													cadUnid.forEach(function callback(currentValue, index, array) {
														cadsqlUni+=" ifnull(LISPA"+parseInt(currentValue)+",'0')>=70 OR ";
														cadsqlUniR+=" ifnull(LISPA"+parseInt(currentValue)+",'0')<70 OR ";
														
														//Verificamos unidades aprobadas y reprobadas
														elsql="SELECT count(*) as N from dlista where BAJA='N' AND IDGRUPO='"+valor.IDDETALLE+"' AND ifnull(LISPA"+parseInt(currentValue)+",'0')>=70";										
														parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
														$.ajax({
															type: "POST",
															data:parametros,
															url:  "../base/getdatossqlSeg.php",
															success: function(dataCalUni){			
																datcaluni=JSON.parse(dataCalUni);								
																$("#u"+parseInt(currentValue)+"_"+valor.IDDETALLE).html(datcaluni[0]["N"]);
															    														

															//	alert (valor.IDDETALLE+" "+ $("#pa_"+valor.IDDETALLE).html()+ " "+datcaluni[0]["N"]+ " "+$("#nu2_"+valor.IDDETALLE).html());
															}
														});	
													});		
												
												}// del if si existen unidades que se evaluan en el corte												
										}
									});		

								});																																			
							}
				});	  // del ajax de los cortes de calificaciones
			}
		});// del ajax de los horarios    	      					  					  		
}



function calcularPorcentajes(){
	c=0;
	$('#tabInformacion tr').each(function () {
		if (c>0) {
			var profesor = $(this).find("td").eq(6).html();
			var materia = $(this).find("td").eq(8).html();
			var materiad = $(this).find("td").eq(9).html();
			var grupo = $(this).find("td").eq(11).html();
			var ciclo = $(this).find("td").eq(2).html();
			var iddet = $(this).find("td").eq(1).html();
		
			suma=0;
			for (i=1; i<=10; i++) {
				valor=parseInt($("#u"+i+"_"+iddet).html());
				if (!isNaN(valor)) {suma+=valor;}
			}
			if ((parseInt($("#nu2_"+iddet).html())>0) && (parseInt($("#ta_"+iddet).html())>0)) {		
				pora=Math.round((suma/parseInt($("#nu2_"+iddet).html())/parseInt($("#ta_"+iddet).html()))*100,2);
				porr=Math.round((100-pora),2);
				$("#pa_"+iddet).html(pora);
				$("#pr_"+iddet).html(porr);
			}

		}
		c++;		
		});
	//	cargarInformacion();
}


function grabarPorcentajes(){
	c=0;
	var losdatos=[];

	$('#tabInformacion tr').each(function () {
		if (c>0) {
			 iddet = $(this).find("td").eq(1).html();
			 ciclo = $(this).find("td").eq(2).html();
			 corte = $(this).find("td").eq(3).html();
			 carrera = $(this).find("td").eq(4).html();
			 carrerad = $(this).find("td").eq(5).html();
			 profesor = $(this).find("td").eq(6).html();
			 profesord = $(this).find("td").eq(7).html();
			 materia = $(this).find("td").eq(8).html();
			 materiad = $(this).find("td").eq(9).html();
			 semestre = $(this).find("td").eq(10).html();
			 grupo = $(this).find("td").eq(11).html();
			 alumtot = $("#ta_"+iddet).html();
			 apr =$("#pa_"+iddet).html();
			 rep =$("#pr_"+iddet).html();
			 depto=$("#selDeptos").val();
		
			
			losdatos[(c-1)]= ciclo+"|"+corte+"|"+carrera+"|"+carrerad+"|"+profesor+"|"+profesord+"|"+materia+"|"+materiad+"|"+
						 semestre+"|"+alumtot+"|"+apr+"|"+rep+"|"+'0'+"|"+iddet+"|"+depto;
		}
		c++;		
		});

	
		
		var loscampos = ["CICLO","CORTE","CARRERA","CARRERAD","CVEPROF","PROFESOR","MATERIA","MATERIAD",
		                 "SEMESTRE","ALUM_TOT","PORC_APR","PORC_REPR","ALUM_REP","IDGRUPO","DEPTO"];
			   parametros={
					tabla:"reprobacion",
				 campollave:"concat(CICLO,CORTE,DEPTO)",
				 bd:"Mysql",
				 valorllave:ciclo+corte+depto,
				 eliminar: "S",
				 separador:"|",
				 campos: JSON.stringify(loscampos),
				 datos: JSON.stringify(losdatos)
			   };
	
			  $.ajax({
				 type: "POST",
				 url:"../base/grabadetalle.php",
				 data: parametros,
				 success: function(data){				
					alert ("Se grabaron los porcentajes a la tabla de indicadores");			                      	                                        					          
				 }					     
			 });    	 

}


function verReporte(elprof){
	enlace="nucleo/repCorte/reporteCorte.php?profesor="+elprof+"&ciclo="+$("#selCiclos").val()+"&corte="+$("#selCortes").val()+
	"&depto="+$("#selDeptos").val();

	abrirPesta(enlace,"Reporte");
}