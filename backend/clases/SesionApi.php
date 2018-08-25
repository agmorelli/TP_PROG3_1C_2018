
<?php
include_once "Sesion.php";
include_once "Empleado.php";
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

    $empleado=Empleado::ValidarEmpleado($usuario,$clave);
    $sesion= new Sesion();
    $sesion->idEmpleado=$empleado->id;
    $sesion->horaInicio= date('Y/m/d G:i,s');
    $idSesion=$sesion->IniciarSesion();
    $datos = array('usuario' => $empleado->usuario,'perfil' => $empleado->perfil, 'idEmpleado' => $empleado->id, 'sector' => $empleado->sector, 'estado' => $empleado->estado, 'idSesion' => $idSesion);
    $token= AutentificadorJWT::CrearToken($datos);
    $respuesta= array('token'=>$token,'datos'=> $datos);

}
catch(Exception  $e)
    {

       echo( $e->getMessage());

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
