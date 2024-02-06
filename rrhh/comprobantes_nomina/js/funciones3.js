
$(document).ready(function(){
    verlistado3()
    //CARGAMOS EL ARCHIVO QUE NOS LISTA LOS REGISTROS, CUANDO EL DOCUMENTO ESTA LISTO


})
function verlistado3(){ //FUNCION PARA MOSTRAR EL LISTADO EN EL INDEX POR JQUERY
              var randomnumber=Math.random()*11;
            $.post("libs/listarhistorias.php", {
                randomnumber:randomnumber
            }, function(data){
              $("#contenido").html(data);
            });



}
