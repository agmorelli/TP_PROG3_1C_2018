<?php

class Mesa
{
    public $idMesa;
    public $estado;
    public $canUsos;

    public function GuardarMesa()
    {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("INSERT into mesas (idMesa, estado, canUsos)values(:idMesa, :estado, :canUsos)");
                $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
                $consulta->bindValue(':canUsos', $this->canUsos, PDO::PARAM_INT);
                $consulta->execute();
				return $objetoAccesoDato->RetornarUltimoIdInsertado();
                
    }

        public function ModificarMesa()
    {
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
				$consulta =$objetoAccesoDato->RetornarConsulta("UPDATE mesas set estado=:estado, canUsos=:canUsos where idMesa=:idMesa");
                $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
                $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
                $consulta->bindValue(':canUsos', $this->canUsos, PDO::PARAM_INT);
			    $consulta->execute();
                return $consulta->rowCount();
				
    }

            public function BorrarMesa()
    {
        
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("
				DELETE 
				from mesas 				
				WHERE idMesa=:idMesa");	
				$consulta->bindValue(':idMesa',$this->idMesa, PDO::PARAM_INT);		
				$consulta->execute();
				return $consulta->rowCount();
    }

    public static function TraerUnaMesa($idMesa)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from mesas WHERE idMesa=:idMesa");
        $consulta->bindValue(':idMesa', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        $mesa=$consulta->fetchObject("Mesa");
        
        if($mesa)
        {
            return $mesa;
        }
        else{
            throw new Exception("No se encontro la mesa.");
        }

    }

    public static function TraerTodasLasMesas()
    {
        $objetoAccesoDato = AccesoDatos::dameunObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from mesas");
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
    }

        public static function TraerMesaVacia($tipo)
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from mesas WHERE estado='vacia'");
        $consulta->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $consulta->execute();
        $mesa=$consulta->fetchObject("Mesa");
                if($mesa)
        {
            return $mesa;
        }
        else{
            throw new Exception("Todas las mesas de este tipo estan ocupadas.");
        }

    }


public static function MasUtilizada()
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM mesas WHERE canUsos=(SELECT MAX(canUsos) FROM mesas WHERE 1) ");
        
        $consulta->execute();
        $cochera=$consulta->fetchObject("Mesa");
       
        return $cochera;

    }


public static function NoSeUso()
    {

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * from mesas WHERE canUsos=0");
        $consulta->execute();
        $mesa=$consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
                if($mesa)
        {
            return $mesa;
        }
        else{
            throw new Exception("No hay mesas sin usar.");
        }

    }

    public static function MenosUtilizada()
    {
         $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM mesas WHERE canUsos=(SELECT MIN(canUsos) FROM mesas WHERE canUsos!=0) ");
        
        $consulta->execute();
        $mesa=$consulta->fetchObject("Mesa");
    
        return $mesa;
    }

    public function Facturar()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(prod.precio) as total from productos as prod where nombre in (SELECT pd.producto FROM pedidodetalle as pd WHERE pd.idPedido in (SELECT id from pedidos as p where p.idMesa=$this->idMesa) and pd.estado='listo para servir') ");
        $consulta->execute();
        return $consulta->fetch();    
    }

    public static function LaQueMasFacturo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.mesa, sum(f.importe) total FROM facturas as f GROUP by f.mesa ORDER BY total DESC LIMIT 1");
        $consulta->execute();
        $mesa=$consulta->fetchAll(PDO::FETCH_CLASS);
    
        return $mesa;
    }

    public static function LaQueMenosFacturo()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.mesa, sum(f.importe) total FROM facturas as f GROUP by f.mesa ORDER BY total ASC LIMIT 1");
        $consulta->execute();
        $mesa=$consulta->fetchAll(PDO::FETCH_CLASS);
    
        return $mesa;
    }      

    public static function LaDeMenorImporte()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.mesa, f.importe from facturas as f where f.importe = (SELECT MIN(importe) from facturas)");
        $consulta->execute();
        $mesa=$consulta->fetchAll(PDO::FETCH_CLASS);

        return $mesa;

    }

    public static function LaDeMayorImporte()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT f.mesa, f.importe from facturas as f where f.importe = (SELECT MAX(importe) from facturas)");
        $consulta->execute();
        $mesa=$consulta->fetchAll(PDO::FETCH_CLASS);

        return $mesa;

    }

    public static function FacturadoDesdeHasta($mesa, $desde, $hasta)
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT SUM(f.importe) total FROM facturas as f WHERE f.mesa= :mesa AND f.fecha <= :hasta AND f.fecha >= :desde");
        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_INT);
        $consulta->bindValue(':desde', $desde, PDO::PARAM_STR);
        $consulta->bindValue(':hasta', $hasta, PDO::PARAM_STR);
        $consulta->execute();
        $mesa=$consulta->fetchAll(PDO::FETCH_CLASS);

        return $mesa;

    }


}

?>