<?php
require_once 'Usuario.php';
require_once 'IApiUsable.php';
require_once 'AutentificadorJWT.php';


class UsuarioApi extends Usuario implements IApiUsable
{


 	public function TraerUno($request, $response, $args) {
     	$id=$args['id'];
        $Usuario=Usuario::TraerUnUsuario($id);
        if(!$Usuario)
        {
            $objDelaRespuesta= new stdclass();
            $objDelaRespuesta->error="No existe El usuario";
            $NuevaRespuesta = $response->withJson($objDelaRespuesta, 500); 
        }else
        {
            $NuevaRespuesta = $response->withJson($empleado, 200); 
        }     
        return $NuevaRespuesta;
    }

     public function TraerTodos($request, $response, $args) {
      	$todosLosUsuario=Usuario::TraerTodoLosUsuarios();
     	$newresponse = $response->withJson($todosLosUsuario,200);  
        
    	return $newresponse;
       
    }
      public function CargarUno($request, $response, $args) {
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
         $usuario= $ArrayDeParametros['usuario'];
        $clave= $ArrayDeParametros['clave'];
        $perfil= $ArrayDeParametros['perfil'];
        $sexo= $ArrayDeParametros['sexo'];
        $estado= "Activo";
       

        $miUsuario= new Usuario();
        $miUsuario->usuario=$usuario;
        $miUsuario->clave=$clave;
        $miUsuario->sexo=$sexo;
        $miUsuario->perfil=$perfil;
        $miUsuario->estado=$estado;

        $ultimoId=$miUsuario->InsertarUsuario();    
        //$response->getBody()->write("se guardo el empleado");
        $objDelaRespuesta->respuesta="Se guardo el Usuario.";
        $objDelaRespuesta->ultimoIdGrabado=$ultimoId;   
        return $response->withJson($objDelaRespuesta, 200);
    }




      public function BorrarUno($request, $response, $args) {
     	$ArrayDeParametros = $request->getParsedBody();
         
     	$id=$ArrayDeParametros['id'];
     	$Usuario= new Usuario();
     	$Usuario->id=$id;
         
     	$cantidadDeBorrados=$Usuario->BorrarUsuario();

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
	    $newResponse = $response->withJson($ArrayDeParametros, 200);  
      	return $newResponse;
    }



     
     public function ModificarUno($request, $response, $args) 
     {
     	
     	$ArrayDeParametros = $request->getParsedBody(); 
        $objDelaRespuesta= new stdclass();

        $usuario= $ArrayDeParametros['usuario'];
        $sexo= $ArrayDeParametros['sexo'];
      $clave= $ArrayDeParametros['clave'];
      $perfil= $ArrayDeParametros['perfil'];
      $id= $ArrayDeParametros['id'];

      $miUsuario= new Usuario();
      $miUsuario->usuario=$usuario;
      $miUsuario->clave=$clave;
      $miUsuario->sexo=$sexo;
      $miUsuario->perfil=$perfil;
      $miUsuario->id=$id;

	   	$resultado =$miEmpleado->ModificarUsuario();
	   	$objDelaRespuesta= new stdclass();
		//var_dump($resultado);
		$objDelaRespuesta->resultado=$resultado;
        $objDelaRespuesta->tarea="modificar";
		return $response->withJson($objDelaRespuesta, 200);		
    }

 public function Login($request, $response, $args) 
 {
     	
     	$ArrayDeParametros = $request->getParsedBody();
	 
	    $usuario=$ArrayDeParametros['usuario'];
	    $clave=$ArrayDeParametros['clave'];
        $usuario=Usuario::ValidarUsuario($usuario,$clave);
        $datos = array('usuario' => $usuario->usuario,'perfil' => $usuario->perfil, 'id'=>$usuario->id, 'sector'=>$usuario->sector , 'estado'=>$usuario->estado);


       $token= AutentificadorJWT::CrearToken($datos);
        $respuesta= array('token'=>$token);
        


		return $response->withJson($respuesta, 200);		
}


         public static function Suspender($request, $response, $args) 
         {
     	
     	 $ArrayDeParametros = $request->getParsedBody(); 
         $id=$ArrayDeParametros['id'];
         $estado=$ArrayDeParametros['estado'];   	
 
         $resultado= Usuario::SuspenderUsuario($id,$estado);
        
	   	 $objDelaRespuesta= new stdclass();
		 //var_dump($resultado);
		 $objDelaRespuesta->resultado=$resultado;
         $objDelaRespuesta->tarea="Suspender";
		 return $response->withJson($objDelaRespuesta, 200);		
         }

    public static function CantidadDeOperaciones($request, $response, $args)
    {
        $id=$args['id'];
        $operaciones=Usuario::CantidadDeOperacionesUsuario($id);
        return $response->withJson($operaciones, 200);
    }

    public static function IngresosAlSistema($request, $response, $args)
    {
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::FechasDeLogueo();

        return $response->withJson($objDelaRespuesta, 200);


    }

    public static function OperacionesTodosUsuarios($request, $response, $args)
    {
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::OperacionesTodosLosUsuarios();
        return $response->withJson($objDelaRespuesta, 200);

    }

    public static function OperacionestodosSectores($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
	    $perfil=$ArrayDeParametros['perfil'];
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::CantidadOperacionesTodosSectores($perfil);
        return $response->withJson($objDelaRespuesta, 200);

    }

    public static function OperacionesUsuarioSeparado($request, $response, $args)
    {
        
        $idUsuario=$args['idUsuario'];
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::CantidadOperacionesUsuariosSeparado($idUsuario);
        return $response->withJson($objDelaRespuesta, 200);

    }
    
    public static function OperacionesUsuariossSector($request, $response, $args)
    {
        
        $perfil=$args['perfil'];
        
        $objDelaRespuesta= new stdclass();
        $objDelaRespuesta=Usuario::CantidadOperacionesUsuariosPorSector($perfil);
        return $response->withJson($objDelaRespuesta, 200);

    }

   
    

    


}