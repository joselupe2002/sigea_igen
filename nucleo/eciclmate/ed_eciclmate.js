

function changeCVEESP(DATO, usuario, institucion, campus){
   elsql="SELECT CLAVE FROM especialidad o where ID='"+$("#CVEESP").val()+"'";   
   parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}   
   $.ajax({
      type: "POST",
      data:parametros,
      url: 'getdatossqlSeg.php', 
      success: function(data){ 
         jQuery.each(JSON.parse(data), function(clave, valor) { 	
           $("#CVEESPSIE").val(valor.CLAVE);
           $("#CVEESPSIE").prop('disabled', true);
                                   
         });
      }
   });
}

function changeCICL_MAPA(DATO, usuario, institucion, campus){
	elsql="SELECT MATE_CLAVE, CONCAT(MATE_DESCRIP,' ',MATE_CLAVE) FROM cmaterias WHERE MATE_CLAVE NOT IN "+
	         "(SELECT CICL_MATERIA FROM eciclmate WHERE CICL_MAPA='"+$("#CICL_MAPA").val()+"') ORDER BY MATE_DESCRIP";
   agregarEspera("imggif_CICL_MATERIA",null);
   
   parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql",sel:'0'}

	$.ajax({
        type: "POST",
        data:parametros,
        url: 'dameselectSeg.php', 
        success: function(data){ 
        	  $("#CICL_MATERIA").empty();
              $("#CICL_MATERIA").html(data);                               
        	  $('#CICL_MATERIA').trigger("chosen:updated"); 
        	          	 
           quitarEspera("imggif_CICL_MATERIA",null);

           elsql="SELECT o.MAPA_CARRERA, o.CVESIE FROM mapas o where MAPA_CLAVE='"+$("#CICL_MAPA").val()+"'";  
           parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql"}          
           $.ajax({
            type: "POST",
            data:parametros,
            url: 'getdatossqlSeg.php', 
            success: function(data){ 
               jQuery.each(JSON.parse(data), function(clave, valor) { 	
                 $("#CARRERASIE").val(valor.MAPA_CARRERA);
                 $("#CARRERASIE").prop('disabled', true);
                 $("#CVEMAPASIE").val(valor.CVESIE); 
                 $("#CVEMAPASIE").prop('disabled', true);                                  
           
               });
              
               agregarEspera("imggif_CVEESP",null);
               elsql="SELECT ID, CONCAT(CLAVE,' ',DESCRIP,' ',CLAVEOF) FROM especialidad u where u.MAPA='"+$("#CICL_MAPA").val()+"' UNION SELECT '0','NO ES DE ESPECIALIDAD' FROM DUAL ORDER BY 1 DESC";   
            
            
               parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql",sel:'0'}
               $.ajax({
                type: "POST",
                data:parametros,
                url: 'dameselectSeg.php', 
                success: function(data){ 
                   $("#CVEESP").empty();
                   $("#CVEESP").html(data);                               
                   $('#CVEESP').trigger("chosen:updated");                   
                   quitarEspera("imggif_CVEESP",null);
                   
                  }
               });
            

         },
         error: function(data) {
            alert('ERROR: '+data);
         }
       });     


     },
     error: function(data) {
        alert('ERROR: '+data);
     }
   });     
}



function changeCICL_CICLO(DATO, usuario, institucion, campus){
	elsql="SELECT CONCAT(CICL_DE, \"|\",CICL_A) FROM ciclosfor WHERE CICL_CLAVE='"+$("#CICL_CICLO").val()+"'";
	
   agregarEspera("imggif_CICL_CUATRIMESTRE",null);
   parametros={sql:elsql,dato:sessionStorage.co,bd:"Mysql",numcol:'0'}
	$.ajax({
        type: "POST",
        data:parametros,
        url: 'damedato.php', 
        success: function(data){  
        	 periodo=data.split("|");
        	  $("#CICL_CUATRIMESTRE").empty();
        	 for (x=periodo[0];x<=periodo[1];x++){
        	     $("#CICL_CUATRIMESTRE").append("<option id=\""+x+"\">"+x+"</option>");   
        	 }
             quitarEspera("imggif_CICL_CUATRIMESTRE",null);
     },
     error: function(data) {
        alert('ERROR: '+data);
     }
   });     
}


function changeCICL_MATERIA(DATO, usuario, institucion, campus){

	if ($("#CICL_CLAVEMAPA").val().length<=0) {
	   $("#CICL_CLAVEMAPA").val( $("#CICL_MATERIA").val());

	}
}