<?php 
	require_once("Libraries/Core/Mysql.php");
	trait TProducto{
		private $conP;
		private $strCategoria;
		private $intIdcategoria;
		private $intIdProducto;
		private $strProducto;
		private $cant;
		private $option;
		private $strRuta;

		public function getProductosT(){
			$this->conP = new Mysql();
			$sql = "SELECT PRO_ID,
						   PRO_CODIGO,
						   PRO_NOMBRE,
						   PRO_DESCRIPCION,
						   PRO_CAT_ID,
						   CAT_NOMBRE,						   
						   PRO_PRECIO,
						   PRO_STOCK,
						   PRO_RUTA
					  FROM PRODUCTO
					       INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
					 WHERE PRO_STATUS != 0 
					 ORDER BY PRO_ID DESC ";
					$request = $this->conP->select_all($sql);
					if(count($request) > 0){
						for ($c=0; $c < count($request) ; $c++) { 
							$intIdProducto = $request[$c]['PRO_ID'];
							$sqlImg = "SELECT IMA_IMAGEN
									     FROM IMAGEN
									    WHERE IMA_PRO_ID = $intIdProducto";
							$arrImg = $this->conP->select_all($sqlImg);
							if(count($arrImg) > 0){
								for ($i=0; $i < count($arrImg); $i++) { 
									$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['IMA_IMAGEN'];
								}
							}
							$request[$c]['images'] = $arrImg;
						}
					}
			return $request;
		}

		public function getProductosCategoriaT(int $idcategoria, string $ruta){
			$this->intIdcategoria = $idcategoria;
			$this->strRuta = $ruta;
			$this->conP = new Mysql();
			$sql_cat = "SELECT CAT_ID,
			                   CAT_NOMBRE
			              FROM CATEGORIA
			             WHERE CAT_ID = '{$this->intIdcategoria}'";
			$request = $this->conP->select($sql_cat);			

			if(!empty($request)){
				$this->strCategoria = $request['CAT_NOMBRE'];
				$sql = "SELECT PRO_ID,
				               PRO_CODIGO,
				               PRO_NOMBRE,
				               PRO_DESCRIPCION,
				               PRO_CAT_ID,
				               CAT_NOMBRE,
				               PRO_PRECIO,
				               PRO_STOCK,
				               PRO_RUTA
						  FROM PRODUCTO
						       INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
						 WHERE PRO_STATUS != 0 
						   AND PRO_CAT_ID  = $this->intIdcategoria 
						   AND CAT_RUTA    = '{$this->strRuta}'";
				$request = $this->conP->select_all($sql);
				if(count($request) > 0){
					for ($c=0; $c < count($request) ; $c++) { 
						$intIdProducto = $request[$c]['PRO_ID'];
						$sqlImg = "SELECT IMA_IMAGEN
								     FROM IMAGEN
								    WHERE IMA_PRO_ID = $intIdProducto";
						$arrImg = $this->conP->select_all($sqlImg);
						if(count($arrImg) > 0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['IMA_IMAGEN'];
							}
						}
						$request[$c]['images'] = $arrImg;
					}
				}
				$request = array('CAT_ID' => $this->intIdcategoria,
				                 'categoria' => $this->strCategoria,
                                 'productos' => $request);
			}
			return $request;
		}

		public function getProductoT(int $idproducto, string $ruta){
			$this->conP          = new Mysql();
			$this->intIdProducto = $idproducto;
			$this->strRuta       = $ruta;
			$sql = "SELECT PRO_ID,
			               PRO_CODIGO,
			               PRO_NOMBRE,
			               PRO_DESCRIPCION,
			               PRO_CAT_ID,
			               CAT_NOMBRE,
			               CAT_RUTA,
			               PRO_PRECIO,
			               PRO_STOCK,
			               PRO_RUTA
					  FROM PRODUCTO
					       INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
					 WHERE PRO_STATUS != 0 
					   AND PRO_ID   = '{$this->intIdProducto}'
					   AND PRO_RUTA = '{$this->strRuta}'";
			$request = $this->conP->select($sql);
			if(!empty($request)){
				$intIdProducto = $request['PRO_ID'];
				$sqlImg = "SELECT IMA_IMAGEN
						     FROM IMAGEN
						    WHERE IMA_PRO_ID = $intIdProducto";
				$arrImg = $this->conP->select_all($sqlImg);
				if(count($arrImg) > 0){
					for ($i=0; $i < count($arrImg); $i++) { 
						$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['IMA_IMAGEN'];
					}
				}else{
					$arrImg[0]['url_image'] = media().'/images/uploads/product.png';
				}
				$request['images'] = $arrImg;
			}
			return $request;
		}

		public function getProductosRandom(int $idcategoria, int $cant, string $option){
			$this->intIdcategoria = $idcategoria;
			$this->cant           = $cant;
			$this->option         = $option;

			if($option == "r"){
				$this->option = " RAND() ";
			}else if($option == "a"){
				$this->option = " PRO_ID ASC ";
			}else{
				$this->option = " PRO_ID DESC ";
			}

			$this->conP = new Mysql();
			$sql = "SELECT PRO_ID,
			               PRO_CODIGO,
			               PRO_NOMBRE,
			               PRO_DESCRIPCION,
			               PRO_CAT_ID,
			               CAT_NOMBRE,
			               PRO_PRECIO,
			               PRO_STOCK,
			               PRO_RUTA
					  FROM PRODUCTO
					       INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
					 WHERE PRO_STATUS != 0 
					   AND PRO_CAT_ID = $this->intIdcategoria
					 ORDER BY $this->option LIMIT  $this->cant ";
			$request = $this->conP->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) { 
					$intIdProducto = $request[$c]['PRO_ID'];
					$sqlImg = "SELECT IMA_IMAGEN
							     FROM IMAGEN
							    WHERE IMA_PRO_ID = $intIdProducto";
					$arrImg = $this->conP->select_all($sqlImg);
					if(count($arrImg) > 0){
						for ($i=0; $i < count($arrImg); $i++) { 
							$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['IMA_IMAGEN'];
						}
					}
					$request[$c]['images'] = $arrImg;
				}
			}
			return $request;

		}

		public function getProductoIDT(int $idproducto){
			$this->conP = new Mysql();
			$this->intIdProducto = $idproducto;
			$sql = "SELECT PRO_ID,
			               PRO_CODIGO,
			               PRO_NOMBRE,
			               PRO_DESCRIPCION,
			               PRO_CAT_ID,
			               CAT_NOMBRE,
			               PRO_PRECIO,
			               PRO_RUTA,
			               PRO_STOCK
					  FROM PRODUCTO
					       INNER JOIN CATEGORIA ON PRO_CAT_ID = CAT_ID
					 WHERE PRO_STATUS != 0 
					   AND PRO_ID = '{$this->intIdProducto}' ";
			$request = $this->conP->select($sql);
			if(!empty($request)){
				$intIdProducto = $request['PRO_ID'];
				$sqlImg = "SELECT IMA_IMAGEN
						     FROM IMAGEN
						    WHERE IMA_PRO_ID = $intIdProducto";
				$arrImg = $this->conP->select_all($sqlImg);
				if(count($arrImg) > 0){
					for ($i=0; $i < count($arrImg); $i++) { 
						$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['IMA_IMAGEN'];
					}
				}else{
					$arrImg[0]['url_image'] = media().'/images/uploads/product.png';
				}
				$request['images'] = $arrImg;
			}
			return $request;
		}
	}
?>