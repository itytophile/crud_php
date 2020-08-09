<?php


class VueAuteur
{

    /**
     * production d'une string contenant un tableau HTML représentant un auteur
     * @param Auteur $auteur
     * @return string
        */
    public static function getHTML4Auteur(Auteur $auteur): string
    {
        $ch = "<table border='1'>
        <tr><th>id_auteur</th><th>nom_auteur</th><th>prenom_auteur</th></tr><tr>\n";
        $ch .= "<tr><td>" . $auteur->getIdAuteur() . "</td>\n";
        $ch .= "<td>" . $auteur->getNomAuteur() . "</td>\n";
        $ch .= "<td>" . $auteur->getPrenomAuteur() . "</td>\n";
        $ch.= "</tr></table>\n";
        return $ch;
    }

    /**
     * production d'une string contenant un formulaire HTML
     * destiné à saisir une nouveau auteur ou à modifier un auteur existant
     * @param array $assoc
     * @return string
        */
    public static function getFormulaire4Auteur(array $assoc): string
    {
        $ch = "<form action='index.php' method='GET'>\n";
        foreach ($assoc as $col => $val) {
            if (is_array($val)) {

                $ch .= "$col : <input name='$col' type='".$val['type']
                                  ."' value='".$val['default']."' required/>\n";
            }
            else{
                $ch .= "$col : <input type='$val' name='$col' required/>\n";
            }

        }
        $ch .= "<input type='submit' name='Valider' value='Sauver'/>\n";
        return $ch."</form>\n";
    }

    /**
     * production d'une string contenant une liste HTML représentant un ensemble de auteurs
     * et permettant de les modifier ou de les supprimer grace à un lien hypertexte
     * @param Auteur $tabAuteur un tableau d'instances d'Auteur
     * @return string
        */
    public static function getAllAuteur(array $tabAuteur): string
    {
        $ch = "<ul>\n";
        foreach ($tabAuteur as $auteur) {
            if ($auteur instanceof Auteur) {
                $ch .= "<li>".$auteur->getIdAuteur()." ";
                $ch .= $auteur->getNomAuteur()." ";
                $ch .= $auteur->getPrenomAuteur()." ";
                $ch .= "<a href='?action=update&id_auteur=".$auteur->getIdAuteur()."'>Modifier</a> ";
                $ch .= "<a href='?action=delete&id_auteur=".$auteur->getIdAuteur()."'>Supprimer</a> ";
                $ch .= "</li>\n";
            }
        }
        return $ch."</ul>\n";
    }

}

?>
