<?php

class Ecriture
{
    protected $id_auteur;
    protected $id_oeuvre;

    private $persistant;

    /**
     * @return int
     */
    public function getIdAuteur(): int
    {
        return $this->id_auteur;
    }

    /**
     * @param int $id_auteur
     * @return Ecriture
     */
    public function setIdAuteur(int $id_auteur): Ecriture
    {
        $this->id_auteur = $id_auteur;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdOeuvre(): int
    {
        return $this->id_oeuvre;
    }

    /**
     * @param int $id_oeuvre
     * @return Ecriture
     */
    public function setIdOeuvre(int $id_oeuvre): Ecriture
    {
        $this->id_oeuvre = $id_oeuvre;
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
     * @return Ecriture
     */
    public function setPersistant(bool $persistant): Auteur
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:Auteur (".$this->getIdAuteur().", ".$this->getIdOeuvre().")";
    }
}
?>
