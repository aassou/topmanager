<?php
//classes loading begin
    function classLoad ($myClass) {
        if(file_exists('model/'.$myClass.'.php')){
            include('model/'.$myClass.'.php');
        }
        elseif(file_exists('controller/'.$myClass.'.php')){
            include('controller/'.$myClass.'.php');
        }
    }
    spl_autoload_register("classLoad"); 
    include('config.php');  
	include('lib/pagination.php');
    //classes loading end
    session_start();
    if(isset($_SESSION['user'])){
    	//classes managers 
    	$affaireManager = new AffaireManager($pdo);
		$clientManager = new ClientManager($pdo);
		$sourceManager = new SourceManager($pdo);
		//classes
		//affaire pagination parameters and process begin
        $mois = $_GET['mois'];
        $annee = $_GET['annee'];
        $affaires = $affaireManager->getAffairesByMonthYear($mois, $annee);
        
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title>TopEntreprise - Management Application</title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/css/metro.css" rel="stylesheet" />
	<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	<link href="assets/fullcalendar/fullcalendar/bootstrap-fullcalendar.css" rel="stylesheet" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style_responsive.css" rel="stylesheet" />
	<link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
	<link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
	<link rel="stylesheet" href="assets/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="shortcut icon" href="favicon.ico" />
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
	<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<?php include("include/top-menu.php"); ?>	
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<!-- BEGIN CONTAINER -->	
	<div class="page-container row-fluid sidebar-closed">
		<!-- BEGIN SIDEBAR -->
		<?php include("include/sidebar.php"); ?>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE CONTAINER-->
			<div class="container-fluid">
				<!-- BEGIN PAGE HEADER-->
				<div class="row-fluid">
					<div class="span12">
						<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
						<h3 class="page-title">
							Gestion des affaires
						</h3>
						<ul class="breadcrumb">
							<li>
								<i class="icon-home"></i>
								<a href="dashboard.php">Accueil</a> 
								<i class="icon-angle-right"></i>
							</li>
							<li>
                                <i class="icon-briefcase"></i>
                                <a href="affaires-group.php">Gestion des affaires</a>
                                <i class="icon-angle-right"></i>
                            </li>
							<li>
                                <a><strong><?= $mois ?>/<?= $annee ?></strong></a>
                            </li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				
				<div class="row-fluid">
					<div class="span12">
				<!-- BEGIN PAGE CONTENT-->
				<!-- BEGIN Rendez-vous TABLE PORTLET-->
				<!-- BEGIN CONDENSED TABLE PORTLET-->
						<div class="portlet box grey">
							<div class="portlet-title">
								<h4><i class="icon-calendar"></i>Liste des affaires</h4>
								<!--div class="tools">
									<a href="javascript:;" class="collapse"></a>
									<a href="javascript:;" class="remove"></a>
								</div-->
							</div>
							<div class="portlet-body">
							    <div class="clearfix">
							        <div class="btn-group pull-left">
                                        <a class="btn blue" href="add-affaire.php?source=2&mois=<?= $mois ?>&annee=<?= $annee ?>">
                                            <i class="icon-plus-sign"></i>
                                            Ajouter Nouvelle Affaire
                                        </a>
                                    </div>
                                    <!--div class="btn-group pull-right">
                                        <button class="btn dropdown-toggle" data-toggle="dropdown">Outils <i class="icon-angle-down"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Print</a></li>
                                            <li><a href="#">Save as PDF</a></li>
                                            <li><a href="#">Export to Excel</a></li>
                                        </ul>
                                    </div-->
                                </div>
								<?php if(isset($_SESSION['affaire-regler-sucess'])){ ?>
                                 	<div class="alert alert-success">
    									<button class="close" data-dismiss="alert"></button>
    									<?= $_SESSION['affaire-regler-sucess'] ?>		
    								</div>
                                 <?php } 
                                 	unset($_SESSION['affaire-regler-sucess']);
                                 ?>
								<table class="table table-striped table-bordered table-hover" id="sample_1">
									<thead>
										<tr>
										    <th style="width:15%">Actions</th>
											<th style="width:10%">Date Sortie</th>
											<th style="width:25%">Client</th>
											<!--th style="width:10%">Propriété</th-->
											<th style="width:10%">Nature</th>
											<th style="width:10%">Prix</th>
											<th style="width:10%">Payé</th>
											<!--th>Reste</th-->
											<th style="width:10%">Tel1</th>
											<!--th>Tel2</th>
											<th>Mandat</th-->
											<th style="width:10%">Status</th>
										</tr>
									</thead>
									<tbody>
									<?php 
										$currentDate = date('Y-m-d');
										$currentTime = date('H:i');
										$color="";
										
										foreach($affaires as $affaire){
											$dateRdv = date('Y-m-d', strtotime($affaire->dateRdv()));
											$status = $affaire->status();
                                            $statusText = "";
											//$link="";
											if ( $status == "encours" ) {
												$color="label-success";
												$statusText = "En cours";
											}
											else if ( $status == "terminee" ) {
												$color = "label-success";
                                                $statusText = "Terminée";
												//$link = "archive-affaire.php?idAffaire=".$affaire->id();
											}
											else if ( $status == "archivee" ) {
												$color = "label-inverse";
                                                $statusText = "Archivée";
											}
									?>
										<tr class="odd gradeX">
											<td>
									        	<a title="Consulter Paiements" class="btn mini" href="paiements.php?idAffaire=<?= $affaire->id() ?>">
									        		<i class="icon-money"></i>
									        	</a>
									        	<a title="Modifier" class="btn mini green" href="archive-affaire.php?idAffaire=<?= $affaire->id() ?>">
                                                    <i class="icon-refresh"></i>
                                                </a>
									        	<a title="Supprimer" class="btn mini red" href="#deleteAffaire<?php echo $affaire->id();?>" data-toggle="modal" data-id="<?php echo $affaire->id(); ?>">
									        		<i class="icon-remove"></i>
									        	</a>
									        	<?php 
                                                if ( $status == "archivee" ) { 
                                                ?>
                                                <a title="Consulter Archive" class="btn mini black" href="affaires-documents.php?idAffaire=<?= $affaire->id() ?>">
                                                    <i class="icon-folder-open"></i>
                                                </a>
                                                <?php 
                                                } 
                                                ?>
											</td>
											<td><?= date("d/m/Y", strtotime($affaire->dateRdv())) ?></td>
											<td><?= $clientManager->getClientById($affaire->idClient())->nom() ?></td>
											<!--td><?= $affaire->propriete() ?></td-->
											<td><?= $affaire->nature() ?></td>
											<td><?= number_format($affaire->prix(), 2, ',', ' ') ?></td>
											<td><?= number_format($affaire->paye(), 2, ',', ' ') ?></td>
											<!--td><?php //echo $affaire->prix()-$affaire->paye() ?></td-->
											<td><?= $clientManager->getClientById($affaire->idClient())->numeroTelefon() ?></td>
											<!--td>
											    <?php 
											    /*if ( $affaire->idSource()!=0 and $affaire->idSource()!='NULL' ) {
											         echo $sourceManager->getSourceById($affaire->idSource())->numeroTelefon(); 
                                                }*/ 
											    ?>
											</td-->
											<!--td><?php //echo $affaire->mandataire() ?></td-->
											<td><span class="label <?= $color ?>"><?= $statusText ?></span></td>
										</tr>
										<!-- delete affaire box begin-->
										<div id="deleteAffaire<?php echo $affaire->id();?>" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="login" aria-hidden="false" >
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
												<h3>Supprimer l'Affaire</h3>
											</div>
											<div class="modal-body">
												<form class="form-horizontal loginFrm" action="controller/DeleteAffaireController.php" method="post">
													<p>Êtes-vous sûr de vouloir supprimer cette affaire ?</p>
													<div class="control-group">
														<label class="right-label"></label>
														<input type="hidden" name="idAffaire" value="<?= $affaire->id() ?>" />
														<button class="btn" data-dismiss="modal"aria-hidden="true">Non</button>
														<button type="submit" class="btn red" aria-hidden="true">Oui</button>
													</div>
												</form>
											</div>
										</div>
										<!-- delete affaire box end -->
									<?php
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
						<!-- END CONDENSED TABLE PORTLET-->
				<!-- END Rendez-vous TABLE PORTLET-->
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->	
		</div>
		<!-- END PAGE -->	 	
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
		2014 &copy; TopEntreprise. Management Application.
		<div class="span pull-right">
			<span class="go-top"><i class="icon-angle-up"></i></span>
		</div>
	</div>
	<!-- END FOOTER -->
	<!-- BEGIN JAVASCRIPTS -->
	<!-- Load javascripts at bottom, this will reduce page load time -->
	<script src="assets/js/jquery-1.8.3.min.js"></script>			
	<script src="assets/breakpoints/breakpoints.js"></script>			
	<script src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>	
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/js/jquery.blockui.js"></script>
	<script src="assets/js/jquery.cookie.js"></script>
	<script src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>	
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/data-tables/jquery.dataTables.js"></script>
    <script type="text/javascript" src="assets/data-tables/DT_bootstrap.js"></script>
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="assets/js/excanvas.js"></script>
	<script src="assets/js/respond.js"></script>
	<![endif]-->
	<script src="assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {			
			// initiate layout and plugins
			App.setPage("table_managed");
			App.init();
		});
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
<?php
}
else{
    header('Location:index.php');    
}
?>