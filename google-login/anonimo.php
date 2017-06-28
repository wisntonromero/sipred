	
	ayuda mail anonimo php no llega a hotmail
« en: 2 Enero 2010, 22:56 »
senores como estan espero que bien 

resulta que tengo un codigo php para mails anonimos que este este 

<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Language" content="es">
<form method="POST" action="anonimo.php">
<p align="center">
&nbsp;&nbsp;&nbsp;&nbsp;
Tu eMail:
<input type="text" name="email" size="20"><br>
Tu nombre:
<input type="text" name="nombre" size="20"><br>
Para (eMail): <input type="text" name="para" size="20"><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Asunto:&nbsp;
<input type="text" name="asunto" size="20"><br>
<br>
Mensaje<br>
<textarea rows="4" name="comentarios" cols="36"></textarea><br>
<br>
<input type="submit" value="Enviar" name="B1">
<input type="reset" value="Borrar todo" name="B2"></p>
</form>
<?
$nombre = $_REQUEST["nombre"];
$remitente = $_REQUEST["email"];
$mensaje = $_REQUEST["comentarios"];
$asunto = $_REQUEST["asunto"];
$para = $_REQUEST["para"];


$headers = 'From: "'.$nombre.'" <'.$remitente.'>' . "\r\n";
$headers .= 'X-Mailer: PHP /'. phpversion(). "\ r \ n"; 
$headers .= 'MIME-Version: 1.0'. "\ r \ n"; 
$headers .= 'Content-Type: text / html; charset = UTF-8';
mail("$para", $asunto, $mensaje, $headers);
?>