<?php
include_once "Pedido.php";
include_once "AutentificadorJWT.php";

class PedidoApi {

      public static function IngresarPedido($request, $response, $args) {
     	
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
            $nuevoPedido->pedido=$pedido;
            $nuevoPedido->tiempoInicio=$tiempoInicio;
            $nuevoPedido->fotoMesa=$ultimoDestinoFoto;
           
           $nuevoPedido->GuardarPedido();


        $objDelaRespuesta->respuesta="Pedido Guardado en id: ". $pedido->id;
           
        return $response->withJson($objDelaRespuesta, 200);
        
    }

/*

public static function SacarAuto($request, $response, $args)
{
    $objDelaRespuesta=new stdclass();
    $ArrayDeParametros = $request->getParsedBody();
    $patente=$ArrayDeParametros['patente']; 
    

   $importe= Operacion::CerrarOperacion($patente);
    $auto=Auto::TraerUnAuto($patente);
    

    $objDelaRespuesta->auto=$auto;
    $objDelaRespuesta->importe=$importe;
    return $response->withJson($objDelaRespuesta, 200);

}


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