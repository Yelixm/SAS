<?php
include_once("clase_mysql.php");
//       Clase cargo hereda a clase CModeloDatos para conectar BD MYSql
class profesion extends conectaBDMy{
	private $idProfesion;	
	private $nom;
//       Metodo Constructor de la clase
    public function profesion(){
		parent::conectaBDMy();	    
		$this->idProfesion="";		
		$this->nom="";		
	}//cirre del constructor
//       Metodos Set para cada propiedad de la clase
 	public function setidProfesion($Valor){
        $this->idProfesion = trim($Valor);
    }  
	public function setNom($Valor){
        $this->nom = mb_strtoupper(trim($Valor), "utf-8");
    }
//       Metodo registrar
 public function iProfesion(){	  
       $sql="INSERT INTO tprofesion(nombre,estatus) values('$this->nom','1')";
     $respuesta= parent::ejecuta_sql($sql);
	 if($respuesta>0)
		  return -1;//Exito
	   else
	      return 1;//Fallo Registro
		  
		  parent::cerrar_bd();
    }
//       Metodo Eliminar
public function eProfesion(){
		$sql= "update tprofesion set estatus='0' where id_profesion='$this->idProfesion'";
		$respuesta = parent::ejecuta_sql( $sql );
			if ( $respuesta>0 )
				return -1;// Exito
			else
				return 1;//fallo la operacion
				
				parent::cerrar_bd();

}	
//       Metodo para listar cargo en los combos
	function lista_profesion()
	{ 
		$c=0;
		$sql="select * from tprofesion where estatus='1' order by nombre";		
		 $cursor=parent::ejecuta_sql($sql);
		 if($row= parent::proxima_tupla($cursor))
		 {
			 DO
			 {
				$fila[$c][1]=$row["id_profesion"];
				$fila[$c][2]=$row["nombre"];			
				$c++;				 
			 }WHILE($row= parent::proxima_tupla($cursor));
		 }
		if ( $fila>0 )
			return $fila;
		else
			return -1;
			
		parent::cerrar_bd();
	}   
public function valida_profesion() { 
		$sql="select * from tprofesion where nombre='$this->nom'"; 
		$cursor=parent::ejecuta_sql( $sql );
		return ( parent::getNRegistro($cursor) );
		//Si encuentra registro envia 1 para validar	
		//si no encuentra registro procede a registrar	
		
		parent::cerrar_bd();							 		
	}   
		
//       Sentencia sql para listar
     public function sql_profesion(){
        $sql="SELECT * FROM tprofesion WHERE estatus='1' order by nombre";
		$cursor=parent::ejecuta_sql($sql);	
// verifica que la consulta arroje al menos 1 fila para poder enviar la sentencia sql
		if(parent::getNRegistro($cursor)<0)
			return 1;//fallo la operacion
			else 
			return $sql;		
			
			parent::cerrar_bd();
     }		

}//cierra la clase
?>
