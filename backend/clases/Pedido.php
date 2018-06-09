<?php

include_once "Empleado.php";

class Pedido{

public $id;
public $idMesa;
public $idEmpleado;
public $pedido;
public $tiempoInicio;
public $tiempoPreparacion;
public $estado;
public $fotoMesa;




public function GuardarPedido()
{
 
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidos (idMesa, pedido, tiempoInicio, estado, fotoMesa)values(:idMesa, :pedido, :tiempoInicio, :estado, :fotoMesa)");
                $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
                $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_STR);
                $consulta->bindValue(':tiempoInicio', $this->tiempoInicio, PDO::PARAM_STR);
                $consulta->bindValue(':estado', "Pendiente", PDO::PARAM_STR);
                $consulta->bindValue(':fotoMesa', $this->fotoMesa, PDO::PARAM_STR);

                $consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
}

public function FinalizarPedido()
{
    
         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
         $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set estado=:estado where id=:id ");
         $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
         $consulta->bindValue(':estado', "Listo Para Servir", PDO::PARAM_STR);
          $consulta->execute();

           return $consulta->execute();
    
}

public function PrepararPedido() 
{
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedidos set idEmpleado=:idEmpleado, tiempoPreparacion=:tiempoPreparacion,  estado=:estado where id=:id ");
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_STR);
        $consulta->bindValue(':estado', "En preparacion", PDO::PARAM_STR);
         $consulta->execute();

          return $consulta->execute();			

			
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