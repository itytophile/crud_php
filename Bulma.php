<?php

function afficheNav($active=null) : void {
    $nav = "<div class='tabs is-toggle is-toggle-rounded is-fullwidth'><ul>";

    $nav .= "<li";
    if($active == "oeuvre") $nav .= " class='is-active'";
    $nav .="><a href='index.php?table=oeuvre'><span class='icon is-small'><i class='fas fa-book' aria-hidden='true'></i></span><span>&OElig;uvre</span></a></li>";

    $nav .= "<li";
    if($active == "auteur") $nav .= " class='is-active'";
    $nav .= "><a href='index.php?table=auteur'><span class='icon is-small'><i class='fas fa-user' aria-hidden='true'></i></span><span>Auteur</span></a></li>";

    $nav .= "<li";
    if($active == "prix_oeuvre") $nav .= " class='is-active'";
    $nav .= "><a href='index.php?table=prix_oeuvre'><span class='icon is-small'><i class='fas fa-award' aria-hidden='true'></i></span><span>Prix &OElig;uvre</span></a></li>";

    $nav .= "<li";
    if($active == "prix_auteur") $nav .= " class='is-active'";
    $nav .= "><a href='index.php?table=prix_auteur'><span class='icon is-small'><i class='fas fa-medal' aria-hidden='true'></i></span><span>Prix Auteur</span></a></li>";
    echo $nav."</ul></div>";
}

class TableauBulma {
    private $tableau;
    private $header;
    private $table;
    private $numPage;
    private $maxPage;
    private $fairePagination;
    private $contexte;
    private $id;
    private $tableAssoc;

    public const DEFAULT = 0;
    public const SELECTION = 1;
    public const CHOIX = 2;


    public function __construct($t, $numPage, $maxPage, bool $fairePagination=true, $contexte = TableauBulma::DEFAULT, $id=null, $tableAssoc=null) {
        $this->tableau = [];
        $this->table = $t;
        $this->numPage = $numPage;
        $this->maxPage = $maxPage;
        $this->fairePagination = $fairePagination;
        $this->contexte = $contexte;
        $this->id = $id;
        $this->tableAssoc = $tableAssoc;
    }

    public function addLigne(array $ligne) : void {
        $this->tableau[] = $ligne;
    }

    public function setHeader(array $ligne) : void {
        $this->header = $ligne;
    }

    private function pagination($current, $max, string $action="index.php", string $args="") : String {
        $retour = "<nav class='pagination is-centered is-rounded' role='navigation' aria-label='pagination'>";

        if($current > 1) $retour .= "<a href='$action?table=".$this->table."&page=".($current-1)."$args' class='pagination-previous'>Précédent</a>";

        if($current < $max) $retour .= "<a href='$action?table=".$this->table."&page=".($current+1)."$args' class='pagination-next'>Suivant</a>";

        $retour .= "<ul class='pagination-list'>";

        if($current > 1) $retour .= "<li><a href='$action?table=".$this->table."&page=1$args' class='pagination-link' aria-label='Goto page 1'>1</a></li>";

        if($current > 2) $retour .= "<li><span class='pagination-ellipsis'>&hellip;</span></li>";

        $retour .= "<li><a class='pagination-link is-current' aria-label='Goto page $current'>$current</a></li>";

        if($current < $max-1) $retour .= "<li><span class='pagination-ellipsis'>&hellip;</span></li>";

        if($current < $max) $retour .= "<li><a href='$action?table=".$this->table."&page=$max"."$args' class='pagination-link' aria-label='Goto page $max'>$max</a></li>";

        return $retour."</ul></nav>";
    }

    public function __toString() : string {
        if($this->fairePagination) {
            if($this->contexte == TableauBulma::CHOIX) {
                $retour = $this->pagination($this->numPage, $this->maxPage, "selection.php", "&id=".$this->id."&tableAssoc=".$this->tableAssoc);
            } else $retour = $this->pagination($this->numPage, $this->maxPage);
        }
        else $retour = "";
        $retour .= "<table class='table is-striped is-narrow is-hoverable is-fullwidth'>\n";
        
        if($this->header != null) {
            $retour .= "<thead>";
            $retour .= "\n<tr>";
            foreach($this->header as $cellule) {
                $retour .= "<th>$cellule</th>";
            }
            $retour .= "<th class='is-narrow has-text-right'>".$this->boutonCreer()."</th></tr>\n</thead>\n";
        }

        if($this->tableau != null) {
            foreach($this->tableau as $ligne) {
                $id = array_shift($ligne);
                $retour .= "<tr>";
                foreach($ligne as $cellule) {
                    $retour .= "<td><a href='detail.php?table=".$this->table."&id=$id'>$cellule</a></td>";
                }
                
                if($this->contexte == TableauBulma::CHOIX) {
                    $retour .= "<td class='is-narrow'><button name='choix' value='".$id."_".$this->id."_".$this->table."_".$this->tableAssoc."' class='button is-success'><span><i class='fas fa-check'></i></span></button></td></tr>\n";
                } else {
                    $retour .= "<td class='is-narrow'><button name='modif' value='".$id."_".$this->table."' class='button is-link is-outlined'><span><i class='fas fa-edit'></i></span></button> 
					<button name='suppr' value='".$id."_".$this->table."' class='button is-danger is-outlined'><span><i class='fas fa-trash-alt'></i></span></button></td></tr>\n";
                }
            }
        }
        $retour .= "</table>";
        if($this->fairePagination) {
            $retour .= $this->pagination($this->numPage, $this->maxPage);
        }
        return $retour;
    }

    private function boutonCreer() : string {
        switch($this->contexte) {
            case TableauBulma::SELECTION:
                $retour = "<a href='selection.php?table=".$this->table."&id=".$this->id."&tableAssoc=".$this->tableAssoc."' class='button is-link'><span><i class='fas fa-plus'></i></span></a>\n";
            break;
            case TableauBulma::CHOIX:
                $retour = "";
            break;
            default:
                $retour = "<a href='index.php?cree=".$this->table."' class='button is-success'><span><i class='fas fa-plus'></i></span></a>\n";
        }
        
        return $retour;
    }
}

function getFormulaire($assoc) : string {
    $formulaire = "<form class='section' action='index.php' method='get'>";

    foreach($assoc as $label => $attributs) {
        $formulaire .=
        "<div class='field'>".
            "<label class='label' for='$label'>$label</label>".
            "<div class='field'>".
                "<p class='control'>"."<input id='$label' class='input' type='text' ";
        foreach($attributs as $attribut => $value) {
            $formulaire .= "$attribut='$value' ";
        }
        $formulaire .= "></p></div></div>";
    }

    return $formulaire."<div class='control'><input class='button is-link is-rounded' type='submit' name='Valider' value='Sauver'/></div></form>";
}