<?php

require_once "Mesa.php";

class MesaApi{

public static function ServirMesa($request, $response, $args)
{
    $respuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $idMesa=$ArrayDeParametros['idMesa'];
    $laMesa= Mesa::TraerUnaMesa($idMesa);
    $laMesa->estado="con cliente comiendo";
    $respuesta=$laMesa->ModificarMesa();

   
    return $response->withJson($respuesta,200);

}

public static function CerrarMesa($request, $response, $args)
{
    $respuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $idMesa=$ArrayDeParametros['idMesa'];
    $laMesa= Mesa::TraerUnaMesa($idMesa);
    $laMesa->estado="Cerrada";
    $respuesta=$laMesa->ModificarMesa();

   
    return $response->withJson($respuesta,200);

}

public static function CobrarMesa($request, $response, $args)
{
    $respuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $idMesa=$ArrayDeParametros['idMesa'];
    $laMesa= Mesa::TraerUnaMesa($idMesa);
    $laMesa->estado="cliente pagando";
    $respuesta=$laMesa->ModificarMesa();

   
    return $response->withJson($respuesta,200);

}


}

?>