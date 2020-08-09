<?php

class Auteur 
{
    protected $id_auteur;
    protected $nom_auteur;
    protected $prenom_auteur;

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
     * @return Auteur
     */
    public function setIdAuteur(int $id_auteur): Auteur
    {
        $this->id_auteur = $id_auteur;
        return $this;
    }

    /**
     * @return string
     */
    public function getNomAuteur(): ?string
    {
        return $this->nom_auteur;
    }

    /**
     * @param string $nom_auteur
     * @return Auteur
     */
    public function setNomAuteur($nom_auteur): Auteur
    {
        $this->nom_auteur = $nom_auteur;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenomAuteur(): ?string
    {
        return $this->prenom_auteur;
    }

    /**
     * @param string $prenom_auteur
     * @return Auteur
     */
    public function setPrenomAuteur(string $prenom_auteur): Auteur
    {
        $this->prenom_auteur = $prenom_auteur;
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
     * @return Auteur
     */
    public function setPersistant(bool $persistant): Auteur
    {
        $this->persistant = $persistant;
        return $this;
    }

    public function __toString()
    {
        return "object:Auteur (".$this->id_auteur.", ".$this->nom_auteur.",
                                        ".$this->prenom_auteur.")";
    }

    public function toArray() : array {
        return [$this->id_auteur, $this->nom_auteur, $this->prenom_auteur];
    }
}
?>
