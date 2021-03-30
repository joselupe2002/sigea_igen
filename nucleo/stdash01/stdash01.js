var id_unico="";
var estaseriando=false;
var matser="";
contR=1;
contMat=1;
var laCarrera="";
var elalumno="";
var miciclo="";
var micarrera="";

var colores=["4,53,252","238,18,8","238,210,7","5,223,5","7,240,191","240,7,223","240,7,7","240,7,12"];



    $(document).ready(function($) { var Body = $('container'); Body.addClass('preloader-site');});
    $(window).load(function() {$('.preloader-wrapper').fadeOut();$('container').removeClass('preloader-site');});


    jQuery(function($) { 


		
		$(".input-mask-hora").mask("99:99");
		$(".input-mask-horario").mask("99:99-99:99");
		$(".input-mask-numero").mask("99");

		$("#lascarreras").append("<span class=\"label label-warning\">Carrera</span>");
		 
		$.ajax({
			type: "GET",
			url:  "../base/getSesion.php?bd=Mysql&campo=carrera",
			success: function(data){  
				addSELECT("selCarreras","lascarreras","PROPIO", "SELECT CARR_CLAVE, CARR_DESCRIP FROM ccarreras where CARR_ACTIVO='S'"+
				" and CARR_CLAVE IN ("+data+")", "",""); 
				},
			error: function(data) {	                  
					   alert('ERROR: '+data);
					   $('#dlgproceso').modal("hide");  
				   }
		   });

		$("#losciclos2").append("<span class=\"label label-danger\">Ciclo Escolar</span>");
		addSELECT("selCiclo","losciclos2","PROPIO", "SELECT CICL_CLAVE, concat(CICL_CLAVE,' ',CICL_DESCRIP) FROM ciclosesc order by CICL_CLAVE DESC", "","");  	
		

		$("#losciclos").append("<i class=\" fa white fa-level-down bigger-180\"></i> ");
		$("#losciclos").append("<strong><span id=\"elciclo\" class=\"text-white bigger-40\"></span></strong>");
		colocarCiclo("elciclo","CLAVE");




		
	});
	
	

		 
	function change_SELECT(elemento) {
		if (elemento=='selCiclo') {miciclo=$("#selCiclo").val(); $("#elciclo").html($("#selCiclo").val());}
		if (elemento=='selCarreras') {micarrera=$("#selCarreras").val();}
	}  

	function limpiar(){
		$(".dashboard").each(function(index) {
			$(this).empty();		
		});
	  
	}


	function evento(pes){
		if (pes=="p2") {cargaReprobacion("danger");}
		if (pes=="p3") {cargaHistMat("warning");}
		if (pes=="p4") {cargaHistMatNew("primary");}
		if (pes=="p5") {cargaHistEgresados("primary");}
	}

    function cargarInformacion(){
		limpiar();
		cargaMatricula("primary");
		cargaMatriculaPer("success");
		cargaEgresados("warning");
		cargaAprobaron('c11b',"=0",'REPROBARON CERO MATERIAS','success');
		cargaAprobaron('c11c',"=1",'REPROBARON 1 MATERIA','warning');
		cargaAprobaron('c11d',">1",'REPROBARON MAS DE 1 MATERIA','danger');
		cargaTitulados("c13b","","INICIARON TITULACIÓN","primary");
		cargaTitulados("c13c"," AND TITULADO='S'","TITULADOS","success");	

	}


	function cargaMatricula(coloret) {
		$('#c11a').append("<img id=\"esperarc11\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		elsql="SELECT IFNULL(SUM(IF (ALUM_SEXO=1,1,0)),0) AS HOMBRES,IFNULL(SUM(IF (ALUM_SEXO=2,1,0)),0) AS MUJERES, IFNULL(COUNT(*),0) as TOTAL "+
		"FROM falumnos where ALUM_MATRICULA IN (SELECT ALUCTR FROM falumnos, dlista, cmaterias"+
		" where PDOCVE='"+miciclo+"' and MATCVE=MATE_CLAVE AND IFNULL(MATE_TIPO,'') "+
		"       NOT IN ('AC','SS','I','T','OC') AND GPOCVE<>'REV' AND ALUCTR=ALUM_MATRICULA AND ALUM_CARRERAREG="+micarrera+" )";

		//console.log(elsql);
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datos=JSON.parse(data);
				$('#c11a').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">MATRÍCULA DEL PROGRAMA EDUCATIVO</span></div>");
				addbtninfo('c11a','Número de Hombres en el Ciclo Escolar','btn-info',datos[0]["HOMBRES"],'<i class="fa fa-male bigger-220"></i>');
				addbtninfo('c11a','Número de Mujeres en el Ciclo Escolar','btn-pink',datos[0]["MUJERES"],'<i class="fa fa-female bigger-220"></i>');
				addbtninfo('c11a','Total de alumnos inscritos en el Ciclo Escolar','btn-success',datos[0]["TOTAL"],'TOTAL');
				$("#esperarc11").remove();
			}  
		});

	}


	function cargaEgresados(coloret) {
		$('#c13a').append("<img id=\"esperarc13a\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		elsql="SELECT ifnull(SUM(IF (ALUM_SEXO=1,1,0)),0) AS HOMBRES,ifnull(SUM(IF (ALUM_SEXO=2,1,0)),0) AS MUJERES, COUNT(*) as TOTAL "+
		"FROM falumnos where ALUM_CARRERAREG='"+micarrera+"' AND ALUM_CICLOTER='"+miciclo+"'";

		//console.log(elsql);
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datos=JSON.parse(data);		
				$('#c13a').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">EGRESADOS</span></div>");		
				addbtninfo('c13a','Número de Hombres egresados en el Ciclo Escolar','btn-info',datos[0]["HOMBRES"],'<i class="fa fa-male bigger-220"></i>');
				addbtninfo('c13a','Número de Mujeres egresados en el Ciclo Escolar','btn-pink',datos[0]["MUJERES"],'<i class="fa fa-female bigger-220"></i>');
				addbtninfo('c13a','Total de alumnos egresados en el Ciclo Escolar','btn-success',datos[0]["TOTAL"],'TOTAL');				
				$("#esperarc13a").remove();
			}  
		});

	}


	function cargaTitulados(contenedor,cadAux,et,coloret) {
		$('#'+contenedor).append("<img id=\"esperar"+contenedor+"\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		elsql="SELECT ifnull(SUM(IF (ALUM_SEXO=1,1,0)),0) AS HOMBRES, ifnull(SUM(IF (ALUM_SEXO=2,1,0)),0) AS MUJERES, COUNT(*) as TOTAL "+
		" FROM vtit_pasantes where CARRERA='"+micarrera+"' AND CICLO='"+miciclo+"' "+cadAux ;

		console.log(elsql);
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datos=JSON.parse(data);	
				$('#'+contenedor).append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">"+et+"</span></div>");			
				addbtninfo(contenedor,'Número de Hombres '+et+' en el Ciclo Escolar','btn-info',datos[0]["HOMBRES"],'<i class="fa fa-male bigger-220"></i>');
				addbtninfo(contenedor,'Número de Mujeres '+et+' en el Ciclo Escolar','btn-pink',datos[0]["MUJERES"],'<i class="fa fa-female bigger-220"></i>');
				addbtninfo(contenedor,'Total de alumnos '+et+' en el Ciclo Escolar','btn-success',datos[0]["TOTAL"],'TOTAL');				
				$("#esperar"+contenedor).remove();
			}  
		});

	}



	function cargaAprobaron(contenedor,numrep,et,coloret) {
		$('#'+contenedor).append("<img id=\"esperar"+contenedor+"\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		elsql="select IFNULL(SUM(IF (SEXO=1,1,0)),0) AS HOMBRES,IFNULL(SUM(IF (SEXO=2,1,0)),0) AS MUJERES, IFNULL(COUNT(*),0) AS TOTAL "+
		" from vstlisnumrep  where CICLO='"+miciclo+"' AND CARRERA="+micarrera+" AND NUMREP"+numrep;
		//console.log(elsql);
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datos=JSON.parse(data);
											
				$('#'+contenedor).append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">"+et+"</span></div>");			
				addbtninfo(contenedor,'Número de Hombres que '+et+' en el Ciclo Escolar','btn-info',datos[0]["HOMBRES"],'<i class="fa fa-male bigger-220"></i>');
				addbtninfo(contenedor,'Número de Mujeres que '+et+' en el Ciclo Escolar','btn-pink',datos[0]["MUJERES"],'<i class="fa fa-female bigger-220"></i>');
				addbtninfo(contenedor,'Total de alumnos que '+et+' en el Ciclo Escolar','btn-success',datos[0]["TOTAL"],'TOTAL');
				$("#esperar"+contenedor).remove();
			}  
		});

	}


	


	function cargaMatriculaPer(coloret) {
		var graphperiodo;
		$('#c12').empty();
		$('#c12').append("<img id=\"esperarc12\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		elsql="SELECT concat('SEM. ',getPeriodos(ALUM_MATRICULA,'"+miciclo+"')) as label, COUNT(*) as value "+
		"FROM falumnos where ALUM_MATRICULA IN (SELECT ALUCTR FROM falumnos, dlista, cmaterias"+
		" where PDOCVE='"+miciclo+"' and MATCVE=MATE_CLAVE AND IFNULL(MATE_TIPO,'') "+
		"       NOT IN ('AC','SS','I','T','OC') AND GPOCVE<>'REV' AND ALUCTR=ALUM_MATRICULA AND ALUM_CARRERAREG="+micarrera+" )"+
		" GROUP BY getPeriodos(ALUM_MATRICULA,'"+miciclo+"')";

		//console.log(elsql);

		$('#c12').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">MATRÍCULA POR PERIODO</span></div>");
		$('#c12').append("<div id=\"graphperiodo\" class=\"graph\"></div>");
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datosgraf=JSON.parse(data);
				graphperiodo= Morris.Donut({
         		   element: 'graphperiodo',
         		   data: datosgraf,
         		
         		 });

				  graphperiodo.redraw();
				  $(window).trigger('resize');

				$("#esperarc12").remove();
			
			}  
		});

	}



	function cargaReprobacion(coloret) {
		var graphRepMat;
		$('#c21').empty();
		$('#c21').append("<img id=\"esperarc21\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		
		elsql="SELECT MATE_DESCRIP, COUNT(*) AS INSCRITOS,SUM(IF (LISCAL>=70,1,0)) AS APROBADOS,"+
		"SUM(IF (LISCAL<70,1,0)) AS REPROBADOS, round((SUM(IF (LISCAL<70,1,0))/COUNT(*)*100),2) AS PORREP "+
		" FROM dlista, cmaterias, falumnos where PDOCVE='"+miciclo+"' AND MATCVE=MATE_CLAVE AND IFNULL(MATE_TIPO,'') NOT IN ('SS','AC','OC','RP')"+
		" AND GPOCVE<>'REV' and ALUCTR=ALUM_MATRICULA AND ALUM_CARRERAREG="+micarrera+
		" GROUP BY MATE_DESCRIP  ";

		//console.log(elsql);

		$('#c21').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">REPROBACIÓN POR MATERIA</span></div>");
		$('#c21').append("<div id=\"graphRepMat\" class=\"graph\" style=\"width:100%;\"></div>");
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datosgraf=JSON.parse(data);

				graphRepMat= Morris.Bar({
         		   element: 'graphRepMat',
         		   data: datosgraf,
         		   xkey: 'MATE_DESCRIP',
         		   ykeys: ['PORREP'],
         		   labels: [''],
         		   xLabelAngle: 90,         	
         		   gridTextSize: '10',
         		   resize: true,
				   postUnits: '', //% $
				   hoverCallback: function(index, options, content) {
					var data = options.data[index];
					return  "<b>"+data.MATE_DESCRIP+"("+data.PORREP+"%)</b><br>"+
							"<b class=\"text-success\">Inscritos: "+data.INSCRITOS+"</b><br>"+
							"<b class=\"text-danger\">Reprobados: "+data.REPROBADOS+"</b><br>";
				}
         		  
         		 });

				  graphRepMat.redraw();
				  $(window).trigger('resize');

				  
          	    $( "#graphRepMat svg rect" ).on("click", function(data) {    			     
                      detalle($(".morris-hover-row-label").html(),'','','');    			     
    			});

				
				

				$("#esperarc21").remove();
			
			}  
		});

	}



	function cargaHistMat(coloret) {
		var graphHisMat;
		$('#c31').empty();
		$('#c31').append("<img id=\"esperarc31\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		
		elsql="select CICLO,MATRICULA from vstmatriculaxcic where CARRERA="+micarrera+" ORDER BY CICLO";
	

		//console.log(elsql);

		$('#c31').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">HISTORIAL DE MATRICULA</span></div>");
		$('#c31').append("<div id=\"graphHisMat\" class=\"graph\" style=\"width:100%;\"></div>");
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datosgraf=JSON.parse(data);
				graphHisMat= Morris.Bar({
         		   element: 'graphHisMat',
         		   data: datosgraf,
         		   xkey: 'CICLO',
         		   ykeys: ['MATRICULA'],
         		   labels: ['MATRICULA'],
         		   xLabelAngle: 00,         	
         		   gridTextSize: '10',
         		   resize: true,
				   postUnits: '', //% $
				  
         		  
         		 });

				  graphHisMat.redraw();
				  $(window).trigger('resize');
				  
          	    $( "#graphHisMat svg rect" ).on("click", function(data) {    			     
                      detalle($(".morris-hover-row-label").html(),'','','');    			     
    			});
				

				$("#esperarc31").remove();
			
			}  
		});

	}


/*==============================================================================*/
	function cargaHistMatNew(coloret) {
		
		var graphHisMatNew;
		$('#c41').empty();
		$('#c41').append("<img id=\"esperarc41\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");	
		elsql="select CICLO,MATRICULA from vstmatriculanewxcic where CARRERA="+micarrera+" ORDER BY CICLO";

		$('#c41').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">HISTORIAL DE MATRICULA DE NUEVO INGRESO</span></div>");
		$('#c41').append("<div id=\"graphHisMatNew\" class=\"graph\" style=\"width:100%;\"></div>");
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datosgraf=JSON.parse(data);
				graphHisMatNew= Morris.Bar({
         		   element: 'graphHisMatNew',
         		   data: datosgraf,
         		   xkey: 'CICLO',
         		   ykeys: ['MATRICULA'],
         		   labels: ['MATRICULA'],
         		   xLabelAngle: 00,         	
         		   gridTextSize: '10',
         		   resize: true,
				   postUnits: '', //% $				         		  
         		 });

				  $("#esperarc41").remove();
				  graphHisMatNew.redraw();
				  $(window).trigger('resize');			

          	    $( "#graphHisMatNew svg rect" ).on("click", function(data) {    			     
                      detalle($(".morris-hover-row-label").html(),'','','');    			     
    			});			
			}  
		});
	}




	function cargaHistEgresados(coloret) {
		var graphRepEgre;
		$('#c51').empty();
		$('#c51').append("<img id=\"esperarc51\" src=\"../../imagenes/menu/esperar.gif\" style=\"width:100%;height:100%;\">");
		
		elsql="SELECT CICLOING,ifnull((SELECT l.MATRICULA FROM vstmatriculanewxcic l "+
			   " where l.CICLO=CICLOING AND l.CARRERA=h.CARRERA),0) as INS,"+			
			   " COUNT(*) AS EGRESADOS, IFNULL(ROUND(COUNT(*)/ifnull((SELECT l.MATRICULA FROM vstmatriculanewxcic l "+
			   "                                where l.CICLO=CICLOING AND l.CARRERA=h.CARRERA),0)*100,0),0) AS EFI "+
			   " FROM vst_egresados h WHERE CARRERA="+micarrera+" GROUP BY CICLOING ORDER BY CICLOING";

		$('#c51').append("<div class=\"row\"><span  class=\"label label-"+coloret+" fontRobotoB\">HISTORIAL DE EGRESADOS</span></div>");
		$('#c51').append("<div id=\"graphRepEgre\" class=\"graph\" style=\"width:100%;\"></div>");
		parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}
		$.ajax({
			type: "POST",
			data:parametros,
			url:  "../base/getdatossqlSeg.php",
			success: function(data){    
				datosgraf=JSON.parse(data);

				graphRepEgre= Morris.Bar({
         		   element: 'graphRepEgre',
         		   data: datosgraf,
         		   xkey: 'CICLOING',
         		   ykeys: ['EFI'],
         		   labels: [''],
         		   xLabelAngle: 90,         	
         		   gridTextSize: '10',
         		   resize: true,
				   postUnits: '', //% $
				   hoverCallback: function(index, options, content) {
					var data = options.data[index];
					return  "<b>"+data.CICLOING+"("+data.EFI+"%)</b><br>"+
							"<b class=\"text-primary\">Inscritos: "+data.INS+"</b><br>"+
							"<b class=\"text-success\">Egresados: "+data.EGRESADOS+"</b><br>";
				}
         		  
         		 });

				  graphRepEgre.redraw();
				  $(window).trigger('resize');

				  
          	    $( "#graphRepEgre svg rect" ).on("click", function(data) {    			     
                      detalle($(".morris-hover-row-label").html(),'','','');    			     
    			});
				$("#esperarc51").remove();
			
			}  
		});

	}


