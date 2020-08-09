<?php

class IterateurTable implements Iterator {
    private $myPdo;
    private $selectStatement;
    private $nomClasse;
    private $index;

    public function __construct(string $nomTable, string $nomColonneId) {
        $this->myPdo = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'], $nomTable);
        
        $query = "SELECT * FROM $nomTable ORDER BY $nomColonneId LIMIT :index, 1";
        $this->selectStatement = $this->myPdo->getPdo()->prepare($query);
        
        $this->nomClasse = ucfirst($nomTable);
        
        $this->rewind();
    }

    public function current() {
        $this->selectStatement->bindValue(":index", $this->index, PDO::PARAM_INT);
        $this->selectStatement->execute();
        return $this->selectStatement->fetchObject($this->nomClasse);
    }

    public function key() {
        return $this->index;
    }

    public function setKey(int $key) {
        $this->index = $key;
    }

    public function next() : void {
        $this->index++;
    }

    public function rewind() : void {
        $this->index = 0;
    }

    public function valid() : bool {
        return $this->index < $this->count();
    }

    public function count() : int {
        $this->myPdo->count();
        return $this->myPdo->getCountValue();
    }
}
