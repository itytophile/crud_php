 <?php

include 'conx.php';
include 'Bulma.php';
include 'MyPDO.php';

session_start();

function getAuteurs($pdo, $id) : string {
    $statement = $pdo->prepare("SELECT * FROM auteur WHERE id_auteur IN (SELECT id_auteur FROM ecriture WHERE id_oeuvre = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma('auteur', 0, 0, false, TableauBulma::SELECTION, $id, 'ecriture');

    $tableauBulma->setHeader(['Nom', 'Prénom']);

    foreach($tableau as $ligne) {
        $tableauBulma->addLigne([$ligne['id_auteur'], $ligne['nom_auteur'], $ligne['prenom_auteur']]);
    }

    return $tableauBulma;
}

function getOeuvres($pdo, $id) : string {
    $statement = $pdo->prepare("SELECT * FROM oeuvre WHERE id_oeuvre IN (SELECT id_oeuvre FROM ecriture WHERE id_auteur = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma('oeuvre', 0, 0, false, TableauBulma::SELECTION, $id, 'ecriture');

    $tableauBulma->setHeader(['Titre']);

    foreach($tableau as $ligne) {
        $tableauBulma->addLigne([$ligne['id_oeuvre'], $ligne['nom_oeuvre']]);
    }

    return $tableauBulma;
}

function getPrixAuteur($pdo, $id) : string {
    $statement = $pdo->prepare("SELECT * FROM prix_auteur WHERE id_prix_auteur IN (SELECT id_prix_auteur FROM attribution_prix_auteur WHERE id_auteur = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma('prix_auteur', 0, 0, false, TableauBulma::SELECTION, $id, 'attribution_prix_auteur');

    $tableauBulma->setHeader(['Nom']);

    foreach($tableau as $ligne) {
        $tableauBulma->addLigne([$ligne['id_prix_auteur'], $ligne['nom_prix_auteur']]);
    }
    
    return $tableauBulma;
}

function getPrixOeuvre($pdo, $id) : string {
    $statement = $pdo->prepare("SELECT * FROM prix_oeuvre WHERE id_prix_oeuvre IN (SELECT id_prix_oeuvre FROM attribution_prix_oeuvre WHERE id_oeuvre = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma('prix_oeuvre', 0, 0, false, TableauBulma::SELECTION, $id, 'attribution_prix_oeuvre');

    $tableauBulma->setHeader(['Nom']);

    foreach($tableau as $ligne) {
        $tableauBulma->addLigne([$ligne['id_prix_oeuvre'], $ligne['nom_prix_oeuvre']]);
    }

    return $tableauBulma;
}

function getAuteursFromPrix($pdo, $id) : string {
    $statement = $pdo->prepare("SELECT * FROM auteur WHERE id_auteur IN (SELECT id_auteur FROM attribution_prix_auteur WHERE id_prix_auteur = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma('auteur', 0, 0, false, TableauBulma::SELECTION, $id, 'attribution_prix_auteur');

    $tableauBulma->setHeader(['Nom', 'Prénom']);

    foreach($tableau as $ligne) {
        $tableauBulma->addLigne([$ligne['id_auteur'], $ligne['nom_auteur'], $ligne['prenom_auteur']]);
    }

    return $tableauBulma;
}

function getOeuvresFromPrix($pdo, $id) : string {
    $statement = $pdo->prepare("SELECT * FROM oeuvre WHERE id_oeuvre IN (SELECT id_oeuvre FROM attribution_prix_oeuvre WHERE id_prix_oeuvre = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma('oeuvre', 0, 0, false, TableauBulma::SELECTION, $id, 'attribution_prix_oeuvre');

    $tableauBulma->setHeader(['Titre']);

    foreach($tableau as $ligne) {
        $tableauBulma->addLigne([$ligne['id_oeuvre'], $ligne['nom_oeuvre']]);
    }

    return $tableauBulma;
}

//tentative
function getAssociation($pdo, $id, string $nomTable, string $nomTableAssoc, string $nomIdTable, string $nomIdTableAssoc, array $header, array $formatLigne) : string {
    $statement = $pdo->prepare("SELECT * FROM $nomTable WHERE $nomIdTable IN (SELECT $nomIdTableAssoc FROM $nomTableAssoc WHERE $nomIdTable = :index)");
    $statement->execute([':index' => $id]);

    $tableau = $statement->fetchAll();

    $tableauBulma = new TableauBulma($nomTable, 0, 0, false, TableauBulma::SELECTION, $id);

    $tableauBulma->setHeader($header);

    foreach($tableau as $ligne) {
        $tuple = [];
        foreach($formatLigne as $nomCol) {
            $tuple[] = $ligne[$nomCol];
        }
        $tableauBulma->addLigne($tuple);
    }

    return $tableauBulma;
}

include 'debut.html';

afficheNav();

if(isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = $_GET['id'];
    $pdo = new PDO("mysql:host=".$_ENV['host'].";dbname=".$_ENV['db'], $_ENV['user'], $_ENV['password']);
				
    switch($table) {
        case 'oeuvre':
            echo "<h1 class='title'>Le(s) auteur(s) de l'&oelig;uvre</h1>";
            $tableau = getAuteurs($pdo, $id);
            echo "<form action='detail.php'>$tableau";
            $tableau = getPrixOeuvre($pdo, $id);
            echo "<h1 class='title'>Prix gagné(s)</h1>";
            echo "$tableau</form>";
        break;
        case 'auteur':
            echo "<h1 class='title'>L(es) &oelig;uvre(s) de l'auteur</h1>";
            $tableau = getOeuvres($pdo, $id);
            echo "<form action='detail.php'>$tableau";
            $tableau = getPrixAuteur($pdo, $id);
            echo "<h1 class='title'>Prix gagné(s)</h1>";
            echo "$tableau</form>";
        break;
        case 'prix_auteur':
            echo "<h1 class='title'>Les lauréats</h1>";
            $tableau = getAuteursFromPrix($pdo, $id);
            echo "<form action='detail.php'>$tableau</form>";
        break;
        default:
            echo "<h1 class='title'>Les lauréats</h1>";
            $tableau = getOeuvresFromPrix($pdo, $id);
            echo "<form action='detail.php'>$tableau</form>";
    }
	$_SESSION['choix'] = $table.'_'.$id ;
}



    if(isset($_GET['suppr'])){
      $val = explode( '_',$_GET['suppr']) ;
      switch ($val[1]) {
        case 'prix' :
			if(isset($_SESSION['choix'])){

				echo $_SESSION['choix'];
				$choix = explode( '_',$_SESSION['choix']) ;
				
				$myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],"attribution_prix_".$choix[0]);

				$myPDO->delete(array('id_'.$choix[0] =>$choix[1],'id_prix_'.$choix[0] =>$val[0]));
				if(!isset($_GET['table'])){
					header( 'location: detail.php?table='.$choix[0].'&id='.$choix[1]);
				}
				}
             break;
        case 'auteur' || 'oeuvre':
			if(isset($_SESSION['choix'])){
				echo $_SESSION['choix'] ;
				
				$choix = explode( '_',$_SESSION['choix']) ;
				if($choix[0] != $val[1]){
				if($choix[0] != 'prix'){
					$myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],"ecriture");
					echo $choix[0] ."";
					$myPDO->delete(array('id_'.$choix[0] =>$choix[1],'id_'.$val[1] =>$val[0]));	
					if(!isset($_GET['table'])){
						header( 'location: detail.php?table='.$choix[0].'&id='.$choix[1]);
					}					
				}else{
					$myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],"attribution_prix_".$choix[1]);
					$myPDO->delete(array('id_'.$choix[0].'_'. $choix[1]=>$choix[2],'id_'.$val[1] =>$val[0]));	
					if(!isset($_GET['table'])){
						header( 'location: detail.php?table='.$choix[0].'_'.$choix[1].'&id='.$choix[2]);
					}
				}
				}

			}
             break;
        default:
            break;
      }
  }












include 'fin.html';
