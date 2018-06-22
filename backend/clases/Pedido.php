<?php

include_once "Empleado.php";

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





}

?>