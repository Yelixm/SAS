<?php
	include_once("../../Clases/PHPPaging.lib.php");
	include_once("../../Clases/clase_departamento.php");
	$departamento= new departamento();
	$paging = new PHPPaging;	
	$consulta=$departamento->sql_departamento();
	$paging->agregarConsulta($consulta); 	
	$paging->porPagina(6);
	$paging->div('div_listar_departamento');
	$paging->verPost(true);
	$paging->ejecutar();	
?>
<table id="grilla" class="lista" width="424">
  <thead>       
        <tr>
            <th width="336">Lista de Departamentos</th>
            <th width="36">&nbsp;</th>           
        </tr>
    </thead>
    <tbody>
    <?php while ($result=$paging->fetchResultado()){?>
        <tr>        
            <td><?php echo $result['nombre'];?></td>           
            <td><a href="javascript: fn_eliminar_departamento(<?php echo $result['id_departamento'];?>);"><img src="../../Imagen_sistema/delete.png" title="Eliminar" /></a></td>
        </tr>        
    <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">
					<?php echo $paging->fetchNavegacion();?><br />					
					Mostrando <?php echo $paging->numRegistrosMostrados();?> registros, 
					de un total de <?php echo $paging->numTotalRegistros();?>                      
            </td>
        </tr>
    </tfoot>  

</table>