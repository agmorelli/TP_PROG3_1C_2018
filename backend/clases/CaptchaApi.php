<?php

class CaptchaApi
{
    public function RecibirCaptcha($request, $response, $args) {
        $ArrayDeParametros = $request->getParsedBody();
        $consulta=$ArrayDeParametros['consulta'];
        $objDelaRespuesta= new stdclass();
 
       $objDelaRespuesta->respuesta = $consulta;

       $newResponse = $response->withJson($objDelaRespuesta, 200);  
         return $newResponse;
    }

}

?>