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
			$id = $model->getId();
			$properties = $model->getProperties();
			$properties['created_at'] = date('Y-m-d H:i:s');
			$properties['updated_at'] = date('Y-m-d H:i:s');				
			if ($id == null) {
				unset($properties['id']);
				$keys = implode(', ', array_keys($properties));
				$values = implode(', :',array_keys($properties));
				$sql = "INSERT INTO {$this->table} ($keys) VALUES ( :$values)";
				$req = Database::getBdd()->prepare($sql);
				return $req->execute($properties);
			} else {
				unset($properties['created_at']);
				$set = array();
	       		foreach (array_keys($properties) as $key => $values) {
					if ($values != 'id') {
						$set[] =  $values . ' = :' . $values;
					}

				}

	          	$set = implode(',', $set);
				$sql = "UPDATE {$this->table} SET $set WHERE id = :id";
				$req = Database::getBdd()->prepare($sql);
				return $req->execute($properties);
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
			$sql = "SELECT * FROM {$this->table} WHERE id = :id";
			$req = Database::getBdd()->prepare($sql);
			$req->execute(['id' => $id]);
			return $req->fetchObject();
		}

	}

?>