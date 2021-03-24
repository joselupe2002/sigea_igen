<?php header('Content-Type: text/html; charset=UTF-8'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<link rel="icon" type="image/gif" href="imagenes/login/sigea.ico">
		<title>Sistema de Gesti&oacute;n Escolar</title>
		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
		<link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="assets/css/select2.min.css" />
		<link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
	    <link rel="stylesheet" href="assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />
		<link rel="stylesheet" href="assets/css/ace-rtl.min.css" />		
		<script src="assets/js/ace-extra.min.js"></script>		
		<link rel="stylesheet" href="assets/css/jquery-ui.min.css" />

	</head>
	<?php  
	    session_start();
        session_destroy();
        //include("./includes/Seguridad.php"); $miSeg = new Seguridad();  echo $miSeg->encriptar("s1g3av2020"); ?>
	
	<body class="login-layout light-login">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
							    <br/>
								<h1>
									<img src="imagenes/login/sigea.png" width="40px" height="40px"></img> 
									<span class="red">SiGEA</span>
									<span class="grey" id="id-text2">Admin</span>
								</h1>
								<h4 class="blue" id="id-company-text">by Webcoretic</h4>
							</div>
							<div class="space-6"></div>

							<div class="position-relative">
								<div id="login-box" class="login-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-lock green"></i>
												Datos de acceso
											</h4>

											<div class="space-6"></div>

											<form>
												<fieldset>
													<label class="block clearfix"></label>
														<span class="block input-icon input-icon-right">
															<input name="login"  id="login" type="text" class="form-control" placeholder="Usuario" />
															<i class="ace-icon fa fa-user"></i>
														</span>
													

													<label class="block clearfix"></label>
														<span class="block input-icon input-icon-right">
															<input name="password" id="password"  type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													

													<div class="space"></div>

													<div class="clearfix">
														
														<button type="button" id="btnLogin" class="width-35 pull-right btn btn-sm btn-primary">
															<i class="ace-icon fa fa-key"></i>
															<span class="bigger-110" >Ingresar</span>
														</button>
													</div>

													<div class="space-4"></div>
												</fieldset>
											</form>

									
											
										</div><!-- /.widget-main -->

										<div class="toolbar clearfix">
											<div>
												<a href="#" data-target="#forgot-box" class="hide forgot-password-link">
													<i class="ace-icon fa fa-arrow-left"></i>
													Recordar clave
												</a>
											</div>

											<div>
												
											</div>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.login-box -->

								<div id="forgot-box" class="forgot-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-key"></i>
												Recordar Password
											</h4>

											<div class="space-6"></div>
											<p>
												Ingrese su numero de control / No Empl.
											</p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="correo_re" id="correo_re" class="form-control" placeholder="" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>

													<div class="clearfix">
														<button id="enviarClave" type="button" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Enviar</span>
														</button>
													</div>
												</fieldset>
											</form>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Regresar
												<i class="ace-icon fa fa-arrow-right"></i>
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

								<div id="signup-box" class="signup-box widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">
											<h4 class="header green lighter bigger">
												<i class="ace-icon fa fa-users blue"></i>
												Nuevo Usuario
											</h4>
											
																	
											<form name="frmReg" id="frmReg"  method="get">
												<fieldset>
												<div class="form-group">
												   <label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="name" id="name" type="text" class="form-control" placeholder="Nombre" />
															<i class="ace-icon fa fa-users"></i>
														</span>
													</label>
													
												</div>
												<div class="form-group">													
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="correo" id="correo"  type="text" class="form-control" placeholder="Email" />
															<i class="ace-icon fa fa-envelope"></i>
														</span>
													</label>
													
											    </div>
												<div class="form-group">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="tel" id="tel"  type="text" class="form-control" placeholder="Celular" />
															<i class="ace-icon fa fa-phone"></i>
														</span>
													</label>
													
												</div>
                                                <div class="form-group">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="pass" id="pass" type="password" class="form-control" placeholder="Password" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
													</label>
													
												</div>
												<div class="form-group">
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input name="pass2" id="pass2"  type="password" class="form-control" placeholder="Repeat password" />
															<i class="ace-icon fa fa-retweet"></i>
														</span>
													</label>
												</div>
                                                <div class="form-group">
													<label class="block">
														<input type="checkbox" class="ace" id="licencia" name="licencia"/>
														<span class="lbl">
															Acepto
															<a href="terminos.pdf" target="_blank">Licencia</a>
														</span>
													</label>
												</div>

													<div class="clearfix">
														<button type="reset" class="width-30 pull-left btn btn-sm">
															<i class="ace-icon fa fa-refresh"></i>
															<span class="bigger-110">Limpiar</span>
														</button>

														<button id ="btnRegistrar" type="submit" class="width-65 pull-right btn btn-sm btn-success">
															<span class="bigger-110">Registrar</span>
															<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
														</button>
													</div>
												</fieldset>
											</form>
										</div>

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												<i class="ace-icon fa fa-arrow-left"></i>
												Regresar a Login
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.signup-box -->
							</div><!-- /.position-relative -->

						
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->


<div id="dialogError" class="hide"></div>
<div id="dialogEspera" class="hide"></div>
										
<script src="assets/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript">
	   if('ontouchstart' in document.documentElement) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/ace-elements.min.js"></script>
<script src="assets/js/ace.min.js"></script>
<script src="assets/js/wizard.min.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
<script src="assets/js/select2.min.js"></script>
<script src="assets/js/ace-elements.min.js"></script>		
<script src="assets/js/jquery-additional-methods.min.js"></script>
<script src="assets/js/bootbox.js"></script>
<script src="assets/js/jquery.maskedinput.min.js"></script>
<script src="assets/js/jquery-ui.min.js"></script>
<script src="assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="js/sha/sha512.js"></script>

<script type="text/javascript"> var laip=""; function get_ip(obj){ laip=obj.ip;}</script>

<script type="text/javascript" src="https://api.ipify.org/?format=jsonp&callback=get_ip"></script>


		
<script type="text/javascript">


function ingresar() {
	var parametros = {
            "login" : $('#login').val(),
			"password" : $('#password').val(),
			"laip":laip
    };
	 $.ajax({
		 data:  parametros,
         type: "POST",
         url: "acceso.php",
         success: function(response)
         {		
			 					        
	         if (!(response==1)) {
		        	 $("#dialogError").html(response);
		        	 var dialog = $( "#dialogError" ).removeClass('hide').dialog({
							modal: true,
							title: "Error",
							title_html: true,
							buttons: [ 								
								{
									text: "OK",
									"class" : "btn btn-primary btn-minier",
									click: function() {
										$( this ).dialog( "close" ); 
									} 
								}
							]
						});
	         }
	         else 
			 {window.location.href = "principal.php";}
			

         }
 }); 

}

	
		
			
			jQuery(function($) {

				
				$("#password").keypress(function(e) {
			        if(e.which == 13) {
			        	ingresar();
			        }
			      });
			      

				 $(document).on('click', '.toolbar a[data-target]', function(e) {
						e.preventDefault();
						var target = $(this).data('target');
						$('.widget-box.visible').removeClass('visible');//hide others
						$(target).addClass('visible');//show target
					 });		
				 
				//================================INGRESO AL SISTEMA =========================================
			   $('#btnLogin').on('click', function(e) {
						ingresar();   					
						e.preventDefault();
					 });

			   //================================RECORDAR CONTRASE&Ntilde;A  =========================================
			   $('#enviarClave').on('click', function(e) {
				var hoy = new Date();
				var tah = hoy.getMinutes().toString()+hoy.getSeconds().toString()+hoy.getHours().toString();
			
				   var parametros = {			              
			                "correo" : $('#correo_re').val(),
							"usuario" : $('#correo_re').val(),
							"tag1":tah,
							"tag2": encrip=sha512(tah)                
			            };
		            $("#dialogEspera").html("<img src=\"imagenes/menu/esperar.gif\" style=\"background: transparent; width: 60px; height: 60px\">"+
				            "<span class=\"red\">Procesando...</span>");
		        	 var dialog = $( "#dialogEspera" ).removeClass('hide').dialog({
		        		    open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
							modal: true
						});
				
		            $.ajax({
		                type: "POST",
		                url:"enviarClave.php",
		                data: parametros,
		                success: function(data){	
		                	$("#dialogEspera").dialog( "close" );
		                	alert (data);	                	
		                }
		            });

					e.preventDefault();
				 });


			   //================================REGISTRO DE NUEVO USUARIO  =========================================

				 $("#frmReg").validate({				    	
					    errorElement: 'div',
						errorClass: 'help-block',
						focusInvalid: false,
						ignore: "",
				        rules: {
				            name: { required: true, minlength: 2},	
				            tel: {  number: true, minlength: 8, maxlength: 10},
				            correo: { required:true, email: true, remote: {url: "verificaUser.php", type: "get"}},
				            pass: { required: true, minlength: 6},
				            pass2:{ required: true, minlength: 6, equalTo: "#pass"}
				        },
				        messages: {
				            name: "Debe introducir su nombre",
				            tel: {
								required: 'Tel&eacute;fono Requerido',
								number: 'Sol&oacute; se permite n&uacute;meros',
								maxlength: 'Longitud M&aacute;xima: 10',
								minlength: 'L&oacute;ngitud M&iacute;nima: 8'
							 },	
				            correo:   {  
		                        required: "Debe introducir un email v&aacute;lido.",  
		                        remote:   "Ya existe un usuario con este correo electr&oacute;nico registrado"  
		                     },  
				            pass:  {  
		                        required: "Campo requerido: Password",  
		                        minlength:   "L&oacute;ngitud M&iacute;nima: 6"  
		                     },    
		                    pass2:  {  
		                         required: "Campo requerido: Confirmaci&oacute;n",  
		                         minlength:   "L&oacute;ngitud M&iacute;nima: 6",
		                         equalTo:   "Las contrase&ntilde;as no coinciden" 
		                      },  
				        },

				    	highlight: function (e) {
							$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
						},
				
						success: function (e) {
							$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
							$(e).remove();
						},
				
						errorPlacement: function (error, element) {
			                  error.insertAfter(element.parent());
					    },
					
				        submitHandler: function(form){	

				        	var condiciones = $("#licencia").is(":checked");
				            if (!condiciones) {
				                alert("Debe aceptar las condiciones para registrarse");				              
				            }
				            else {
				            
					        	 $("#dialogEspera").html("<img src=\"imagenes/menu/espere.gif\" style=\"background: transparent; width: 60px; height: 60px\">"+
						            "<span class=\"red\">Procesando...</span>");
				        	      var dialog = $( "#dialogEspera" ).removeClass('hide').dialog({
				        		       open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); },
									   modal: true
								  });

                              
								          
						            var parametros = {
							                "name" : $('#name').val(),
							                "correo" : $('#correo').val(),
							                "tel" : $('#tel').val(),
							                "pass" : $('#pass').val(),
							                "pag":"prin",
							                "USUA_CREDITO":1,
							                "idTorneo":"0",
							                "usuario" : $('#correo').val()	                
							            };
						            $.ajax({
						                type: "POST",
						                url:"guardaUser.php",
						                data: parametros,
						                success: function(data){
							                if (data=="") {
							                	$("#dialogEspera").dialog( "close" );
							                	alert("Tu registro esta en proceso se ha enviado un correo electr&oacute;nico para confirmar tu correo (Si no aparece en bandeja de entrada, favor de checar en correos no deseados o spam)");							        	                      
				                                location.href ="index.php";                                
								                } 
							                else {
							                  alert (data);  
						                    }			
						                }
						            });
				            
				       			}//else de las condiciones
				        } //cierre del submit del form
				    });
		
				 			
			});
		</script>
	</body>
</html>
