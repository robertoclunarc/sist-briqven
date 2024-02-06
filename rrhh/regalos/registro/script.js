// JavaScript Document 
            $(document).ready(function(){
                fn_dar_eliminar();
				fn_cantidad();
                $("#frm_usu").validate(); 		
            });
			
			function fn_cantidad(){
				cantidad = $("#grilla tbody").find("tr").length;
				$("#span_cantidad").html(cantidad);
			};
            
            function fn_agregar(){		
			var i = 1;			
			var str = '';
			var opt;
			var mat=true;
			var boton=document.getElementById("agregar");					
			respuesta = confirm("Esta Seguro de la Opcion Seleccionada ? ");
          if (respuesta){
          	boton.disabled = true;
			for (i=1; i <= $("#items").val();i++)
				if (document.getElementById("opc_"+i))
			  {
				chkhcm=document.getElementById("opc_"+i);
				if (chkhcm.checked)
				{
					opt=chkhcm.value;
					mat=false;
				}
			  }	
			str=$("#cedula").val() + '|' + opt;
			   
//respuesta = confirm(str);

 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////               
                 //   aqui puedes enviar un conjunto de tados ajax para agregar al usuario
					
                	$.ajax({
					url: 'agregar.php',
					data: 'frm=' + str,			
					type: 'post',
					success: function(data){
						if(data!="")
							alert(data);
					//fn_buscar()
		       		}
		     });
		
		//fn_dar_eliminar();
		fn_cantidad();
        alert("Opcion "+ opt + " Seleccionada");
        imprmir=ventana('201601');
       
            };
}

function ventana(idp) 
{ 
var imprimir = window.open('imprimirobsequios.php?idp='+idp, "Imprimir", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=NO,left=100,top=100");
} ;

///////////////////////////////////////////////////////////////////////
      
       function fn_dar_eliminar(){
		var itm;		
            $("a.elimina").click(function(){
		itm = $(this).parents("tr").find("td").eq(0).html();
        //        	fe = $(this).parents("tr").find("td").eq(1).html();
		//cond = $(this).parents("tr").find("td").eq(0).html();
            //   respuesta = confirm("Desea eliminar el " + cont);                   
                    $(this).parents("tr").fadeOut("normal", function(){
                        $(this).remove();
                        //alert("Dato " + id + " eliminado")
                        /*
                            aqui puedes enviar un conjunto de datos por ajax
                            
                        */
			
			
                    })
               
            });
        };
