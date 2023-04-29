<?php 

	class ProductosModel extends Mysql
	{
		private $intIdProducto;
		private $strNombre;
		private $strDescripcion;
		private $intCodigo;
		private $intCategoriaId;
		private $intPrecio;
		private $intStock;
		private $intPromocion;
		private $intStatus;
		private $strRuta;
		private $strImagen;

		public function __construct()
		{
			parent::__construct();
		}

		public function selectProductos(){
			$sql = "SELECT PRO_ID, PRO_CODIGO, PRO_NOMBRE, PRO_DESCRIPCION, PRO_CAT_ID, CAT_NOMBRE, PRO_PRECIO, PRO_STOCK,PRO_PROMOCION, PRO_STATUS
			FROM PRODUCTO
			INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
			WHERE PRO_STATUS != 0 ";
			$request = $this->select_all($sql);
			return $request;
		}	

		public function insertProducto(string $nombre, string $descripcion, int $codigo, int $categoriaid, string $precio, int $stock, int $promocion, string $ruta, int $status){
			$this->strNombre      = $nombre;
			$this->strDescripcion = $descripcion;
			$this->intCodigo      = $codigo;
			$this->intCategoriaId = $categoriaid;
			$this->strPrecio      = $precio;
			$this->intStock       = $stock;
			$this->intPromocion   = $promocion;
			$this->strRuta        = $ruta;
			$this->intStatus      = $status;
			$return = 0;
			$sql = "SELECT * 
			FROM PRODUCTO 
			WHERE PRO_CODIGO = '{$this->intCodigo}'";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$query_insert  = "INSERT INTO PRODUCTO (PRO_CAT_ID, PRO_CODIGO, PRO_NOMBRE, PRO_DESCRIPCION, PRO_PRECIO, PRO_STOCK, PRO_PROMOCION, PRO_RUTA, PRO_STATUS) 
				VALUES(?,?,?,?,?,?,?,?,?)";
	        	$arrData = array($this->intCategoriaId,
        			$this->intCodigo,
        			$this->strNombre,
        			$this->strDescripcion,
        			$this->strPrecio,
        			$this->intStock,
					$this->intPromocion,
        			$this->strRuta,
        			$this->intStatus);
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function updateProducto(int $idproducto, string $nombre, string $descripcion, int $codigo, int $categoriaid, string $precio, int $stock, int $promocion, string $ruta, int $status){
			$this->intIdProducto  = $idproducto;
			$this->strNombre      = $nombre;
			$this->strDescripcion = $descripcion;
			$this->intCodigo      = $codigo;
			$this->intCategoriaId = $categoriaid;
			$this->strPrecio      = $precio;
			$this->intStock       = $stock;
			$this->intPromocion   = $promocion;
			$this->strRuta        = $ruta;
			$this->intStatus      = $status;
			$return = 0;
			$sql = "SELECT * 
			FROM PRODUCTO 
			WHERE PRO_CODIGO = '{$this->intCodigo}' AND PRO_ID != $this->intIdProducto ";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE PRODUCTO
				SET PRO_CAT_ID=?, PRO_CODIGO=?, PRO_NOMBRE=?, PRO_DESCRIPCION=?, PRO_PRECIO=?, PRO_STOCK=?, PRO_PROMOCION=?, PRO_RUTA=?, PRO_STATUS=? 
				WHERE PRO_ID = $this->intIdProducto ";
				$arrData = array($this->intCategoriaId,
        		$this->intCodigo,
        		$this->strNombre,
        		$this->strDescripcion,
        		$this->strPrecio,
        		$this->intStock,
        		$this->intPromocion,
        		$this->strRuta,
        		$this->intStatus);

	        	$request = $this->update($sql,$arrData);
	        	$return = $request;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectProducto(int $idproducto){
			$this->intIdProducto = $idproducto;
			$sql = "SELECT PRO_ID, PRO_CODIGO, PRO_NOMBRE, PRO_DESCRIPCION, PRO_PRECIO, PRO_STOCK, PRO_PROMOCION, PRO_CAT_ID, CAT_NOMBRE, PRO_STATUS
			FROM PRODUCTO
			INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
			WHERE PRO_ID = $this->intIdProducto";
			$request = $this->select($sql);
			return $request;
		}

		public function insertImage(int $idproducto, string $imagen){
			$this->intIdProducto = $idproducto;
			$this->strImagen = $imagen;
			$query_insert  = "INSERT INTO IMAGEN(IMA_PRO_ID, IMA_IMAGEN) 
			VALUES(?,?)";
	        $arrData = array($this->intIdProducto, $this->strImagen);
	        $request_insert = $this->insert($query_insert,$arrData);
	        return $request_insert;
		}

		public function selectImages(int $idproducto){
			$this->intIdProducto = $idproducto;
			$sql = "SELECT IMA_PRO_ID,
			               IMA_IMAGEN
					  FROM IMAGEN
					 WHERE IMA_PRO_ID = $this->intIdProducto";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deleteImage(int $idproducto, string $imagen){
			$this->intIdProducto = $idproducto;
			$this->strImagen = $imagen;
			$query  = "DELETE 
			             FROM IMAGEN 
						WHERE IMA_PRO_ID = $this->intIdProducto 
						  AND IMA_IMAGEN = '{$this->strImagen}'";
	        $request_delete = $this->delete($query);
	        return $request_delete;
		}

		public function deleteProducto(int $idproducto){
			$this->intIdProducto = $idproducto;
			$sql = "UPDATE PRODUCTO
			           SET PRO_STATUS = ? 
			         WHERE PRO_ID = $this->intIdProducto ";
			$arrData = array(0);
			$request = $this->update($sql,$arrData);
			return $request;
		}
	}
 ?>