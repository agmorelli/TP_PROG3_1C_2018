<?php


class Detalle{

public $idPedido;
public $producto;
public $tiempoPreparacion;
public $idEmpleado;
public $estado;





public function GuardarDetalle()
{
 
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidodetalle (idPedido, producto, idEmpleado, estado)values(:idPedido, :producto, :idEmpleado, :estado)");
                $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
                $consulta->bindValue(':idEmpleado', $this->idEmpleado, PDO::PARAM_INT);
                $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
                $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
                
                $consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
}





public static function TraerTodosLosPedidos() 
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedipedido-detalle ");  
			$consulta->execute();
			$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
            
            return $pedidos;
							
			
}



}

?>