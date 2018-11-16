<?php
require_once 'Cochera.php';
require_once 'IApiUsable.php';
require_once 'AutentificadorJWT.php';
require_once 'Imagenes.php';

class CocheraApi extends Cochera implements IApiUsable
{


 	public function TraerUno($request, $response, $args) {
     	$idCochera=$args['idCochera'];
         $objDelaRespuesta= new stdclass();

try{
    $cochera=Cochera::TraerUnaCochera($idCochera);
    $objDelaRespuesta = $response->withJson($cochera, 200); 
    return $objDelaRespuesta;
}
catch(Exception $e){

$objDelaRespuesta->error=$e->getMessage();
$NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
return $NuevaRespuesta;

}           
            
        
       
}

     public function TraerTodos($request, $response, $args) {
      	$listaDeCocheras=Cochera::TraerTodasLasCocheras();
     	$newresponse = $response->withJson($listaDeCocheras, 200);  
    	return $newresponse;
}


      public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
         $tipo= $ArrayDeParametros['tipo'];
          $piso= $ArrayDeParametros['piso'];
        $numero= $ArrayDeParametros['numero'];

        $miCochera = new Cochera();
        $miCochera->tipo=$tipo;
        $miCochera->piso=(int)$piso;
        $miCochera->numero=(int)$numero;
        $miCochera->estado="Libre";
        $miCochera->canUsos=0;

        $ultimoId=$miCochera->GuardarCochera();
    
        $objDelaRespuesta->respuesta="Se guardo La cochera.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
        return $response->withJson($objDelaRespuesta, 200);
    }



 public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
     	$idCochera=$ArrayDeParametros['idCochera'];
         
     	$cochera= new Cochera();
     	$cochera->idCochera=$idCochera;
     	$cantidadDeBorrados=$cochera->BorrarCochera();

     	$objDelaRespuesta= new stdclass();
	    $objDelaRespuesta->cantidad=$cantidadDeBorrados;
	    if($cantidadDeBorrados>0)
	    	{
	    		 $objDelaRespuesta->resultado="Borrados $cantidadDeBorrados elementos.";
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
         $tipo=$ArrayDeParametros['tipo'];   	
         $piso= $ArrayDeParametros['piso'];
          $numero= $ArrayDeParametros['numero'];
          $estado= $ArrayDeParametros['estado'];
          $canUsos= $ArrayDeParametros['canUsos'];
          $idCochera=$ArrayDeParametros['idCochera'];

 
        $miCochera = new Cochera();
        $miCochera->tipo=$tipo;
        $miCochera->piso=$piso;
        $miCochera->numero=$numero;
        $miCochera->estado=$estado;
        $miCochera->canUsos=$canUsos;
        $miCochera->idCochera=$idCochera;

	   	$resultado =$miCochera->ModificarCochera();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }


    public static function MasUtilizada($request, $response, $args) 
    {
        $respuesta= new stdclass();
        $cochera=Cochera::MasUtilizada();
        return $response->withJson($cochera,200);
    }

        public static function MenosUtilizada($request, $response, $args) 
    {
        
        $respuesta= new stdclass();
        $cochera=Cochera::MenosUtilizada();
        return $response->withJson($cochera,200);
    }

        public static function NoSeUso($request, $response, $args) 
    {
        $respuesta= new stdclass();
        try{
            $respuesta->cochera= Cochera::NoSeUso();
            return $response->withJson($respuesta, 200);	
        }
        catch(Exeption $e)
        {
                $respuesta->error=$e->getMessage();
            return $response->withJson($respuesta, 200);
        }
        
    }




    

    


}