
<?php
include_once "Sesion.php";

include_once "Usuario.php";
include_once "AutentificadorJWT.php";

class SesionApi
{

    public function Login($request, $response, $args) {
     	
         $respuesta= new stdclass();
     	$ArrayDeParametros = $request->getParsedBody();
	    $usuario=$ArrayDeParametros['usuario'];
        $clave=$ArrayDeParametros['clave'];
  
try
{

    $usuario=Usuario::ValidarUsuario($usuario,$clave);
    $sesion= new Sesion();
    $sesion->idEmpleado=$usuario->id;
    $sesion->horaInicio= date('Y/m/d G:i,s');
    $idSesion=$sesion->IniciarSesion();
    $datos = array('usuario' => $usuario->usuario,'perfil' => $usuario->perfil, 'idUsuario' => $usuario->id, 'idSesion' => $idSesion);
    $token= AutentificadorJWT::CrearToken($datos);
    $respuesta= array('token'=>$token,'datos'=> $datos);

}
catch(Exception  $e)
    {

       $respuesta->error = $e->getMessage();

    }

		return $response->withJson($respuesta, 200);		
}

public static function CerrarSesion($request, $response)
{
    
    try
    {
    
        $respuesta= new stdclass();
        $ArrayDeParametros=$request->getParsedBody();
        $token=$ArrayDeParametros["token"];
        $payload=AutentificadorJWT::ObtenerData($token);

        $idSesion=$payload->idSesion;
        $fechaFinal=date('Y/m/d G:i,s');
        
        $ok=Sesion::CerrarSesion($idSesion, $fechaFinal);
        

        if($ok)
        {
            $respuesta="Cerraste la sesion con exito.";
        }

    }
 catch(Exception $e)
        {
            $respuesta= $e->getMessage();
        }

         return $response->withJson($respuesta, 200);		

}

/////FINAL CLASE///////

}

?>
