 <?php
    include 'TableAffichable.php';
    include 'Bulma.php';
    include 'MyPDO.php';
    include 'conx.php';
    include 'Auteur.php';
    include 'Oeuvre.php';
    include 'Prix_auteur.php';
    include 'Prix_oeuvre.php';
    include 'VueAuteur.php';

    include 'IterateurTable.php';
    session_start();

    function affichePage(string $nomPage, $formulaire="", $numPage) {
        $tablesValides = ['oeuvre', 'auteur', 'prix_auteur', 'prix_oeuvre'];
        if(!in_array($nomPage, $tablesValides)) $nomPage = 'oeuvre';

        $iterateur = new IterateurTable($nomPage, "id_".$nomPage);

        $iterateur->setKey(($numPage-1)*30);

        $tableau = new TableauBulma($nomPage, $numPage, ceil($iterateur->count()/30));

        switch($nomPage) {
            case 'auteur':
              $header = ['Nom', 'Prénom'];
            break;
            case 'prix_auteur':
            case 'prix_oeuvre':
              $header = ['Nom'];
            break;
            default: //oeuvre
                $header = ['Titre'];
        }
        $tableau->setHeader($header);
        while($iterateur->valid() && $iterateur->key() < $numPage*30) {
            $tableau->addLigne($iterateur->current()->toArray());
            $iterateur->next();
        }
        afficheNav($nomPage);
        echo $formulaire;
        echo "<form action='index.php'>$tableau</form>";
    }

    $formulaire = "";

    include 'debut.html';
    if(isset($_GET['modif'])){
      $val = explode( '_',$_GET['modif']);
      if(sizeOf($val) == 2){
        $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]);
        $objet = $myPDO->get('id_'.$val[1], $val[0]);
        $_SESSION['etat'] = 'modif_'.$val[1].'_'.$val[0];
      }
      switch ($val[1]) {
        case 'auteur':
            $tab = ['Nom' => ['name' => 'nom_auteur', 'value' => $objet->getNomAuteur()],
                    'Prénom' => ['name' => 'prenom_auteur', 'value' => $objet->getPrenomAuteur()]];
            break;
        case 'oeuvre':
            $tab = ['Titre' => ['name' => 'nom_oeuvre', 'value' => $objet->getNomOeuvre()]];
            break;
        case 'prix':
            $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]."_".$val[2]);
            $prix = $myPDO->get('id_prix_'.$val[2], $val[0]);
            $_SESSION['etat'] = 'modif_prix_'.$val[2].'_'.$val[0];
            if($val[2] == 'auteur'){
                $value = $prix->getNomPrixAuteur();
            }else{
                $value = $prix->getNomPrixOeuvre();
            }
            $tab = ['Prix' => ['name' => 'nom_prix_'.$val[2], 'value' => $value] ];
            break;
        default:
          break;
      }
          $formulaire = getFormulaire($tab);
    }

    if(isset($_GET['suppr'])){
      $val = explode( '_',$_GET['suppr']) ;
      switch ($val[1]) {
        case 'prix' :
				 $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]."_".$val[2]);
				 $count = $myPDO->getCountValueAttribut('id_'.$val[1].'_'.$val[2],$val[0]);
				 if($count  == 0){
				   $myPDO->delete(array('id_'.$val[1].'_'.$val[2] =>$val[0]));
				 }else{
				   echo "<script>alert(\"SUPRESSION IMPOSSIBLE \")</script>";
				 }
			
             break;
        case 'auteur' || 'oeuvre':
				 $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]);
				 $count = $myPDO->getCountValueAttribut('id_'.$val[1],$val[0]);
				 if($count  == 0){
				   $myPDO->delete(array('id_'.$val[1] =>$val[0]));
				 }else{
				   echo "<script>alert(\"SUPRESSION IMPOSSIBLE \")</script>";
				 }

             break;
        default:
            break;
      }
  }


    if(isset($_GET['Valider'])){
      $val = explode( '_',$_SESSION['etat']) ;
      if($val[0] == "modif"){
        switch ($val[1]) {
          case 'prix' :
            $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]."_".$val[2]);
            $id = 'id_prix_'.$val[2] ;
            $tab = array('id_prix_'.$val[2]=>$val[3], 'nom_prix_'.$val[2] =>$_GET['nom_prix_'.$val[2]]);
            break;
          case 'auteur' || 'oeuvre':
            $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]);
            $id = 'id_'.$val[1] ;
            if($val[1] == 'auteur'){
              $tab = array('id_'.$val[1]=>$val[2], 'nom_'.$val[1] =>$_GET['nom_'.$val[1]], 'prenom_'.$val[1] =>$_GET['prenom_'.$val[1]]);
            }
            else{
              $tab = array('id_'.$val[1]=>$val[2], 'nom_'.$val[1] =>$_GET['nom_'.$val[1]]);
            }
            break;
          default:
            break;
        }
        $myPDO->update($id, $tab);
      }else{

        if($val[0] == "cree"){

          switch ($val[1]) {
            case 'prix':
              $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]."_".$val[2]);
              $myPDO->insert(array('nom_prix_'.$val[2]=>$_GET['nom_prix_'.$val[2]]));
              break;
            case 'auteur':
              $valid= '#^[a-zA-Z éèîÉÈïÎÏ][a-zç éèîï]+([- \s][a-zA-Z éèîÉÈïÎÏ][a-z çé èîï]+)?$#';
              if(preg_match($valid,$_GET['nom_auteur']) && preg_match($valid,$_GET['prenom_auteur'])){
                $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]);
                $myPDO->insert(array('nom_auteur'=>$_GET['nom_auteur'], 'prenom_auteur'=>$_GET['prenom_auteur']));
              }else{
                echo "informations invalide";
                $_SESSION['etat'] = 'cree_auteur_';
                $formulaire = getFormulaire([
                  'Nom' => ['name' => 'nom_auteur', 'value' => ''],
                  'Prénom' => ['name' => 'prenom_auteur', 'value' => '']
                ]);
              }
              break;
            case 'oeuvre':
              $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[1]);
              $myPDO->insert(array('nom_oeuvre'=>$_GET['nom_oeuvre']));
              break;
            default:
              break;
          }
        }
      }
    }
    if(isset($_GET['cree'])){
      $myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$_GET['cree']);
      switch ($_GET['cree']) {
        case 'auteur':
          $_SESSION['etat'] = 'cree_auteur_';
          $formulaire = getFormulaire([
            'Nom' => ['name' => 'nom_auteur', 'value' => ''],
            'Prénom' => ['name' => 'prenom_auteur', 'value' => '']
          ]);
          break;
        case 'oeuvre':
          $_SESSION['etat'] = 'cree_oeuvre_';
          $formulaire = getFormulaire([
                          'Titre' => ['name' => 'nom_oeuvre', 'value' => '']
                        ]);
          break;
        case 'prix_auteur' || 'prix_oeuvre'  :
          $_SESSION['etat'] = 'cree_'.$_GET['cree'];
          $formulaire = getFormulaire([
            'Prix' => ['name' => 'nom_'.$_GET['cree'], 'value' => ''],
          ]);
          break;
        default:
          break;
      }
    }
    $numPage = 1;

    if(isset($_GET['page']) && $_GET['page'] > 0) $numPage = $_GET['page'];

    $table = 'oeuvre';

    if(isset($_GET['table'])) {
      $table = $_GET['table'];
    } else {
      if(isset($val[2]) && $val[1] == 'prix'){
        $table = $val[1]."_".$val[2];
      }else{
        if(isset($val[1])){
            $table = $val[1];
          } else if(isset($_GET['cree'])) {
            $table = $_GET['cree'];
          }
      }
    }
    affichePage($table, $formulaire, $numPage);

    include 'fin.html';
?>
