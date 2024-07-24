<?php /* Template Name: Thank You Page - Credit Monitoring */ ?>

<?php 
    get_header();
?>
	<style>
		.icon-div{
			text-align: center;
		    font-size: 50px;
		    color: #3441a4;
		    margin-top: 20px;
		    font-weight: 600;
		}
		.dashboard-btn{
			text-align: center;
			margin-bottom: 40px;
			margin-top: 20px;
		}
		.dashboard-btn a{
			font-size: 18px;
		    font-weight: 600;
		    fill: #3441A4;
		    color: #3441A4;
		    background-color: #FEC14C;
		    padding: 10px 25px;
		    border-radius: 3px;
		}
		.dashboard-btn a:hover{
			color: #FEC14C;
		    background-color: #3441A4;
		}
	</style>
<?php
    $lang = "";
    if(isset($_GET['lang']) && $_GET['lang'] == "es"){
    	$lang = $_GET['lang'];
?>
		<div class="contact-thanks es">
			<h1 class="thanks-title">Gracias por registrarse en Credit Montoring</h1>
			<div class="icon-div">
				<i aria-hidden="true" class=" stm-mail_13"></i>
			</div>
			<h5>Gracias por participar <span style="color:#FEC14C;">Monitoreo de crédito</span>. Puede acceder al programa Credit Montoring desde su panel de control.</h5> 
		 	<div class="contact-data row">
		 	</div>
		 	<div class="dashboard-btn">
		 		<a href="https://www.premierconsumer.org/dashboard/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Ir al panel de control</a>
		 	</div>
		</div>
<?php
    }else{
?>
		<div class="contact-thanks">
			<h1 class="thanks-title">Thanks for Registering with Credit Monitoring</h1>
			<div class="icon-div">
				<i aria-hidden="true" class=" stm-mail_13"></i>
			</div>
			<h5>Thanks for joining <span style="color:#FEC14C;">Credit Monitoring</span>. You can access Credit Monitoring program into your Dashboard.</h5> 
		 	<div class="contact-data row">
		 	</div>
		 	<div class="dashboard-btn">
		 		<a href="https://www.premierconsumer.org/dashboard/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go to Dashboard</a>
		 	</div>
		</div> 	
<?php 
	}
	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		$api_data = get_apikey();
	    $api_user_id = get_user_meta($user_id,"api_user_id",true);
	    $first_name = get_user_meta($user_id,"first_name",true);
	    $last_name = get_user_meta($user_id,"last_name",true);
	    $mobile = get_user_meta($user_id,"mobile",true);
	    $mobile = ($mobile != "") ? str_replace(array( '(', ')', '-', ' ' ), '', $mobile) : "";
	    $email = $current_user->user_email;
	    if($api_user_id == ""){
?>
		<!-- Modal -->
		<div class="modal fade" id="stichModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header text-center">
						<h4 class="modal-title w-100 font-weight-bold">Please fill the details</h4>
					</div>
					<form method="post" action="<?php echo $home_link;?>/wp-admin/admin-ajax.php" id="formStitchcreditApi">
						<input type="hidden" name="action" value="saveStitchcreditApi">
						<div class="modal-body mx-3">
							<div class="row">
								<div class="col-md-6">
									<div class="md-form mb-5">
										<label data-error="wrong" data-success="right" for="orangeForm-fname">First Name</label>
										<input type="text" id="orangeForm-name" class="form-control validate" name="fname" value="<?php echo $first_name;?>" required="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form mb-5">
										<label data-error="wrong" data-success="right" for="orangeForm-lname">Last Name</label>
										<input type="text" id="orangeForm-lname" class="form-control validate" name="lname"  value="<?php echo $last_name;?>" required="">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="md-form mb-5">
										<label data-error="wrong" data-success="right" for="orangeForm-ssn">Email</label>
										<input type="email" id="orangeForm-email" class="form-control validate" value="<?php echo $email;?>" required="" readonly="">
									</div>
								</div>
								<div class="col-md-6">
									<div class="md-form mb-5">
										<label data-error="wrong" data-success="right" for="orangeForm-mobile">Mobile</label>
										<input type="number" id="orangeForm-mobile" class="form-control validate" name="mobile" value="<?php echo $mobile;?>" required="" >
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer d-flex justify-content-center">
							<button class="btn btn-deep-orange">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="overlay_div overlay_hide">
			<div class="overlay__inner">
				<div class="overlay__content"><span class="spinner"></span></div>
			</div>
		</div>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#formStitchcreditApi").submit(function(e){
					jQuery(".overlay_div").removeClass("overlay_hide");
					e.preventDefault();
					jQuery.ajax({
						type : "post",
						url : "<?php echo $home_link;?>/wp-admin/admin-ajax.php",
						data : jQuery(this).serialize(),
						success: function(response) {
							jQuery(".overlay_div").addClass("overlay_hide");
							var result = jQuery.parseJSON(response);
							if(result.error == 1){
								swal("", result.msg, "warning");
								jQuery("#stichModal").modal("show");
							}else if(result.error == 0){
								swal("", result.msg, "success");
								window.location.reload();
							}else{
								swal("", "something went wrong", "error");
								jQuery("#stichModal").modal("show");
							}
						}
					}); 
				});

				if(jQuery("#formStitchcreditApi").length > 0){
					jQuery("#formStitchcreditApi").submit();
				}
			});             
		</script>
<?php
		}
	}
	get_footer();
?>