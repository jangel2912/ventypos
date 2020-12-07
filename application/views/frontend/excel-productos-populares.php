<?php
/*header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=abonos.csv");
// Disable caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies
*/
$filename = "PRODUCTOS_POPULARES.xls";
   header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment;filename=".$filename); //tell browser what's the file name
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: max-age=0");
?>
<table cellspacing="0" cellpadding="0">
 <tr>
 <th>PRODUCTO</th>
 <th>CANTIDAD VENDIDA</th>
  <th>DESDE</th>
  <th>HASTA</th>  
    <th>ALMACEN</th> 
 </tr>

<?php
foreach($data['productos'] as $value){
?>
  <tr>
 <td><?php echo $value->nombre_producto ?></td>
  <td><?php echo $value->count_productos ?></td>
   <td><?php echo $this->input->post('fecha_desde'); ?></td> 
    <td><?php echo $this->input->post('fecha_hasta'); ?></td>  
    <td><?php echo $value->nombre; ?></td>  	 
  </tr>
<?php
   }
?>

</table>
