<?php

include_once "Usuario.php";

class Pedido{

public $id;
public $idMesa;
public $tiempoInicio;
public $fotoMesa;





public function GuardarPedido()
{
 
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (idMesa, tiempoInicio, fotoMesa)values(:idMesa, :tiempoInicio, :fotoMesa)");
    $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
    $consulta->bindValue(':tiempoInicio', $this->tiempoInicio, PDO::PARAM_STR);
    $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);
    $consulta->execute();
	return $objetoAccesoDato->RetornarUltimoIdInsertado();
}


public static function TraerTodosLosPedidos() 
{
	$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidos ");  
	$consulta->execute();
	$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
            
    return $pedidos;
									
}

public static function PedidosCancelados()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT idPedido, producto, estado from pedidodetalle where estado='cancelado' ");  
	$consulta->execute();
	$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS);
            
    return $pedidos;
}


public static function MasVendido()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT producto FROM pedidodetalle GROUP BY producto HAVING COUNT(*) =(SELECT COUNT( producto ) tot FROM pedidodetalle GROUP BY producto ORDER BY tot DESC LIMIT 1)");  
	$consulta->execute();
	$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS);
            
    return $pedidos;
}
public static function MenosVendido()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT producto FROM pedidodetalle GROUP BY producto HAVING COUNT(*) =(SELECT COUNT( producto ) tot FROM pedidodetalle GROUP BY producto ORDER BY tot Asc LIMIT 1)");  
	$consulta->execute();
	$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS);
            
    return $pedidos;
}




}

?>