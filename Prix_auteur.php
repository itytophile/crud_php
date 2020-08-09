<?php

class Prix_auteur implements TableAffichable
{
    protected $id_prix_auteur;
    protected $nom_prix_auteur;

    private $persistant;

    /**
     * @return int
     */
    public function getIdPrixAuteur(): int
    {
        return $this->id_prix_auteur;
    }

    /**
     * @param int $id_PrixAuteur
     * @return PrixAuteur
     */
    public function setIdPrixAuteur(int $id_prix_auteur): PrixAuteur
    {
        $this->id_prix_auteur = $id_prix_auteur;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomPrixAuteur(): string
    {
        return $this->nom_prix_auteur;
    }

    /**
     * @param string $nom_PrixAuteur
     * @return PrixAuteur
     */
    public function setNomPrixAuteur($nom_prix_auteur): PrixAuteur
    {
        $this->nom_prix_auteur = $nom_prix_auteur;
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
     * @return PrixAuteur
     */
    public function setPersistant(bool $persistant): PrixAuteur
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:PrixAuteur (".$this->getIdPrixAuteur().", ".$this->getNomPrixAuteur().")";
    }

    public function toArray() : array {
        return [$this->id_prix_auteur, $this->nom_prix_auteur];
    }
}

?>
