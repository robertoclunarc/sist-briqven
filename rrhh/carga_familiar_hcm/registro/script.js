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

		function eliminar_carga(arreglo){			
			//var res=arreglo.split("|")
			$.ajax({
					url: 'eliminar.php',
					data: 'frm=' + arreglo,			
					type: 'post',
					success: function(data){
						if(data!="")
							alert(data);
					//fn_buscar()
		       		}
		     });
			};
            
            function fn_agregar(){
		
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			var i = 1;			
			var str = '';
			var hcm, mat;
			var arrsec = "";			
			respuesta = confirm("Desea Actualizar su Carga Familiar ? ");
          if (respuesta){
			for (i=1; i <= $("#items").val();i++)
				if (document.getElementById("hcm_"+i))
			  {

				chkhcm=document.getElementById("hcm_"+i);
				if (chkhcm.checked)
					hcm=1;
				else 
					hcm=0;
				chkmat=document.getElementById("maternidad_"+i);
				if (chkmat.checked)
					mat=1;
				else 
					mat=0;
			   str = str + $("#secuencia_"+i).val() + "|" + $("#persona_relacionada_"+i).val() + "|" +  $("#cedula").val() + "|" + hcm + "|" + mat + "|";
			  }
			 else{
			  	arrsec = arrsec + i + "|";
			  	//j=j + 1;
			  		  	
			  }
			  if (arrsec != ""){
				  arrsec=arrsec.substring(0, arrsec.length - 1);
				  rr=eliminar_carga($("#cedula").val() + "|" + arrsec);
				}				
			  str=str.substring(0, str.length - 1);
//respuesta = confirm(str);

 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////               
                 //   aqui puedes enviar un conjunto de tados ajax para agregar al usuario
					
                	$.ajax({
					url: 'actualizar.php',
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
        alert("Carga Familiar Actualizada");
        imprmir=ventana($("#cedula").val());
        }
            };


function ventana(idp) 
{ 
var imprimir = window.open('../imprimircarga.php?idp='+idp, "Imprimir", "width=800,height=400,menubar=NO,toolbar=NO,directories=NO,scrollbars=YES,resizable=NO,left=100,top=100");
} ;


///////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////

            
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
