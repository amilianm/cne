<?php
/*
Scrip para consulta de usuarios CNE

Desarrollado Por: Ing. Alain Milian Marin
Correo: amilianm@yahoo.es

Extrae los campos separados por coma, copie el contenido y envielo a un archivos csv, despues habralo con un excel o cal y pongale delimitador por: , sin comillas dobles
Recomendación: No solicitar más de 100 campos

Not: Solo funciona por el momento con Venezolanos, no extrae el campo municipio pero se puede resolver si se sabe un poco de lenguaje php

*/

$host = 'localhost';
$user = 'root';
$pass = 'root';
$bd = 'basededatos';
$table = 'tabla';
$field = 'campo';

//Conectamos a la Base de Datos
$link = mysql_connect($host, $user, $pass) or die('No se pudo Conectar: ' . mysql_error());
mysql_select_db($bd) or die('No se pudo seleccionar la base de datos');
//Consultamos a la Base de Datos
$query = "SELECT $field FROM $table LIMIT 0,100";
$result = mysql_query($query) or die('Consulta Fallida: ' . mysql_error());

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
	foreach ($line as $cedula) {
    	$array = array($cedula.",");

		foreach ($array as $valor) {

			$data = file_get_contents("http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=V&cedula=$valor");

			if ( preg_match('|<td align="left"><b><font color="#00387b">Cédula:</font></b></td>\s+<td align="left">V-(.*?)</td>|is' , $data , $cedula ) )
			{
			    echo $cedula[1].",";
			}
			if ( preg_match('|<td align="left"><b><font color="#00387b">Nombre:</font></b></td>\s+<td align="left"><b>(.*?)</b></td>|is' , $data , $nombres ) )
			{
			    echo $nombres[1].",";
			}
			if ( preg_match('|<td align="left"><b><font color="#00387b">Estado:</font></b></td>\s+<td align="left">(.*?)</td>|is' , $data , $entidad ) )
			{
			    echo $entidad[1].",";
			}
			/* Acá hay un error en esta linea que agradecería si pudiesen arreglar: <td align="left"><b><font color="#00387b">Municipio:</font></b></tdsta['descripcion'] = $s_res['descripcion'];> */
			//if ( preg_match('|<td align="left"><b><font color="#00387b">Municipio:</font></b></tdsta['descripcion'] = $s_res['descripcion'];>\s+<td align="left">(.*?)</td>|is' , $data , $municipio ) )
			//{
			//    echo $municipio[1].",";
			//}

			if ( preg_match('|<td align="left"><b><font color="#00387b">Parroquia:</font></b></td>\s+<td align="left">(.*?)</td>|is' , $data , $parroquia ) )
			{
			    echo $parroquia[1].",";
			}

			if ( preg_match('|<td align="left"><b><font color="#00387b">Centro:</font></b></td>\s+<td align="left"><font color="#0000FF">(.*?)</font></td>|is' , $data , $centro ) )
			{
			    echo $centro[1].",";
			}

			if ( preg_match('|<td align="left"><b><font color="#00387b">Dirección:</font></b></td>\s+<td align="left"><font color="#0000FF">(.*?)</font></td>|is' , $data , $direccion ) )
			{
			    echo $direccion[1];
			}
			echo "</br>";
		}
    }
}

//Liberamos y Cerramos la Consulta
mysql_free_result($result);
mysql_close($link);
?>
