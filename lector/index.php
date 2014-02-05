<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lector de Codigo</title>
</head>

<body>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
	<label>Archivo de Codigo de Barras:</label>
    <input type="file" name="imagen" /> </label>  
	<input name="archivo" type="submit" value="Consultar" class="boton"/>
</form>

<?php
if(isset($_POST['archivo']))	{

	//Guarda un registro del archivo del codigo de barras//
	
	$rutaEnServidor = 'files';
	$rutaTemporal=$_FILES['imagen']['tmp_name'];
	$nombreImagen=$_FILES['imagen']['name'];
	$rutaDestino=$rutaEnServidor.'/'.$nombreImagen;
	move_uploaded_file($rutaTemporal,$rutaDestino);
	$fecha = date('d-m-y');
		
// Abrimos la conexion a la base de datos  
include("abre_conexion.php");
		
	$_GRABAR_SQL = "INSERT INTO archivos (archivo,fecha) VALUES ('$rutaDestino','$fecha')"; 
	mysql_query($_GRABAR_SQL); 
	if ($_GRABAR_SQL){
		echo "el archivo ha sido copiado exitosamente<br/>";
	} else {
		echo "ocurrio un error al copiar el archivo.<br/>";
		die();
	}
		
	//Leemos el archivo recien guardado//
	
	$_pagi_sql = "select * from archivos order BY id desc";   
    $_pagi_result = mysql_query($_pagi_sql);   
	$archivo = mysql_fetch_array($_pagi_result); 
	echo"Este es el contenido del archivo:<br/>";		
	
	$file = fopen($archivo['archivo'], "r") or exit("NO PUDE ABRIR EL ARCHIVO!");
	//Output a line of the file until the end is reached
	while(!feof($file))
	{
	echo fgets($file). "<br />";
	}
	fclose($file);
		
// Cerramos la conexion a la base de datos		
include("cierra_conexion.php"); 

}

?>

</body>
</html>