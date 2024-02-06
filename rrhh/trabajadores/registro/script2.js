// JavaScript Document 
            $(document).ready(function(){  				
                $("#frm_usu").validate(); 		
            });			
            
     function fn_agregar(){					
		  respuesta = confirm("Esta Seguro de la modificacion de datos para el trabajador "+document.getElementById("nombres").value+" ?");
          if (respuesta){
                 //   aqui puedes enviar un conjunto de tados ajax para agregar al usuario					
                	$.ajax({
					url: 'modificar.php',
					data: $("#frm_usu").serialize(),			
					type: 'POST',
					success: function(data){
						if(data!="")
							alert(data);
					//fn_buscar()
		       		}
		     });		
        alert("Proceso realizado excitosamente");
        window.close();       
        };
	}
