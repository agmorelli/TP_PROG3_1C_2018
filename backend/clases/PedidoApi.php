<?php
include_once "Pedido.php";
include_once "Detalle.php";
include_once "AutentificadorJWT.php";

class PedidoApi {

public static function IngresarPedido($request, $response, $args) 
{
     	
        $objDelaRespuesta= new stdclass();
        
        $ArrayDeParametros = $request->getParsedBody();
        //$token=$ArrayDeParametros['token'];
     //  $payload=AutentificadorJWT::ObtenerData($token);
      
        $idMesa= $ArrayDeParametros['idMesa'];
        $pedido= $ArrayDeParametros['pedido'];
        $tiempoInicio= date('Y/m/d G:i,s');

        $archivos = $request->getUploadedFiles();
        $destino="./fotos/";
        $logo="logo.png";
        
            $nombreAnterior=$archivos['foto']->getClientFilename();
            $extension= explode(".", $nombreAnterior)  ;
         
            $extension=array_reverse($extension);

            $ultimoDestinoFoto=$destino.$idMesa.".".$extension[0];

            if(file_exists($ultimoDestinoFoto))
            {
              
                copy($ultimoDestinoFoto,"./backup/".date("Ymd").$idMesa.".".$extension[0]);
            }

            $archivos['foto']->moveTo($ultimoDestinoFoto);

            $nuevoPedido= new Pedido();
            $nuevoPedido->idMesa=$idMesa;
            $nuevoPedido->tiempoInicio=$tiempoInicio;
            $nuevoPedido->fotoMesa=$ultimoDestinoFoto;   
           $idPedido=$nuevoPedido->GuardarPedido();

           $arrayDetalle=explode(",",$pedido);
           

           foreach ($arrayDetalle as $producto)
           {

            $detallePedido=new Detalle();
            $detallePedido->idPedido=$idPedido;
            $detallePedido->producto=$producto;
            $detallePedido->estado="pendiente";
            
                if ($producto=='trago'|| $producto=='vino'){
                    $detallePedido->sector="barra";
                }
                if($producto=='pizza'|| $producto=='empanadas' || $producto=='plato')
                {
                    $detallePedido->sector="cocina";
                }
                if($producto=='cerveza')
                {
                    $detallePedido->sector="chopera";
                }
                if($producto=='postre')
                {
                    $detallePedido->sector="candy bar";
                }
        
            $detallePedido->GuardarDetalle();

           }

        $objDelaRespuesta->idPedido= $idPedido;
           
        return $response->withJson($objDelaRespuesta, 200);
        
}

public static function TraerPendientesEmpleado($request, $response, $args)
{
    $objDelaRespuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $token=$ArrayDeParametros['token'];
    $payload=AutentificadorJWT::ObtenerData($token);
    $idEmpleado=$payload->idEmpleado;
   $objDelaRespuesta=Detalle::TraerPendientes($idEmpleado);

    return $response->withJson($objDelaRespuesta, 200);

}

/*
public static function TraerTodos($request, $response, $args)
{
    $respuesta=new stdclass();

    $respuesta=Operacion::TraerTodasLasOperaciones();

    return $response->withJson($respuesta,200);

}

public static function CalcularCosto($request, $response, $args)
{
        $objDelaRespuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $patente=$ArrayDeParametros["patente"];
    $operacion=Operacion::TraerUnaOperacionAbierta($patente);
    $respuesta=$operacion->CalcularCosto();
    return $response->withJson($respuesta,200);

}*/
}

?>