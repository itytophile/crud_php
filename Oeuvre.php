<?php

class Oeuvre 
{
    protected $id_oeuvre;
    protected $nom_oeuvre;

    private $persistant;

    /**
     * @return int
     */
    public function getIdOeuvre(): int
    {
        return $this->id_oeuvre;
    }

    /**
     * @param int $id_oeuvre
     * @return Oeuvre
     */
    public function setIdOeuvre(int $id_oeuvre): Oeuvre
    {
        $this->id_oeuvre = $id_oeuvre;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomOeuvre(): string
    {
        return $this->nom_oeuvre;
    }

    /**
     * @param string $nom_oeuvre
     * @return Oeuvre
     */
    public function setNomOeuvre($nom_oeuvre): Oeuvre
    {
        $this->nom_oeuvre = $nom_oeuvre;
        return $this;
    }

    /**
     * @return bool
     */
    public function getPersistant(): bool
    {
        return $this->persistant;
    }

    /**
     * @param bool $persistant
     * @return Oeuvre
     */
    public function setPersistant(bool $persistant): Oeuvre
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:Oeuvre (".$this->id_oeuvre.", ".$this->nom_oeuvre.")";
    }

    public function toArray() : array {
        return [$this->id_oeuvre, $this->nom_oeuvre];
    }
}
?>
