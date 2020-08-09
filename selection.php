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

function affichePage(string $nomPage, $numPage, $id, $tableAssoc) {
    $tablesValides = ['oeuvre', 'auteur', 'prix_auteur', 'prix_oeuvre'];
    if(!in_array($nomPage, $tablesValides)) $nomPage = 'oeuvre';

    $iterateur = new IterateurTable($nomPage, "id_".$nomPage);

    $iterateur->setKey(($numPage-1)*30);

    $tableau = new TableauBulma($nomPage, $numPage, ceil($iterateur->count()/30), true, TableauBulma::CHOIX, $id, $tableAssoc);

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

    echo "<h1 class='title'>Ajout association</h1>";
    echo "<form action='selection.php'>$tableau </form>";

}

include 'debut.html';

if(isset($_GET['page']) && $_GET['page'] > 0){
	$numPage = $_GET['page'];
}else $numPage = 1;
if(isset($_GET['choix'])){
	$choix = str_replace(' ','',$_GET['choix']);
	$val = explode( '_',$choix) ;
	switch ($val[3]) {
		case "ecriture":
			$myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$val[3]);
			if($val[2] == "auteur"){
				$table = "oeuvre";
              $myPDO->insert(array('id_oeuvre'=>$val[1],'id_auteur'=>$val[0]));	
			}			
			if($val[2] == "oeuvre"){
				$table = "auteur";
              $myPDO->insert(array('id_oeuvre'=>$val[0],'id_auteur'=>$val[1]));	
			}
				break;
		case "auteur" || "oeuvre":
			if($val[3] != "attribution"){
				//affiche formulaire pour choisir lannne
				$table = "attribution_prix_".$val[3];
				$myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$table);
				$myPDO->insert(array('id_prix_'.$val[3]=>$val[0],'id_'.$val[3]=>$val[1],'annee'=>'2015'));	//
				$table = $val[3];

			}else{
				$table = "attribution_prix_".$val[2];
				$myPDO = new MyPDO($_ENV['host'], $_ENV['db'], $_ENV['user'], $_ENV['password'],$table);
				$myPDO->insert(array('id_prix_'.$val[2]=>$val[1],'id_'.$val[2]=>$val[0],'annee'=>'1901'));	//			
				$table = "prix_".$val[2];

			}
			break;
		}
}
else affichePage($_GET['table'], $numPage, $_GET['id'], $_GET['tableAssoc']);
if(!isset($_GET['table'])){
header( 'location: detail.php?table='.$table.'&id='.$val[1]);
}

include 'fin.html';