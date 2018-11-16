<?php
class Usuario
{
	public $id;
	public $usuario;
  	public $clave;
	public $perfil;
	public $sexo;
	public $estado;


  	public function BorrarUsuario()
	 {
	 		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				delete 
				from usuarios 				
				WHERE id=:id");	
				$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
	 }


	public function ModificarUsuario()
	 {

			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set usuario='$this->usuario',
				clave='$this->clave',
				perfil='$this->perfil',
				sexo='$this->sexo',
				estado='$this->estado'
				WHERE id='$this->id'");
				
			return $consulta->execute();

	 }
	
  
	 public function InsertarUsuario()
	 {
		 
		$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
		$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into usuarios (usuario, clave, perfil, sexo, estado) VALUES(:usuario, :clave, :perfil, :sexo, :estado)");

		$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
		$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
		$consulta->bindValue(':sexo', $this->sexo, PDO::PARAM_STR);
		$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
		$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
		
		 $consulta->execute();

		 return $objetoAccesoDato->RetornarUltimoIdInsertado();			

	 }

	  public function ModificarUsuarioParametros()
	 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				update usuarios 
				set usuario=:usuario,
				clave=:clave,
				perfil=:perfil,
				sexo=:sexo,
				estado=:estado
				WHERE id=:id");
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);
			$consulta->bindValue(':usuario',$this->usuario, PDO::PARAM_STR);
			$consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
			$consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
			$consulta->bindValue(':perfil', $this->perfil, PDO::PARAM_STR);
			$consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
			

			return $consulta->execute();
	 }


	 public function GuardarUsuario()
	 {

	 	if($this->id>0)
	 		{
	 			$this->ModificarUsuario();
	 		}else {
	 			$this->InsertarUsuario();
	 		}

	 }


  	public static function TraerTodoLosUsuarios()
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM usuarios");
			$consulta->execute();			
			return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");		
	}


	public static function TraerUnUsuario($id) 
	{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("select * from usuarios where id = $id");
			$consulta->execute();
			$usuario= $consulta->fetchObject('Usuario');
			return $usuario;				

			
	}

	public static function FechasDeLogueo()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT u.usuario, s.horaInicio from usuarios as u, sesiones as s where s.idEmpleado=u.id ORDER by u.usuario");
		$consulta->execute();
		$fechas= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $fechas;
	}

	public static function OperacionesTodosLosUsuarios()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT u.usuario as usuario, COUNT(*) as operaciones FROM usuarios as u, pedidodetalle as pd WHERE pd.idEmpleado= u.id GROUP by u.usuario");
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}

	public static function CantidadOperacionesTodosSectores()
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT perfil as perfil, COUNT(*) as operaciones from pedidodetalle GROUP by perfil");
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}

	public static function CantidadOperacionesUsuariosSeparado($idUsuario)
	{
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, COUNT(*) as operaciones from usuarios as e, pedidodetalle as pd where pd.idUsuario in (SELECT e.id from usuarios WHERE e.id= :idUsuario)");
		$consulta->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
		
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}


	public static function CantidadOperacionesEmpleadoPorSector($perfil)
	{
		
		$objetoAccesoDato= AccesoDatos::DameUnObjetoAcceso();
		$consulta=$objetoAccesoDato->RetornarConsulta("SELECT e.usuario, COUNT(*) as operaciones FROM usuarios as e, pedidodetalle as pd WHERE pd.idUsuario= e.id and pd.perfil=:perfil GROUP by e.usuario");
		$consulta->bindValue(':perfil', $perfil, PDO::PARAM_STR);
		
		$consulta->execute();
		$operaciones= $consulta->fetchAll(PDO::FETCH_CLASS);
		return $operaciones;
		
	}






    public static function ValidarUsuario($usuario, $clave) 
	{
		
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT  * from usuarios WHERE usuario=:usuario and clave=:clave");
			$consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
			$consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
			$consulta->execute();
			$usuarioBuscado= $consulta->fetchObject('Usuario');			

				return $usuarioBuscado;
			  
	}


		  public static function SuspenderUsuario($id, $estado)
	 {
		 
		 if($estado=="activo")
		 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update usuarios set estado='suspendido' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "Suspendido";

		 }
		 else
		 {
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("update usuarios set estado='activo' WHERE id=:id");
			$consulta->bindValue(':id',$id, PDO::PARAM_INT);

			 $consulta->execute();
			 return "activado";
		 }

	 }

public static function CantidadDeOperacionesUsuario($id)
{
			$objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("SELECT * FROM operaciones WHERE idUsuario=:id");
			$consulta->bindValue(':id', $id, PDO::PARAM_INT);
			 $consulta->execute();
			 return $consulta->rowCount();

      			
}



}