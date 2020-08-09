<?php

class AttributionPrixAuteur
{
    protected $id_auteur;
    protected $id_prix;
    protected $annee;

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
     * @return AttributionPrixAuteur
     */
    public function setIdAuteur(int $id_auteur): AttributionPrixAuteur
    {
        $this->id_auteur = $id_auteur;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdPrix(): int
    {
        return $this->id_prix;
    }

    /**
     * @param int $id_prix
     * @return AttributionPrixAuteur
     */
    public function setIdPrix(int $id_prix): AttributionPrixAuteur
    {
        $this->id_prix = $id_prix;
        return $this;
    }


    /**
     * @return int
     */
    public function getAnnee(): int
    {
        return $this->annee;
    }

    /**
     * @param int $annee
     * @return AttributionPrixAuteur
     */
    public function setAnnee(int $annee): AttributionPrixAuteur
    {
        $this->annee = $annee;
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
     * @return AttributionPrixAuteur
     */
    public function setPersistant(bool $persistant): Auteur
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:AttributionPrixAuteur (".$this->getIdAuteur().", ".$this->getIdPrix().
        ", ".$this->getAnnee().")";
    }
}

?>
