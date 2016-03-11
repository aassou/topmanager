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
    //classes loading end
    session_start();
    if ( isset($_SESSION['user']) ) {
    	//get classes managers
		$clientManager = new ClientManager($pdo);
		$sourceManager = new SourceManager($pdo);
        
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
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-timepicker/compiled/timepicker.css" />
	<link rel="stylesheet" type="text/css" href="assets/clockface/css/clockface.css" />
	<link href="assets/css/style.css" rel="stylesheet" />
	<link href="assets/css/style_responsive.css" rel="stylesheet" />
	<link href="assets/css/style_default.css" rel="stylesheet" id="style_color" />
	<link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
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
								<a>Ajouter une affaire</a>
							</li>
						</ul>
						<!-- END PAGE TITLE & BREADCRUMB-->
					</div>
				</div>
				<!-- END PAGE HEADER-->
				<!-- BEGIN PAGE CONTENT-->
				<!-- BEGIN Charges TABLE PORTLET-->
				<div class="tab-pane active" id="tab_1">
                    <div class="portlet box grey">
                        <div class="portlet-title">
                            <h4>Ajouter une affaire</h4>
                        </div>
                        <div class="portlet-body form">
                        <?php if(isset($_SESSION['affaire-add-success'])){ ?>
                            <div class="alert alert-success">
						      <button class="close" data-dismiss="alert"></button>
							     <?= $_SESSION['affaire-add-success'] ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['affaire-add-success']);
                         ?>
                         <?php if(isset($_SESSION['affaire-add-error'])){ ?>
                         	<div class="alert alert-error">
								<button class="close" data-dismiss="alert"></button>
								<?= $_SESSION['affaire-add-error'] ?>		
							</div>
                         <?php } 
                         	unset($_SESSION['affaire-add-error']);
                         ?>
                             <form id="add-affaire-form" action="controller/AffaireActionController.php" method="POST" class="horizontal-form" enctype="multipart/form-data">
                                <h3>Informations Client</h3>
                                <div class="row-fluid">
                                    <div class="span3 ">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="client">Nom du Client</label>
                                            <div class="controls">
                                                <input required="required" type="text" id="client_id" name="client" class="m-wrap span12" onkeyup="autocomplet()" />
                                                <ul id="client_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label class="control-label" for="cin">CIN</label>
                                            <div class="controls">
                                                <input required="required" type="text" id="cin" name="cin" class="m-wrap span12" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                       <div class="control-group">
                                           <label class="control-label" for="numeroTelefon1">Téléphone Client</label>
                                           <div class="controls">
                                               <input type="text" id="numeroTelefon1" name="numeroTelefon1" class="m-wrap span12" />
                                           </div>
                                       </div>
                                   	</div>
                                   	<div class="span3">
                                        <div class="control-group">
                                            <label class="control-label" for="mandataire">Mandataire</label>
                                            <div class="controls">
                                                <input type="text" id="mandataire" name="mandataire" class="m-wrap span12" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>Informations Source</h3>
                                <div class="row-fluid">
                                   	<div class="span4">
                                      <div class="control-group autocomplet_container">
                                         <label class="control-label" for="source_id">Source</label>
                                         <div class="controls">
                                            <input type="text" id="source_id" name="source" class="m-wrap span12" />
                                            <ul id="source_list_id"></ul>
                                         </div>
                                      </div>
                                   </div>
                                   <div class="span4">
                                      <div class="control-group">
                                         <label class="control-label" for="numeroTelefon2">Téléphone Source</label>
                                         <div class="controls">
                                            <input type="text" id="numeroTelefon2" name="numeroTelefon2" class="m-wrap span12" />
                                         </div>
                                      </div>
                                   </div>
                                   <div class="span4">
                                      <div class="control-group">
                                         <label class="control-label" for="montantSource">Montant Source</label>
                                         <div class="controls">
                                            <input type="text" id="montantSource" name="montantSource" class="m-wrap span12" />
                                         </div>
                                      </div>
                                   </div>  
                                </div>
                                <h3>Dates, heure et Nature de travail</h3>
                                <div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="natureTravail">Nature du travail</label>
                                            <div class="controls">
                                                <input type="text" id="natureTravail" name="natureTravail" class="m-wrap span12" onkeyup="autocomplet_nature()" />
                                                <ul id="nature_list"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label class="control-label" for="dateRdv">Date Rendez-Vous</label>
                                            <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
    		                                        <input name="dateRdv" id="dateRdv" class="m-wrap span12 m-ctrl-small date-picker" type="text" />
    		                                        <span class="add-on"><i class="icon-calendar"></i></span>
    		                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3">
                                        <div class="control-group">
                                            <label class="control-label" for="dateSortie">Date Sortie</label>
                                            <div class="controls">
                                                <div class="input-append date date-picker" data-date="" data-date-format="yyyy-mm-dd">
    			                                    <input name="dateSortie" id="dateSortie" class="m-wrap span12 m-ctrl-small date-picker" type="text" />
    			                                    <span class="add-on"><i class="icon-calendar"></i></span>
    			                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
    							        <label class="control-label">Heure RDV</label>
    								        <div class="controls">
    	                                        <div class="input-append">
    	                                            <input type="text" name="heureRdv" id="clockface_2" class="m-wrap span12 small"  readonly="" />
    	                                            <button class="btn" type="button" id="clockface_2_toggle-btn">
    	                                                <i class="icon-time"></i>
    	                                            </button>
    	                                        </div>
    	                                     </div>
    							        </div>
                                    </div>
                                </div>
                                <h3>Informations Zone</h3>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="province_id">Province</label>
                                            <div class="controls">
                                                <input type="text" id="province_id" name="province" class="m-wrap span12" onkeyup="autocomplet_province()" />
                                                <ul id="province_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="municipalite_id">M.P</label>
                                            <div class="controls">
                                                <input type="text" id="municipalite_id" name="mp" class="m-wrap span12" onkeyup="autocomplet_municipalite()" />
                                                <ul id="municipalite_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="commune_id">C.R</label>
                                            <div class="controls">
                                                <input type="text" id="commune_id" name="cr" class="m-wrap span12" onkeyup="autocomplet_commune()" />
                                                <ul id="commune_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="quartier_id">Quartier</label>
                                            <div class="controls">
                                                <input type="text" id="quartier_id" name="quartier" class="m-wrap span12" onkeyup="autocomplet_quartier()" />
                                                <ul id="quartier_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="sousquartier_id">Sous quartier</label>
                                            <div class="controls">
                                                <input type="text" id="sousquartier_id" name="sousquartier" class="m-wrap span12" onkeyup="autocomplet_sousquartier()" />
                                                <ul id="sousquartier_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2">
                                        <div class="control-group">
                                            <label class="control-label" for="propriete">Propriété dite</label>
                                            <div class="controls">
                                                <input type="text" id="propriete" name="propriete" class="m-wrap span12" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>Informations Topographe et Service</h3>
                                <div class="row-fluid">
                                    <div class="span2">
                                        <div class="control-group">
                                            <label class="control-label" for="cachet">Cachet</label>
                                            <div class="controls">
                                                <input type="checkbox" id="cachet" value="cachet" checked="checked" name="cachet" class="checkbox" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3 hiddenInput">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="topographe_id">Topographe</label>
                                            <div class="controls">
                                                <input type="text" id="topographe_id" name="topographe_id" class="m-wrap span12" onkeyup="autocomplet_topographe()" />
                                                <ul id="topographe_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2 hiddenInput">
                                        <div class="control-group">
                                            <label class="control-label" for="montantTopographe">Montant Topographe</label>
                                            <div class="controls">
                                                <input type="text" id="montantTopographe" name="montantTopographe" class="m-wrap span12" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3 hiddenInput">
                                        <div class="control-group autocomplet_container">
                                            <label class="control-label" for="service_id">Service</label>
                                            <div class="controls">
                                                <input type="text" id="service_id" name="service_id" class="m-wrap span12" onkeyup="autocomplet_service()" />
                                                <ul id="service_list_id"></ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span2 hiddenInput">
                                        <div class="control-group">
                                            <label class="control-label" for="montantService">Montant Service</label>
                                            <div class="controls">
                                                <input type="text" id="montantService" name="montantService" class="m-wrap span12" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>Informations Paiement</h3>
                          		<div class="row-fluid">
                                    <div class="span4">
                                        <div class="control-group">
                                            <label class="control-label" for="prix">Prix total</label>
                                            <div class="controls">
                                                <input type="text" id="prix" name="prix" class="m-wrap span12" value="0" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="control-group">
                                            <label class="control-label" for="paye">Payé</label>
                                            <div class="controls">
                                                <input type="text" id="paye" name="paye" class="m-wrap span12" value="0" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span4">
                                        <div class="control-group">
                                            <label class="control-label" for="reste">Reste</label>
                                            <div class="controls">
                                                <input type="text" id="reste" name="reste" class="m-wrap span12" value="0" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3>Archiver l'affaire</h3>
                                <div class="row-fluid"> 
                                    <div class="span3 ">
                                        <div class="control-group">
                                            <label class="control-label" for="archiver">Finir et archiver l'affaire</label>
                                            <div class="controls">
                                                <input type="checkbox" id="archiver" name="archiver" class="checkbox archiver_affaire" onchange="valueChanged()" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="span3 files_upload">
                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="file" name="documentsAffaire[]" multiple />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <input type="hidden" name="action" value="add" />
                                	<input type="hidden" id="idClient" name="idClient" />
                                	<input type="hidden" id="idTopographe" name="idTopographe" />
                                	<input type="hidden" id="idService" name="idService" />
                                	<input type="hidden" id="idSource" name="idSource" />
                                	<input type="hidden" id="idProvince" name="idProvince" />
                                	<input type="hidden" id="idMp" name="idMp" />
                                	<input type="hidden" id="idCr" name="idCr" />
                                	<input type="hidden" id="idQuartier" name="idQuartier" />
                                    <input type="hidden" id="idSousQuartier" name="idSousQuartier" />
                                    <a href="affaires.php" class="btn red"><i class="m-icon-swapleft m-icon-white"></i> Retour</a>
                                    <button type="submit" class="btn black"><i class="icon-save"></i> Enregistrer</button>
                                </div>
                             </form>
                        </div>
                   </div>
                </div>		
				<!-- END Charges TABLE PORTLET-->
				<!-- END PAGE CONTENT-->
			</div>
			<!-- END PAGE CONTAINER-->	
		</div>
		<!-- END PAGE -->	 	
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<div class="footer">
		<?= "2015 - ".date('Y') ?> &copy; TopEntreprise. Management Application.
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
	<script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/clockface/js/clockface.js"></script>
	<script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="assets/jquery-validation/jquery.validate.js"></script>
	<script type="text/javascript" src="script.js"></script>
	<script>
	$(function(){
        $('#prix, #paye').change(function(){
            var prix = parseFloat($('#prix').val());
            var paye = parseFloat($('#paye').val());
            var reste = 0;
            reste = prix - paye;
            $('#reste').val(reste);
        });
    });
    function valueChanged(){
	    if($('.archiver_affaire').is(":checked"))   
	        $(".files_upload").show();
	    else
	        $(".files_upload").hide();
	}
	$("#add-affaire-form").validate({
	    rules:{
           montantSource: {
               number: true
           },
           montantService: {
               number: true
           },
           montantTopographe: {
               number: true
           },
           prix: {
               number: true
           },
           paye: {
               number: true
           },
           reste: {
               number: true
           }
         },
         errorClass: "error-class",
         validClass: "valid-class"
    });
    </script>    
	<!-- ie8 fixes -->
	<!--[if lt IE 9]>
	<script src="assets/js/excanvas.js"></script>
	<script src="assets/js/respond.js"></script>
	<![endif]-->
	<script src="assets/js/app.js"></script>		
	<script>
		jQuery(document).ready(function() {
			$(".files_upload").hide();			
			// initiate layout and plugins
			App.setPage('calendar');
			App.init();
		});
	</script>
	<script>
	//hidden inputs source and topographe begin
	$(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).attr("value")=="cachet"){
                $(".hiddenInput").toggle();
            }
            if($(this).attr("value")=="archiver"){
                $(".hiddenUpload").toggle();
            }
        });
    });
	//hidden inputs source and topographe end
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