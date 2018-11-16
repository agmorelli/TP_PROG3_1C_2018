<?php

class Auto
{
    public $patente;
    public $color;
    public $marca;

    public function GuardarAuto()
    {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into autos (patente, color, marca)values('$this->patente','$this->color','$this->marca')");
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

        public function ModificarAuto()
    {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE autos set color=:color, marca=:marca where patente=:patente");
                $consulta->bindValue(':color', $this->color, PDO::PARAM_STR);
                $consulta->bindValue(':patente', $this->patente, PDO::PARAM_STR);
                $consulta->bindValue(':marca', $this->marca, PDO::PARAM_STR);
				$consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

            public function BorrarAuto()
    {
        
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				DELETE 
				from AUTOS 				
				WHERE patente=:patente");	
				$consulta->bindValue(':patente',$this->patente, PDO::PARAM_STR);		
				$consulta->execute();
				return $consulta->rowCount();
    }

    public static function TraerUnAuto($patente)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from autos WHERE patente=:patente");
        $consulta->bindValue(':patente', $patente, PDO::PARAM_STR);
        $consulta->execute();
        $auto=$consulta->fetchObject("Auto");
        return $auto;

    }

    public static function TraerTodosLosAutos()
    {
        $objetoAccesoDato = AccesoDatos::dameunObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from autos");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Auto");
    }

    public function AgregarAutoBD()
    {
        if(!Self::TraerUnAuto($this->patente))
        {

           return $this->GuardarAuto();

            
        }
        else
        {
            return -1;

        } 
    }


    public static function AutosEstacionadosBD()
    {
        $objetoAccesoDato = AccesoDatos::dameunObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT a.marca, a.color, a.patente 
                                                            from autos as a, operaciones as o, cocheras as c 
                                                            where a.patente=o.patente and o.idCochera= c.idCochera and c.estado='Ocupada' and o.fechaSalida=''");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Auto");
    
    }
}

?>