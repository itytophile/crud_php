<?php
    include "MyPDO.php";
    include "VueAuteur.php";
    include "Auteur.php";
    include "conx.php";

    session_start();

    $myPDOAuteur = new MyPDO( $_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'], 'auteur');

    $contenu = "";
    $message = "";

    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'read':
                $auteur = $myPDOAuteur->get('id_auteur', $_GET['id_auteur']);
                if($auteur !=false){
                    $contenu .= VueAuteur::getHTML4Auteur($auteur);
                }else {
                  $contenu .= "l auteur n 'existe pas";
                }

                $_SESSION['etat'] = 'lecture';
                break;
            case 'create':
                $nbAuteurs = $myPDOAuteur->count();
                $contenu .= VueAuteur::getFormulaire4Auteur(array('nom_auteur' => 'text', 'prenom_auteur' => 'text'));
                $_SESSION['etat'] = 'création';
                break;
            case 'update':
                $auteur = $myPDOAuteur->get('id_auteur',$_GET['id_auteur']);
                if($auteur !=false){
                  $contenu .= VueAuteur::getFormulaire4Auteur(
                      array(
                          'id_auteur'=>array('type'=>'number','default'=>$auteur->getIdAuteur()),
                          'nom_auteur'=>array('type'=>'text','default'=>$auteur->getNomAuteur()),
                          'prenom_auteur'=>array('type'=>'text','default'=>$auteur->getPrenomAuteur())
                      )
                  );
                }else {
                  $contenu .= "l auteur n 'existe pas";
                }
                $_SESSION['etat'] = 'modification';
                break;
            case 'delete':
                $myPDOAuteur->delete(array('id_auteur'=>$_GET['id_auteur']));
                $_SESSION['etat'] = 'suppression';
                break;
            default:
                $message .= "<p>Action ".$_GET['action']." non implémentée.</p>\n";
        }
    } else if (isset($_SESSION['etat'])) {
        switch($_SESSION['etat']) {
            case 'création':
                $valid= '#^[a-zA-ZéèîÉÈïÎÏ][a-zçéèîï]+([- \s][a-zA-ZéèîÉÈïÎÏ][a-zçéèîï]+)?$#';
                if(preg_match($valid,$_GET['nom_auteur']) && preg_match($valid,$_GET['prenom_auteur'])){
                  $myPDOAuteur->insert(array('nom_auteur'=>$_GET['nom_auteur'], 'prenom_auteur'=>$_GET['prenom_auteur']));

                }

                $_SESSION['etat'] = 'créé';
                break;
            case 'modification':
              $valid= '#^[a-zA-ZéèîÉÈïÎÏ][a-zçéèîï]+([- \s][a-zA-ZéèîÉÈïÎÏ][a-zçéèîï]+)?$#';
              if(preg_match($valid,$_GET['nom_auteur']) && preg_match($valid,$_GET['prenom_auteur'])){
                $myPDOAuteur->update('id_auteur', array('id_auteur'=>$_GET['id_auteur'], 'nom_auteur'=>$_GET['nom_auteur'], 'prenom_auteur'=>$_GET['prenom_auteur']));
              }else{
                //renvoiye le formulaire du modif
              }
              $_SESSION['etat'] = 'modifié';
                break;
            case 'suppression':
                $_SESSION['etat']= 'supprimé';
                break;
            case 'créé':
                break;
            case 'modifié':
                break;
            case 'supprimé':
                break;
            default:
                $_SESSION['etat'] = 'neutre';
        }
    }

    $myPDOAuteur->count();
    $nbAuteurs = $myPDOAuteur->getPdosCount()->fetch(PDO::FETCH_LAZY)->count;

    $message .= "<p>La table Auteur contient ".$nbAuteurs." enregistrements.</p>\n";

    $contenu .=
    "<form action='traitement.php' method='GET'>
    <select name='action'>
    <option value='read'>Lire</option>
    <option value='update'>Modifier</option>
    <option value='delete'>Supprimer</option>
    </select>
    <input type='number' min='1' name='id_auteur'/>
    <input type='submit' name='envoi' value='Go' />
    </form>\n";

    $contenu .="<p><a href='?action=create'>Créer Auteur ";
    //$contenu .= $nbAuteurs+1;
    $contenu .= "</a> </p>";

    include 'debut.html';
    echo $message;
    echo $contenu;
    include 'fin.html';
?>
