<?php
require_once 'Auto.php';
require_once 'IApiUsable.php';
require_once 'AutentificadorJWT.php';
require_once 'Imagenes.php';

class AutoApi extends Auto implements IApiUsable
{


 	public function TraerUno($request, $response, $args) {
     	$patente=$args['patente'];
         
        $auto=Auto::TraerUnAuto($patente);
        if(!$auto)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No esta El Auto";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($auto, 200); 
        }     
        return $NuevaRespuesta;
    }



public function TraerTodos($request, $response, $args) {
      	$listaDeAutos=Auto::TraerTodosLosAutos();
     	$newresponse = $response->withJson($listaDeAutos, 200);  
    	return $newresponse;
    }


public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
         $patente= $ArrayDeParametros['patente'];
          $color= $ArrayDeParametros['color'];
        $marca= $ArrayDeParametros['marca'];

        $miAuto = new Auto();
        $miAuto->patente=$patente;
        $miAuto->color=$color;
        $miAuto->marca=$marca;
        
     $ultimoId=$miAuto->AgregarAutoBD();
        
if($ultimoId>-1)
{
    $objDelaRespuesta->respuesta="Se guardo el Auto.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
}
else{

$objDelaRespuesta->respuesta="Ya existe el Auto.";

}
        
      
        return $response->withJson($objDelaRespuesta, 200);
    }




public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$patente=$ArrayDeParametros['patente'];
         var_dump($patente);
     	$auto= new Auto();
     	$auto->patente=$patente;
     	$cantidadDeBorrados=$auto->BorrarAuto();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="algo borro!!!";
	    	}
	    	else
	    	{
	    		$objDelaRespuesta->resultado="no Borro nada!!!";
	    	}
	    $newResponse = $response->withJson($objDelaRespuesta, 200);  
      	return $newResponse;
    }
     
     public function ModificarUno($request, $response, $args) {
     	
     	$ArrayDeParametros = $request->getParsedBody(); 
         $patente=$ArrayDeParametros['patente'];   	
         $color= $ArrayDeParametros['color'];
          $marca= $ArrayDeParametros['marca'];

 
        $miAuto = new Auto();
        $miAuto->patente=$patente;
        $miAuto->color=$color;
        $miAuto->marca=$marca;
        var_dump($patente);

	   	$resultado =$miAuto->ModificarAuto();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }

    public static function AutosEstacionados($request, $response)
    {
        $respuesta= new stdclass();
        $listaAutos=parent::AutosEstacionadosBD();
        $respuesta=$response->withJson( $listaAutos,200);
        return $respuesta;

    }





    

    


}