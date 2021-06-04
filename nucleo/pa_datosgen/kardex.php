
<?php session_start(); if (($_SESSION['inicio']==1)) {
	header('Content-Type: text/html; charset='.$_SESSION['encode']);
	require('../../fpdf/fpdf.php');
	include("../.././includes/Conexion.php");
	include("../.././includes/UtilUser.php");
	$miConex = new Conexion();
	$miUtil= new UtilUser();
	$logouser="../../imagenes/login/sigea.png";
	$nivel="../../";
	

	
	
	class PDF extends FPDF {
       
        
        function parseVar($key='',$value='') {
            if(empty($key) or empty($value)) return;
            $nb = $this->page;
            for($n=1;$n<=$nb;$n++) {
               $this->pages[$n] = str_replace($key,$value,$this->pages[$n]);
            }
         }

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
                $h=4*$nb;
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

/*===================================================================================================================*/
   	        var $eljefe="";
   	        var $eljefepsto="";
 
   	
			function LoadDatosCursadas()
			{				
                $miConex = new Conexion();
                $sql="SELECT MATRICULA, NOMBRE,MATERIA, MATERIAD, SEMESTRE,CREDITO,TIPOMAT, VECES, PRIMERA, SEGUNDA, TERCERA, ".
                "(CASE WHEN TIPOMAT='AC' THEN 'AC'  ELSE max(CAL) END) AS CAL,".
                " MAX(TCAL) as TCAL FROM kardexcursadas  where MATRICULA='".$_GET["matricula"]."' AND CERRADO='S' ".
                " GROUP BY  MATRICULA, NOMBRE,MATERIA, MATERIAD, SEMESTRE,CREDITO,TIPOMAT, VECES, PRIMERA, SEGUNDA, TERCERA ".
                " ORDER BY SEMESTRE, MATERIAD";
                
            
           
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

            function LoadDatosCiclo()
			{				
                $miConex = new Conexion();
                $sql="SELECT getciclo() FROM dual ";
                
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
            }
            

            function LoadDatosCursando($ciclo)
			{	
                $data=[];			
                $miConex = new Conexion();
                $sql="SELECT * FROM kardexcursadas where MATRICULA='".$_GET["matricula"]."' and CICLO='".$ciclo."' AND CERRADO='N' ORDER BY SEMESTRE, MATERIAD";
                
               
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}

            function LoadDatosporCursar($ciclo)
			{	
                $data=[];			
                $miConex = new Conexion();
                $sql="select p.VMAT_MATERIA AS MATERIA, p.VMAT_MATERIAD AS MATERIAD, p.VMAT_CUATRIMESTRE AS SEMESTRE,".
                " p.`VMAT_CREDITO` AS CREDITO from falumnos o, vmatciclo p ".
                " where o.ALUM_MAPA=p.VMAT_MAPA and  ifnull(p.CVEESP,'0')='0' ".
                " and o.ALUM_MATRICULA='".$_GET["matricula"]."' and VMAT_TIPOMAT NOT IN ('T') ".
                " and VMAT_MATERIA NOT IN (SELECT MATCVE from dlista h where ".
                " h.ALUCTR='".$_GET["matricula"]."' and (PDOCVE='".$ciclo."')) ".
                " AND VMAT_MATERIA NOT IN (SELECT MATCVE FROM dlista where ALUCTR='".$_GET["matricula"]."'".
                " AND LISCAL>=70)".
                " UNION ".
                " select p.VMAT_MATERIA AS MATERIA, p.VMAT_MATERIAD AS MATERIAD,p.VMAT_CUATRIMESTRE AS SEMESTRE,".
                " p.`VMAT_CREDITO` AS CREDITO from falumnos o, vmatciclo p ".
                " where o.ALUM_MAPA=p.VMAT_MAPA  and o.ALUM_MATRICULA='".$_GET["matricula"]."' ".
                " and VMAT_TIPOMAT NOT IN ('T') AND ifnull(p.CVEESP,'0')=ALUM_ESPECIALIDAD ".
                " and VMAT_MATERIA NOT IN (SELECT MATCVE from dlista h where h.ALUCTR='".$_GET["matricula"]."' ".
                " and ( PDOCVE='".$ciclo."')) AND VMAT_MATERIA NOT IN (SELECT MATCVE FROM dlista where ALUCTR='".$_GET["matricula"]."'
                AND LISCAL>=70)";
               // echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
				foreach ($resultado as $row) {
					$data[] = $row;
				}
				return $data;
			}


            function LoadDatosAlumnos()
			{				
                $miConex = new Conexion();
                $sql="select ALUM_MATRICULA, CONCAT(ALUM_NOMBRE, ' ',ALUM_APEPAT, ' ',ALUM_APEMAT) AS NOMBRE, ".
                " ALUM_PERCONV AS PERCONV, ALUM_CARRERAREG AS CARRERA, ALUM_ACTIVO AS SITUACION, ALUM_CICLOTER AS CICLOTER, ".
                " ALUM_CICLOINS AS CICLOINS, CARR_DESCRIP AS CARRERAD, ".
                " PLACRED, PLAMAT,  c.CLAVEOF AS ESPECIALIDAD, ALUM_MAPA AS MAPA,".
                " getAvanceCred('".$_GET["matricula"]."') as AVANCE, ".
                " getPromedio('".$_GET["matricula"]."','N') as PROMEDIO_SR,".
                " getPromedio('".$_GET["matricula"]."','S') as PROMEDIO_CR, ".
                " getPeriodos('".$_GET["matricula"]."',getciclo()) AS PERIODOS,".
                " (select SUM(a.CREDITO) from kardexcursadas a where a.CICLO=getciclo() and CERRADO='N' and a.MATRICULA='".$_GET["matricula"]."') AS CRECUR, ".
                " (select SUM(a.CREDITO) from kardexcursadas a where CERRADO='S' and a.cal>=70 and a.MATRICULA='".$_GET["matricula"]."') AS CREDACUM ".
                " from falumnos a LEFT outer JOIN especialidad c on (a.ALUM_ESPECIALIDAD=c.ID), ccarreras b, mapas d where ".
                " CARR_CLAVE=ALUM_CARRERAREG".
                " and ALUM_MAPA=d.MAPA_CLAVE and a.ALUM_MATRICULA='".$_GET["matricula"]."'";
               //echo $sql;
				$resultado=$miConex->getConsulta($_SESSION['bd'],$sql);				
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
                //Para que cuando se cambie a la otra pagina empiece a la derecha y la stablas no se descuadren
                $this->SetX(10);
				$this->Ln(5);	
			}
			
			

			function Footer()
			{				
                $miutil = new UtilUser();
                $nombre=$miutil->getJefe('303');//Nombre del puesto de Recursos Humanos
                $miutil->getPie($this,'V');
                
				$this->SetFont('Montserrat-Medium','',5);
                $this->SetX(10);$this->SetY(-60);
                $this->Cell(0,5,"CR: CREDITOS CAL: CALIFICACION",'',0,'L');
                $this->SetX(90);
                $this->Cell(0,5,"MAT.TOT: MATERIAS TOTALES",'',0,'L');
                $this->SetX(125);
                $this->Cell(0,5,"CRE.ACU: CREDITOS ACUMULADOS",'',1,'L');

                $this->SetX(10);$this->SetY(-57);
                $this->Cell(0,5,"TC: TIPO DE CALIFICACION. 1 ORDINARIO 2 REGULARIZACION 3 EXTRAORDINARIO",'',0,'L');
                $this->SetX(90);
                $this->Cell(0,5,"MAT.CUR: MATERIAS CURSADAS",'',0,'L');
                $this->SetX(125);
                $this->Cell(0,5,"CRE.CUR: CREDITOS CURSANDO",'',1,'L');

                
                $this->SetX(10);$this->SetY(-54);
                $this->Cell(0,5,"4 ORDINARIO EN REPITE 5 REGULARIZACION EN REPITE 6 EXAMEN ESPECIAL",'',0,'L');
                $this->SetX(90);
                $this->Cell(0,5,"MAT.APR: MATERIAS APROBADAS",'',0,'L');
                $this->SetX(125);
                $this->Cell(0,5,"PCTJE: PORCENTAJE DE CREDITOS",'',1,'L');

                $this->SetX(10);$this->SetY(-51);
                $this->Cell(0,5,"7 EX.ESPECIAL POR 2A 91 CONVALIDACION 92 REVALIDACION 93 EQUIVALENCIA",'',0,'L');
                $this->SetX(90);
                $this->Cell(0,5,"MAT.APR.AC: MATERIAS APROBADAS AC",'',0,'L');
                $this->SetX(125);
                $this->Cell(0,5,"NP.CONV: PERIODOS CONVALIDADOS",'',1,'L');

                $this->SetX(10);$this->SetY(-48);
                $this->Cell(0,5,"AC: CALIFICACION CON VALOR AC SIN VALOR NUMERICO",'',0,'L');
                $this->SetX(90);
                $this->Cell(0,5,"CRE.TOT: CREDITOS TOTALES	",'',0,'L');
                $this->SetX(125);
                $this->Cell(0,5,"NPRDO: NUMERO DE PERIODO ACTUAL",'',1,'L');

				$this->SetX(10);$this->SetY(-35);
                $this->SetFont('Montserrat-Medium','',5);
                $this->Cell(200,5,"LAS CALIFICACIONES QUE AMPARA EL PRESENTE DOCUMENTO, SERAN VALIDAS, PREVIO COTEJO DE LAS ACTAS CORRESPONDIENTES",'',0,'C'); 

								
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
		$pdf->SetMargins(10, 25 , 25);
		$pdf->SetAutoPageBreak(true,60); 
        $pdf->AddPage();
        $dataCiclo=$pdf->LoadDatosCiclo();
        $elciclo=$dataCiclo[0][0];
		 
        $dataAlum = $pdf->LoadDatosAlumnos();
        $data = $pdf->LoadDatosCursadas();
        $data2 = $pdf->LoadDatosCursando($elciclo);
        $miutil = new UtilUser();
        
        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(0,0,'KARDEX DEL ALUMNO',0,1,'C');
        $pdf->Ln(5);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'ALUMNO: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(30);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["ALUM_MATRICULA"]." ".$dataAlum[0]["NOMBRE"]),0,1,'L');
        $fecha=date("d/m/Y"); 
        $pdf->SetFont('Montserrat-Medium','',9); $pdf->setX(195); $pdf->Cell(0,0,$fecha,0,1,'R');
        
        //======================================================================
        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'CARRERA: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(30);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["CARRERAD"]),0,1,'L');
        
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(115); $pdf->Cell(0,0,'CRE.TOT: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(135);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["PLACRED"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(160); $pdf->Cell(0,0,'PCTJE: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(180);$pdf->Cell(0,0,round(($dataAlum[0]["CREDACUM"]/$dataAlum[0]["PLACRED"]),2)*100,0,1,'L');
        //======================================================================
        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'PLAN: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(30);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["MAPA"]),0,1,'L');
        
        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(115); $pdf->Cell(0,0,'CRE.ACU: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(135);$pdf->Cell(0,0,$dataAlum[0]["CREDACUM"],0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(160); $pdf->Cell(0,0,'NP.CONV: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(180);$pdf->Cell(0,0,$dataAlum[0]["PERCONV"],0,1,'L');
        
        //======================================================================
        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'ESPEC: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(30);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["ESPECIALIDAD"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(115); $pdf->Cell(0,0,'CRE.CUR: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(135);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["CRECUR"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(160); $pdf->Cell(0,0,'NPRDO: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(180);$pdf->Cell(0,0,$dataAlum[0]["PERIODOS"],0,1,'L');

        //======================================================================
        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'INGRESO: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(30);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["CICLOINS"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(70); $pdf->Cell(0,0,'MAT. TOT: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(90);$pdf->Cell(0,0,$dataAlum[0]["PLAMAT"],0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(115); $pdf->Cell(0,0,'MAT. APR: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(135);$pdf->Cell(0,0,"{matapr}",0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(160); $pdf->Cell(0,0,'CON REPROB: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(185);$pdf->Cell(0,0,"{promreprobadas}",0,1,'L');
        
        //======================================================================
        $pdf->Ln(3);
        $pdf->SetFont('Montserrat-ExtraBold','B',9);$pdf->Cell(0,0,'TERMINO: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(30);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["CICLOTER"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(70); $pdf->Cell(0,0,'MAT. CUR: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(90);$pdf->Cell(0,0,"{matcursadas}",0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(115); $pdf->Cell(0,0,'SIT: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(135);$pdf->Cell(0,0,utf8_decode($dataAlum[0]["SITUACION"]),0,1,'L');

        $pdf->SetFont('Montserrat-ExtraBold','B',9); $pdf->setX(160); $pdf->Cell(0,0,'SIN REPROB: ',0,1,'L');
        $pdf->SetFont('Montserrat-Medium','',9);$pdf->setX(185);$pdf->Cell(0,0,round($dataAlum[0]["PROMEDIO_SR"],0),0,1,'L');
        
        $pdf->Ln(5);
        $pdf->setX(30);
        $pdf->SetFont('Montserrat-Medium','',9);
        $pdf->Cell(10,5,'MATERIAS CURSADAS ',0,0,'C');

        $pdf->Ln(5);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(255);  
        $pdf->SetFont('Montserrat-ExtraBold','B',7);
        $pdf->Cell(10,5,'NO.',1,0,'C',true);
        $pdf->Cell(15,5,'CLAVE',1,0,'C',true);
        $pdf->Cell(70,5,'NOMBRE',1,0,'C',true);
        $pdf->Cell(10,5,'CR',1,0,'C',true);
        $pdf->Cell(10,5,'CAL',1,0,'C',true);
        $pdf->Cell(10,5,'TC',1,0,'C',true);
        $pdf->Cell(30,5,'POR PRIMERA',1,0,'C',true);
        $pdf->Cell(15,5,'SEGUNDA',1,0,'C',true);
        $pdf->Cell(15,5,'ESPECIAL',1,0,'C',true);
        $pdf->Cell(10,5,'AC',1,0,'C',true);

        $pdf->Ln();
        $pdf->SetFont('Montserrat-Medium','',7);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(10,15,70, 10,10,10,30,15,15,10));
        $n=1;
        $sumacursadas=0;
        $cursadas=0;
        $materiasaprobadas=0;
        $matTotales=0;
        foreach($data as $row) {
            $lacal=$row["CAL"];
            if ($row["CAL"]<70 && ($row["CAL"]!='AC')) {$lacal='NA';} else {$materiasaprobadas++;}
            $pdf->Row(array( str_pad($n,  3, "0",STR_PAD_LEFT),
                             utf8_decode($row["MATERIA"]),
                             utf8_decode($row["MATERIAD"]),
                             utf8_decode($row["CREDITO"]),
                             utf8_decode($lacal),
                             utf8_decode($row["TCAL"]),
                             utf8_decode($row["PRIMERA"]),
                             utf8_decode($row["SEGUNDA"]),
                             utf8_decode($row["TERCERA"]),
                             "",
                             )
                      );
            $n++;
            $matTotales++;
            if (is_numeric($row["CAL"]) && ($row["TIPOMAT"]!='SS')) {$sumacursadas+=$row["CAL"]; $cursadas++; }            
        }

        if ($materiasaprobadas>0) {
            $pdf->parseVar('{matapr}',$materiasaprobadas); // convertimos la variable.
        }
        else {$pdf->parseVar('{matapr}'," ");}
        $pdf->parseVar('{promreprobadas}',round($sumacursadas/($cursadas),0)); // Sacamos el promedio con materias reprobadas
        $pdf->parseVar('{matcursadas}',$cursadas); // convertimos la variable de materias cursadas

//=====================================================================================================
if (count($data2)>0) {
        $pdf->Ln(5);
        $pdf->setX(30);
        $pdf->SetFont('Montserrat-Medium','',9);
        $pdf->Cell(10,5,'MATERIAS CURSANDO '.$elciclo,0,0,'C');
        $pdf->Ln(5);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(255);  
        $pdf->SetFont('Montserrat-ExtraBold','B',7);
        $pdf->Cell(10,5,'NO.',1,0,'C',true);
        $pdf->Cell(15,5,'CLAVE',1,0,'C',true);
        $pdf->Cell(70,5,'NOMBRE',1,0,'C',true);
        $pdf->Cell(10,5,'CR',1,0,'C',true);
        $pdf->Cell(10,5,'CAL',1,0,'C',true);
        $pdf->Cell(10,5,'TC',1,0,'C',true);
        $pdf->Cell(30,5,'POR PRIMERA',1,0,'C',true);
        $pdf->Cell(15,5,'SEGUNDA',1,0,'C',true);
        $pdf->Cell(15,5,'ESPECIAL',1,0,'C',true);
        $pdf->Cell(10,5,'AC',1,0,'C',true);

        $pdf->Ln();
        $pdf->SetFont('Montserrat-Medium','',7);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(10,15,70, 10,10,10,30,15,15,10));
        $n=1;
        foreach($data2 as $row) {
            
            $pdf->Row(array( str_pad($n,  3, "0",STR_PAD_LEFT),
                             utf8_decode($row["MATERIA"]),
                             utf8_decode($row["MATERIAD"]),
                             utf8_decode($row["CREDITO"]),
                             utf8_decode($row["CAL"]),
                             utf8_decode($row["TCAL"]),
                             utf8_decode($row["PRIMERA"]),
                             utf8_decode($row["SEGUNDA"]),
                             utf8_decode($row["TERCERA"]),
                             "",
                             )
                      );
            $n++;
            $matTotales++;
        }
        $pdf->parseVar('{matcursando}',$n-1); // convertimos la variable de materias cursando
        $materiascursando=$n-1;
    }
      
       
      
//=====================================================================================================

        $data3 = $pdf->LoadDatosporCursar($elciclo); 
        if (count($data3)>0) {
                $pdf->Ln(5);
                $pdf->setX(30);
                $pdf->SetFont('Montserrat-Medium','',9);
                $pdf->Cell(10,5,'MATERIAS POR CURSAR',0,0,'C');
                $pdf->Ln(5);
                $pdf->SetFillColor(172,31,6);
                $pdf->SetTextColor(255);  
                $pdf->SetFont('Montserrat-ExtraBold','B',7);
                $pdf->Cell(10,5,'NO.',1,0,'C',true);
                $pdf->Cell(15,5,'CLAVE',1,0,'C',true);
                $pdf->Cell(70,5,'NOMBRE',1,0,'C',true);
                $pdf->Cell(10,5,'CR',1,0,'C',true);
                $pdf->Cell(10,5,'CAL',1,0,'C',true);
                $pdf->Cell(10,5,'TC',1,0,'C',true);
                $pdf->Cell(30,5,'POR PRIMERA',1,0,'C',true);
                $pdf->Cell(15,5,'SEGUNDA',1,0,'C',true);
                $pdf->Cell(15,5,'ESPECIAL',1,0,'C',true);
                $pdf->Cell(10,5,'AC',1,0,'C',true);

                $pdf->Ln();
                $pdf->SetFont('Montserrat-Medium','',7);
                $pdf->SetFillColor(172,31,6);
                $pdf->SetTextColor(0);
                $pdf->SetWidths(array(10,15,70, 10,10,10,30,15,15,10));
                $n=1;
                foreach($data3 as $row) {
            
                    $pdf->Row(array( str_pad($n,  3, "0",STR_PAD_LEFT),
                                    utf8_decode($row["MATERIA"]),
                                    utf8_decode($row["MATERIAD"]),
                                    utf8_decode($row["CREDITO"]),
                                    utf8_decode(""),
                                    utf8_decode(""),
                                    utf8_decode(""),
                                    utf8_decode(""),
                                    utf8_decode(""),
                                    "",
                                    )
                            );
                    $n++;
                    $matTotales++;
                }
                $pdf->parseVar('{matcur}',$n-1); // convertimos la variable.
                $pdf->parseVar('{mattotales}',$matTotales); // convertimos la variable de materias totales
            }

        $pdf->SetFont('Montserrat-ExtraBold','B',7);
        $pdf->SetFillColor(255,255,255);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(10,15,70, 10,10,10,30,15,15,10));
        $pdf->Ln();
        $pdf->Cell(0,5,utf8_decode('Nota: Este Reporte solo es válido para los trámites internos avalados '.
        'por la Dirección General del ITSM y para uso personal de alumno.'),0,0,'J',true);


        $cadena= "FECHA:".str_replace("/","",$fecha)."|".str_replace(" ","|",$dataAlum[0]["ALUM_MATRICULA"]).
                str_replace(" ","|",$dataAlum[0]["NOMBRE"])."|".str_replace(" ","|",$dataAlum[0]["CARRERAD"]).
                 "|MATAPR:".$materiasaprobadas.
                 "|CURSANDO:".$materiascursando."|AVANCE:".round(($dataAlum[0]["CREDACUM"]/$dataAlum[0]["PLACRED"]),2)*100;
        
                        
        $pdf->Image('https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl='.$cadena.'&.png',160,210,40,40);
        
        


        /*
        str_pad($input,  3, "*");

        $pdf->Ln(10);

        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(255);  
        $pdf->SetFont('Montserrat-ExtraBold','B',9);
        $pdf->Cell(7,5,'No ',1,0,'C',true);
        $pdf->Cell(17,5,'Control',1,0,'C',true);
        $pdf->Cell(60,5,'Nombre',1,0,'C',true);
        
        for ($i=1;$i<=10; $i++) {     
            $pdf->SetFillColor(172,31,6);
            $pdf->SetTextColor(255);       
            if ($i<=$numUni) {
                $pdf->SetFillColor(23,5,124);
                $pdf->SetTextColor(255);
            }
            $pdf->Cell(8,5,'U'.$i,1,0,'C',true); 
        }

        $pdf->SetFillColor(23,5,124);
        $pdf->SetTextColor(255);
        $pdf->Cell(8,5,'CF',1,0,'C',true);

        $pdf->Ln();
        $pdf->SetFont('Montserrat-Medium','',7);
        $pdf->SetFillColor(172,31,6);
        $pdf->SetTextColor(0);
        $pdf->SetWidths(array(7,17, 60, 8,8,8,8,8,8,8,8,8,8,8));
        $n=1;


        foreach($data as $row) {
            //$pdf->Cell(70,5,$row["NOMBRE"],1,0,'L');            
            $pdf->Row(array($n,
                             utf8_decode($row["ALUM_MATRICULA"]),
                             utf8_decode($row["NOMBRE"]),
                             utf8_decode($row[1]),
                             utf8_decode($row[2]),
                             utf8_decode($row[3]),
                             utf8_decode($row[4]),
                             utf8_decode($row[5]),
                             utf8_decode($row[6]),
                             utf8_decode($row[7]),
                             utf8_decode($row[8]),
                             utf8_decode($row[9]),
                             utf8_decode($row[10]),
                             utf8_decode($row["LISCAL"])
                             )
                      );
            $n++;
        }
        
    */

            $pdf->Output(); 


 } else {header("Location: index.php");}
 
 ?>
