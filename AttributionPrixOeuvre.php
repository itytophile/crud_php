<?php

class AttributionPrixOeuvre
{
    protected $id_oeuvre;
    protected $id_prix;
    protected $annee;

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
     * @return AttributionPrixOeuvre
     */
    public function setIdOeuvre(int $id_oeuvre): AttributionPrixOeuvre
    {
        $this->id_oeuvre = $id_oeuvre;
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
     * @return AttributionPrixOeuvre
     */
    public function setIdPrix(int $id_prix): AttributionPrixOeuvre
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
     * @return AttributionPrixOeuvre
     */
    public function setAnnee(int $annee): AttributionPrixOeuvre
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
     * @return AttributionPrixOeuvre
     */
    public function setPersistant(bool $persistant): Oeuvre
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:AttributionPrixOeuvre (".$this->getIdOeuvre().", ".$this->getIdPrix().
        ", ".$this->getAnnee().")";
    }
}
?>
