<?PHP
require_once('../libs/conexion_2.php');
$cn=  Conectarse_posgres();
session_start();
$login= isset($_POST["login"])?$_POST["login"]:"";         //
$pregunta_secreta_1= isset($_POST["pregunta_secreta_1"])?$_POST["pregunta_secreta_1"]:"";   // 
$respuesta_secreta_1= isset($_POST["respuesta_secreta_1"])?$_POST["respuesta_secreta_1"]:"";               // 
$pregunta_secreta_2= isset($_POST["pregunta_secreta_2"])?$_POST["pregunta_secreta_2"]:"";   //
$respuesta_secreta_2= isset($_POST["respuesta_secreta_2"])?$_POST["respuesta_secreta_2"]:"";   // 
//$cant_entradas= isset($_POST["hddcant_entradas"])?$_POST["hddcant_entradas"]:0;   // 
$email= isset($_POST["email"])?$_POST["email"]:"";   // 
$password= isset($_POST["password"])?$_POST["password"]:"";   // 
$cedula= isset($_POST["cedula"])?$_POST["cedula"]:"";   //  

$consulta="select * from usuarios where login_username = '".$login."'";
$result = pg_query($cn,$consulta);
$row = pg_fetch_array($result);
$res_password=$row['login_userpass'];
$cant_entradas=$row['cant_entradas'];
$res_respuesta_secreta_1=$row['respuesta_secreta_1'];
$res_respuesta_secreta_2=$row['respuesta_secreta_2'];
pg_free_result($result);

$query = "UPDATE usuarios SET ";
$query .= "pregunta_secreta_1 = '".$pregunta_secreta_1."', ";
$query .= "pregunta_secreta_2 = '".$pregunta_secreta_2."', ";
$query .= "respuesta_secreta_1 = '".$respuesta_secreta_1."', ";
$query .= "respuesta_secreta_2 = '".$respuesta_secreta_2."', ";
$query .= "fecha_cambio_passw = NOW(), ";
$query .= "email = '".$email."', ";
$query .= "login_userpass = MD5('".$password."') ";
$query .= "WHERE login_username = '".$login."' ";
$query .= "AND cedula = '".$cedula."' ";
$query .= "AND login_userpass = '".$res_password."' ";

if (($cant_entradas<=1) && ($res_respuesta_secreta_1==''))
//if((isset($_SESSION['cant_entradas'])) && (isset($_SESSION['nivel'])))
//if (($_SESSION['cant_entradas']>=1)  && ($_SESSION['nivel']==3))
  {
  		$query .= ";";
  }
  else{
  		$query .= "AND respuesta_secreta_1 = '".$respuesta_secreta_1."' ";
  		$query .= "AND respuesta_secreta_2 = '".$respuesta_secreta_2."' ";
  		$query .= "AND pregunta_secreta_1 = '".$pregunta_secreta_1."' ";
  		$query .= "AND pregunta_secreta_2 = '".$pregunta_secreta_2."';";	
  }

$res = pg_query($cn,$query);
$cmdtuples = pg_affected_rows($res);
pg_free_result($res);
pg_close($cn);	
echo $cmdtuples;
?>