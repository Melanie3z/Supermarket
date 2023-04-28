<?php 
require_once("Libraries/Core/Mysql.php");
trait TCliente{
	private $conCl;
	private $intIdUsuario;
	private $strNombre;
	private $strApellido;
	private $intTelefono;
	private $strEmail;
	private $strPassword;
	private $strToken;
	private $intTipoId;
	private $intIdTransaccion;

	public function insertCliente(string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid){
		$this->conCl       = new Mysql();
		$this->strNombre   = $nombre;
		$this->strApellido = $apellido;
		$this->intTelefono = $telefono;
		$this->strEmail    = $email;
		$this->strPassword = $password;
		$this->intTipoId   = $tipoid;

		$return = 0;
		$sql = "SELECT * 
		          FROM PERSONA 
		         WHERE PER_EMAIL = '{$this->strEmail}' ";
		$request = $this->conCl->select_all($sql);		
		if(empty($request))
		{
			$query_insert  = "INSERT INTO PERSONA(PER_NOMBRE,
				                                  PER_APELLIDOS,
				                                  PER_TELEFONO,
				                                  PER_EMAIL,
				                                  PER_PASSWORD,
				                                  PER_ROL_ID) 
							  VALUES(?,?,?,?,?,?)";
        	$arrData = array($this->strNombre,
    						$this->strApellido,
    						$this->intTelefono,
    						$this->strEmail,
    						$this->strPassword,
    						$this->intTipoId);
        	$request_insert = $this->conCl->insert($query_insert,$arrData);
        	$return = $request_insert;
		}else{
			$return = "exist";
		}
        return $return;
	}

	public function insertDetalleTemp(array $pedido){
		$this->intIdUsuario = $pedido['idcliente'];
		$this->intIdTransaccion = $pedido['idtransaccion'];
	 	$productos = $pedido['productos'];

	 	$this->conCl = new Mysql();
	 	$sql = "SELECT * 
	 	          FROM DETALLE_TEMP
	 	         WHERE DTE_TRANSACCION_ID = '{$this->intIdTransaccion}' 
	 	           AND DTE_PER_ID = $this->intIdUsuario";
	 	$request = $this->conCl->select_all($sql);

		if(empty($request)){
			foreach ($productos as $producto) {
				$query_insert  = "INSERT INTO DETALLE_TEMP(DTE_PER_ID,
					                                       DTE_PRO_ID,
					                                       DTE_PRECIO,
					                                       DTE_CANTIDAD,
					                                       DTE_TRANSACCION_ID) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array($this->intIdUsuario,
	        					$producto['idproducto'],
	    						$producto['precio'],
	    						$producto['cantidad'],
	    						$this->intIdTransaccion
	    					);
	        	$request_insert = $this->conCl->insert($query_insert,$arrData);
			}
		}else{
			$sqlDel = "DELETE FROM DETALLE_TEMP
			                 WHERE DTE_TRANSACCION_ID = '{$this->intIdTransaccion}' 
			                   AND DTE_PER_ID = $this->intIdUsuario";
			$request = $this->conCl->delete($sqlDel);
			foreach ($productos as $producto) {
				$query_insert  = "INSERT INTO DETALLE_TEMP(DTE_PER_ID,
					                                       DTE_PRO_ID,
					                                       DTE_PRECIO,
					                                       DTE_CANTIDAD,
					                                       DTE_TRANSACCION_ID) 
								  VALUES(?,?,?,?,?)";
	        	$arrData = array($this->intIdUsuario,
	        					$producto['idproducto'],
	    						$producto['precio'],
	    						$producto['cantidad'],
	    						$this->intIdTransaccion
	    					);
	        	$request_insert = $this->conCl->insert($query_insert,$arrData);
			}
	 	}
	}
}

?>