<?php
class Factura
{
	public $numero;
	public $mesa;
    public $importe;

    public function GuardarFactura()
    {
        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
        $consulta =$objetoAccesoDato->RetornarConsulta("INSERT into facturas (mesa, importe, fecha)values(:mesa, :importe, :fecha)");
        $consulta->bindValue(':importe', $this->importe, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_INT);

        $consulta->execute();
        return $objetoAccesoDato->RetornarUltimoIdInsertado();
    }

}
?>


