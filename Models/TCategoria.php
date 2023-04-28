<?php 
	require_once("Libraries/Core/Mysql.php");
	trait TCategoria{
		public $conC;

		public function getCategoriasT(string $categorias){
			$this->conC = new Mysql();
			$sql = "SELECT CAT_ID, 
			               CAT_NOMBRE, 
			               CAT_DESCRIPCION, 
			               CAT_PORTADA,
			               CAT_RUTA
					  FROM CATEGORIA
					 WHERE CAT_STATUS = 1
					   AND CAT_ID IN ($categorias)";
			$request = $this->conC->select_all($sql);
			if(count($request) > 0){
				for ($c=0; $c < count($request) ; $c++) { 
					$request[$c]['CAT_PORTADA'] = BASE_URL.'/Assets/images/uploads/'.$request[$c]['CAT_PORTADA'];		
				}
			}
			return $request;
		}		
	}

?>