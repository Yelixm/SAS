<?php
// Llamado a la clase a usarse
include_once("../Clases/clase_titular.php");
include_once("../Clases/clase_detalle_discapacidad.php");
include_once("../Clases/clase_detalle_profesion.php");
include_once("../Clases/clase_detalle_recaudos.php");
$ope = $_POST['ope'];
switch($ope){
  case "I":	{
	incluir();
	break;
	}
  case "Buscar":{
	buscar();
	break;
	}
  case "M":	{	
  	modificar();
	break;
	}
  case "Elimina":{
	elimina();
	break;
	}
}
//       Funcion para Registrar
function incluir(){
// Se crea un Objeto  de la clase 
	$titular = new titular();
	$detalle_disc = new detalle_disc();
	$detalle_pro = new detalle_pro();
	$detalle_rec = new detalle_rec();
//declaracion de unas variables a usarse
	$idTitular='0';
	$idTitular_pro='0';
	$idTitular_disc='0';
	$idTitular_rec='0';
	$var_control= false; //variable de control para seguir o terminar la transaccion
	// Se envian los datos por los metodos set
		$titular->setTipo($_POST["tipo_nomina"]);
		$titular->setNac($_POST["nacionalidad"]);
		$titular->setCed($_POST["cedula"]);
		$titular->setNom1($_POST["nombre1"]);
		$titular->setNom2($_POST["nombre2"]);
		$titular->setApe1($_POST["apellido1"]);
		$titular->setApe2($_POST["apellido2"]);
		$titular->setSex($_POST["sexo"]);
		
		$titular->setEsta_civ($_POST["estado_civ"]);
		$titular->setCel($_POST["celular"]);
		$titular->setTlf($_POST["telefono"]);
		$titular->setEmail($_POST["correo"]);
		$titular->setCorreo($_POST["correo2"]);
		$titular->setFec_ingr($_POST["fecha_ingr"]);
		$titular->setidProfesion($_POST["profesion"]);
		$titular->setidCargo($_POST["cargo"]);
		$titular->setidCiudad($_POST["ciudad"]);
		$titular->setidCiudadnac($_POST["ciudad2"]);
		$titular->setDepartamento($_POST["departamento"]);			
		$titular->setDireccion($_POST["direccion"]);
		$titular->setUpsa($_POST["upsa"]);
		$titular->setObserv($_POST["observacion"]);	
		// Se inicia la Transacción
		$titular->IniciaTransaccion();
		// Se verifica que no exista para poder incluir
		$ValidaTitular=$titular->validar_titular();	
		if (strlen($_POST["fecha_nac"])==10)
		{
			$elDia=substr($_POST["fecha_nac"],0,2);
			$elMes=substr($_POST["fecha_nac"],3,2);
			$elYear=substr($_POST["fecha_nac"],6,4);
			$fecha=$elYear."-".$elMes."-".$elDia;
		}
			$titular->setFec_nac($fecha			);		
		$MayorEdad=$titular->edad();
		if($MayorEdad<18){
			echo $FechaBD."Esta persona es menor de edad. \nNo puede Registrarlo como trabajador!";	
			$var_control=true;	
			exit();				
		}
		if ($ValidaTitular=='-1'){
		// Si $vadila_titular no encuentra nada (-1) 
		// Busca el ultimo registro de la entrada e incrementa el id
				$result = $titular->buscaUltimoID();	
					if ($result){
					$result = $titular->sig_tupla($result);		
					$idTitular = $result["id_titular"] + 1;
					}
					$titular->setidTitular($idTitular);
		//Registramos El trabajador			
					$iTitular= $titular->iTitular();
					if($iTitular!='-1'){
						$var_control=true;
						echo "Error 1";
					}
			$arreglo_disc = $_POST["discapacidad"]; //Arreglo de discapacidad
			$cont_disc='0';
			while($cont_disc<count($arreglo_disc)){	
		//Consultamos el ultimo $id_detalle_discapacidad y traemos el ultimo	
		// Busca el ultimo registro de la entrada e incrementa el id	
					$result = $detalle_disc->tUltimoID_Disc();
					if ($result){
						$result = $detalle_disc->sig_tupla($result);		
						$idTitular_disc = $result["id_titular_discapacidad"] + 1;
					}	
					$detalle_disc->setId_titular_disc($idTitular_disc);	
					$detalle_disc->setidTitular($idTitular);	
					$detalle_disc->setidDiscapacidad($arreglo_disc[$cont_disc]);
		//Registramos el Detalle de la Discapacidad
				$iTitular_Discapacidad=$detalle_disc->iTitular_Discapacidad();
					if($iTitular_Discapacidad!='-1'){
					 $var_control=true;	 
					 echo "Error 3";
					 }
			 $cont_disc++;	 
			 }	
			 $arreglo_recaudos = $_POST["recaudos"]; //Arreglo de discapacidad
			 $cont_rec='0';
			 while($cont_rec<count($arreglo_recaudos)){
				 $result = $detalle_rec->tUltimoID_Rec();
				 if ($result){
						$result = $detalle_rec->sig_tupla($result);		
						$idTitular_rec = $result["id_titular_recaudo"] + 1;
					}	
					$detalle_rec->setId_titular_rec($idTitular_rec);
					$detalle_rec->setidTitular($idTitular);
					$detalle_rec->setidRecaudos($arreglo_recaudos[$cont_rec]);
				//Registramos el Detalle de los recaudos
				$iTitular_Recaudos=$detalle_rec->iTitular_Recaudos();
					if($iTitular_Recaudos!='-1'){
					 $var_control=true;	 
					 echo "Error 4";
					 }
			 $cont_rec++;	 	
			 }
		}else{
			echo 'Este usuario ya ha sido incluido al sistema.';//usuario ya registrado			
			$var_control=true;
			exit();
		}
		
		if ($var_control){	
			$titular->RompeTransaccion();		
			echo "No se pudo llevar a cabo el registro debido a un error interno de la Base de Datos.";
			exit();
		}else{
			$titular->FinTransaccion();	
			echo "Los Datos se guardaron con Exito!";
			exit();	
		}

}
//       Funcion para Modificar
function modificar(){
// Se crea un Objeto  de la clase 
	$titular = new titular();
	$detalle_disc = new detalle_disc();
	$detalle_pro = new detalle_pro();
	$detalle_rec = new detalle_rec();
	$var_control= false; //variable de control para seguir o terminar la transaccion
	// Se envian los datos por los metodos set		
		$titular->setidTitular($_POST['id_titular']);
		$titular->setTipo($_POST["tipo_nomina"]);
		$titular->setNac($_POST["nacionalidad"]);
		$titular->setCed($_POST["cedula"]);
		$titular->setNom1($_POST["nombre1"]);
		$titular->setNom2($_POST["nombre2"]);
		$titular->setApe1($_POST["apellido1"]);
		$titular->setApe2($_POST["apellido2"]);
		$titular->setSex($_POST["sexo"]);
		$titular->setFec_nac($_POST["fecha_nac"]);	
		$titular->setEsta_civ($_POST["estado_civ"]);
		$titular->setCel($_POST["celular"]);
		$titular->setTlf($_POST["telefono"]);
		$titular->setEmail($_POST["correo"]);
		$titular->setCorreo($_POST["correo2"]);
		$titular->setFec_ingr($_POST["fecha_ingr"]);
		$titular->setidCargo($_POST["cargo"]);
		$titular->setidCiudad($_POST["ciudad"]);
		$titular->setidCiudadnac($_POST["ciudad2"]);
		$titular->setDepartamento($_POST["departamento"]);			
		$titular->setDireccion($_POST["direccion"]);
		$titular->setUpsa($_POST["upsa"]);
		$titular->setObserv($_POST["observacion"]);	
				// Se inicia la Transacción
				$titular->IniciaTransaccion();	
		$mTitular=$titular->mTitular();
		if($mTitular!='-1'){				
			$var_control=true;
			echo "Error interno de la Base de Datos 1!";
			exit();
		}
//Para trabajo mas facil borramos el detalle de las discapacidades e insertamos nuevamente.
		$detalle_disc->setidTitular($_POST['id_titular']);
		$eTitular_Discapacidad=$detalle_disc->eTitular_Discapacidad();
		if($eTitular_Discapacidad!='-1'){				
			$var_control=true;
			echo "Error interno de la Base de Datos 2!";
			exit();
		}
		$arreglo_disc = $_POST["discapacidad"]; //Arreglo de discapacidad
			$cont_disc='0';
			while($cont_disc<count($arreglo_disc)){	
		//Consultamos el ultimo $id_detalle_discapacidad y traemos el ultimo	
		// Busca el ultimo registro de la entrada e incrementa el id	
					$result = $detalle_disc->tUltimoID_Disc();
					if ($result){
						$result = $detalle_disc->sig_tupla($result);		
						$idTitular_disc = $result["id_titular_discapacidad"] + 1;
					}	
					$detalle_disc->setId_titular_disc($idTitular_disc);	
					$detalle_disc->setidDiscapacidad($arreglo_disc[$cont_disc]);
		//Registramos el Detalle de la Discapacidad
				$iTitular_Discapacidad=$detalle_disc->iTitular_Discapacidad();
					if($iTitular_Discapacidad!='-1'){
					 $var_control=true;	 
					 echo "Error interno de la Base de Datos 3!";
					 }
			 $cont_disc++;	 
			 }	
//Para trabajo mas facil borramos el detalle de las discapacidades e insertamos nuevamente.
//Detalle Titular - Profesion
		$detalle_pro->setidTitular($_POST['id_titular']);
			$eTitular_Profesion=$detalle_pro->eTitular_Profesion();
				if($eTitular_Profesion!='-1'){				
					$var_control=true;
					echo "Error interno de la Base de Datos 4!";
					exit();
				}
			 $arreglo_pro = $_POST["profesion"]; //Arreglo de Profesion
			 $cont_pro='0';	
			 while($cont_pro<count($arreglo_pro)){	
		//Consultamos el ultimo $id_profesion_titular y traemos el ultimo	
		// Busca el ultimo registro de la entrada e incrementa el id
					$result = $detalle_pro->tUltimoID_Pro();
						if ($result){
						$result = $detalle_pro->sig_tupla($result);		
						$idTitular_pro = $result["id_titular_profesion"] + 1;
						}		
						$detalle_pro->setId_titular_pro($idTitular_pro);
						$detalle_pro->setidProfesion($arreglo_pro[$cont_pro]);
		//Registramos el detalle de profesion			
						$iTular_Profesion=$detalle_pro->iTitular_Profesion();
						if($iTular_Profesion!='-1'){
							$var_control=true;	 
							echo "Error interno de la Base de Datos 5!";
						}
				$cont_pro++;	 
				}
//Para trabajo mas facil borramos el detalle de las discapacidades e insertamos nuevamente.
// Detalle Titular - Recaudos
				$detalle_rec->setidTitular($_POST['id_titular']);
				$eTitular_Recaudos=$detalle_rec->eTitular_Recaudos();
					if($eTitular_Recaudos!='-1'){				
					$var_control=true;
					echo "Error interno de la Base de Datos 6!";
					exit();
				}
				 $arreglo_recaudos = $_POST["recaudos"]; //Arreglo de discapacidad
				 $cont_rec='0';
				 while($cont_rec<count($arreglo_recaudos)){
					 // Busca el ultimo registro de la entrada e incrementa el id
					 $result = $detalle_rec->tUltimoID_Rec();
					 if ($result){
							$result = $detalle_rec->sig_tupla($result);		
							$idTitular_rec = $result["id_titular_recaudo"] + 1;
						}	
						$detalle_rec->setId_titular_rec($idTitular_rec);
						$detalle_rec->setidRecaudos($arreglo_recaudos[$cont_rec]);
					//Registramos el Detalle de los recaudos
					$iTitular_Recaudos=$detalle_rec->iTitular_Recaudos();
						if($iTitular_Recaudos!='-1'){
						 $var_control=true;	 
						 echo "Error 4";
						 }
				 $cont_rec++;	 	
				 }
		if ($var_control){	
			$titular->RompeTransaccion();		
			echo "No se pudo llevar a cabo el registro debido a un error interno de la Base de Datos.";
			exit();
		}else{
			$titular->FinTransaccion();	
			echo "Los datos fueron guardados con Exito!!!";
			exit();	
		}	
}
// se envian los datos del formulario por los metodos set de cada uno

?>
