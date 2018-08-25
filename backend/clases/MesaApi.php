<?php

require_once "Mesa.php";
require_once "Factura.php";


class MesaApi{

public static function CargarMesa($request, $response, $args)
{
    $respuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $idMesa=$ArrayDeParametros['idMesa'];
    $mesa=new Mesa();
    $mesa->idMesa=$idMesa;
    $mesa->estado="cerrada";
    $mesa->canUsos=0;
    $respuesta=$mesa->GuardarMesa();

   
    return $response->withJson($respuesta,200);

}

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
    $laFactura= new Factura();
    $laFactura->importe=(int)$total['total'];
    $laFactura->mesa=$laMesa->idMesa;
    $laFactura->fecha=date('Y-m-d');
    //var_dump($laFactura);
    $laFactura->GuardarFactura();
    Detalle::Cerrar($idMesa);
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



public static function MasFacturo($request, $response, $args)
{
    $respuesta=new stdclass();
  

   $respuesta= Mesa::LaQueMasFacturo();

   
   return $response->withJson($respuesta,200);
   

}

public static function MenosFacturo($request, $response, $args)
{
    $respuesta=new stdclass();
  

   $respuesta= Mesa::LaQueMenosFacturo();

   
   return $response->withJson($respuesta,200);
   

}

public static function MenorFactura($request, $response, $args)
{
    $respuesta=new stdclass();
  

   $respuesta= Mesa::LaDeMenorImporte();

   
   return $response->withJson($respuesta,200);
   

}

public static function MayorFactura($request, $response, $args)
{
    $respuesta=new stdclass();
  

   $respuesta= Mesa::LaDeMayorImporte();

   
   return $response->withJson($respuesta,200);
   

}



public static function FacturadoEntreFechas($request, $response, $args)

{   
    $parametros = $request->getParsedBody();
    $mesa=$parametros['mesa'];
    $desde=$parametros['desde'];
    $hasta=$parametros['hasta'];
    $respuesta=new stdclass();

   $respuesta= Mesa::FacturadoDesdeHasta($mesa, $desde, $hasta);

   
   return $response->withJson($respuesta,200);
   

}


}

?>