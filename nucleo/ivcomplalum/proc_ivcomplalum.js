

function oficioLibCompl(modulo,usuario,institucion, campus,essuper){
	table = $("#G_"+modulo).DataTable();
	if (table.rows('.selected').data().length>0) {		
		
		enlace="nucleo/ivcomplalum/oficioComp.php?tipo=0&carrerad="+table.rows('.selected').data()[0]["CARRERAD"]+"&carrera="+table.rows('.selected').data()[0]["CARRERA"];
		abrirPesta(enlace,"OficioCom");
		
	    return false;
	 
	}
	else {
		alert ("Debe seleccionar una actividad complementaria para determinar la carrera ");
		return 0;

		}
	
}



