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
    //classes loading end
    session_start();
    
    //post input processing
	$idSalaire = $_GET['idSalaire'];   
	$idCharge = $_POST['idCharges'];
    $salairesManager = new SalairesManager($pdo);
	$salairesManager->delete($idSalaire);
	header('Location:../charges-salaires.php?idCharges='.$idCharge);
    
    