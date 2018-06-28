<?php

class Producto
{
    public $nombre;
    public $precio;

    
public function GuardarProducto()
{
 
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into productos (nombre, precio)values(:nombre, :precio)");
    $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
    $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
    $consulta->execute();
	return $objetoAccesoDato->RetornarUltimoIdInsertado();
}


public static function TraerTodosLosProductos() 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
	$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from productos ");  
	$consulta->execute();
	$producto= $consulta->fetchAll(PDO::FETCH_CLASS, "Productos");
            
    return $producto;
									
}

public static function TraerProducto($nombre) 
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("SELECT * from productos WHERE nombre= :nombre ");  
    $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
	$consulta->execute();
	$producto= $consulta->fetchObject('Producto');
            
    return $producto;
									
}

public function BorrarProducto()
{
    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("
        delete 
        from productos 				
        WHERE nombre=:nombre");	
    $consulta->bindValue(':nombre',$this->nombre, PDO::PARAM_STR);		
    $consulta->execute();
    return $consulta->rowCount();
}


public function ModificarProducto()
{

    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
    $consulta =$objetoAccesoDato->RetornarConsulta("
           update productos 
           set precio='$this->precio'
           WHERE nombre='$this->nombre'");
           
    return $consulta->execute();

}


}

?>