<?php

require_once "AutentificadorJWT.php";

class MWLaComanda
{
    public static function VerificarAdministrador($request, $response, $next)
{
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->respuesta="";
    $ArrayDeParametros = $request->getParsedBody();
    $token=$ArrayDeParametros['token'];
    $payload=AutentificadorJWT::ObtenerData($token);
    if($payload->perfil=="admin")
		{
			$response = $next($request, $response);
        }	
        else{

            $objDelaRespuesta->respuesta ="solo administrador!";
            return $response->withJson($objDelaRespuesta, 401);  
        }
        return $response;
}

public static function VerificarSuspendido($request, $response, $next)
{
    if($request->isGet())
    {
    
     $response = $next($request, $response);
    }
    else
    {

    
    $objDelaRespuesta= new stdclass();
    $objDelaRespuesta->respuesta="";
    $ArrayDeParametros = $request->getParsedBody();
    $token=$ArrayDeParametros['token'];
    $payload=AutentificadorJWT::ObtenerData($token);
    var_dump($payload);
    if($payload->estado != "suspendido")
		{
			$response = $next($request, $response);
        }	
        else{

            $objDelaRespuesta->respuesta ="Usted esta suspendido!";
            return $response->withJson($objDelaRespuesta, 401);  
        }
    }
        return $response;
    
}
}

	


?>