<?php

if(!is_user_logged_in()){
    echo "<script>window.location.href='".home_url()."'</script>";
    die;
}

$home_link = substr(home_url(), 0, strpos(home_url(), '?'));

$credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22873","22874","22875");
$user_id = get_current_user_id();
$current_user = wp_get_current_user();
$member = pms_get_member( $user_id );
$site_lang = apply_filters( 'wpml_current_language', null );

$show_banner = false;
if(!pms_is_member_of_plan( $credit_monitoring_plans, $user_id )){
    $show_banner = true;
}

if(isset($member->subscriptions)){
    $user_plans = $member->subscriptions;
    /* user data - start */
    $first_name = get_user_meta($user_id,"first_name",true);
    $last_name = get_user_meta($user_id,"last_name",true);
    $mobile = get_user_meta($user_id,"mobile",true);
    $mobile = ($mobile != "") ? str_replace(array( '(', ')', '-', ' ' ), '', $mobile) : "";
    $email = $current_user->user_email;
    /* user data - end */

    if(count($user_plans) > 0){
        foreach (array_reverse($user_plans) as $key => $user_plan) {
            if($user_plan['status'] != "active"){
                if(count($user_plans) == 1){
                    echo("<span class='error_membership'>You did not have any active membership plan</span>");
                }
                continue;
            }

            $plan_id = $user_plan['subscription_plan_id'];
            $credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22873","22874","22875");
            $cim_plans = array("19780","16638","16637");
            $mim_plans = array("19781","16370","16336");
            /* credit monitoring */
            if(in_array($plan_id, $credit_monitoring_plans)){
                $api_data = get_apikey();
                $api_user_id = get_user_meta($user_id,"api_user_id",true);
                if($api_user_id != "" && isset($api_data['apikey'])){
?>
                    <iframe id="sc-iframe" allowTransparency="true" src="https://wgt.stitchcredit.com/login-direct?key=<?php echo $api_data['apikey'];?>"></iframe>
                    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
                    <script>
                        const id = '<?php echo $api_user_id;?>';
                        window.addEventListener("message", receiveMessage, false);
                        function receiveMessage(event) {
                            if(event && event.source && event.data) {
                                if(event.data.type === 'AUTH_REQUIRED') {
                                    const es = event.source;
                                    jQuery(".overlay_div").removeClass("overlay_hide");

                                      jQuery.ajax({
                                          type : "post",
                                          url : "<?php echo $home_link;?>/wp-admin/admin-ajax.php",
                                          data : {"action":"get_pretoken",id:id},
                                          success: function(response) {
                                             
                                              jQuery(".overlay_div").addClass("overlay_hide");
                                              var result = jQuery.parseJSON(response);
                                              if(result.error == 1){
                                                 swal("", result.msg, "warning");
                                              }else if(result.error == 0){
                                                 es.postMessage({type: 'PREAUTH', token: result.token},"*");
                                              }else{
                                                 swal("", "something went wrong", "error");
                                                 jQuery("#stichModal").modal("show");
                                              }
                                              
                                          }
                                      }); 
                                }
                                else if(event.data.type === 'REG_STARTED') {
                                    const es = event.source;
                                    es.postMessage({type: 'REG', data: {fname:"PAULINE", lname:"KOONZ", email: "<?php echo $email;?>"}},"*");
                                }
                                else if(event.data.type === 'IDENTITY_STARTED') {
                                    const es = event.source;
                                    es.postMessage({type: 'IDENTITY', data: {fname: "<?php echo $first_name; ?>", lname: "<?php echo $last_name; ?>", email: "<?php echo $email; ?>", street1:"", street2: "", city: "", state: "", zip: "", mobile: "<?php echo $mobile;?>"}},"*");
                                }
                                else if(event.data.type === 'LOGIN_SUCCESSFUL') {
                                    console.log("User succesfully logged in");
                                }
                                else if(event.data.type === 'LOGIN_FAILED') {
                                    console.log("User login failed");
                                }
                                else if(event.data.type === 'USER_ENROLLED') {
                                    //User successfully completed identity and has been enrolled for consumer data
                                    console.log("User enrollment successful");
                                }
                                else if(event.data.type === 'IDENTITY_FAILED') {
                                    setTimeout(function(){
                                        window.location.reload();
                                    },5000)
                                    //Identity process failed, user is likely "stuck" as they cannot continue
                                    console.log("User identity failure");
                                }
                                else if(event.data.type === 'SERVICE_FAILURE') {
                                    //Identity process failed most likely due to a service outage, but the user is stuck as they cannot continue without passing identity
                                    console.log("Identity service failure");
                                }
                            }

                            function getData(req, action) {
                                var xhr = new XMLHttpRequest();
                                xhr.responesType = 'json';
                                xhr.onload = action;
                                xhr.open("GET", req);
                                xhr.setRequestHeader("Content-Type", "application/json");
                                xhr.send();
                            }

                        }
                    </script>
<?php
                    }
                    if($api_user_id == ""){
?>
                    <!-- Modal -->
                    <div class="modal fade" id="stichModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                      aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
            /* credit monitoring */

            /* CIM subscription Data - Start */
            if(in_array($plan_id, $cim_plans)){
                $type = "cim";
                $account_type = get_user_meta($user_id, $type."_account_type", true);
                $cm_sp_user = $cm_sp_pass = "";
                $cm_user = get_user_meta($user_id, $type."_user_id", true);
                $cm_pass = get_user_meta($user_id, $type."_user_password", true);
                if($account_type == "joint"){
                    $cm_sp_user =  get_user_meta($user_id, $type."_sp_user_id", true);
                    $cm_sp_pass = get_user_meta($user_id, $type."_sp_user_password", true);
                }
                $cm_className = "Prefiling Credit Counseling";
                $cm_login_link = "http://www.counselinginmotion.com/studentlogin.php";
                if($site_lang == "es"){
?>
                    <p class="class-msg">Se ha registrado correctamente en nuestro <span style="text-decoration: underline;font-weight: 600;"><?php echo $cm_className; ?></span> clase de Educación. <a href="<?php echo $cm_login_link; ?>" target="_blank" style="color: #fec14c;">Haga clic aquí para iniciar sesión</a> con el siguiente nombre de usuario y contraseña.<br><br>
                    <span class="highlight-login">UserID</span> : <?php echo $cm_user; ?><br>
                    <span class="highlight-login">Contraseña</span> : <?php echo $cm_pass; ?><br>
<?php
                    if($cm_sp_user != ""){
?>
                        <br>
                        <span>Aquí está el ID de inicio de sesión del cónyuge y la contraseña.</span><br>
                        <span class="highlight-login">ID de usuario del cónyuge</span> : <?php echo $cm_sp_user; ?><br>
                        <span class="highlight-login">Contraseña del cónyuge</span> : <?php echo $cm_sp_pass; ?><br>
<?php
                    }
                    echo "</p>";
                }else{
?>
                    <p class="class-msg">You have successfully register for our <span style="text-decoration: underline;font-weight: 600;"><?php echo $cm_className; ?></span> Education class. <a href="<?php echo $cm_login_link; ?>" target="_blank" style="color: #fec14c;">Click here to login</a> with the following User ID and Password.<br><br>
                    <span class="highlight-login">UserID</span> : <?php echo $cm_user; ?><br>
                    <span class="highlight-login">Password</span> : <?php echo $cm_pass; ?><br>
<?php
                    if($cm_sp_user != ""){
?>
                        <br><span>Here is Spouse Login ID and Password.</span><br>
                        <span class="highlight-login">Spouse UserID</span> : <?php echo $cm_sp_user; ?><br>
                        <span class="highlight-login">Spouse Password</span> : <?php echo $cm_sp_pass; ?><br>
<?php            
                    }
                    echo '</p>';
                }
            }
            /* CIM subscription Data - End */

            /* MIM subscription Data - Start */
            if(in_array($plan_id, $mim_plans)){
                $type = "mim";
                $account_type = get_user_meta($user_id, $type."_account_type", true);
                $mim_sp_user = $mim_sp_pass = "";
                $mim_user = get_user_meta($user_id, $type."_user_id", true);
                $mim_pass = get_user_meta($user_id, $type."_user_password", true);
                if($account_type == "joint"){
                    $mim_sp_user =  get_user_meta($user_id, $type."_sp_user_id", true);
                    $mim_sp_pass = get_user_meta($user_id, $type."_sp_user_password", true);
                }
                $mim_className = "Debtor";
                $mim_login_link = "https://www.moneyinmotion.us/studentlogin.php";
                if($site_lang == "es"){
?>
                    <p class="class-msg">Se ha registrado correctamente en nuestro <span style="text-decoration: underline;font-weight: 600;"><?php echo $mim_className; ?></span> clase de Educación. <a href="<?php echo $mim_login_link; ?>" target="_blank" style="color: #fec14c;">Haga clic aquí para iniciar sesión</a> con el siguiente nombre de usuario y contraseña.<br><br>
                    <span class="highlight-login">UserID</span> : <?php echo $mim_user; ?><br>
                    <span class="highlight-login">Contraseña</span> : <?php echo $mim_pass; ?><br>
<?php
                    if($mim_sp_user != ""){
?>
                        <br>
                        <span>Aquí está el ID de inicio de sesión del cónyuge y la contraseña.</span><br>
                        <span class="highlight-login">ID de usuario del cónyuge</span> : <?php echo $mim_sp_user; ?><br>
                        <span class="highlight-login">Contraseña del cónyuge</span> : <?php echo $mim_sp_pass; ?><br>
<?php
                    }
                    echo "</p>";
                }else{
?>
                    <p class="class-msg">You have successfully register for our <span style="text-decoration: underline;font-weight: 600;"><?php echo $mim_className; ?></span> Education class. <a href="<?php echo $mim_login_link; ?>" target="_blank" style="color: #fec14c;">Click here to login</a> with the following User ID and Password.<br><br>
                    <span class="highlight-login">UserID</span> : <?php echo $mim_user; ?><br>
                    <span class="highlight-login">Password</span> : <?php echo $mim_pass; ?><br>
<?php
                    if($mim_sp_user != ""){
?>
                        <br><span>Here is Spouse Login ID and Password.</span><br>
                        <span class="highlight-login">Spouse UserID</span> : <?php echo $mim_sp_user; ?><br>
                        <span class="highlight-login">Spouse Password</span> : <?php echo $mim_sp_pass; ?><br>
<?php            
                    }
                    echo '</p>';               
                }
            }
            /* MIM subscription Data - End */
        }
    }else{
        // no plan
        echo("<span class='error_membership'>You did not have any membership plan</span>");
    }
}else{
    // no plan
    echo("<span class='error_membership'>You did not have any membership plan</span>");
}
