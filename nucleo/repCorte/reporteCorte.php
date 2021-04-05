
<?php session_start(); if (($_SESSION['inicio']==1)) {
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	require('../../fpdf/PDF_WriteTag.php');
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";
	$nivel="../../";
	

	
	
	class PDF extends PDF_WriteTag {
   	
		/*========================================================================================================*/
		var $widths;
		var $aligns;
		
		function SetWidths($w) {$this->widths=$w;}
		
		function SetAligns($a) {$this->aligns=$a;}
		
		function Row($data)
		{
			//Calculate the height of the row
			$nb=0;
			for($i=0;$i<count($data);$i++)
				$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
				$h=5*$nb;
				//Issue a page break first if needed
				$this->CheckPageBreak($h);
				//Draw the cells of the row
				for($i=0;$i<count($data);$i++)
				{
					$w=$this->widths[$i];
					$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
					//Save the current position
					$x=$this->GetX();
					$y=$this->GetY();
					//Draw the border
					$this->Rect($x,$y,$w,$h);
					//Print the text
					$this->MultiCell($w,4,$data[$i],0,$a);
					//Put the position to the right of the cell
					$this->SetXY($x+$w,$y);
				}
				//Go to the next line
				$this->Ln($h);
		}
		
		function CheckPageBreak($h)
		{
			//If the height h would cause an overflow, add a new page immediately
			if($this->GetY()+$h>$this->PageBreakTrigger)
				$this->AddPage($this->CurOrientation);
		}
		
		function NbLines($w,$txt)
		{
			//Computes the number of lines a MultiCell of width w will take
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
				$w=$this->w-$this->rMargin-$this->x;
				$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
				$s=str_replace("\r",'',$txt);
				$nb=strlen($s);
				if($nb>0 and $s[$nb-1]=="\n")
					$nb--;
					$sep=-1;
					$i=0;
					$j=0;
					$l=0;
					$nl=1;
					while($i<$nb)
					{
						$c=$s[$i];
						if($c=="\n")
						{
							$i++;
							$sep=-1;
							$j=$i;
							$l=0;
							$nl++;
							continue;
						}
						if($c==' ')
							$sep=$i;
							$l+=$cw[$c];
							if($l>$wmax)
							{
								if($sep==-1)
								{
									if($i==$j)
										$i++;
								}
								else
									$i=$sep+1;
									$sep=-1;
									$j=$i;
									$l=0;
									$nl++;
							}
							else
								$i++;
					}
					return $nl;
		}
		
   	
   	        var $eljefe="";
   	        var $eljefepsto="";
			var $inicia="";
			var $termina="";

			var $eldocente="";
	


			
			function LoadCorte()
			{				
				$miConex = new Conexion();
				$elsql="SELECT * FROM ecortescal where ID='".$_GET['corte']."'";
				
				$resultado=$miConex->getConsulta($_SESSION['bd'],$elsql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


			function LoadCiclo()
			{				
				$miConex = new Conexion();
				$elsql="SELECT * FROM ciclosesc where CICL_CLAVE='".$_GET['ciclo']."'";
				
				$resultado=$miConex->getConsulta($_SESSION['bd'],$elsql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


			function LoadApr($elsql)
			{				
				$miConex = new Conexion();
			
				$resultado=$miConex->getConsulta($_SESSION['bd'],$elsql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
	
			function LoadMaterias()
			{				
				$miConex = new Conexion();
				$elsql="SELECT a.*, (SELECT COUNT(*) from dlista n where n.IDGRUPO=a.IDDETALLE and n.BAJA='N') as ALUMNOS".
				" FROM vedgrupos a where CICLO='".$_GET["ciclo"]."' and DEPTO='".$_GET["depto"].
				"' and PROFESOR='".$_GET["profesor"]."' order by PROFESOR,MATERIA";
			
				$resultado=$miConex->getConsulta($_SESSION['bd'],$elsql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
			

			function LoadFirmas($empl)
			{				
				$miConex = new Conexion();
				$elsql="SELECT a.EMPL_NUMERO, CONCAT(a.EMPL_ABREVIA,' ',a.EMPL_NOMBRE,' ',a.EMPL_APEPAT,' ',a.EMPL_APEMAT) AS NOMBRE,".
				"URES_DESCRIP AS DEPARTAMENTO, URES_JEFE as JEFE, CONCAT(b.EMPL_ABREVIA,' ',b.EMPL_NOMBRE,' ',b.EMPL_APEPAT,' ',b.EMPL_APEMAT) AS NOMBREJEFE, ".
				" b.EMPL_FIRMAOF AS PSTOJEFE FROM pempleados a, fures c, pempleados b where a.EMPL_NUMERO='".$empl."' and a.EMPL_DEPTO=URES_URES and URES_JEFE=b.EMPL_NUMERO";

			
				$resultado=$miConex->getConsulta($_SESSION['bd'],$elsql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

		
			function LoadUnidades($materia,$grupo)
			{				
				$miConex = new Conexion();

				$elsql="SELECT GROUP_CONCAT(NUMUNIDAD) as UNIDADES, count(*) as NUNIDADES from eplaneacion where STR_TO_DATE(FECHA,'%d/%m/%Y') BETWEEN ".
				" STR_TO_DATE('".$this->inicia."','%d/%m/%Y') AND ".
				" STR_TO_DATE('".$this->termina."','%d/%m/%Y') AND MATERIA='".$materia."' AND GRUPO='".$grupo."'";

				$resultado=$miConex->getConsulta($_SESSION['bd'],$elsql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}




			function LoadDatosGen()
			{
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta("SQLite","SELECT * from INSTITUCIONES where _INSTITUCION='".$_SESSION['INSTITUCION']."'");
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}
			
			function Header()
			{
				$miutil = new UtilUser();
				$miutil->getEncabezado($this,'V');	
				
				
			}
			
			

			function Footer()
			{				
				$miutil = new UtilUser();
				$miutil->getPie($this,'V');

				
				
				$this->SetX(10);$this->SetY(-60);
				$this->SetFont('Montserrat-ExtraBold','B',10);
				$this->Cell(75,0,utf8_decode('DOCENTE'),0,0,'C');
				$this->Cell(20,0,'',0,0,'L');
				$this->Cell(75,0,utf8_decode($this->eljefepsto),0,0,'C');


				$this->SetX(10);$this->SetY(-45);
				$this->Cell(75,0,utf8_decode($this->eldocente),0,0,'C');
				$this->Cell(20,0,'',0,0,'L');
				$this->Cell(75,0,utf8_decode($this->eljefe),0,0,'C');
				
			}
			
			function LoadProf($matricula)
			{
				$miConex = new Conexion();
				$resultado=$miConex->getConsulta($_SESSION['bd'],
						"select  EMPL_NUMERO, CONCAT(EMPL_NOMBRE,' ',EMPL_APEPAT,' ',EMPL_APEMAT) as NOMBRE, EMPL_CORREOINS ".
						"from pempleados where EMPL_NUMERO='".$matricula."'");
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

		}
		
		$pdf = new PDF('P','mm','Letter');
		header("Content-Type: text/html; charset=UTF-8");
		
		$pdf->SetFont('Arial','',10);
		$pdf->SetMargins(25, 25 , 25);
		$pdf->SetAutoPageBreak(true,30); 
		$pdf->AddPage();
		
	
		 
		$dataCorte = $pdf->LoadCorte();
		$dataCiclo = $pdf->LoadCiclo();
		$dataMaterias = $pdf->LoadMaterias();
		$pdf->inicia=$dataCorte[0]["INICIA"];
		$pdf->termina=$dataCorte[0]["TERMINA"];


		$pdf->SetStyle("p","Montserrat-Medium","",10,"0,0,0");
        $pdf->SetStyle("vs","Montserrat-Medium","U",10,"0,0,0");
		$pdf->SetStyle("vsb","Montserrat-ExtraBold","UB",10,"0,0,0");
      


		$pdf->Ln();
		$pdf->SetFont('Montserrat-ExtraBold','',9);
		$pdf->Cell(0,0,utf8_decode("ACREDITACIÓN DE ASIGNATURAS"),0,1,'C');	
		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-SemiBold','',9);
		$pdf->Cell(0,0,utf8_decode("REPORTE PARCIAL"),0,1,'C');	
		$pdf->Ln(10);
	
	

			
		$txt=utf8_decode("<p><vsb>".$dataCorte[0]["DESCRIPCION"]."</vsb>        DEL SEMESTRE: <vsb>".$dataCiclo[0]["CICL_DESCRIP"]."</vsb></p>");
        $pdf->WriteTag(0,6,$txt,0,"J",0,0);
		$pdf->Ln();
		$txt=utf8_decode("<p>DOCENTE: <vsb>".$dataMaterias[0]["PROFESORD"]."</vsb></p>");
        $pdf->WriteTag(0,6,$txt,0,"J",0,0);
		
		$c=0; $dif=1; $matantes=$dataMaterias[0]["MATERIA"];
		foreach($dataMaterias as $row) {			
			if ($matantes!=$row["MATERIA"]){			
				$matantes=$row["MATERIA"];
				$dif++;
			}
			$c++;
		}

		$dataMaterias = $pdf->LoadMaterias();
		$pdf->Ln();
		$txt=utf8_decode("<p>NO. DE GRUPOS ATENDIDOS: <vsb>".$c."</vsb>    NO. DE ASIGNATURAS DIFERENTES: <vsb>".$dif."</vsb></p>");
        $pdf->WriteTag(0,6,$txt,0,"J",0,0);


		foreach($dataMaterias as $row) {			
			if ($matantes!=$row["MATERIA"]){			
				$matantes=$row["MATERIA"];
				$dif++;
			}
			$c++;
		}


		$pdf->Ln(5);
		$pdf->SetFillColor(172,31,6);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.2);
		

		$header = array('ASIGNATURA', 'COMP/SEM', 'CARRERA','A','B','C','D','E');		
		$w = array(55, 20,20,15,15,15,15,15);
		$pdf->SetFont('Montserrat-ExtraBold','B',7);
		$pdf->SetWidths(array(55, 20,20,15,15,15,15,15));	
		for($i=0;$i<count($header);$i++)
			$pdf->Cell($w[$i],7,$header[$i],1,0,'C',true);
			$pdf->Ln();
			// Restauraci�n de colores y fuentes
			$pdf->SetFillColor(255,255,255);
			$pdf->SetTextColor(0);
			$pdf->SetFont('');
			// Datos
			$fill = false;
			$pdf->SetFont('Montserrat-Medium','',6);
			$suma=0;
			$alto=3;
			if ($dataMaterias) {

				

				foreach($dataMaterias as $row)
				{

					$totApr=0;$totRep=0;$total=0;$numUni=0;

					$dataUni= $pdf->LoadUnidades($row["MATERIA"],$row["SIE"]);

					$numUni=$dataUni[0]["NUNIDADES"];
					$lasUni=explode(",",$dataUni[0]["UNIDADES"]);	
					if ($numUni>0) {			
							foreach($lasUni as $rowUni) {
								$cadsqlUni=" ifnull(LISPA".$rowUni.",'0')>=70 OR ";
								$cadsqlUniR=" ifnull(LISPA".$rowUni.",'0')<70 OR ";

								$elsql="SELECT count(*) as NUM, sum(if ((LISPA".intval($rowUni).">=70 AND BAJA='N'),1,0)) AS APR, ".
								" sum(if ((LISPA".intval($rowUni)."<70 and BAJA='N'),1,0)) AS REP from dlista where  IDGRUPO='".$row["IDDETALLE"]."'";
								
								$dataApr = $pdf->LoadApr($elsql);
								$totApr+=$dataApr[0]["APR"];
								$totRep+=$dataApr[0]["REP"];
								$total+=$dataApr[0]["NUM"];

							}
							$total=round(($total/$numUni),0);							
							$totApr=round(($totApr/$numUni),0);
							$totRep=$total-$totApr;
							$porcApr=round((($totApr/$total)*100),0);
							$porcRep=100-$porcApr;
						}
						else {$total="SC";$totApr="SC";$totRep="SC";$porcApr="SC";$porcRep="SC";}
					


					//$this->setX(10);
					$pdf->Row(array(utf8_decode($row["MATERIAD"]),
					        utf8_decode($dataUni[0]["UNIDADES"]."(".$dataUni[0]["NUNIDADES"].") / ".$row["SEMESTRE"]),utf8_decode($row["CARRERAC"]),
							utf8_decode($total),utf8_decode($totApr),
							utf8_decode($porcApr."%"),utf8_decode($totRep),
							utf8_decode($porcRep."%")
					));
				}
			}

		$pdf->Ln(10);
		$pdf->SetFont('Montserrat-ExtraBold','',9);
		$pdf->Cell(5,0,utf8_decode("A"),0,0,'J');
		$pdf->SetFont('Montserrat-Medium','',9);	
		$pdf->Cell(5,0,utf8_decode("TOTAL DE ALUMNOS POR MATERIA"),0,0,'J');

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','',9);
		$pdf->Cell(5,0,utf8_decode("B"),0,0,'J');
		$pdf->SetFont('Montserrat-Medium','',9);	
		$pdf->Cell(5,0,utf8_decode("NÚMERO DE ALUMNOS QUE ALCANZARON LA COMPETENCIA"),0,0,'J');
	
		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','',9);
		$pdf->Cell(5,0,utf8_decode("C"),0,0,'J');
		$pdf->SetFont('Montserrat-Medium','',9);	
		$pdf->Cell(5,0,utf8_decode("PORCENTAJE DE ALUMNOS QUE ALCANZARON LA COMPETENCIA"),0,0,'J');

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','',9);
		$pdf->Cell(5,0,utf8_decode("D"),0,0,'J');
		$pdf->SetFont('Montserrat-Medium','',9);	
		$pdf->Cell(5,0,utf8_decode("NÚMERO DE ALUMNOS QUE NO ALCANZARON LA COMPETENCIA"),0,0,'J');

		$pdf->Ln(5);
		$pdf->SetFont('Montserrat-ExtraBold','',9);
		$pdf->Cell(5,0,utf8_decode("E"),0,0,'J');
		$pdf->SetFont('Montserrat-Medium','',9);	
		$pdf->Cell(5,0,utf8_decode("PORCENTAJE DE ALUMNOS QUE NO ALCANZARON LA COMPETENCIA"),0,0,'J');


		$dataFirmas=$pdf->LoadFirmas($_GET["profesor"]);
		$pdf->eljefe=$dataFirmas[0]["NOMBREJEFE"];
		$pdf->eljefepsto=$dataFirmas[0]["PSTOJEFE"];

		$pdf->eldocente=$dataFirmas[0]["NOMBRE"];




		$pdf->Output(); 

 } else {header("Location: index.php");}
 
 ?>
