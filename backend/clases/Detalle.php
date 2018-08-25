<?php


class Detalle{

public $idDetalle;
public $idPedido;
public $producto;
public $tiempoPreparacion;
public $tiempoServido;
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


public static function TraerTodosLosDetalles() 
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle");  
			$consulta->execute();
			$pedidos= $consulta->fetchAll(PDO::FETCH_CLASS, "Detalle");
            
            return $pedidos;
							
			
}

public static function TraerUnDetalle($idDetalle) 
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle WHERE idDetalle=:idDetalle");  
            $consulta->bindValue(':idDetalle', $idDetalle, PDO::PARAM_INT);
			$consulta->execute();
			$detalle= $consulta->fetchAll(PDO::FETCH_CLASS, "Detalle");
            
            return $detalle;
							
			
}

public static function TraerPendientes($idEmpleado)
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle as pd, empleados as e where pd.sector in(SELECT e.sector from empleados where e.id=:id) and (pd.estado='pendiente' or pd.estado='en preparacion') and (pd.idEmpleado=:id or pd.idEmpleado=0)");  
    $consulta->bindValue(':estado', "pendiente", PDO::PARAM_STR);
    $consulta->bindValue(':id', $idEmpleado, PDO::PARAM_INT);
    $consulta->execute();
    $pedidos= $consulta->fetchAll(PDO::FETCH_CLASS, "Detalle");
   // $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle as pd where pd.sector in (select e.sector from empleados as e where e.id=$idEmpleado) and pd.estado=:estado or pd.estado= $idEmpleado");
    return $pedidos;
}

public function ModificarDetalle()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("update pedidodetalle set idPedido=:idPedido, producto=:producto, tiempoPreparacion=:tiempoPreparacion, idEmpleado=:idEmpleado, estado=:estado, sector=:sector, tiempoEntrega= :tiempoEntrega WHERE idDetalle=:id");
        $consulta->bindValue(':idPedido',$this->idPedido, PDO::PARAM_INT);
        $consulta->bindValue(':producto',$this->producto, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoPreparacion',$this->tiempoPreparacion, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado',$this->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':sector',$this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEntrega',$this->tiempoEntrega, PDO::PARAM_STR);
        $consulta->bindValue(':id',$this->idDetalle, PDO::PARAM_INT);
       return $consulta->execute();

}

public function PrepararDetalle()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("update pedidodetalle set tiempoPreparacion=:tiempoPreparacion, idEmpleado=:idEmpleado, estado=:estado WHERE idDetalle=:id");
        $consulta->bindValue(':tiempoPreparacion',$this->tiempoPreparacion, PDO::PARAM_STR);
        $consulta->bindValue(':idEmpleado',$this->idEmpleado, PDO::PARAM_INT);
        $consulta->bindValue(':estado',$this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':id',$this->idDetalle, PDO::PARAM_INT);
       return $consulta->execute();

}

public function ServirDetalle()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("update pedidodetalle set tiempoServido=:tiempoServido, estado=:estado WHERE idDetalle=:id");
        $consulta->bindValue(':tiempoServido',$this->tiempoServido, PDO::PARAM_STR);
        $consulta->bindValue(':estado',"listo para servir", PDO::PARAM_STR);
        $consulta->bindValue(':id',$this->idDetalle, PDO::PARAM_INT);
       return $consulta->execute();

}

public static function TiempoRestante($idMesa, $idPedido)
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT tiempoPreparacion from pedidodetalle WHERE idpedido=$idPedido");  
    $consulta->execute();

    return $consulta->fetch();
   
}


public static function TraerDetalleDelPedido($idPedido) 
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from pedidodetalle where idPedido=$idPedido");  
			$consulta->execute();
			$detalles= $consulta->fetchAll(PDO::FETCH_CLASS, "Detalle");
            
            return $detalles;
							
			
}

public static function Cerrar($idMesa){

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `pedidodetalle` as pd SET pd.estado ='facturado' WHERE pd.idPedido IN (SELECT p.id from pedidos as p where p.idMesa=$idMesa)");  
            $consulta->execute();
           return $consulta->fetch();
}

public static function CancelarDetalles($idPedido)
{
    
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE `pedidodetalle` as pd SET `estado`='cancelado' WHERE pd.idPedido = $idPedido");  
            $consulta->execute();
           return $consulta->fetch();
}








}

?>