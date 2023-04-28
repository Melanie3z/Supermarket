<?php 
require_once("Libraries/Core/Mysql.php");
trait TTipoPago{
	private $con;

	public function getTiposPagoT(){
		$this->con = new Mysql();
		$sql = "SELECT * 
		          FROM TIPO_PAGO
		         WHERE TPA_STATUS != 0";
		$request = $this->con->select_all($sql);
		return $request;
	}
}
?>