<?php 

	class CategoriasModel extends Mysql
	{
		public $intIdcategoria;
		public $strCategoria;
		public $strDescripcion;
		public $intStatus;
		public $strPortada;

		public function __construct()
		{
			parent::__construct();
		}

		public function inserCategoria(string $nombre, string $descripcion, string $portada, int $status){
			$return               = 0;
			$this->strCategoria   = $nombre;
			$this->strDescripcion = $descripcion;
			$this->strPortada     = $portada;
			$this->intStatus      = $status;

			$sql = "SELECT * 
			          FROM CATEGORIA 
			         WHERE CAT_NOMBRE = '{$this->strCategoria}' ";
			$request = $this->select_all($sql);

			if(empty($request)){
				$query_insert  = "INSERT INTO CATEGORIA(CAT_NOMBRE,
					                                    CAT_DESCRIPCION,
					                                    CAT_PORTADA,
					                                    CAT_STATUS) 
				                                 VALUES(?,?,?,?)";
		       	$arrData = array($this->strCategoria, 
								 $this->strDescripcion, 
								 $this->strPortada, 
								 $this->intStatus);
		       	$request_insert = $this->insert($query_insert,$arrData);
		       	$return = $request_insert;
			}else{
				$return = "exist";
			}
			return $return;
		}

		public function selectCategorias()
		{
			$sql = "SELECT * 
			          FROM CATEGORIA
					 WHERE CAT_STATUS != 0 ";
			$request = $this->select_all($sql);
			return $request;
		}

		public function selectCategoria(int $idcategoria){
			$this->intIdcategoria = $idcategoria;
			$sql = "SELECT * 
			          FROM CATEGORIA
					 WHERE CAT_ID = $this->intIdcategoria";
			$request = $this->select($sql);
			return $request;
		}

		public function updateCategoria(int $idcategoria, string $categoria, string $descripcion, string $portada, int $status){
			$this->intIdcategoria = $idcategoria;
			$this->strCategoria   = $categoria;
			$this->strDescripcion = $descripcion;
			$this->strPortada     = $portada;
			$this->intStatus      = $status;

			$sql = "SELECT * 
			          FROM CATEGORIA 
			         WHERE CAT_NOMBRE  = '{$this->strCategoria}' 
			           AND CAT_ID     != $this->intIdcategoria";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE CATEGORIA 
				           SET CAT_NOMBRE      = ?, 
				               CAT_DESCRIPCION = ?, 
				               CAT_PORTADA     = ?, 
				               CAT_STATUS      = ? 
				         WHERE CAT_ID = $this->intIdcategoria ";
				$arrData = array($this->strCategoria, 
								 $this->strDescripcion, 
								 $this->strPortada, 
								 $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

		public function deleteCategoria(int $idcategoria)
		{
			$this->intIdcategoria = $idcategoria;
			$sql = "SELECT * 
			          FROM PRODUCTO
			         WHERE PRO_CAT_ID = $this->intIdcategoria";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE CATEGORIA 
				           SET CAT_STATUS = ? 
				         WHERE CAT_ID = $this->intIdcategoria ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}	
	}
 ?>