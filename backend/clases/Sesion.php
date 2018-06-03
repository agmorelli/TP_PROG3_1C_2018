<?php
include_once "AccesoDatos.php";
class Sesion
{
    public $id;
    public $idEmpleado;
    public $horaInicio;
    public $horafinal;

    public function IniciarSesion()
    {
        	    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into sesiones (idEmpleado,horaInicio)values(:idEmpleado,:horaInicio)");
				$consulta->bindValue(':idEmpleado',$this->idEmpleado, PDO::PARAM_INT);
				$consulta->bindValue(':horaInicio',$this->horaInicio, PDO::PARAM_STR);

				$consulta->execute();		
				return $objetoAccesoDato->RetornarUltimoIdInsertado();

    }


    public static function CerrarSesion($id,$horafinal)
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
    }


}
?>