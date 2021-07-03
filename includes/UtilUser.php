<?php

header("Content-Type: text/html;charset=UTF-8");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';



class UtilUser {

	public function enviarCorreo($receptor,$asunto,$cuerpo,$adj1) {
		$res="";
		$emisor="sigea@smariaoro.tecnm.mx";
		$clave="Lox40224";
		
		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
		$mail->Host = "smtp.office365.com";	
		$mail->Port = 587; // or 587
		$mail->SMTPSecure = "tls";
		$mail->IsHTML(true);
		$mail->Username = $emisor;
		$mail->Password = $clave;
		$mail->SetFrom($emisor,$asunto);
		$mail->Subject = $asunto;
		$mail->Body =$cuerpo;
		$mail->AddAddress($receptor);
		$mail->CharSet = 'UTF-8';
		
		if (!($adj1=="")) {
			 $mail->AddStringAttachment($adj1, 'documento.pdf', 'base64', 'application/pdf');
		}
		
//		$mail->AddAttachment($adj1,$adj1);
//		$mail->AddAttachment($adj2,$adj2);
		if(!$mail->Send()) {
			$res="Ocurrio error al enviar correo a: ".$receptor." (". $mail->ErrorInfo.")";
		} else {
			$res="";
		}
		
		return $res;
	}
	

	public function enviarCorreoCopia($receptor,$asunto,$cuerpo,$adj1,$ccopia) {
		$res="";
		$emisor="sigea@smariaoro.tecnm.mx";
		$clave="Lox40224";
		
		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
		$mail->Host = "smtp.office365.com";	
		$mail->Port = 587; // or 587
		$mail->SMTPSecure = "tls";
		$mail->IsHTML(true);
		$mail->Username = $emisor;
		$mail->Password = $clave;
		$mail->SetFrom($emisor,$asunto);
		$mail->Subject = $asunto;
		$mail->Body =$cuerpo;
		$mail->AddAddress($receptor);
		$mail->CharSet = 'UTF-8';
		$mail->addCC($ccopia);
		
		if (!($adj1=="")) {
			 $mail->AddStringAttachment($adj1, 'oficio.pdf', 'base64', 'application/pdf');
		}
		
		if(!$mail->Send()) {
			$res="Ocurrio error al enviar correo a: ".$receptor." (". $mail->ErrorInfo.")";
		} else {
			$res="";
		}
		
		return $res;
	}

	public function enviarCorreoAdj($receptor,$asunto,$cuerpo,$adj1) {
		$res="";
	
		$emisor="sigea@smariaoro.tecnm.mx";
		$clave="Lox40224";

		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 0; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		//$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
		$mail->Host = "smtp.office365.com";	
		$mail->Port = 587; // or 587
		$mail->SMTPSecure = "tls";
		$mail->IsHTML(true);
		$mail->Username = $emisor;
		$mail->Password = $clave;
		$mail->SetFrom($emisor,$asunto);
		$mail->Subject = $asunto;
		$mail->Body =$cuerpo;
		$mail->AddAddress($receptor);
		$mail->CharSet = 'ISO-8859-1';

		
	
		if (!($adj1=="")) {
			$mail->AddAttachment($adj1,$adj1);
		}
		
		if(!$mail->Send()) {
			$res="Ocurrio error al enviar correo a: ".$receptor." (". $mail->ErrorInfo.")";
		} else {
			$res="";
		}
		
		
		return $res;
	}
	


	function verificaOficio($depto,$tipo,$elidControl){
		$fecha_actual=date("d/m/Y");
		$anio=date("Y");
		$miConex = new Conexion();
		$ofisolo="0";
		
		$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT count(*) as N from contoficios where CONT_TIPO='".$tipo."' and CONT_CONTROL='".$elidControl."'");
		foreach ($resultado as $row) {$hay=$row["N"];}
		
		if ($hay==0) {
			$numofi="NO HAY CONSECUTIVO";
			$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT CONS_NUMERO, CONS_PRE from pconsoficios where CONS_URES='".$depto."' and CONS_ANIO='".$anio."'");
			foreach ($resultado as $row) {$ofisolo=$row["CONS_NUMERO"]; $numofi=$row["CONS_PRE"]."-".$row["CONS_NUMERO"]."/".$anio;}
			$res=$miConex->afectaSQL($_SESSION['bd'],"UPDATE pconsoficios set CONS_NUMERO=CONS_NUMERO+1 where CONS_URES='".$depto."'");
			
			if (!($numofi=="NO HAY CONSECUTIVO")){
				$res=$miConex->afectaSQL($_SESSION['bd'],"INSERT INTO contoficios (CONT_TIPO,CONT_NUMOFI,CONT_FECHA, CONT_CONTROL,".
						"CONT_USUARIO,_INSTITUCION,_CAMPUS,CONT_SOLO) values ('".$tipo."','".$numofi."','".$fecha_actual."','".$elidControl."',".
						"'".$_SESSION['usuario']."','".$_SESSION['INSTITUCION']."','".$_SESSION['CAMPUS']."','".$ofisolo."');");
			}
			$data[0]["CONT_FECHA"]=$fecha_actual;
			$data[0]["CONT_NUMOFI"]=$numofi;
			$data[0]["CONT_SOLO"]=$ofisolo;
		}
		else {
			$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from contoficios where CONT_TIPO='".$tipo."' and CONT_CONTROL='".$elidControl."'");
			foreach ($resultado as $row) {$data[] = $row;}
		}
		return $data;
	}
	

	function dameCardinal($num){
		$arr=["SINPERIODO","1ER","2DO","3ER","4TO","5TO","6TO","7MO","8VO","9NO","10MO"];
		if (intval($num)>=11) {$res=$num."VO";}
		else $res=$arr[$num];
		return $res;
	}


	
	function getConsecutivoDocumento($tipo,$elidControl){
		$fecha_actual=date("d/m/Y");
		$anio=date("Y");
		$miConex = new Conexion();
		
		$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT count(*) as N from econstancias where TIPO='".$tipo."' and MATRICULA='".$elidControl."'");
		foreach ($resultado as $row) {$hay=$row["N"];}
		
		if ($hay==0) {
			$numofi="NO HAY CONSECUTIVO";
			$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT CONSECUTIVO from econsoficial where ANIO='".$anio."' and TIPO='".$tipo."'");
			foreach ($resultado as $row) {$ofisolo=$row["CONSECUTIVO"]; $numofi=$row["CONSECUTIVO"]."/".$anio;}
			$res=$miConex->afectaSQL($_SESSION['bd'],"UPDATE econsoficial set CONSECUTIVO=CONSECUTIVO+1 where ANIO='".$anio."' and TIPO='".$tipo."'");
			
			if (!($numofi=="NO HAY CONSECUTIVO")){				

				$res=$miConex->afectaSQL($_SESSION['bd'],"INSERT INTO econstancias (CONSECUTIVO,MATRICULA,FECHA, ".
				        "TIPO,USUARIO,_INSTITUCION,_CAMPUS) VALUES (".
						"'".$numofi."','".$elidControl."','".$fecha_actual."','".$tipo."',".
						"'".$_SESSION['usuario']."','".$_SESSION['INSTITUCION']."','".$_SESSION['CAMPUS']."');");
			}
			$data[0]["FECHA"]=$fecha_actual;
			$data[0]["CONSECUTIVO"]=$numofi;
		}
		else {
			$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT * from econstancias where TIPO='".$tipo."' and MATRICULA='".$elidControl."'");
			foreach ($resultado as $row) {$data[] = $row;}
		}
		return $data;
	}


	
	function LoadURES($campo,$depto)
			{				
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],"SELECT ".$campo." from fures where URES_URES='".$depto."'");				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


	public function getJefe($depto){
		
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta($_SESSION["bd"],"SELECT CONCAT(IFNULL(EMPL_ABREVIA,''),' ',EMPL_NOMBRE,' ',EMPL_APEPAT,' ',EMPL_APEMAT) AS NOMBRE ".
				" FROM fures a, pempleados b where URES_URES='".$depto."' and URES_JEFE=EMPL_NUMERO");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
	}
	
	public function getJefeNum($depto){
		
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta($_SESSION["bd"],"SELECT EMPL_NUMERO ".
				" FROM fures a, pempleados b where URES_URES='".$depto."' and URES_JEFE=EMPL_NUMERO");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
	}
	
	
	public function getDatoEmpl($empl,$campo){		
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta($_SESSION["bd"],"SELECT ".$campo." AS DEPTO ".
				" FROM pempleados b where EMPL_NUMERO='".$empl."'");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
		
	}
	
	public function getDatoAlum($alum,$campo){
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta($_SESSION["bd"],"SELECT ".$campo." AS CAMPO ".
				" FROM falumnos b where ALUM_MATRICULA='".$alum."' limit 1");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
	}
	
	public function getDatoGen($tabla,$campo,$campok,$datok){
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta($_SESSION["bd"],"SELECT ".$campo." AS CAMPO ".
				" FROM ".$tabla." where ".$campok."='".$datok."'");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
	}
	
	

	function basico($numero) {
		$valor = array ('uno','dos','tres','cuatro','cinco','seis','siete','ocho',
		'nueve','diez','once','doce','trece','catorce','quince','dieciséis','diecisiete','dieciocho',
		'diecinueve','veinte','veintiuno','veintidos','veintitres',
		 'veinticuatro','veinticinco',
		'veintiseis','veintisiete','veintiocho','veintinueve');
		return $valor[$numero - 1];
		}
		
    function decenas($n) {
		$decenas = array (30=>'treinta',40=>'cuarenta',50=>'cincuenta',60=>'sesenta',
		70=>'setenta',80=>'ochenta',90=>'noventa');
		if( $n <= 29) return $this->basico($n);
		$x = $n % 10;
		if ( $x == 0 ) {
		return $decenas[$n];
		} else return $decenas[$n - $x].' y '. $this->basico($x);
		}
		
	function centenas($n) {
		$cientos = array (100 =>'cien',200 =>'doscientos',300=>'trecientos',
		400=>'cuatrocientos', 500=>'quinientos',600=>'seiscientos',
		700=>'setecientos',800=>'ochocientos', 900 =>'novecientos');
		if( $n >= 100) {
		if ( $n % 100 == 0 ) {
		return $cientos[$n];
		} else {
		$u = (int) substr($n,0,1);
		$d = (int) substr($n,1,2);
		return (($u == 1)?'ciento':$cientos[$u*100]).' '.$this->decenas($d);
		}
		} else return $this->decenas($n);
		}
		
	function miles($n) {
		if($n > 999) {
		if( $n == 1000) {return 'mil';}
		else {
		$l = strlen($n);
		$c = (int)substr($n,0,$l-3);
		$x = (int)substr($n,-3);
		if($c == 1) {$cadena = 'mil '.$this->centenas($x);}
		else if($x != 0) {$cadena = $this->centenas($c).' mil '.$this->centenas($x);}
		else $cadena = $this->centenas($c). ' mil';
		return $cadena;
		}
		} else return $this->centenas($n);
		}
		
	function millones($n) {
		if($n == 1000000) {return 'un millon';}
		else {
		$l = strlen($n);
		$c = (int)substr($n,0,$l-6);
		$x = (int)substr($n,-6);
		if($c == 1) {
		$cadena = ' millon ';
		} else {
		$cadena = ' millones ';
		}
		return $this->miles($c).$cadena.(($x > 0)?$this->miles($x):'');
		}
		}
	
	function aletras($n) {
		switch (true) {
		case ( $n >= 1 && $n <= 29) : return $this->basico($n); break;
		case ( $n >= 30 && $n < 100) : return $this->decenas($n); break;
		case ( $n >= 100 && $n < 1000) : return $this->centenas($n); break;
		case ($n >= 1000 && $n <= 999999): return $this->miles($n); break;
		case ($n >= 1000000): return $this->millones($n);
		}
    }
		
	

	
	
	public function formatFecha($fecha) {
	    $dia=substr($fecha,0,2);
	    $mes=substr($fecha,3,2);
	    $anio=substr($fecha,6,4);
	    return ($anio."-".$mes."-".$dia);
	}
	
	

	public function getFecha($fecha,$tipo) {
		$dias=["Domingo","Lunes", "Martes", "Miercoles", "Jueves","Viernes","Sabado"];
		$meses=["Enero","Febrero", "Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		
		if ($tipo=="DIA") {
		    return($dias[date("w", strtotime($fecha))-1]);
		}
		if ($tipo=="MES") {
			return($meses[date("n", strtotime($fecha))-1]);
		}
		    
	}

	public function getMesLetra($num) {
		$meses=["Enero","Febrero", "Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];		
			return($meses[$num-1]);		    
	}


	public function getMesRomano($num) {
		$meses=["I","II", "III","IV","V","VI","VII","VIII","IX","X","XI","XII"];		
			return($meses[$num-1]);		    
	}

	
	public function  getPie($pdf,$orienta){	
		$top1=253; $top2=243; $left1=10; $left2=160; $iniciaTexto=50; $anchoTexto=110;
		if ($orienta=='H') {$top1=192; $top2=188; $left1=20; $left2=215;  $iniciaTexto=80; $anchoTexto=150;}
		$pre='../../'; if (($orienta=='H2')||($orienta=='V2')) {$pre='../';}

		$direccion=""; $telefonos=""; $pagina="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT * FROM INSTITUCIONES A WHERE A.inst_clave='".$_SESSION["INSTITUCION"]."'");
		foreach ($res as $row) {
			$direccion=$row["inst_direccion"];
			$telefonos=$row["inst_telefono"];
			$pagina=$row["inst_pagina"];
		}
	
		$pdf->Image($pre.'imagenes/empresa/piepag1.png',$left1,$top1,36);
		$pdf->SetFont('Montserrat-Medium','',7);
		$pdf->SetY(-25);
		$elpie= utf8_decode($direccion)." ".utf8_decode($telefonos)."\n ".utf8_decode($pagina);
		
		$pdf->SetX($iniciaTexto);
		$pdf->MultiCell($anchoTexto,3,$elpie,0,'C',false);
		$pdf->Image($pre.'imagenes/empresa/piepag2.png',$left2,$top2,46,20);
	}
	
	public function  getEncabezado($pdf,$orienta){
		$left2=110; $left3=160; $anchoenc=163;
		if ($orienta=='H') {$left2=210; $left3=260; $anchoenc=213;}
		$pre='../../'; if (($orienta=='H2')||($orienta=='V2')) {$pre='../';}
		$pdf->Image($pre.'imagenes/empresa/fondo.png',0,0,187,275);
		$pdf->Image($pre.'imagenes/empresa/encabezado.png',20,8,$anchoenc);
	
		$pdf->AddFont('Montserrat-Black','B','Montserrat-Black.php');
		$pdf->AddFont('Montserrat-Black','','Montserrat-Black.php');
		$pdf->AddFont('Montserrat-Medium','B','Montserrat-Medium.php');
		$pdf->AddFont('Montserrat-Medium','','Montserrat-Medium.php');
		$pdf->AddFont('Montserrat-SemiBold','','Montserrat-SemiBold.php');
		$pdf->AddFont('Montserrat-SemiBold','B','Montserrat-SemiBold.php');
		$pdf->AddFont('Montserrat-ExtraBold','B','Montserrat-ExtraBold.php');
		$pdf->AddFont('Montserrat-ExtraBold','','Montserrat-ExtraBold.php');
		$pdf->AddFont('Montserrat-ExtraBold','I','Montserrat-ExtraBold.php');
		$pdf->AddFont('Montserrat-ExtraLight','I','Montserrat-ExtraLight.php');
		$pdf->AddFont('Montserrat-ExtraLight','','Montserrat-ExtraLight.php');
		
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT * FROM INSTITUCIONES A WHERE A.inst_clave='".$_SESSION["INSTITUCION"]."'");
		$lema=""; $nombreins="";
		foreach ($res as $row) {
			$lema=$row["inst_campo1"];	
			$nombreins=$row["inst_razon"];	
		}
		

		$pdf->SetFont('Montserrat-Black','B',9);
		$pdf->Ln(6);
		$pdf->Cell(0,0,utf8_decode($nombreins),0,0,'R');
		
		$pdf->SetFont('Montserrat-Medium','B',8);
		$pdf->Ln(6);
		
		if ($lema=='') {$lema='';} else {$lema='"'.$lema.'"';}
		$pdf->Cell(0,0,utf8_decode($lema),0,0,'C');
		
	}
	
	
	
	public function getConfInputFile($datos) {
		foreach ($datos as $row) {
			if (($row['tipo']=='IMAGEN')||($row['tipo']=='IMAGEN_DRIVE')||($row['tipo']=='PDF')||
			    ($row['tipo']=='PDF_DRIVE')||($row['tipo']=='ARCHIVO_CARPETA')||($row['tipo']=='ARCHIVO_DRIVE')) {
		 	echo "$('#file_".$row['colum_name']."').ace_file_input({
		                 no_file:'Sin selección ...',
		                 btn_choose:'Elegir',
		                 btn_change:'Cambiar',
		                 droppable:false,
		                 onchange:function() {
			                      eliminarArchivo(\"file_".$row['colum_name']."\",\"img_".$row['colum_name']."\",\"".$row['colum_name']."\");
			             return true;
		              },
		        thumbnail:false, //| true | large
		        whitelist:'gif|png|jpg|jpeg|pdf',
		        blacklist:'exe|php',
		        before_remove: function() {
			        eliminarArchivo(\"file_".$row['colum_name']."\",\"img_".$row['colum_name']."\",\"".$row['colum_name']."\");
			    return true;
		      },
	       });";
		 }
		}
	}
	
	
	public function getConfInputFileEncuestas($datos) {
		foreach ($datos as $row) {
			if (($row['TIPO']=='IMAGEN')||($row['TIPO']=='IMAGEN_DRIVE')||($row['TIPO']=='PDF')||($row['TIPO']=='PDF_DRIVE')){
				echo "$('#file_".$row['CLAVE']."').ace_file_input({
		                 no_file:'Sin selección ...',
		                 btn_choose:'Elegir',
		                 btn_change:'Cambiar',
		                 droppable:false,
		                 onchange:function() {
			                      eliminarArchivo(\"file_".$row['CLAVE']."\",\"img_".$row['CLAVE']."\",\"".$row['CLAVE']."\");
			             return true;
		              },
		        thumbnail:false, //| true | large
		        whitelist:'gif|png|jpg|jpeg|pdf',
		        blacklist:'exe|php',
		        before_remove: function() {
			        eliminarArchivo(\"file_".$row['CLAVE']."\",\"img_".$row['CLAVE']."\",\"".$row['CLAVE']."\");
			    return true;
		      },
	       });";
			}
		}
	}
	
	
	
	public function getReglasVal($rescampos,$op){
		$rules=""; $msj=""; $hay=false; $cadres="";
		foreach ($rescampos as $campos) {
			if (($campos["keys"]=='S') && ($op=='A')) {continue;}
			if (strlen($campos["validacion"])>0){				
				$hay=true;
				$rules.="\t\t\t\t\t\t".$campos["colum_name"].":".$campos["validacion"].",\n";
				$msj.="\t\t\t\t\t\t".$campos["colum_name"].":".$campos["msjval"].",\n";
			}
		}
		if ($hay) {
			$cadres="rules: {\n".substr($rules,0,strlen($rules)-2)."\n\t\t\t\t},\n";
			$cadres.="messages: {\n".substr($msj,0,strlen($msj)-2)."\n\t\t\t\t}\n";
		} 
		return $cadres;
	}
	
	public function getReglasValEncuestas($rescampos,$op){
		$rules=""; $msj=""; $hay=false; $cadres="";
		foreach ($rescampos as $campos) {

			if (strlen($campos["VALIDACION"])>0){
				$hay=true;
				$rules.="\t\t\t\t\t\t".$campos["CLAVE"].":".$campos["VALIDACION"].",\n";
				$msj.="\t\t\t\t\t\t".$campos["CLAVE"].":".$campos["MSJVAL"].",\n";
			}
		}
		if ($hay) {
			$cadres="rules: {\n".substr($rules,0,strlen($rules)-2)."\n\t\t\t\t},\n";
			$cadres.="messages: {\n".substr($msj,0,strlen($msj)-2)."\n\t\t\t\t}\n";
		}
		return $cadres;
	}
	
	
	
	public function getMetodosVal(){
		 $cadres="";
		 
		 $cadres= "$.validator.addMethod(\"time\", function (value, element) { \n".
		 	      "return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);\n".
		          "}, \"Por favor coloque formato HH:MM\");\n";
		 	
		 $cadres= $cadres."$.validator.addMethod(\"horario\", function (value, element) { \n".
				 "return this.optional(element) || /^(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?-(([0-1]?[0-9])|([2][0-3])):([0-5]?[0-9])(:([0-5]?[0-9]))?$/i.test(value);\n".
				 "}, \"Por favor coloque formato HH:MM\");\n";
		 
		 	
		 $cadres=$cadres."jQuery.validator.addMethod(\"fechaESP\", function( value, element) { ".
		 		         " var validator = this; var datePat = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; var fechaCompleta = value.match(datePat);".
		 		         " if (fechaCompleta == null) { $.validator.messages.fechaESP = \"OLA K ASE, ESA FECHA NO ES VALIDA O K ASE?\";return false;}".		 		
		 		         " dia = fechaCompleta[1]; mes = fechaCompleta[3]; anio = fechaCompleta[5];".		 		
		 		         " if (dia < 1 || dia > 31) { $.validator.messages.fechaESP = \"El valor del día debe estar comprendido entre 1 y 31.\";return false;}".
		 		         " if (mes < 1 || mes > 12) { $.validator.messages.fechaESP = \"El valor del mes debe estar comprendido entre 1 y 12.\";return false;}".
		 		         " if ((mes==4 || mes==6 || mes==9 || mes==11) && dia==31) { $.validator.messages.fechaESP = \"El mes \"+mes+\" no tiene 31 días!\";return false;}".
		 		         " if (mes == 2) { var bisiesto = (anio % 4 == 0 && (anio % 100 != 0 || anio % 400 == 0)); ".
		 			     " if (dia > 29 || (dia==29 && !bisiesto)) { $.validator.messages.fechaESP = \"Febrero del \" + anio + \" no contiene \" + dia + \" dias!\";return false;}".
		 		         " } return true; }, \"OLA K ASE, ESA FECHA NO ES VALIDA O K ASE?\"); });";
	
		 return $cadres;
	}
	
	
	public function getCampoLlave($tabla){
		
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT colum_name FROM all_col_comment WHERE table_name='".$tabla."' and keys='S'");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
	}
	
	
	public function getParamSubmit($rescampos,$tabla,$op,$bd){	
		$util= new UtilUser();
		date_default_timezone_set('America/Mexico_City');
		$param="";$resparam="";$paramadd="";
		foreach ($rescampos as $campos) {
			$cad=$campos["colum_name"].":$(\"#".$campos["colum_name"]."\").val(),\n";
			
			if ($campos["tipo"]=="SELECT_MULTIPLE"){				
				$cad=$campos["colum_name"].":  $(\"#".$campos["colum_name"]."\").val()==null?' ':$(\"#".$campos["colum_name"]."\").val().toString(),\n";
			}
			
			if ($campos["tipo"]=="EDITOR"){				
				$cad=$campos["colum_name"].":  $(\"#".$campos["colum_name"]."\").html(),\n";
			}

			if ($campos["tipo"]==":USUARIO"){
				$cad=$campos["colum_name"].":\"".$_SESSION['usuario']."\",\n";
			}
			if ($campos["tipo"]==":FECHA"){
				$cad=$campos["colum_name"].":\"".date("d-m-Y H:i")."\",\n";
			}
			if ($campos["keys"]=="S"){
				$llave=$campos["colum_name"];
			}
			if ($campos["autoinc"]=="AUTOMATICO"){
				$cad=""; 
			}
			$param.=$cad;
		}	
		
		if (($op=="A")||($op=="E")){
			//$llave=$util->getCampoLlave($tabla);	
			$paramadd="campollave".":\"".$llave."\",\n";
			$paramadd.="valorllave".":$(\"#".$llave."\").val(),\n";
		}
		
		$paramadd.="bd".":\"".$bd."\",\n";
		$paramadd.="_INSTITUCION".":\"".$_SESSION["INSTITUCION"]."\",\n";
		$paramadd.="_CAMPUS".":\"".$_SESSION["CAMPUS"]."\",\n";
		
		$resparam.="parametros={\n"."tabla:\"".$tabla."\",\n".$paramadd.substr($param,0,strlen($param)-2)."\n\t\t\t\t};\n";
		return $resparam;
	}
	
	
	
	public function getParamSubmitEncuestas($rescampos,$tabla,$op,$bd,$valorllave,$ciclo){
		$util= new UtilUser();
		$param="";$resparam="";$paramadd="";
		foreach ($rescampos as $campos) {
			$cad=$campos["CLAVE"].":$(\"#".$campos["CLAVE"]."\").val(),\n";
			
			if ($campos["TIPO"]=="SELECT_MULTIPLE"){
				$cad=$campos["CLAVE"].":  $(\"#".$campos["CLAVE"]."\").val()==null?' ':$(\"#".$campos["CLAVE"]."\").val().toString(),\n";
			}
			
			if ($campos["TIPO"]==":USUARIO"){
				$cad=$campos["colum_name"].":\"".$_SESSION['usuario']."\",\n";
			}
			if ($campos["TIPO"]==":FECHA"){
				$cad=$campos["colum_name"].":\"".date("d-m-Y")."\",\n";
			}
			
			$param.=$cad;
		}
		
		if (($op=="A")||($op=="E")){
			//$llave=$util->getCampoLlave($tabla);
			$paramadd="campollave".":\"ID\",\n";
			$paramadd.="valorllave".":$(\"#".$valorllave."\").val(),\n";
		}
		
		$paramadd.="bd".":\"".$bd."\",\n";
		$paramadd.="_INSTITUCION".":\"".$_SESSION["INSTITUCION"]."\",\n";
		$paramadd.="_CAMPUS".":\"".$_SESSION["CAMPUS"]."\",\n";
		$paramadd.="IDRESPONDE".":\"".$_SESSION["usuario"]."\",\n";
		$paramadd.="USUARIO".":\"".$_SESSION["usuario"]."\",\n";
		$paramadd.="FECHAUS".":\"".date("d-m-Y G:i:s")."\",\n";
		$paramadd.="IDENC".":\"".$valorllave."\",\n";
		$paramadd.="CICLO".":\"".$ciclo."\",\n";
		
		$resparam.="parametros={\n"."tabla:\"".$tabla."\",\n".$paramadd.substr($param,0,strlen($param)-2)."\n\t\t\t\t};\n";
		return $resparam;
	}
	
	public function getParamEliminar($modulo,$valorllave){
		$util= new UtilUser();
		$paramadd="";
		$laTabla=$util->getTablaModulo($modulo);
		
		$llave=$util->getCampoLlave($tabla);
		$paramadd.="tabla".":\"".$laTabla.",\n";
		$paramadd.="campollave".":\"".$llave.",\n";
		$paramadd.="valorllave".":".$valorllave."\"\n";
		
		return $paramadd;
		
	}
	
	
	public function superUsuario($usuario){
		$resul="N";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT USUA_SUPER FROM CUSUARIOS WHERE USUA_USUARIO='".$usuario."'");
		foreach ($res as $lin) {$resul=$lin[0];}
		return $resul;
	}
	
	public function getMenus($usuario,$esSuper,$conRol) {
	   $util= new UtilUser();
	   $miConexU = new Conexion();
	  
	   $sq="select distinct(modu_modulo), modu_pred, modu_rapido, modu_rapico, modu_descrip, modu_aplicacion,
                   modu_version, modu_ejecuta, modu_icono, modu_imaico, modu_cancel, modu_teclarap, 
                   modu_auxiliar, modu_automatico, derm_modulo, modu_pagina, derm_inserta, derm_edita, derm_borra, derm_detalle,modu_bd
                   from SDERMODU,CMODULOS where 
                   CMODULOS.modu_modulo=SDERMODU.derm_modulo and modu_cancel='N'
                   and modu_auxiliar<>'S'";
	   
	   $misRoles=$util->losRoles($usuario);
	   if (!($misRoles=='')) {$us=$misRoles;} else {$us="'".$usuario."'";}
	  
	   if (!($conRol)) {$us="'".$usuario."'";}
	   
	   if ($esSuper=='S') {
	   	    $sq="select *,'S' as derm_inserta,'S' as derm_edita ,'S' as derm_borra, 'S' as derm_detalle  from cmodulos where modu_cancel='N' and modu_auxiliar<>'S' order by modu_aplicacion, modu_pred, modu_descrip ASC"; }
	   else {
	   	    $sq=$sq. " and derm_usuario in (".$us.") order by modu_aplicacion, modu_pred, modu_descrip ASC";}

	   $res=$miConexU->getConsulta("SQLite",$sq);
       return $res;
	}
	
	
	
	
	public function getNotificaciones($usuario,$esSuper,$conRol) {
		$util= new UtilUser();
		$miConexU = new Conexion();
	
		$misRoles=$util->losRoles($usuario);
		if (!($misRoles=='')) {$us=$misRoles;} else {$us="'".$usuario."'";}
		
		if (!($conRol)) {$us="'".$usuario."'";}
		
		$sq="select * from enotificaciones h where  STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') ".
				" Between STR_TO_DATE(h.`ENOT_INICIA`,'%d/%m/%Y') and STR_TO_DATE(h.`ENOT_TERMINA`,'%d/%m/%Y')".
				" and ENOT_ID NOT IN (SELECT IDNOT FROM enotivistas where USUARIO='".$usuario."')"; 
		
		if (!($esSuper=='S')) {	$sq=$sq. " and ENOT_USUARIO in (".$us.")";}
		
		$sq=$sq. " ORDER BY ENOT_ID DESC";

		//echo $sq;
		$res=$miConexU->getConsulta("Mysql",$sq);
		return $res;
	}
	
	
	
	public function getEncuestas($usuario,$esSuper,$conRol) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		
		$misRoles=$util->losRoles($usuario);
		if (!($misRoles=='')) {$us=$misRoles;} else {$us="'".$usuario."'";}
		
		if (!($conRol)) {$us="'".$usuario."'";}
		
		$sq="select h.*, (SELECT count(*) FROM encrespuestas where IDENC=h.ID and IDRESPONDE='".$usuario."') as N from encuestas h where  STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') ".
				" Between STR_TO_DATE(h.`INICIA`,'%d/%m/%Y') and STR_TO_DATE(h.`TERMINA`,'%d/%m/%Y')";
		
		if (!($esSuper=='S')) {	$sq=$sq. " and USUARIOS in (".$us.")";}
		
		$sq=$sq. " ORDER BY DESCRIP";
		$res=$miConexU->getConsulta("Mysql",$sq);
		return $res;
	}
	
	
	public function getExamenes($usuario,$tipo) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		
		$sq="select h.*, (SELECT count(*) FROM lincontestar where IDEXAMEN=h.IDEXAMEN and IDPRESENTA='".$usuario."' and TERMINADO='S') as N from vlinaplex h where  STR_TO_DATE(DATE_FORMAT(now(),'%d/%m/%Y'),'%d/%m/%Y') ".
				" Between STR_TO_DATE(h.`INICIA`,'%d/%m/%Y') and STR_TO_DATE(h.`TERMINA`,'%d/%m/%Y') and tipo='".$tipo."'";
		
		$sq=$sq. " ORDER BY 1";
		$res=$miConexU->getConsulta("Mysql",$sq);
		return $res;
	}

	
	public function getSELECTDEP($tabla) {
		$util= new UtilUser();
		$miConexU = new Conexion();		
		$sq="select colum_name, sql from all_col_comment  where tipo in ('SELECT_DEPENDE','SELECT_DEPENDE_BUSQUEDA') and 
             table_name='".$tabla."'";
		$res=$miConexU->getConsulta("SQLite",$sq);
		
		$lista = [];
		$entre=false;
		foreach ($res as $row) {
			
			$elsql=$row["sql"];
			
			$datos = explode('|', $elsql);
			$elsql=$datos[1];
			$graba=false; $cad="";
			$i=0;
			for ($x=0; $x<strlen($elsql); $x++) {
				
				if ($elsql[$x]=='{') {$graba=true;}
				if ($elsql[$x]=='}') {$graba=false;$lista[$i]=substr($cad,1,strlen($cad));$i++; $cad="";}
				if ($graba) {$cad.=$elsql[$x];}
			}
			
			for($i=0;$i<count($lista);$i++){
				$elsql=str_replace ("{".$lista[$i]."}","'\"+$(\"#".$lista[$i]."\").val()+\"'",$elsql);
			}
			$cadFin="\"";
			if (substr($elsql,strlen($elsql)-3,strlen($elsql))=="+\"'") {
				$elsql=substr($elsql,0,strlen($elsql)-3);
				$cadFin="+\"'\"";
			}
			$sqlFin="";

			
		
			for($i=0;$i<count($lista);$i++){
			    
				echo "\n$('#".$lista[$i]."').change(function(){    
                        
                          val_".$row["colum_name"]."=$('#".$row["colum_name"]."').val();
						  parametros={sql:".$elsql.$cadFin.",dato:'1',bd:'Mysql',sel:''}
                          $.ajax({
                              type: \"POST\",
                              url: 'dameselectSeg.php', 
                              success: function(data){   
                                            
                                   $('#".$row["colum_name"]."').html(data);  
                          
                                   adaptarSelectBus(); 
                                   $('#".$row["colum_name"]."').val(val_".$row["colum_name"].");     
                           },
                           error: function(data) {
                              alert('ERROR: '+data);
                           }
                         }); 
                      });\n";

                if (!($entre)) {      
				echo "\n$('#".$lista[$i]."').change();\n"; $entre=true;}
					
			}
		}
		
		return 0;
	}
	
	
	
	
	public function getTablaModulo($modulo){
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT modu_tabla FROM cmodulos A WHERE A.modu_modulo='".$modulo."'");
		foreach ($res as $row) {$laTabla=$row[0];}
		if (strlen($laTabla)<0) {$laTabla=$modulo;}
	    return $laTabla;
	}
	
	
	
	public function getTablaModuloGraba($modulo){
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT modu_tablagraba FROM cmodulos A WHERE A.modu_modulo='".$modulo."'");
		foreach ($res as $row) {$laTabla=$row[0];}
		if (strlen($laTabla)<0) {$laTabla=$modulo;}
		return $laTabla;
	}
	
	
	public function getColGrid($modulo){
		$util= new UtilUser();
		$miConexU = new Conexion();
		$laTabla=$util->getTablaModulo($modulo);
		$res=$miConexU->getConsulta("SQLite","select comments,width, keys, colum_name from all_col_comment WHERE TABLE_NAME='".$laTabla.
				"' and visgrid='S' order by numero");
		return $res;
	}
	
	
	public function getProcesos ($usuario,$esSuper,$modulo) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		if ($esSuper=="S"){ 
			$sql="select proc_proceso, proc_descrip from sprocesos a where a.proc_modulo='".$modulo."' order by proc_descrip";
		}
		else {
			$misRoles=$util->losRoles($usuario);
			if (!($misRoles=='')) {$us=$misRoles;} else {$us=$usuario;}
	
			$sql="select a.proc_proceso, a.proc_descrip  from sprocesos a, sderprocesos b  WHERE 
                  (a.proc_modulo=b.derp_modulo and a.proc_proceso=b.derp_proceso)
	              and b.derp_usuario in (".$us.") and b.derp_modulo in ('".$modulo."') order by proc_descrip";			
		}
		
		$res=$miConexU->getConsulta("SQLite",$sql);
		return $res;
	}
	
	
	public function getTabs($modulo) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$laTabla=$util->getTablaModulo($modulo);
		$res=$miConexU->getConsulta("SQLite","select distinct(seccion) from all_col_comment WHERE TABLE_NAME='".$laTabla."'
                                     and visfrm='S' order by seccion");
		return $res;
	}
	
	
	public function getTabsEncuestas($id) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("Mysql","select distinct(SECCION) from encpreguntas WHERE ID='".$id."'
                                     order by SECCION");
		return $res;
	}
	
	 
	
	public function getCamposForm($modulo) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$laTabla=$util->getTablaModulo($modulo);
		$res=$miConexU->getConsulta("SQLite","select * from all_col_comment WHERE TABLE_NAME='".$laTabla."'
                                     and visfrm='S' order by seccion,numero");
		return $res;
	}
	
	
	public function getCamposFormEncuestas($id) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("Mysql","select * from encpreguntas WHERE IDENC='".$id."'
                                     order by SECCION,CLAVE");		

		return $res;
	}
	
	public function getPermisos($usuario,$esSuper,$modulo) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$perm=[];
		
		if ($esSuper=="S"){ 
			$perm[0]="S";
			$perm[1]="S";
			$perm[2]="S";
		}
		else {			
				$misRoles=$util->losRoles($usuario);
				if (!($misRoles=='')) {$us=$misRoles;} else {$us=$usuario;}

				
				$res=$miConexU->getConsulta("SQLite","SELECT * FROM sdermodu A  WHERE A.derm_usuario in (".$us.") and 
		                                      A.derm_modulo in ('".$modulo."')");
			
				foreach ($res as $row) {
					 $perm[0]=$row["derm_inserta"];
					 $perm[1]=$row["derm_edita"];
					 $perm[2]=$row["derm_borra"];
				}
		}
		
     return $perm;
	}
	
	
	public function getDatosGrid($usuario,$esSuper,$modulo,$bd) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$laTabla=$util->getTablaModulo($modulo);
		
		$res=$miConexU->getConsulta("SQLite","select * from all_col_comment WHERE TABLE_NAME='".$laTabla."' and visgrid='S' order by numero");
		$cad=""; $key="";
		foreach ($res as $row) {
			if (($row["tipo"]=="FECHA") && ($bd=="Oracle")) {$cad=$cad."to_char(".$row["colum_name"].",'dd/mm/yyyy') as ".$row["comments"].","; }
			else {$cad=$cad.$row["colum_name"]." as ".$row["comments"].",";}
			if ($row["keys"]=="S") $key=$row["colum_name"];
		}
		$cad=substr($cad,0,strlen($cad)-1);
		
		$sql="SELECT ".$cad." FROM ".$laTabla." ORDER BY ".$key;
		
		$misRoles=$util->losRoles($usuario);
		if (!($misRoles=='')) {$us=$misRoles;} else {$us=$usuario;}

		$sql=$util->convSQLFiltro($sql,$us);
	    $res=$miConexU->getConsulta($bd,$sql);
	    return $res;
	}
	
	
	
	public function getConsultaFiltro($usuario,$esSuper,$modulo,$bd) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$laTabla=$util->getTablaModulo($modulo);
		
		$res=$miConexU->getConsulta("SQLite","select * from all_col_comment WHERE TABLE_NAME='".$laTabla."' and visgrid='S' order by numero");
		$cad=""; $key="";
		foreach ($res as $row) {
			if (($row["tipo"]=="FECHA") && ($bd=="Oracle")) {$cad=$cad."to_char(".$row["colum_name"].",'dd/mm/yyyy') as ".$row["comments"].","; }
			else {$cad=$cad.$row["colum_name"]." as ".$row["comments"].",";}
			if ($row["keys"]=="S") $key=$row["colum_name"];
		}
		$cad=substr($cad,0,strlen($cad)-1);
		
		$sql="SELECT ".$cad." FROM ".$laTabla." ORDER BY ".$key. " DESC ";
		
		$misRoles=$util->losRoles($usuario);
		if (!($misRoles=='')) {$us=$misRoles;} else {$us=$usuario;}
		
		$sql=$util->convSQLFiltro($sql,$us);
		$res=$sql;
		return $res;
	}
	
	
	
	public function getCamposFrm($laTabla,$bd) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","select * from all_col_comment WHERE TABLE_NAME='".$laTabla."' and visfrm='S' order by numero");
		$cad=""; $key="";
		foreach ($res as $row) {
			if (($row["tipo"]=="FECHA") && ($bd=="Oracle")) {$cad=$cad."to_char(".$row["colum_name"].",'dd/mm/yyyy') as ".$row["colum_name"].","; }
			else {$cad=$cad.$row["colum_name"].",";}
		}
		$cad=substr($cad,0,strlen($cad)-1);
		
		return $cad;
		
	}
	
	
	public function getDatosGridLim($usuario,$esSuper,$modulo,$bd,$lim) {
		$util= new UtilUser();
		$miConexU = new Conexion();
		$laTabla=$util->getTablaModulo($modulo);
		
		$res=$miConexU->getConsulta("SQLite","select * from all_col_comment WHERE TABLE_NAME='".$laTabla."' and visgrid='S' order by numero");
		$cad=""; $key="";
		foreach ($res as $row) {
			$cad=$cad.$row["colum_name"]." as ".$row["comments"].",";
			if ($row["keys"]=="S") $key=$row["colum_name"];
		}
		$cad=substr($cad,0,strlen($cad)-1);
		
		$sql="SELECT ".$cad." FROM ".$laTabla." WHERE ROWNUM<=10 ORDER BY ".$key;
		
		$misRoles=$util->losRoles($usuario);
		if (!($misRoles=='')) {$us=$misRoles;} else {$us=$usuario;}
		
		$sql=$util->convSQLFiltro($sql,$us);
		$res=$miConexU->getConsulta($bd,$sql);
		
		$res=$sql;
		return $res;
	}
	
	
	
	public function devTablas ($csql){
		
		$csq=trim(substr($csql,strpos($csql,"FROM")+4,strlen($csql)));
		
		if (strpos(strtoupper ($csq),"ORDER BY")!==false) {
			$csq=substr($csq,0,strpos($csq," "));
		}
		$i=0;
				
		while (strpos($csq,",")!==false){
			$l[$i]=substr($csq,0,strpos($csq,","));
			$csq=substr($csq,strpos($csq,",")+1,strlen($csq));
			$i++;
		}
		$l[$i]=$csq;
		return $l;
	}
	
	
	public function getCadenaApos($cad) {
		$cap=$cad;
		$i=0;
		while (strpos($cap,",")!==false){
			$lis[$i]=substr($cap,0,strpos($cap,","));
			$cap=substr($cap,strpos($cap,",")+1,strlen($cap));
			$i++;
		}
		$lis[$i]=$cap;
		$cap="";
		for($i=0; $i<=count($lis)-1; $i++){
			{
				$cap=$cap."'".$lis[$i]."',";
			}
		}
		$cap=substr($cap,0,strlen($cap)-1);
		return $cap;
	}
	
	
	public function checaPermiso ($modulo, $user) {
		
		$permisos[0]="N";//tiene derecho
		$permisos[1]="N";//Permiso de Insertar
		$permisos[2]="N";//Permiso de modificar
		$permisos[3]="N";//Permiso de Eliminar
		
		$miConexU = new Conexion();
		if ($miConexU->superUsuario($user)=="S") {$permisos[0]="S";$permisos[1]="S";$permisos[2]="S";$permisos[3]="S";}
		else {
			$rol=$miConexU->losRoles($user);
			$res=$miConexU->getConsulta("SQLite","SELECT DERM_EDITA,DERM_INSERTA,DERM_BORRA FROM SDERMODU A WHERE A.DERM_MODULO='".$modulo."'".
					" AND A.DERM_USUARIO IN (".$rol.")");
			foreach ($res as $row) {
				$permisos[0]="S";
				$permisos[1]=$row[1];
				$permisos[2]=$row[0];
				$permisos[3]=$row[2];
			}
		}
		return $permisos;
	}
	
	
	public function getPermisoTabla ($modulo, $user){
		$miConexU = new Conexion();
		$util= new UtilUser();
		
		
		$res=$miConexU->getConsulta("SQLite","SELECT DERC_CAMPO, DERC_VALUES FROM SDERCAMPOS WHERE DERC_MODULO='".$modulo."' and DERC_USUARIO IN (".$user.") AND DERC_VALUES IS NOT NULL");
		$cad="";
		foreach ($res as $row) {
			if (strlen($row[1])>0) {
				if (strlen($cad)>0){
					$cad=$cad." and ";
				}
				if ($row[1]=="USER") {
					$cad=$cad.$row[0]." IN (".$user.")";
				}
				elseif ($row[1]=="CARRERA") {
					$cad=$cad.$row[0]." IN (".$_SESSION["carrera"].")";
				}
				elseif ($row[1]=="DEPTO") {
					$cad=$cad.$row[0]." IN (".$_SESSION["depto"].")";
				}
				else {
					$cad=$cad.$row[0]." IN (".$util->getCadenaApos($row[1]).")";
				}
			}
		}
		
		return $cad;
	}
	
	
	
	public function getSQLfiltro($sq, $loscampos,$losdatos,$limite){
		$o="";
		$sqNew=$sq;
		$cad="";
		
		
		if (strpos(strtoupper ($sq),"ORDER BY")!==false) {
			$o=substr(strtoupper($sq),strpos(strtoupper ($sq),"ORDER BY"),strlen($sq));
			$sqNew=substr($sq,0,strpos(strtoupper ($sq),"ORDER BY"));
		}
		
		
		$loscamposf= explode(",",$loscampos);
		$losdatosf= explode(",",$losdatos);
		
		if (strlen($loscampos)>0) {
			$operaciones = array("=", ">", "<", "<=",">=","!","<>","|");
			for ($x=0; $x<=count($loscamposf)-1; $x++) {
				$op1=substr($losdatosf[$x],0,1);
				$op2=substr($losdatosf[$x],0,2);
		
				if ($op1=="|") {
					$valor=str_replace("|","','",$losdatosf[$x]);
					$valor=substr($valor,3,strlen($valor));
					$cad.=" AND ".$loscamposf[$x]." in ('".$valor."')"; 
				}
				else if (in_array($op2,$operaciones)) {
					$valor=substr($losdatosf[$x],2,strlen($losdatosf[$x]));
					$cad.=" AND ".$loscamposf[$x].$op2."'".$valor."'"; 
				} else if (in_array($op1,$operaciones)){
					$valor=substr($losdatosf[$x],1,strlen($losdatosf[$x]));
					$cad.=" AND ".$loscamposf[$x].$op1."'".$valor."'"; 
				} 
				else {
				    $cad.=" AND ".$loscamposf[$x]." like '%".$losdatosf[$x]."%'"; 
				}
			}

	        $cad=substr($cad,4,strlen($cad));
		}
		
		if (strpos(strtoupper($sqNew),"WHERE")!==false) {
			
			$sq1=substr($sqNew,0,strpos(strtoupper($sqNew),"WHERE")+5);
			$sq2=substr($sqNew,strpos(strtoupper($sqNew),"WHERE")+5, strlen($sqNew));
			
			
			if (strlen($cad)>0) {
				$res=$sq1." ".$cad." AND ".$sq2;
			}
			else {
				$res=$sq1." ".$sq2;
			}
		}
		else {
			$sq1=$sqNew;
			if (strlen($cad)>0) {
				$res=$sq1." WHERE ".$cad;
			}
			else {
				$res=$sq1;
			}
		}
		$lim='';
		if ($limite=="S") {$lim=' LIMIT 30';}
		$res=$res." ".$o.$lim;
		
		return $res;
		
	}
	
	
	public function convSQLFiltro($sq, $user){
		$miConexU = new Conexion();
		$util= new UtilUser();
		$l=$util->devTablas($sq);
		$o="";
		$sqNew=$sq;
		$cad="";
		
		
		if (strpos(strtoupper ($sq),"ORDER BY")!==false) {
			$o=substr(strtoupper($sq),strpos(strtoupper ($sq),"ORDER BY"),strlen($sq));
			$sqNew=substr($sq,0,strpos(strtoupper ($sq),"ORDER BY"));
		}
		
		
		
		for ($x=0; $x<=count($l)-1; $x++) {
			$ope="";
			$pt=$util->getPermisoTabla($l[$x],$user);
			
			if ((strlen($cad)>0)&&(strlen($pt)>0)){
				$ope=" AND ";
			}
			$cad=$cad.$ope.$pt;
		}
		
		$filtro=" _INSTITUCION='".$_SESSION['INSTITUCION']."' AND _CAMPUS='".$_SESSION['CAMPUS']."'";
		if (strlen($cad)>0) {$cad.=" AND ".$filtro; }
		else {$cad.=$filtro; }
		
		
		if (strpos(strtoupper($sqNew),"WHERE")!==false) {
			
			$sq1=substr($sqNew,0,strpos(strtoupper($sqNew),"WHERE")+5);
			$sq2=substr($sqNew,strpos(strtoupper($sqNew),"WHERE")+5, strlen($sqNew));
			
			
			if (strlen($cad)>0) {
				$res=$sq1." ".$cad." AND ".$sq2;
			}
			else {
				$res=$sq1." ".$sq2;
			}
		}
		else {
			$sq1=$sqNew;
			if (strlen($cad)>0) {
				$res=$sq1." WHERE ".$cad;
			}
			else {
				$res=$sq1;
			}
		}
		
		$res=$res." ".$o;
		
		return $res;
		
	}
	
	
	public function losRoles($user){
		$resul="";
		$miConexU = new Conexion();
		$res=$miConexU->getConsulta("SQLite","SELECT usua_usuader FROM CUSUARIOS WHERE usua_usuario='".$user."'");
		$resul="'".$user."',";
		
		foreach ($res as $row) {
			if (strlen($row[0])>0) {$resul.="'".str_replace (",","','",$row[0])."',"; }
			
		}
		$resul=substr($resul,0,strlen($resul)-1);		
		return $resul;
	}
	
	
	public function getCampos($tabla, $tipo, $getID) {
		try {
			$miConexU = new Conexion();	
			$res=$miConexU->getConsulta("SQLite","SELECT COUNT(*) AS NUM FROM CAMPOSABC WHERE ".$tipo."='".$tabla."'");		
			foreach ($res as $row) {$numCampos=$row[0];}
			if ($numCampos>0) {
				$res2=$miConexU->getConsulta("SQLite","SELECT CAMPO,TITCOLUMNA,ANCHO FROM CAMPOSABC WHERE ".$tipo."='".$tabla."' ORDER BY ORDEN");				
				$j=1;
				$cadSQL="";
				foreach ($res2 as $row2) {
					if ($getID=="S") {$losCampos[0][$j-1]=$row2[0];} else {$losCampos[0][$j-1]=$row2[1];}
					
					$losCampos[1][$j-1]=$row2[2]*10;
					$j++;
				}
			}
			else {
				$losCampos[0][0]=":";
				$losCampos[1][0]=":"; };
				
		} catch (PDOException $e) {
			print "�Error!: " . $e->getMessage() . "<br/>";
			die();
		}
		$link = null;
		$sql = null;
		return $losCampos;
	}
	
	

	public function convTitulo($cad) {
		$cadRes="";
		if (!($cad=='')) {			
			for($i=0;$i<strlen($cad)-1;$i++) {
				
				if ($cad[$i]==" ") { $cadRes.=" ".strtoupper($cad[$i+1]);$i++;}
				else { $cadRes.=strtolower($cad[$i]);} 
			}
			
			$cadRes[0]=strtoupper($cadRes[0]);
		}
		else {$cadRes="";}
		return $cadRes;
	}

	
	
	public function addFiltro ($filtroAdd, $sql){
		$filtro =str_replace("-","'",$filtroAdd);
		$filtro =str_replace("\$_","'%",$filtro);
		$filtro =str_replace("_$","%'",$filtro);
		$filtro =str_replace("$","=",$filtro);
		$filtro =str_replace(">_",">=",$filtro);
		$filtro =str_replace("<_","<=",$filtro);
		
		
		if (strpos(strtoupper($sql),"WHERE")!==false) {
			$sq1=trim(substr($sql,0,strpos(strtoupper(trim($sql)),"WHERE")));
			
			if (strpos(strtoupper($sql),"ORDER BY")!==false) {
				$sq2=trim(substr($sql,strpos(strtoupper($sql),"WHERE")+6,strpos(strtoupper($sql),"ORDER BY")-strpos(strtoupper($sql),"WHERE")-6));
				$sq3=trim(substr($sql,strpos(strtoupper($sql),"ORDER BY")+9,strlen($sql)));
			}
			else {
				$sq2=trim(substr($sql,strpos(strtoupper($sql),"WHERE")+6,strlen($sql)));
				$sq3="";
			}
			
			
		}
		else {
			if (strpos(strtoupper($sql),"ORDER BY")!==false) {
				
				$sq1=trim(substr($sql,0,strpos(strtoupper($sql),"ORDER BY")));
				$sq2="";
				$sq3=trim(substr($sql,strpos(strtoupper($sql),"ORDER BY")+9,strlen($sql)));
			}
			
			if ((strpos(strtoupper($sql),"WHERE")==false)&&(strpos(strtoupper($sql),"ORDER BY")==false)){
				$sq1=$sql;
				$sq2="";
				$sq3="";
			}
		}
		
		
		if (strlen($sq2)>0) {$sqlNew=$sq1." WHERE ".$filtro." AND ".$sq2; }
		else { $sqlNew=$sq1." WHERE ".$filtro; }
		
		if (strlen($sq3)>0) {
			$sqlNew=$sqlNew." ORDER BY ".$sq3;
		}
		
		return $sqlNew;
		
	}
	
	
}