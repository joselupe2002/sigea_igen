<?php  
   session_start();
   header('Content-Type: text/html; charset='.$_SESSION['encode']);
   if ($_SESSION['inicio']==1) { 
    
       if (isset($_GET['imgborrar'])) {  	
       	if (($_GET['imgborrar']!='../../imagenes/menu/default.png')&&($_GET['imgborrar']!='../../imagenes/menu/default.png')) {
       		if (file_exists ($_GET['imgborrar'])) {    
                       echo "si entre a borrar";   		
       			unlink($_GET['imgborrar']);
                   }
                   else {echo "El archivo no se encontro: ".$_GET['imgborrar'];}
       		
       	}
       }
       
 } else {header("Location: index.php");}
?>
