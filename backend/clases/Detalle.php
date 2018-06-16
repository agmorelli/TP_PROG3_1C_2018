<?php


class Detalle{

public $idPedido;
public $producto;
public $tiempoPreparacion;
public $idEmpleado;
public $estado;
public $sector;





public function GuardarDetalle()
{
 
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into pedidodetalle (idPedido, producto, estado, sector)values(:idPedido, :producto, :estado, :sector)");
                $consulta->bindValue(':idPedido', $this->idPedido, PDO::PARAM_INT);
                $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
                $consulta->bindValue(':producto', $this->producto, PDO::PARAM_STR);
                $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
                
                $consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
}





public static function TraerTodosLosPedidos() 
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedipedidodetalle");  
			$consulta->execute();
			$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS, "Pedido");
            
            return $pedidos;
							
			
}

public static function TraerPendientes($idEmpleado)
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle as pd where pd.sector in (select e.sector from empleados as e where e.id=:id) and pd.estado=:estado");  
    $consulta->bindValue(':estado', "pendiente", PDO::PARAM_STR);
    $consulta->bindValue(':id', $idEmpleado, PDO::PARAM_INT);
    $consulta->execute();
    $pedidos= $consulta->fetchAll(PDO::FETCH_CLASS, "Detalle");
    
    return $pedidos;
}

/*
public static function PrepararPedido($idEmpleado)
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("update sesiones set horaFinal=:horaFinal WHERE id=:id");
        $consulta->bindValue(':horaFinal',$horafinal, PDO::PARAM_STR);
        $consulta->bindValue(':id',$id, PDO::PARAM_INT);

         $cantidadFilas=$consulta->execute();
         if($cantidadFilas>0)
         {
             return true;
         }
         else{
             throw new Exception("No se pudo cerrar la sesion!!!");
         }
}*/



}

?>