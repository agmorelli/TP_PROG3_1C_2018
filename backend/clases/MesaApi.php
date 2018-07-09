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
    $total=$laMesa->Facturar();
    $laMesa->estado="cliente pagando";
    $laMesa->ModificarMesa();
    //Detalle::Cerrar($idMesa);
    $respuesta=$total;

   
    return $response->withJson($respuesta,200);

}

public static function MasUtilizada($request, $response, $args)
{
    $respuesta= Mesa::MasUtilizada();
    return $response->withJson($respuesta,200);
    
}
public static function MenosUtilizada($request, $response, $args)
{
    $respuesta= Mesa::MenosUtilizada();
    return $response->withJson($respuesta,200);
    
}
public static function NoSeUso($request, $response, $args)
{
    $respuesta= Mesa::NoSeUso();
    return $response->withJson($respuesta,200);
    
}
/*
public static function CerrarMesa($request, $response, $args)
{
    $respuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $idMesa=$ArrayDeParametros['idMesa'];
    $laMesa= Mesa::TraerUnaMesa($idMesa);
   $laMesa->estado="cerrada";
  

   $respuesta= $lamesa->ModificarMesa();

   
   return $response->withJson($respuesta,200);
   

}*/


}

?>