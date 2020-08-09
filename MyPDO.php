 <?php

class MyPDO
{
    private static $pdo;

    private $pdos_selectAll;
    private $pdos_select;
    private $pdos_update;
    private $pdos_insert;
    private $pdos_delete;
    private $pdos_delete_ecriture;
    private $pdos_count;
    private $pdos_count_attribut;
    private $nomTable;


    /**
     * MyPDO constructor.
     * @param $sgbd
     * @param $host
     * @param $db
     * @param $user
     * @param $password
     * @param $nomTable
        */
    public function __construct($host, $db, $user, $password, $nomTable)
    {

        if(!isset(MyPDO::$pdo)) {
		    MyPDO::$pdo = new PDO("mysql:host=".$host.";dbname=".$db, $user, $password);
            // pour récupérer aussi les exceptions provenant de PDOStatement
            MyPDO::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        $this->nomTable = $nomTable;
    }

    /**
     * préparation de la requête SELECT * FROM $nomTable
     * instantiation de $this->pdos_selectAll
        */
    public function initPDOS_selectAll() {
        $this->pdos_selectAll = MyPDO::$pdo->prepare('SELECT * FROM '.$this->nomTable);
    }

    /**
     * Suppose une convention de nommage de la classe entité et de son namespace !!
     * @return array
        */
    public function getAll(): array {
        if (!isset($this->pdos_selectAll))
            $this->initPDOS_selectAll();
        $this->getPdosSelectAll()->execute();
        return $this->getPdosSelectAll()->fetchAll(PDO::FETCH_CLASS,ucfirst($this->getNomTable()));
    }

    /**
     * préparation de la requête SELECT * FROM $this->nomTable WHERE $nomColId = :id
     * instantiation de $this->pdos_select
     * @param string $nomColID
        */
    public function initPDOS_select(string $nomColID = "id"): void
    {
        $requete = "SELECT * FROM ".$this->nomTable ." WHERE $nomColID = :$nomColID";
        $this->pdos_select = MyPDO::$pdo->prepare($requete);
    }

    /**
     * Suppose une convention de nommage de la classe entité et de son namespace !!
     * @param $key le nom de la colonne associée à la clé primaire
     * @param $val
     * @return mixed
        */
    public function get($key, $val) {
        if (!isset($this->pdos_select))
            $this->initPDOS_select($key);
        $this->getPdosSelect()->bindValue(":".$key,$val);
        $this->getPdosSelect()->execute();
        return $this->getPdosSelect()
                      ->fetchObject(ucfirst($this->getNomTable()));
    }

    /**
     * @param string $nomColId
     * @param array $colNames
        */
    public function initPDOS_update(string $nomColId, array $colNames): void {
        $query = "UPDATE ".$this->nomTable." SET ";
        foreach ($colNames as $colName) {
            $query .= $colName."=:".$colName.", ";
        }
        $query = substr($query,0, strlen($query)-2);
        $query .= " WHERE ".$nomColId."=:".$nomColId;
        $this->pdos_update =  MyPDO::$pdo->prepare($query);
    }

    /**
     * @param string $id
     * @param array $assoc
        */
    public function update(string $id, array $assoc): void {
        if (! isset($this->pdos_update))
            $this->initPDOS_update($id, array_keys($assoc));
        foreach ($assoc as $key => $value) {
            $this->getPdosUpdate()->bindValue(":".$key, $value);
        }
        $this->getPdosUpdate()->execute();
    }

    /**
     * @param array
        */
    public function initPDOS_insert(array $colNames): void {
        $query ="";
        $attribut = "(";
        foreach ($colNames as $colName) {
            $query .= ":".$colName.", ";
            $attribut .= $colName.", ";
        }
        $query = substr($query,0, strlen($query)-2);
        $query .= ')';

        $attribut = substr($attribut,0, strlen($attribut)-2);
        $attribut .= ')';

        $query = "INSERT INTO ".$this->nomTable." ".$attribut ." VALUES(".$query;
        $this->pdos_insert = MyPDO::$pdo->prepare($query);
    }

    /**getPrenomAuteur
     * @param array $assoc
        */
    public function insert(array $assoc): void {
        if (! isset($this->pdos_insert))
            $this->initPDOS_insert(array_keys($assoc));
        foreach ($assoc as $key => $value) {
            $this->pdos_insert->bindValue(":".$key, $value);
        }
        try {
            $this->pdos_insert->execute();
        } catch(PDOException $e) {

        }
    }

    /**
     * @param string
        */
    public function initPDOS_delete(string $nomColId = "id"): void {
        if($this->nomTable == 'auteur' || $this->nomTable == 'oeuvre'){
          $this->pdos_delete_ecriture = MyPDO::$pdo->prepare("DELETE FROM ecriture
                                                       WHERE $nomColId=:".$nomColId.";");
        }
        $this->pdos_delete = MyPDO::$pdo->prepare("DELETE FROM ". $this->nomTable
                                                      ." WHERE $nomColId=:".$nomColId.";");

	}
    public function initPDOS_delete_attribution(string $nomColId1 ,string $nomColId2): void {
        $this->pdos_delete = MyPDO::$pdo->prepare("DELETE FROM ". $this->nomTable
                                                      ." WHERE ($nomColId1,$nomColId2)= (:".$nomColId1 .", :".$nomColId2 .");");
	}
	
	
    /**
     * @param array $assoc
        */
    public function delete(array $assoc) {
        if (! isset($this->pdos_delete)){
			if($this->nomTable== "attribution_prix_auteur" || $this->nomTable== "attribution_prix_oeuvre" || $this->nomTable==  "ecriture"){
				
				$this->initPDOS_delete_attribution(array_keys($assoc)[0],array_keys($assoc)[1]);
			}else{
				$this->initPDOS_delete(array_keys($assoc)[0]);
			}
		}
            
        foreach ($assoc as $key => $value) {
            $this->getPdosDelete()->bindValue(":".$key, $value);
        }
        if($this->nomTable == 'auteur' || $this->nomTable == 'oeuvre'){
          $this->pdos_delete_ecriture->bindValue(array_keys($assoc)[0],array_values($assoc)[0]);
          $this->pdos_delete_ecriture->execute();
        }

        $this->pdos_delete->execute();
    }

    /**
     * préparation de la requête SELECT COUNT(*) FROM livre
     * instantiation de self::$_pdos_count
        */
    public function initPDOS_count() {
        $this->pdos_count = MyPDO::$pdo->prepare('SELECT COUNT(*) FROM '.$this->nomTable);
    }

    /**
     * execute de la requête SELECT COUNT(*) FROM livre
     * instantiation de self::$_pdos_count
        */
    public function count() {
      if (!isset($this->pdos_count)) {
          $this->initPDOS_count();
      }
      return $this->pdos_count->execute();
    }

    public function getCountValue() : int {
        $this->count();
        return $this->pdos_count->fetch(PDO::FETCH_NUM)[0];
    }

    public function initPDOS_count_attribut(string $att) {
      if($this->nomTable == 'auteur' || $this->nomTable == 'oeuvre'){
        $query = 'SELECT COUNT(*) FROM attribution_prix_'.$this->nomTable .' where '.$att .' = :'.$att;
      }else{
        if($this->nomTable == 'prix_auteur' || $this->nomTable == 'prix_oeuvre'){
          $val = explode('_',$this->nomTable);
          $query = 'SELECT COUNT(*) FROM attribution_prix_'.$val[1].' where '.$att .' = :'.$att;
        }
      }
        $this->pdos_count_attribut = MyPDO::$pdo->prepare($query);
    }

    public function count_attribut(string $att, int $id) {
      if (!isset($this->pdos_count_attribut)) {
          $this->initPDOS_count_attribut( $att);
      }
      $this->pdos_count_attribut->bindValue(":".$att,$id);
      return $this->pdos_count_attribut->execute();
    }

    public function getCountValueAttribut(string $att, int $id) : int {
        $this->count_attribut( $att,  $id);
        return $this->pdos_count_attribut->fetch(PDO::FETCH_NUM)[0];
    }


    /**
     * @return PDO
        */
    public function getPdo(): PDO
    {
        return MyPDO::$pdo;
    }

    /**
     * @return PDOStatement
        */
    public function getPdosSelect() : PDOStatement
    {
        return $this->pdos_select;
    }


    /**
     * @return PDOStatement
        */
    public function getPdosSelectAll(): PDOStatement
    {
        return $this->pdos_selectAll;
    }

    /**
     * @return PDOStatement
        */
    public function getPdosUpdate(): PDOStatement
    {
        return $this->pdos_update;
    }

    /**
     * @return PDOStatement
        */
    public function getPdosInsert(): PDOStatement
    {
        return $this->pdos_insert;
    }

    /**
     * @return PDOStatement
        */
    public function getPdosDelete(): PDOStatement
    {
        return $this->pdos_delete;
    }

    /**
     * @return PDOStatement
        */
    public function getPdosCount(): PDOStatement
    {
        return $this->pdos_count;
    }

    /**
     * @return string
        */
    public function getNomTable(): string
    {
        return $this->nomTable;
    }
}

?>
