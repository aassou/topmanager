<?php

    //classes loading begin
    function classLoad ($myClass) {
        if(file_exists('../model/'.$myClass.'.php')){
            include('../model/'.$myClass.'.php');
        }
        elseif(file_exists('../controller/'.$myClass.'.php')){
            include('../controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('../config.php');  
    include('../lib/image-processing.php');
    //classes loading end
    session_start();
    //post input processing
    $action = htmlentities($_POST['action']);
    //This var contains result message of CRUD action
    $actionMessage = "";
    $typeMessage = "";
    $redirectLink = "";
    //Component Class Manager
    $affaireManager = new AffaireManager($pdo);
    $historyManager = new HistoryManager($pdo);
	//Action Add Processing Begin
    if($action == "add"){
        if( !empty($_POST['client']) ){
			//add important classes managers
            $clientManager = new ClientManager($pdo);
            $sourceManager = new SourceManager($pdo);
            $rendezVousManager = new RendezVousManager($pdo);       
            /* Get form fields begin processes : ################################################# 
             * here we get first all the form fields 
             * */
            //client informations
            $idClient = 0;
            if(!empty($_POST['idClient'])){
                $idClient = htmlentities($_POST['idClient']);   
            }
            $nomClient = htmlentities($_POST['client']);
            $cinClient = htmlentities($_POST['cin']);
            $telefonClient = htmlentities($_POST['numeroTelefon1']);
            $mandataire = htmlentities($_POST['mandataire']);
            //source informations
            $idSource = 0;
            if(!empty($_POST['idSource'])){
                $idSource = htmlentities($_POST['idSource']);   
            }
            $nomSource = htmlentities($_POST['source']);
            $telefonSource = htmlentities($_POST['numeroTelefon2']);
            $montantSource = htmlentities($_POST['montantSource']);
            //affaire informations
            $nature = htmlentities($_POST['natureTravail']);
            $dateRdv = htmlentities($_POST['dateRdv']);
            $heureRdv = htmlentities($_POST['heureRdv']);
            $dateSortie = htmlentities($_POST['dateSortie']);
            if ( empty($dateSortie) ) {
                $dateSortie = htmlentities($_POST['dateRdv']);
            }
            $prix = htmlentities($_POST['prix']);
            $paye = htmlentities($_POST['paye']);
            //zone informations
            $province = "";
            $municipalite = "";
            $commune = "";
            $quartier = "";
            $sousQuartier = "";
            $propriete = "";
            if ( !empty($_POST['propriete']) ) {
                $propriete = htmlentities($_POST['propriete']); 
            }
            
            /* In this case it's supposed that Services and Topographes are all ready in our DB.
             * So, here we don't need to add them automatically.
             * */
            $idService = 0;
            if ( !empty($_POST['idService']) ) {
                $idService = htmlentities($_POST['idService']);
            }
            $idTopographe = 0;
            if ( !empty($_POST['idTopographe']) ) {
                $idTopographe = htmlentities($_POST['idTopographe']);
            }
            //We use Zero just to set other value than NULL in the DB
            $montantService = 0;
            $montantTopographe = 0;
            $montantSource = 0;
            if ( !empty($_POST['montantService']) ) {
                $montantService = $_POST['montantService'];
            }
            if ( !empty($_POST['montantTopographe']) ) {
                $montantTopographe = $_POST['montantTopographe'];
            }
            if ( !empty($_POST['montantSource']) ) {
                $montantSource = $_POST['montantSource'];
            }
            /*If the CheckBox "Terminer et Archiver Affaire" isn't checked, the status of our affaire is "terminee",
             * else the status is "Archivee", wich mean we can not modify some informations. 
             */
            $status = "terminee";
            if ( !empty($_POST['archiver']) ) {
                $status = "archivee";
            }
            /* ---------- Test if client exists in DB Begin: 
             * if no, add him to DB, 
             * else do not add him ----- */
            $clientFromDb = "";
            if($idClient==0){
                $client = new Client(array('nom'=>$nomClient, 'cin'=>$cinClient, 'numeroTelefon'=>$telefonClient));
                $clientManager->add($client);
                $clientFromDb = $clientManager->getClientById($clientManager->getLastId());
            }
            else{
                $clientFromDb = $clientManager->getClientById($idClient);
            }
            $idClientFromDb = $clientFromDb->id();
            /* ---------- Test if client exists in DB End */ 
            /* ---------- Test if source exists in DB Begin: 
             * if no, add him to DB, 
             * else do not add him ----- */
            $sourceFromDb = "";
            if ( $idSource == 0 and !empty($_POST['source']) ) {
                $code = "S101";
                $sourceLastId = $sourceManager->getLastId(); 
                if ( $sourceLastId >= 1 ) {
                    $code = "S".($sourceLastId+101);
                }
                $source = new Source(array('nom'=>$nomSource, 'numeroTelefon'=>$telefonSource, 'code'=>$code));
                $sourceManager->add($source);
                $sourceFromDb = $sourceManager->getSourceById($sourceManager->getLastId());
                $idSourceFromDb = $sourceFromDb->id();
            }
            else if ( $idSource != 0 ) {
                $sourceFromDb = $sourceManager->getSourceById($idSource);
                $idSourceFromDb = $sourceFromDb->id();
            }
            else if ( $idSource == 0 and empty($_POST['source']) ) {
                $idSourceFromDb = $idSource;
            }
            /* ---------- Test if source exists in DB End */
            /* ---------- Test if zone inputs exist in DB Begin: 
             * if no, add them to DB, 
             * else do not add them ----- */
            //province processing begin
            $province = htmlentities($_POST['province']);
            if ( empty($_POST['idProvince']) and !empty($_POST['province']) ) {
                $provinceManager = new ProvinceManager($pdo);
                if ( !$provinceManager->exists2($province) ) {
                    //$province = new Province(array('nom'=>$province));
                    $provinceManager->add(new Province(array('nom'=>$province)));   
                }
            }
            //mp process begin
            $municipalite = htmlentities($_POST['mp']);
            if ( empty($_POST['idMp']) and !empty($_POST['mp']) ) {
                $mpManager = new MunicipaliteManager($pdo);
                if ( !$mpManager->exists2($municipalite) ) {
                    $mp = new Municipalite(array('nom'=>$municipalite));
                    $mpManager->add($mp);
                }
            }
            //cr process begin
            $commune = htmlentities($_POST['cr']);
            if ( empty($_POST['idCr']) and !empty($_POST['cr']) ) {
                $crManager = new CommuneRuraleManager($pdo);
                if ( !$crManager->exists2($commune) ) {
                    $cr = new CommuneRurale(array('nom'=>$commune));
                    $crManager->add($cr);
                }
            }
            //quartier process begin
            $quartier = htmlentities($_POST['quartier']);
            if ( empty($_POST['idQuartier']) and !empty($_POST['quartier']) ) {
                $quartierManager = new QuartierManager($pdo);
                if ( !$quartierManager->exists2($quartier) ) {
                    //$quartier = new Quartier(array('nom'=>$quartier));
                    $quartierManager->add(new Quartier(array('nom'=>$quartier)));
                }
            }
            //sousquartier process begin
            $sousQuartier = htmlentities($_POST['sousquartier']);
            if ( empty($_POST['idSousQuartier']) and !empty($_POST['sousquartier']) ) {
                $sousQuartierManager = new SousQuartierManager($pdo);
                if ( !$sousQuartierManager->exists2($sousQuartier) ) {
                    //$sousQuartier = new SousQuartier(array('nom'=>$sousQuartier));
                    $sousQuartierManager->add(new SousQuartier(array('nom'=>$sousQuartier)));
                }
            }
            //zone processing end
            /* ---------- Test if zone inputs exist in DB End --------------- */
            /* Get form fields & other processes End : ################################################# 
             * here we get first all the form fields 
             * */
			$createdBy = $_SESSION['userMerlaTrav']->login();
            $created = date('Y-m-d h:i:s');
            //create object
            $affaire = new Affaire(array(
				'dateRdv' => $dateRdv,
				'heureRdv' => $heureRdv,
				'dateSortie' => $dateSortie,
				'nature' => $nature,
				'prix' => $prix,
				'paye' => $paye,
				'mandataire' => $mandataire,
				'status' => $status,
				'idTopographe' => $idTopographe,
				'idSource' => $idSource,
				'idService' => $idService,
				'idClient' => $idClient,
				'province' => $province,
				'mp' => $municipalite,
				'cr' => $commune,
				'quartier' => $quartier,
				'sousquartier' => $sousQuartier,
				'propriete' => $propriete,
				'montantTopographe' => $montantTopographe,
				'montantService' => $montantService,
				'montantSource' => $montantSource,
				'created' => $created,
            	'createdBy' => $createdBy
			));
            //add it to db
            $affaireManager->add($affaire);
            //Data traceability Begin
            $history = new History(array(
                'action' => "Ajout",
                'target' => "Table des affaires",
                'description' => "Ajout Nouvelle Affaire = Client : ".$nomClient." - CIN Client : ".$cinClient." - Nature Travail : ".$nature." - Date Rdv/Sortie : ".$dateRdv,
                'created' => $created,
                'createdBy' => $createdBy
            ));
            //print_r($affaire);
            //print_r($history);
            $historyManager->add($history);
            //Data traceability End
            $actionMessage = "Opération Valide : Affaire Ajouté(e) avec succès.";  
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Ajout affaire : Vous devez remplir le champ 'Client'.";
            $typeMessage = "error";
        }
        $redirectLink = "Location:../add-affaire.php";
    }
    //Action Add Processing End
    //Action Update Processing Begin
    else if($action == "update"){
        $idAffaire = htmlentities($_POST['idAffaire']);
        if(!empty($_POST['dateRdv'])){
			$dateRdv = htmlentities($_POST['dateRdv']);
			$heureRdv = htmlentities($_POST['heureRdv']);
			$dateSortie = htmlentities($_POST['dateSortie']);
			$nature = htmlentities($_POST['nature']);
			$prix = htmlentities($_POST['prix']);
			$paye = htmlentities($_POST['paye']);
			$mandataire = htmlentities($_POST['mandataire']);
			$status = htmlentities($_POST['status']);
			$idTopographe = htmlentities($_POST['idTopographe']);
			$idSource = htmlentities($_POST['idSource']);
			$idService = htmlentities($_POST['idService']);
			$idClient = htmlentities($_POST['idClient']);
			$province = htmlentities($_POST['province']);
			$mp = htmlentities($_POST['mp']);
			$cr = htmlentities($_POST['cr']);
			$quartier = htmlentities($_POST['quartier']);
			$sousquartier = htmlentities($_POST['sousquartier']);
			$propriete = htmlentities($_POST['propriete']);
			$montantTopographe = htmlentities($_POST['montantTopographe']);
			$montantService = htmlentities($_POST['montantService']);
			$montantSource = htmlentities($_POST['montantSource']);
			$updatedBy = $_SESSION['userMerlaTrav']->login();
            $updated = date('Y-m-d h:i:s');
            $affaire = new Affaire(array(
				'id' => $idAffaire,
				'dateRdv' => $dateRdv,
				'heureRdv' => $heureRdv,
				'dateSortie' => $dateSortie,
				'nature' => $nature,
				'prix' => $prix,
				'paye' => $paye,
				'mandataire' => $mandataire,
				'status' => $status,
				'idTopographe' => $idTopographe,
				'idSource' => $idSource,
				'idService' => $idService,
				'idClient' => $idClient,
				'province' => $province,
				'mp' => $mp,
				'cr' => $cr,
				'quartier' => $quartier,
				'sousquartier' => $sousquartier,
				'propriete' => $propriete,
				'montantTopographe' => $montantTopographe,
				'montantService' => $montantService,
				'montantSource' => $montantSource,
				'updated' => $updated,
            	'updatedBy' => $updatedBy
			));
            $affaireManager->update($affaire);
            $actionMessage = "Opération Valide : Affaire Modifié(e) avec succès.";
            $typeMessage = "success";
        }
        else{
            $actionMessage = "Erreur Modification Affaire : Vous devez remplir le champ 'dateRdv'.";
            $typeMessage = "error";
        }
    }
    //Action Update Processing End
    //Action Delete Processing Begin
    else if($action == "delete"){
        $idAffaire = htmlentities($_POST['idAffaire']);
        $affaireManager->delete($idAffaire);
        $actionMessage = "Opération Valide : Affaire supprimé(e) avec succès.";
        $typeMessage = "success";
    }
    //Action Delete Processing End
    $_SESSION['affaire-action-message'] = $actionMessage;
    $_SESSION['affaire-type-message'] = $typeMessage;
    header($redirectLink);

