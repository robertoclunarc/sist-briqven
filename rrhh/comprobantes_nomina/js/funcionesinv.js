
$(document).ready(function(){
    verlistado1()
    //CARGAMOS EL ARCHIVO QUE NOS LISTA LOS REGISTROS, CUANDO EL DOCUMENTO ESTA LISTO


})
function verlistado1(){ //FUNCION PARA MOSTRAR EL LISTADO EN EL INDEX POR JQUERY
              var randomnumber=Math.random()*11;
            $.post("libs/listarinv.php", {
                randomnumber:randomnumber
            }, function(data){
              $("#contenido").html(data);
            });



}
