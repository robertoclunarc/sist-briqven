
$(document).ready(function(){
    verlistado2()
    //CARGAMOS EL ARCHIVO QUE NOS LISTA LOS REGISTROS, CUANDO EL DOCUMENTO ESTA LISTO


})
function verlistado2(){ //FUNCION PARA MOSTRAR EL LISTADO EN EL INDEX POR JQUERY
              var randomnumber=Math.random()*11;
            $.post("libs/listarresumen.php", {
                randomnumber:randomnumber
            }, function(data){
              $("#contenido").html(data);
            });



}
