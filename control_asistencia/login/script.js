$('document').ready(function()
{ 
     /* validation */
	 $("#login-form").validate({
      rules:
	  {
			password: {
			required: true,
			},
			login: {
            required: true,
            email: false
            },
	   },
       messages:
	   {
            password:{
                      required: "por favor coloque su password"
                     },
            login: "por favor coloque su login",
       },
	   submitHandler: submitForm	
       });  
	   /* validation */
	   
	   /* login submit */
	   function submitForm()
	   {		
			var data = $("#login-form").serialize();
				
			$.ajax({
				
			type : 'POST',
			url  : 'login_process.php',
			data : data,
			beforeSend: function()
			{	
				$("#error").fadeOut();
				$("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Direccionando ...');
			},
			success :  function(response)
			  {	
			  		var resp = response.split('|', 2);
			  		var modo = resp[0];
			  		var estilo = resp[1];
			  		var pages='http://10.50.188.48/control_asistencia';
			  		if (estilo=='inverse'){
			  			pages='http://10.50.188.48/control_asistencia_black';
			  		}
			  		
						switch (modo) {

					  case "ok":

					    $("#btn-login").html('<img src="btn-ajax-loader.gif" /> &nbsp; Accediendo ...');
						setTimeout(' window.location.href = "'+pages+'/pages/index.php"; ',3000);
					    break;
					  case "N5":
					    $("#btn-login").html('<img src="btn-ajax-loader.gif" /> &nbsp; Accediendo ...');
								setTimeout(' window.location.href = "'+pages+'/index.php"; ',3000);
					    break;
					  default:
					    $("#error").fadeIn(1000, function(){						
							$("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+modo+' !</div>');
													$("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In');
							});
					    break;
					}
			  }
			});
			return false;
		}
	   /* login submit */
});
