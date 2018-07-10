<?php
class Empleado
{
	public $id;
	public $usuario;
  	public $clave;
	  public $sector;
	  public $perfil;
	  public $estado;


  	public function BorrarEmpleado()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from empleados 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }


	public function ModificarEmpleado()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update empleados 
				set usuario='$this->usuario',
				clave='$this->clave',
				perfil='$this->perfil',
				sector='$this->sector'
				WHERE id='$this->id'");
				
			return $consulta->execute();

	 }
	
  
	 public function InsertarEmpleado()
	 {
		 
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into empleados (usuario, clave, sector, perfil, estado) VALUES(:usuario, :clave, :sector, :perfil, :estado)");

		$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
		
		 $consulta->execute();

		 return $objetoAccesoDato->RetornarUltimoIdInsertado();			

	 }

	  public function ModificarEmpleadoParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update empleados 
				set usuario=:usuario,
				clave=:clave,
				perfil=:perfil,
				sector=:sector
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
			$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
			$consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
			$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
			

			return $consulta->execute();
	 }


	 public function GuardarEmpleado()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarEmpleado();
	 		}else {
	 			$this->InsertarEmpleado();
	 		}

	 }


  	public static function TraerTodoLosEmpleados()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM empleados");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Empleado");		
	}


	public static function TraerUnEmpleado($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from empleados where id = $id");
			$consulta->execute();
			$empleadoBuscado= $consulta->fetchObject('Empleado');
			return $empleadoBuscado;				

			
	}

	public static function FechasDeLogueo()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, s.horaInicio from empleados as e, sesiones as s where s.idEmpleado=e.id ORDER by e.usuario");
		$consulta->execute();
		$fechas= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $fechas;
	}

	public static function OperacionesTodosLosEmpleados()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario as empleado, COUNT(*) as operaciones FROM empleados as e, pedidodetalle as pd WHERE pd.idEmpleado= e.id GROUP by e.usuario");
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}

	public static function CantidadOperacionesTodosSectores()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT sector as sector, COUNT(*) as operaciones from pedidodetalle GROUP by sector");
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}

	public static function CantidadOperacionesEmpleadoSeparado($idEmpleado)
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, COUNT(*) as operaciones from empleados as e, pedidodetalle as pd where pd.idEmpleado in (SELECT e.id from empleados WHERE e.id= :idEmpleado)");
		$consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
		
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}


	public static function CantidadOperacionesEmpleadoPorSector($sector)
	{
		var_dump($sector);
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, COUNT(*) as operaciones FROM empleados as e, pedidodetalle as pd WHERE pd.idEmpleado= e.id and pd.sector=:sector GROUP by e.usuario");
		$consulta->bindValue(':sector', $sector, PDO::PARAM_STR);
		
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}






	public static function ValidarEmpleado($usuario, $clave) 
	{
		
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT  * from empleados WHERE usuario=:usuario and clave=:clave");
			$consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
			$consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
			$consulta->execute();
			$empleadobuscado= $consulta->fetchObject('Empleado');			

				return $empleadobuscado;
			  
	}


		  public static function SuspenderEmpleado($id, $estado)
	 {
		 
		 if($estado=="activo")
		 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update empleados set estado='suspendido' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "Suspendido";

		 }
		 else
		 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update empleados set estado='activo' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "activado";
		 }

	 }

public static function CantidadDeOperacionesEmp($id)
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM operaciones WHERE idEmpleado=:id");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			 $consulta->execute();
			 return $consulta->rowCount();

      			
}



}