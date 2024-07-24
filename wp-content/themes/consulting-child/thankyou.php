<?php /* Template Name: Thank You */ ?>

<?php 
get_header();

$user_id = get_current_user_id();

if(!is_user_logged_in()){
echo "<script>window.location.href='".home_url()."'</script>";
die;
}

$ssn = get_user_meta($user_id,"ssn",true);


$current_user = wp_get_current_user();

$first_name = get_user_meta($user_id,"first_name",true);
$last_name = get_user_meta($user_id,"last_name",true);
$mobile = get_user_meta($user_id,"mobile",true);
$email = $current_user->user_email;
$api_data = get_apikey();
if(!isset($api_data['apikey'])){
  die("<span class='error_membership'>Womething went wrong with api</span>");
}
?>


<?php

$api_user_id = get_user_meta($user_id,"api_user_id",true);




if($api_user_id != ""){


/*
   $fname = get_user_meta($user_id,"fname",true);
   $lname = get_user_meta($user_id,"lname",true);
   $email = $current_user->user_email;;
   $ssn = get_user_meta($user_id,"ssn",true);
   $mobile = get_user_meta($user_id,"mobile",true);
   $dob = get_user_meta($user_id,"dob",true);
   $street1 = "122 RR 3 APT A";
   $city = get_user_meta($user_id,"city",true);
   $state = get_user_meta($user_id,"state",true);
   $zip = get_user_meta($user_id,"zip",true);*/
?>

<iframe
  id="sc-iframe" allowTransparency="true"
  src="https://wgt.stitchcredit.com/login-direct?key=<?php echo $api_data['apikey'];?>"
></iframe>
<script>
//this just throws in a default ID if one isn't provided on the query string
const id = '<?php echo $api_user_id;?>';
console.log("ID: ",id);
console.log("Loading Message Listener...");

window.addEventListener("message", receiveMessage, false);

function receiveMessage(event) {
    if(event && event.source && event.data) {
        // You only need to implement the types that are important/relevant to your use case.
        if(event.data.type === 'AUTH_REQUIRED') {
            //The iframe clident will post this message when a valid preauth token does not exist.
            //Posting a proper message with a preauth token in response allows the client to continue seemlessly.
            //console.log("Auth Required Event Received");
            const es = event.source;
            jQuery(".overlay_div").removeClass("overlay_hide");

              jQuery.ajax({
                  type : "post",
                  url : "<?php echo home_url();?>/wp-admin/admin-ajax.php",
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
           /* getData("<?php //echo home_url();?>/wp-admin/admin-ajax.php"+id, function() {
                const token = JSON.parse(this.responseText).token;
                console.log("Status: ", this.status, ", token: ", token);
                es.postMessage({type: 'PREAUTH', token: this.status == 200 ? token : null},"*");
            });*/
           
            //this code uses a test endpoint on the server to provide a preauth-token for any user ID without the usual hurdles.
            //This is ONLY for testing and does not exist in the production environment.
            /*getData("https://efx-dev.stitchcredit.com/api/test/preauth-token/"+id, function() {
                const token = JSON.parse(this.responseText).token;
                console.log("Status: ", this.status, ", token: ", token);
                es.postMessage({type: 'PREAUTH', token: this.status == 200 ? token : null},"*");
            });*/
        }
        else if(event.data.type === 'REG_STARTED') {
            const es = event.source;
            //only valid for full web implementation, Direct API already creates the customer, so this will never happen in those instances
			// (new Date().valueOf()) - is used to generate a new email ID on the fly for testing purposes
            es.postMessage({type: 'REG', data: {fname:"PAULINE", lname:"KOONZ", email: "<?php echo $email;?>"}},"*");
        }
        else if(event.data.type === 'IDENTITY_STARTED') {
            const es = event.source;
            //You could use this function to pre-populate the given fields.  DoB and SSN will never be prepopulated as it violates compliance
			// new Date().valueOf().toString() - generates a unique number for street2 to ensure each run goes through the full identity process, remove to test sequential sign up of the same user
            es.postMessage({type: 'IDENTITY', data: {street1:"", street2: "", city: "", state: "", zip: "", mobile: "<?php echo $mobile;?>"}},"*");
            //es.postMessage({type: 'IDENTITY', data: {street1:"122 RR 3 APT A", street2: new Date().valueOf().toString(), city: "SPRINGFIELD", state: "VA", zip: "22153", mobile: "<?php echo $mobile;?>"}},"*");
        }
        else if(event.data.type === 'LOGIN_SUCCESSFUL') {
            console.log("User succesfully logged in");
        }
        else if(event.data.type === 'LOGIN_FAILED') {
            //if you see this message more than a few times in a row, it's likely an issue
            //typically this will only occur for full web implementations, not Direct API
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
<?php } ?>
<?php // echo do_shortcode('[pms-payment-history]');?>

<?php get_footer();?>
<?php if($api_user_id == ""){ ?>

<!-- Modal -->
<div class="modal fade" id="stichModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Please fill the details</h4>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> -->
      </div>
      <form method="post" action="<?php echo home_url();?>/wp-admin/admin-ajax.php" id="formStitchcreditApi">
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
     //jQuery("#stichModal").modal("show");
     //

     jQuery("#formStitchcreditApi").submit(function(e){
          jQuery(".overlay_div").removeClass("overlay_hide");
          e.preventDefault();
            jQuery.ajax({
               type : "post",
               url : "<?php echo home_url();?>/wp-admin/admin-ajax.php",
               data : jQuery(this).serialize(),
               success: function(response) {
                  jQuery(".overlay_div").addClass("overlay_hide");
                  var result = jQuery.parseJSON(response);

                  if(result.error == 1){
                     swal("", result.msg, "warning");
                     jQuery("#stichModal").modal("show");
                  }else if(result.error == 0){
                     swal("", result.msg, "success");
                     window.location.href = "<?php echo home_url();?>/dashboard/";
                  }else{
                     swal("", "something went wrong", "error");
                     jQuery("#stichModal").modal("show");
                  }
                  
                }
            }); 
     });
     jQuery("#formStitchcreditApi").submit();
  });
  
</script>
<?php } ?>