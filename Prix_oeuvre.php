<?php

class Prix_oeuvre implements TableAffichable
{
    protected $id_prix_oeuvre;
    protected $nom_prix_oeuvre;

    private $persistant;

    /**
     * @return int
     */
    public function getIdPrixOeuvre(): int
    {
        return $this->id_prix_oeuvre;
    }

    /**
     * @param int $id_prix_oeuvre
     * @return PrixOeuvre
     */
    public function setIdPrixOeuvre(int $id_prix_oeuvre): PrixOeuvre
    {
        $this->id_prix_oeuvre = $id_prix_oeuvre;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomPrixOeuvre(): string
    {
        return $this->nom_prix_oeuvre;
    }

    /**
     * @param string $nom_prix_oeuvre
     * @return PrixOeuvre
     */
    public function setNomPrixOeuvre($nom_prix_oeuvre): PrixOeuvre
    {
        $this->nom_prix_oeuvre = $nom_prix_oeuvre;
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
     * @return PrixOeuvre
     */
    public function setPersistant(bool $persistant): PrixOeuvre
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:PrixOeuvre (".$this->getIdPrixOeuvre().", ".$this->getNomPrixOeuvre().")";
    }

    public function toArray() : array {
        return [$this->id_prix_oeuvre, $this->nom_prix_oeuvre];
    }
}
?>
