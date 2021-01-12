<?php
	namespace mvc\Core;

	use mvc\Config\Database;
	use PDO;

	class ResourceModel implements ResourceModelInterface
	{
		protected $table;
		protected $id;
		protected $model;

		public function _init($table, $id, $model)
		{
			$this->table = $table;
			$this->id = $id;
			$this->model = $model;
		}

		public function save($model)
		{	
			$checkId = $model->getId();
			$properties = $model->getProperties();
			$properties['created_at'] = date('Y-m-d H:i:s');
			$properties['updated_at'] = date('Y-m-d H:i:s');

				
			if ($checkId == null) {
				unset($properties['id']);
				$keys = implode(', ', array_keys($properties));
				$value = implode(" ', ' ", array_values($properties));
				$sql = "INSERT INTO {$this->table} ($keys) VALUES ('$value')";
				$req = Database::getBdd()->prepare($sql);
				return $req->execute();
			} else {
				unset($properties['created_at']);
				$set = array();
	       		foreach ($properties as $k => $v) {	
	            	$set[] = $k ."= '".$v."'";
	        	}
	          	$set = implode(',', $set);
				$sql = "UPDATE {$this->table} SET $set WHERE id =:id";
				//print_r($sql);
				$req = Database::getBdd()->prepare($sql);
				return $req->execute(['id'=>$checkId]);
			}
			
		}

		public function all($model)
	    {
	        
	        $sql = "SELECT * FROM {$this->table}";
	        $req = Database::getBdd()->prepare($sql);
	        $req->execute();

	        return $req->fetchAll(PDO::FETCH_OBJ);
	    }

		public function delete($model)
		{
			$id = $model->getId();
			$sql = "DELETE FROM {$this->table} WHERE id = :id";		
	        $req = Database::getBdd()->prepare($sql);
	        return $req->execute(['id'=>$id]);
	     
		}

		public function find($id)
		{
			$sql = 'SELECT * FROM {$this->table} WHERE id = :id';
			$req = Database::getBdd()->prepare($sql);
			$req->execute(['id' => $id]);
			return $req->fetch();
		}

	}

?>