<?php

add_action( 'wp_enqueue_scripts', 'consulting_child_enqueue_parent_styles');
function consulting_child_enqueue_parent_styles() {
	wp_enqueue_style( 'consulting-style', get_template_directory_uri() . '/style.css', array( 'bootstrap' ), CONSULTING_THEME_VERSION, 'all' );
	
    $skin = get_theme_mod('site_skin', 'skin_default');
    if ($skin == 'skin_default') {
        wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'consulting-layout' ), CONSULTING_THEME_VERSION, 'all' );
    } else {
        wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( 'consulting-layout', 'stm-skin-custom-generated' ), CONSULTING_THEME_VERSION, 'all' );
    }
}

// retrieves the attachment ID from the file URL
function pippin_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
    return $attachment[0]; 
}
// generate meta tags based on post content
function pc_auto_meta_tags() {
	if (is_single()) {
		global $post;
		$meta_description = substr(strip_tags($post->post_content), 0, 155);
		echo '<meta name="description" content="' . $meta_description . '">' . "\n";
	}
}
add_action('wp_head', 'pc_auto_meta_tags');

function add_gtag_to_head() {
    get_template_part( 'templates/include_head' );
} 
add_action('wp_head', 'add_gtag_to_head');

function add_gtag_to_body() { 
    get_template_part( 'templates/include_body' );
 } 
add_action('wp_body_open', 'add_gtag_to_body');

function add_gtag_to_footer() { 
    get_template_part( 'templates/footer_section' );
}
add_action('wp_footer', 'add_gtag_to_footer');

add_action( 'wp_ajax_nopriv_get_pretoken', 'get_pretoken' );
add_action( 'wp_ajax_get_pretoken', 'get_pretoken' );
function get_pretoken(){
	$token = get_token();
	if(isset($token->token)){
		$get_get_preauthtoken = get_preauthtoken($token->token,$_POST["id"]);
		if(isset($get_get_preauthtoken->token)){
			$data_message = array("error"=>0,"token"=>$get_get_preauthtoken->token);
			echo json_encode($data_message);
			die;
		}else{
			$data_message = array("error"=>1,"msg"=>"Token error");
			echo json_encode($data_message);
			die;
		}
	}else{
   	    $data_message = array("error"=>1,"msg"=>"Token error");
		echo json_encode($data_message);
        die;
    }
}

function get_preauthtoken($token,$id){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/direct/preauth-token/'.$id);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer '.$token;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result3= json_decode($result);
}


add_action( 'wp_ajax_nopriv_saveStitchcreditApi', 'saveStitchcreditApi' );
add_action( 'wp_ajax_saveStitchcreditApi', 'saveStitchcreditApi' );
function saveStitchcreditApi() {
	$error = "";
	if($_POST["fname"] == ""){
		$error = "First Name field is required";
	}
	if($_POST["lname"] == ""){
		$error = "Last Name field is required";
	}
	/*if($_POST["ssn"] == "" || strlen($_POST["ssn"]) != 9){
		$error = "Social security number must contain 9 characters.";
	}*/
	if($_POST["mobile"] == ""){
		$error = "Mobile field is required";
	}
	/*if($_POST["dob"] == ""){
	    $error = "DOB field is required";
	}
	if($_POST["street1"] == ""){
		$error = "Address field is required";
	}
	if($_POST["city"] == ""){
		$error = "City field is required";
	}
	if($_POST["state"] == ""){
		$error = "State field is required";
	}
	if($_POST["zip"] == "" || strlen($_POST["zip"]) != 5){
		$error = "Zip field must contain 5 numbers.";
	}*/
	if($error != ""){
		$data_message = array("error"=>1,"msg"=>$error);
		echo json_encode($data_message);
        die;
	}
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$email = $current_user->user_email;
	$fname = $_POST["fname"];
	$lname = $_POST["lname"];
	$mobile = $_POST["mobile"];

	// print_r($email);die;

	if(!empty($mobile)){
		update_user_meta($user_id,"mobile",$mobile,false);
	}
	/*$ssn = $_POST["ssn"];
	$mobile = $_POST["mobile"];
	$dob = $_POST["dob"];
	$city = $_POST["city"];
	$state = $_POST["state"];
	$zip = $_POST["zip"];*/

	/*update_user_meta($user_id,$fname,false);
	update_user_meta($user_id,$lname,false);
	update_user_meta($user_id,$ssn,false);
	update_user_meta($user_id,$mobile,false);
	update_user_meta($user_id,$dob,false);
	update_user_meta($user_id,$city,false);
	update_user_meta($user_id,$state,false);
	update_user_meta($user_id,$zip,false);*/

    $user_meta = get_user_meta($user_id);
    $token = get_token();
    $user_token = $token->token;

    if(isset($token->token)){
    	$reg_user = reg_user($fname,$lname,$email,$mobile,$user_token);

	   	  // print_r($user_token);die;
	   	  // print_r($reg_user);die;
    	if(isset($reg_user->userId)){
    		update_user_meta($user_id,"api_user_id",$reg_user->userId,false);
    		$api_user_id = $reg_user->userId;
    		$data_message = array("error"=>0,"msg"=>"Successfully register with stitchcredit api!");
    		echo json_encode($data_message);
    		die;
    	}elseif(isset($reg_user->messages) && isset($reg_user->messages[0])){
    		$data_message = array("error"=>1,"msg"=>$reg_user->messages[0]);
    		echo json_encode($data_message);
    		die;
    	}else{
    		$data_message = array("error"=>1,"msg"=>"Something went wrong");
    		echo json_encode($data_message);
    		die;
    	}
    }else{
   	    $data_message = array("error"=>1,"msg"=>"Token error");
		echo json_encode($data_message);
        die;
    }
    $data_message = array("error"=>1,"msg"=>"Something went wrong");
    echo json_encode($data_message);
    die;
}


function get_apikey(){
	$user_id = get_current_user_id();
	$user_meta = get_user_meta($user_id);
	$member = pms_get_member( pms_get_current_user_id() );
	$subscription_plan_id = "";

	if(isset($member->subscriptions) && !empty($member->subscriptions)){
		$user_plans = $member->subscriptions;
		if(count($user_plans) > 0){
        	foreach ($user_plans as $key => $user_plan) {
        		if($user_plan['status'] == "active"){
	        		$plan_id = $user_plan['subscription_plan_id'];
		            // $credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953");
		            $credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22873","22874","22875");
		            if(in_array($plan_id, $credit_monitoring_plans)){
		            	$subscription_plan_id = $plan_id;
		            	break;
		            }
		        }
        	}
        }
        if($subscription_plan_id != ""){
	        $same_plan = getSamePlan($subscription_plan_id);	        
        	if($subscription_plan_id == "7231" || $subscription_plan_id == "22875" || $same_plan == "7231"){
	        	return  $post = array(
						    'apikey' => '8e95a79a-7cf8-43ce-85f8-cca0eb970ed9',
						    'secret' => '952c71fc-44fb-4053-98d6-af9eaeaeaf2c'
						);
	        }elseif($subscription_plan_id == "7769" || $subscription_plan_id == "11984" || $subscription_plan_id == "22874" || $same_plan == "11984"){
	        	return  $post = array(
						    'apikey' => '36ec996d-e50a-414f-9059-88801116c528',
						    'secret' => '6a6100f3-f04a-4948-a66e-8047ae2a2922'
						);
	        }elseif($subscription_plan_id == "7770" || $subscription_plan_id == "12039" || $subscription_plan_id == "22873" || $same_plan == "12039"){
	        	return  $post = array(
						    'apikey' => '2459fd6a-565b-4049-87c3-d7e1e634aa10',
						    'secret' => 'a5daacea-4fb8-41f3-aa49-a13f360177b8'
						);
	        }else{
	        	return  $post =array();
	        }	         
        }      
	}else{
		echo("<span class='error_membership'>You did not have any membership plan</span>");
	}
}


function reg_user($fname,$lname,$email,$mobile,$token){
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/direct/user-reg');
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_POST, 1);
	$mobile = str_replace(array( '(', ')', '-', ' ' ), '', $mobile);
	$post = array(
	    'email' => $email,
	    'fname' => $fname,
	    'lname' => $lname,
	    'mobile' => $mobile
	);

	curl_setopt($ch1, CURLOPT_POSTFIELDS, json_encode($post));
	$headers = array();
	$headers[] = 'Accept: */*';
	$headers[] = 'Content-Type: application/json';
	$headers[] = 'Authorization: Bearer '.$token;
	curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
	$result2 = curl_exec($ch1);
	if (curl_errno($ch1)) {
	    echo 'Error:' . curl_error($ch1);
	}
	$httpcode = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
	curl_close($ch1);
	return $result2 = json_decode($result2);
}

function get_token(){
	$api_data = get_apikey();
	if(!isset($api_data['apikey'])){
	  die("Something went wrong with api");
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/direct/login');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	$post = array(
	    'apikey' => $api_data['apikey'],
	    'secret' => $api_data['secret']
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result1 = json_decode($result);
}
add_action( 'pms_register_form_validation',"extra_field_validation" );

function extra_field_validation(){
	if (!isset($_POST['mobile']) || empty($_POST['mobile']))
            pms_errors()->add('mobile', __('Please enter a Mobile number.', 'paid-member-subscriptions'));
    if (!isset($_POST['first_name']) || empty($_POST['first_name']))
            pms_errors()->add('first_name', __('Please enter a First name.', 'paid-member-subscriptions'));
    if (!isset($_POST['last_name']) || empty($_POST['last_name']))
            pms_errors()->add('last_name', __('Please enter a Last name.', 'paid-member-subscriptions'));  
        // Stop if there are errors
    if( count( pms_errors()->get_error_messages() ) > 0 )
        return false;
    else
        return true;      
}

add_action( 'pms_register_form_after_create_user', "after_create_user" );
function after_create_user($user_data){
	$site_lang = apply_filters( 'wpml_current_language', null );
	if(isset($user_data["user_id"])){
		$sub_id = $user_data['subscriptions'][0];
	    $user_id = $user_data["user_id"];
		update_user_meta($user_data["user_id"],"mobile",$_POST["mobile"],false);
		if(isset($_POST['person'])){
		    update_user_meta($user_data["user_id"],"no_of_person",$_POST["person"],false);
		}
		if(isset($_POST['clang'])){
		    update_user_meta($user_data["user_id"],"course_language",$_POST["clang"],false);
		}
		if(isset($_POST['creason'])){
		    update_user_meta($user_data["user_id"],"course_reason",$_POST["creason"],false);
		}
		if(isset($_POST['bdistrict'])){
		    update_user_meta($user_data["user_id"],"bankruptcy_district",$_POST["bdistrict"],false);
		}
		if(isset($_POST['bnumber'])){
		    update_user_meta($user_data["user_id"],"bankruptcy_number",$_POST["bnumber"],false);
		}
		/* spouse data */
		if(isset($_POST['sp_fname']) && $_POST['sp_fname'] != ""){
			update_user_meta($user_data["user_id"],"sp_fname",$_POST['sp_fname'],false);
		}
		if(isset($_POST['sp_lname']) && $_POST['sp_lname'] != ""){
			update_user_meta($user_data["user_id"],"sp_lname",$_POST['sp_lname'],false);
		}
		if(isset($_POST['sp_mobile']) && $_POST['sp_mobile'] != ""){
			update_user_meta($user_data["user_id"],"sp_mobile",$_POST['sp_mobile'],false);
		}
		if(isset($_POST['sp_email']) && $_POST['sp_email'] != ""){
			update_user_meta($user_data["user_id"],"sp_email",$_POST['sp_email'],false);
		}
		/* spouse data - end */
		if(isset($_POST['tclass'])){
			if($_POST['tclass'] == "single"){
				update_user_meta($user_data["user_id"],"account_type","single",false);
			}else{
				update_user_meta($user_data["user_id"],"account_type","joint",false);				
			}
		}
		if(isset($_POST['yearly_income'])){
		    update_user_meta($user_data["user_id"],"yearly_income",$_POST["yearly_income"],false);
		    update_user_meta($user_data["user_id"],"verification_status",'pending',false);
		    /* send email to admin */
    		$edit_profile_link = $user_data["user_id"];
		    $user_name = $user_data['user_email'];
		    $sub = 'New Document on shareFile - Premier Consumer';
            $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
            $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
            $content = str_replace(
                array('%user_name%', '%edit_profile_link%'),
                array($user_name,    $edit_profile_link),
                file_get_contents(get_stylesheet_directory().'/templates/admin_verification_alert.php')
            );
            wp_mail('jglen@premierconsumer.org', $sub, $content, $mheaders); // here admin email - shradha.sohal@xcelance.com
		}

		if(isset($_POST['clang']) && isset($_POST['course_type'])){
			if($sub_id == "16637" || $sub_id == "16336"){
				$fee = '0.00';
				$sdata = true;
			}elseif($sub_id == "19780" || $sub_id == "19781"){
				$fee = '75.00';
				$sdata = true;
			}elseif($sub_id == "16638" || $sub_id == "16370"){
				$fee = '50.00';
				$sdata = true;
			}
			if($_POST['sp_fname'] != "" && isset($_POST['sp_fname'])){
				$sdata = true;
			}else{
				$sdata = false;
			}

            $user_email = $_POST['user_email'];
            $first_name = ucfirst($_POST['first_name']);
            $last_name = ucfirst($_POST['last_name']);
            $current_date = date('d-m-Y');
            $courseLanguage = $_POST['clang'];
            $courseReason   = $_POST['creason'];
            $BankruptcyDistrict = isset($_POST['bdistrict']) ? $_POST['bdistrict'] : "";
            $BankruptcyNumber   = isset($_POST['bnumber']) ? $_POST['bnumber'] : "";
            $phoneNumber   = $_POST['mobile'];
            $courseName = $_POST['course_type'];
            if($site_lang == "es"){
            	if($courseName == 'CIMBK'){
		            $mail_tmp_title = 'Asesoramiento crediticio';
		            $mail_tmp_t_content = 'Asesoría de crédito antes de la declaración de Bancarrota';
		            $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
		        }else {
		            $mail_tmp_title = 'Deudor';
		            $mail_tmp_t_content = 'Deudor';
		            $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
		        }
            }else{
	            if($courseName == 'CIMBK'){
	                $mail_tmp_title = 'Credit Counseling';
	                $mail_tmp_t_content = 'Prefiling Credit Counseling';
	                $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
	            }else {
	                $mail_tmp_title = 'Debtor';
	                $mail_tmp_t_content = 'Debtor';
	                $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
	            }
	        }
            // if($courseLanguage != '' && $courseReason != '' && $BankruptcyDistrict != '' && $BankruptcyNumber != ''){
            if($courseLanguage != ''){
            	if($sdata){
            		$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>'.$courseName.'</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason>'.$courseReason.'</CourseReason><CourseLanguage>'.$courseLanguage.'</CourseLanguage></CourseInformation><StudentInformation><LastName>'.$last_name.'</LastName><FirstName>'.$first_name.'</FirstName><Phone>'.$phoneNumber.'</Phone><Email>'.$user_email.'</Email><Fee>'.$fee.'</Fee><BankruptcyDistrict>'.$BankruptcyDistrict.'</BankruptcyDistrict><BankruptcyNumber>'.$BankruptcyNumber.'</BankruptcyNumber><Together>Y</Together><SpouseLastName>'.ucfirst($_POST['sp_lname']).'</SpouseLastName><SpouseFirstName>'.ucfirst($_POST['sp_fname']).'</SpouseFirstName><SpousePhone>'.$_POST['sp_mobile'].'</SpousePhone><SpouseEmail>'.$_POST['sp_email'].'</SpouseEmail><SpouseFee>'.$fee.'</SpouseFee></StudentInformation></ACCERequest>';
            	}else{
                	$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>'.$courseName.'</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason>'.$courseReason.'</CourseReason><CourseLanguage>'.$courseLanguage.'</CourseLanguage></CourseInformation><StudentInformation><LastName>'.$last_name.'</LastName><FirstName>'.$first_name.'</FirstName><Phone>'.$phoneNumber.'</Phone><Email>'.$user_email.'</Email><Fee>'.$fee.'</Fee><BankruptcyDistrict>'.$BankruptcyDistrict.'</BankruptcyDistrict><BankruptcyNumber>'.$BankruptcyNumber.'</BankruptcyNumber></StudentInformation></ACCERequest>';
                }
                $url = "https://www.acce-online.com/pp/0433/parseuser_xml.php"; // URL                 

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlstring=" . $xml);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                $xmlResponse = (array)simplexml_load_string($data);
                if($xmlResponse['RequestOK'] == 'Y'){
                    // send email & send data to MIM
                    $b = (array)$xmlResponse['Bankruptcy'];
                    $login_id =  $b['UserID'];
                    $login_pass = $b['PassWord'];
                    /* 01-dec code start */
                    if($courseName == 'CIMBK'){
                    	$meta = "cim";
                    }else{
                    	$meta = "mim";
                    }
                    update_user_meta($user_id, $meta."_user_id",$login_id,false);
                    update_user_meta($user_id, $meta."_user_password",$login_pass,false);
                    if($sdata){
                    	$slogin_id = $b['SpouseUserID'];
                    	$slogin_pass = $b['SpousePassWord'];
                    	update_user_meta($user_id, $meta."_sp_user_id",$slogin_id,false);
                    	update_user_meta($user_id, $meta."_sp_user_password",$slogin_pass,false);
                    	update_user_meta($user_id, $meta."_account_type","joint",false);
                    }else{
                    	update_user_meta($user_id, $meta."_account_type","single",false);
                    }
                    /* 01-dec code end */
                    // update_user_meta($user_id,"mim_user_id",$login_id,false);
                    // update_user_meta($user_id,"mim_user_password",$login_pass,false);
                    // if($sdata){
                    // 	$slogin_id = $b['SpouseUserID'];
                    // 	$slogin_pass = $b['SpousePassWord'];
                    // 	update_user_meta($user_id,"mim_sp_user_id",$slogin_id,false);
                    // 	update_user_meta($user_id,"mim_sp_user_password",$slogin_pass,false);
                    // 	update_user_meta($user_id,"account_type","joint",false);
                    // }else{
                    // 	update_user_meta($user_id,"account_type","single",false);
                    // }
                    if($site_lang == "es"){
                    	$sub = $mail_tmp_title.' acceso a la educación - Premier Consumer';
                    }else{
	                    $sub = $mail_tmp_title.' Education Login - Premier Consumer';
                    }
                    $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
                    $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
                    if($site_lang == "es"){
                    	if($sdata){
	                    	$content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%mim_suser%', '%mim_spass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $slogin_id, $slogin_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/smim_register_user_es.php')
		                    );
	                    }else{
		                    $content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/mim_register_user_es.php')
		                    );
		                }
                    }else{
	                    if($sdata){
	                    	$content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%mim_suser%', '%mim_spass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $slogin_id, $slogin_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/smim_register_user.php')
		                    );
	                    }else{
		                    $content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/mim_register_user.php')
		                    );
		                }
		            }
                    $send_email = wp_mail($user_email, $sub, $content, $mheaders);
                }
            }
        }
        send_register_mail_to_admin($user_data);
	}
}

function send_register_mail_to_admin($user_data){
	$site_lang = apply_filters( 'wpml_current_language', null );
	$plan_name = $program = "";
	$user_id = $user_data["user_id"];
	$user = get_user_by( 'id', $user_id );
	$name = $user->data->display_name;
	$email = $user->data->user_email;
	$phone = get_user_meta( $user_id, "mobile", true );
	$household = get_user_meta($user_id, "no_of_person", true);
	$income =  get_user_meta($user_id, "yearly_income", true);
	$reason =  get_user_meta($user_id, "course_reason", true);
	$bnumber =  get_user_meta($user_id, "bankruptcy_number", true);
	$f_name = get_user_meta($user_id, "sp_fname", true);
	$l_name = get_user_meta($user_id, "sp_lname", true);
	$s_email = get_user_meta($user_id, "sp_email", true);
	$s_mob = get_user_meta($user_id, "sp_mobile", true);
	if($s_email !== null && $s_email !== false && $s_email != ""){
		$s_name = $f_name.' '.$l_name;
		$sp_data = true;
	}
	$content = $sub_id = "";
	if(isset($user_data['subscriptions'])){
		$sub_id = $user_data['subscriptions'][0];
		if($sub_id == "7231" || $sub_id == "11984" || $sub_id == "12039" || $sub_id == "17015" || $sub_id == "22873" || $sub_id == "22874" || $sub_id == "22875" || $sub_id == "19950" || $sub_id == "19953"){
			$program = "Credit Monitoring";
		}elseif($sub_id == "16637" || $sub_id == "16638" || $sub_id == "19780"){
			$program = "Pre-Filing Credit Counseling";
		}elseif($sub_id == "16336" || $sub_id == "16370" || $sub_id == "19781"){
			$program = "Post-Filing Debtor Education";
		}
		$plan_info = get_post($sub_id);
		if(isset($plan_info->post_title)){
			$plan_name = $plan_info->post_title;
		}
	}
	if($site_lang == "es"){
		if($plan_name == "Creditor Education Joint Class"){
			$plan_name = "Clase conjunta de formación de acreedores";
		}elseif($plan_name == "Creditor Education Single Class"){
			$plan_name = "Educación del Acreedor Clase Única";
		}elseif($plan_name == "Creditor Education Waiver"){
			$plan_name = "Renuncia a la educación del acreedor";
		}elseif($plan_name == "Debtor Education Joint Class"){
			$plan_name = "Clase conjunta de educación del deudor";
		}elseif($plan_name == "Debtor Education Single Class"){
			$plan_name = "Educación del deudor Clase única";
		}elseif($plan_name == "Debtor Education Waiver"){
			$plan_name = "Renuncia a la educación del deudor";
		}elseif($plan_name == "Subscription Plan Max Advance with 7 day free trial"){
			$plan_name = "Plan de suscripción Max Advance con 7 días de prueba gratuita";
		}elseif($plan_name == "Subscription Plan Max Advanced"){
			$plan_name = "Plan de suscripción Max Advanced";
		}elseif($plan_name == "Subscription Plan Max Pro"){
			$plan_name = "Plan de suscripción Max Pro";
		}elseif($plan_name == "Subscription Plan Max Plus"){
			$plan_name = "Plan de suscripción Max Plus";
		}elseif($plan_name == "Subscription Plan Max Plus with 7 day free trial"){
			$plan_name = "Plan de suscripción Max Plus con 7 días de prueba gratuita";
		}elseif($plan_name == "Subscription Plan Max Pro with 7 day free trial"){
			$plan_name = "Plan de suscripción Max Pro con 7 días de prueba gratuita";
		}

	}
	$sub = 'A New User has registered to your website';
    $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
    $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
    if($sub_id == "7231" || $sub_id == "11984" || $sub_id == "12039" || $sub_id == "17015" || $sub_id == "22873" || $sub_id == "22874" || $sub_id == "22875" || $sub_id == "19950" || $sub_id == "19953"){
    	$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%'),
	        array($name,  $program, $email, $plan_name, $phone),
	        file_get_contents(get_stylesheet_directory().'/templates/cm_register_mailto_admin.php')
	    );
    }elseif($sub_id == "16637"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%user_household%', '%user_income%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $household, $income, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/free_register_mailto_admin.php')
	    );
	}elseif($sub_id == "16336"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%user_household%', '%user_income%', '%course_reason%', '%bnumber%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $household, $income, $reason, $bnumber, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/free_mim_register_mailto_admin.php')
	    );
	}elseif($sub_id == "16638" || $sub_id == "19780"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/paid_register_mailto_admin.php')
	    );
	}elseif($sub_id == "16370" || $sub_id == "19781"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%course_reason%', '%bnumber%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $reason, $bnumber, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/paid_mim_register_mailto_admin.php')
	    );
	}else{
	    $content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%'),
	        array($name,  $program, $email, $plan_name),
	        file_get_contents(get_stylesheet_directory().'/templates/register_mail_to_admin.php')
	    );
	}
    $send_email = wp_mail("jglen@premierconsumer.org", $sub, $content, $mheaders);
    // $send_email1 = wp_mail("shradha.sohal@xcelance.com", $sub, $content, $mheaders);
}

if ( ! function_exists( 'consulting_get_structure' ) ) {
    function consulting_get_structure( $sidebar_id, $sidebar_type, $sidebar_position, $layout = false ) {
        $output                   = array();
        $output['content_before'] = $output['content_after'] = $output['sidebar_before'] = $output['sidebar_after'] = '';
        $output['class']          = 'posts_list';
        if ( $layout == 'grid' ) {
            $output['class'] = 'posts_grid';
        }
        if ( ! empty( $_GET['layout'] ) && $_GET['layout'] == 'grid' ) {
            $output['class'] = 'posts_grid';
        }
        if ( $sidebar_type == 'vc' ) {
            if ( $sidebar_id ) {
                $sidebar = get_post( $sidebar_id );
            }
        } else {
            if ( $sidebar_id ) {
                $sidebar = true;
            }
        }
        if ( isset( $sidebar ) ) {
            $output['class'] .= ' with_sidebar';
        }
        if ( $sidebar_position == 'right' && isset( $sidebar ) ) {
        	$output['content_before'] .= '<div class="container">';
            $output['content_before'] .= '<div class="row">';
            $output['content_before'] .= '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
            $output['content_before'] .= '<div class="col_in __padd-right">';
            $output['content_after'] .= '</div>';
            $output['content_after'] .= '</div>'; // col
            $output['sidebar_after'] .= '</div>'; // row
            $output['sidebar_after'] .= '</div>'; // container
        }

        if ( $sidebar_position == 'left' && isset( $sidebar ) ) {
            $output['content_before'] .= '<div class="row">';
            $output['content_before'] .= '<div class="col-lg-9 col-lg-push-3 col-md-9 col-md-push-3 col-sm-12 col-xs-12">';
            $output['content_before'] .= '<div class="col_in __padd-left">';
            $output['content_after'] .= '</div>';
            $output['content_after'] .= '</div>'; // col
            $output['sidebar_before'] .= '<div class="col-lg-3 col-lg-pull-9 col-md-3 col-md-pull-9 hidden-sm hidden-xs">';
            // .sidebar-area
            $output['sidebar_after'] .= '</div>'; // col
            $output['sidebar_after'] .= '</div>'; // row
        }
        return $output;
    }
}

add_filter( 'pms_merge_tags', 'pms_add_tags_in_password', 10, 2 );
function pms_add_tags_in_password( $merge_tags, $type = '' ){
    if( $type == 'sort' )
        return $merge_tags;
    /* unescaped because they might contain html */
    $merge_tags[] = 'password';
    return $merge_tags;
}

add_filter( 'pms_merge_tag_password', 'pms_merge_tag_password', 10, 2 );
function pms_merge_tag_password($value, $user_info){
    if(isset($_POST["pass1"])){
        return $_POST["pass1"];
    }else{
    	return "";
    }
}

/*
add_filter("pms_member_account_tabs",'pms_member_account_client_report_tab', 10, 2 );
function pms_member_account_client_report_tab($merge_tab,$tabs){
   $merge_tab["client_report"] = 'Client Report';
   return $merge_tab;
}
*/
/*
add_filter("pms_account_shortcode_content",'pms_account_shortcode_content_tabs', 10, 2 );
function pms_account_shortcode_content_tabs($content,$tabs){
  echo "<pre>"; print_r($content); die;
}*/

add_filter("pms_member_account_before_client_report_tab",'pms_member_account_before_client_report_tab_fun', 10, 2 );
function pms_member_account_before_client_report_tab_fun($active_tab,$tabs){
  if($active_tab == "client_report"){
     return wp_redirect(home_url()."/client-report");
  }
}

/*add_filter( 'login_redirect','login_redirect', 100, 101 );
function login_redirect( $redirect_to, $request, $user ) {
	die;
	return $redirect_to;
}
*/
/*add_action( 'wp_footer', 'redirect_cf7' );
function redirect_cf7() {
// php tag end
<script type="text/javascript">
document.addEventListener( 'wpcf7mailsent', function( event ) {
   if ( '5354' == event.detail.contactFormId ) {   
		if(session_id() == '') {
		    session_start();
		}
		$current_submission = WPCF7_Submission::get_instance();
		$_SESSION['cf7_submission'] = $current_submission->get_posted_data();
    	location = 'https://premierconsumer.org/dev/thank-you/';
    }
}, false );
</script>
// php tag start
}*/
function rmc_sidebar_init() {
	register_sidebar( array(
        'name' => __( 'Upgrade Plan widget', 'wpb' ),
        'id' => 'upgrade-plan',
        'description' => __( 'Upgrade Plan widget.', 'rmc' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Subscription Plan Max Advanced widget', 'wpb' ),
        'id' => 'max-advanced',
        'description' => __( 'Subscription Plan Max Advanced.', 'rmc' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Subscription Plan Max Plus widget', 'wpb' ),
        'id' => 'max-plus',
        'description' => __( 'Subscription Plan Max Plus widget.', 'rmc' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Subscription Plan Max Pro widget', 'wpb' ),
        'id' => 'max-pro',
        'description' => __( 'Subscription Plan Max Pro widget.', 'rmc' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Privacy Policy Menu', 'wpb' ),
        'id' => 'privacy-policy',
        'class' => 'fdgf',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}
add_action( 'widgets_init', 'rmc_sidebar_init' );

/*Custom Post type start*/
function cw_post_type_videos() {
    $supports = array(
    'title', // post title
    'editor', // post content
    'author', // post author
    'thumbnail', // featured images
    'excerpt', // post excerpt
    'custom-fields', // custom fields
    'comments', // post comments
    'revisions', // post revisions
    'post-formats', // post formats
    );
    $labels = array(
    'name' => _x('videos', 'plural'),
    'singular_name' => _x('videos', 'singular'),
    'menu_name' => _x('Videos', 'admin menu'),
    'name_admin_bar' => _x('videos', 'admin bar'),
    'add_new' => _x('Add New', 'add new'),
    'add_new_item' => __('Add New videos'),
    'new_item' => __('New videos'),
    'edit_item' => __('Edit videos'),
    'view_item' => __('View videos'),
    'all_items' => __('All videos'),
    'search_items' => __('Search videos'),
    'not_found' => __('No videos found.'),
    );
    $args = array(
    'supports' => $supports,
    'labels' => $labels,
    'public' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'videos'),
    'has_archive' => true,
    'hierarchical' => false,
    );
    register_post_type('videos', $args);
}
add_action('init', 'cw_post_type_videos');
/*Custom Post type end*/

add_action( 'init', 'pc_custom_post' );
function pc_custom_post() {
    $supports = array( 
        'title'
    );
    register_post_type( 'Poverty Guidelines',
        // CPT Options
        array(
            'supports' => $supports,
            'labels' => array(
            'name' => __( 'Poverty Guidelines' ),
            'singular_name' => __( 'Poverty Guideline' )
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'poverty_guidelines'),
            'menu_icon' => 'dashicons-editor-ul',
        )
    );

    /* discount code for plan */
    $supports = array( 
        'title'
    );
    register_post_type( 'Discount Codes',
        // CPT Options
        array(
            'supports' => $supports,
            'labels' => array(
            'name' => __( 'Discount Codes' ),
            'singular_name' => __( 'Discount Code' ),
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'discount_codes'),
            'menu_icon' => 'dashicons-tickets',
            'menu_position' => 70,
        )
    );

    /* Custom Post type for newsletter HTML */
    // $supports_cs = array( 
    //     'title', 'editor'
    // );
    $supports_cs = array( 
        'title'
    );
    register_post_type( 'Newsletter HTML Pages',
        // CPT Options
        array(
            'supports' => $supports_cs,
            'labels' => array(
            'name' => __( 'Newsletter HTML Pages' ),
            'singular_name' => __( 'Newsletter HTML Page' ),
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'newsletter_html_page'),
            'menu_icon' => 'dashicons-welcome-add-page',
            'menu_position' => 75,
        )
    );

    /* Custom Messages HTML */
    $supports_cm = array( 
        'title'
    );
    register_post_type( 'Custom Message HTML',
        // CPT Options
        array(
            'supports' => $supports_cm,
            'labels' => array(
            'name' => __( 'Custom Message HTML' ),
            'singular_name' => __( 'Custom Message HTML' ),
            ),
            'public' => true,
            'has_archive' => false,
            'rewrite' => array('slug' => 'custom_msg_html'),
            'menu_icon' => 'dashicons-welcome-write-blog',
            'menu_position' => 75,
        )
    );
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

function alert_endpoint(WP_REST_Request $request_data) {

	$myfile = fopen(ABSPATH."/newfile.txt", "w") or die("Unable to open file!");
	$txt = json_encode($request_data);
	fwrite($myfile, $txt);
	fclose($myfile);
	$myfile = fopen(ABSPATH."/newfile3.txt", "w") or die("Unable to open file!");
	$txt = json_encode($request_data->get_params());
	fwrite($myfile, $txt);
	fclose($myfile);

    

	global $wpdb;
	$table = $wpdb->prefix.'stitch_alert';
    $auth_data = $request_data->get_header('authorization');
    if($auth_data != ''){
        $ar = explode(' ',$auth_data);
        $decode_auth = base64_decode($ar[1]);
        if($decode_auth != ''){
            $arrdata = explode(':',$decode_auth);
            $user = $arrdata[0];
            $pass = $arrdata[1];
            if(strlen($user) > 15 || strlen($pass) > 15){
                $response = array(
                        'status' => false,
                        'message' => 'Username and password must be less than 15 characters'
                    );
                wp_send_json(array($response),401);
            }
        }
    }
    $data = $request_data->get_params();
    if(count($data) > 0){
        foreach($data as $d){
            $id = $d['id'];
            $alert = $d['alert_id'];
            $user = $d['user_id'];
            if($alert != '' && $user != ''){
            	$user_data = get_users(array(
			        'meta_key' => 'api_user_id',
			        'meta_value' => $user
			    ));
			    $user_id = $user_data[0]->data->ID;
			    $user_email = $user_data[0]->data->user_email;
                $get_token = get_token_for_admin($user_id);
		        if(isset($get_token->token)){
		        	$tkn = $get_token->token;
		        	$ch = curl_init();
				// 	curl_setopt($ch, CURLOPT_URL, 'https://efx-dev.stitchcredit.com/api/direct/efx-alert/'.$alert);
    				curl_setopt($ch, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/direct/efx-alert/'.$alert);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


					$headers = array();
					$headers[] = 'Content-Type: application/json';
					$headers[] = 'Authorization: Bearer '.$tkn;
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


					$result = curl_exec($ch);
					if (curl_errno($ch)) {
					    echo 'Error:' . curl_error($ch);
					}
					curl_close($ch);
					$checkExists = $wpdb->get_results("SELECT * FROM $table WHERE user_id='$user_id'");
					$current_time = date('M-Y');
					if(count($checkExists) > 0){
						$desc[$current_time] = 1;
						$tid = $checkExists[0]->id;
						// update data
						$rec_desc = $checkExists[0]->description;
						if($rec_desc != ''){
							$rec_desc_arr = json_decode($rec_desc);
							if(array_key_exists($current_time,$rec_desc_arr)){
								$t_data = (int)$rec_desc_arr->$current_time+1;
								$rec_desc_arr->$current_time = $t_data;
								$update = $wpdb->update(
					                        $table,
					                        array( 
					                            'description' => json_encode($rec_desc_arr)
					                        ),
					                        array(
					                            'id' => $tid
					                        )
					                    ); 
							}else{
								$update = $wpdb->update(
					                        $table,
					                        array( 
					                            'description' => json_encode($desc)
					                        ),
					                        array(
					                            'id' => $tid
					                        )
					                    );
							}
						}else{
							$update = $wpdb->update(
				                        $table,
				                        array( 
				                            'description' => json_encode($desc)
				                        ),
				                        array(
				                            'id' => $tid
				                        )
				                    );
						}
					}else{
						// add new data
						$wpdb->insert($table, array(
			                'user_id'   	=> $user_id,
			                'description' 	=> json_encode($desc),
			                'created_at'   	=> date('Y-m-d h:i:s')
			            ));
					}
					$alert_res = json_decode($result);
				    $alert_name = $alert_res->alertType;
                    $name = $user_data[0]->data->display_name;
                    $sub = 'New Notification - Premier Consumer';
                    $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
                    $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
                    $content = str_replace(
				        array('%name%', '%alert_name%'),
				        array($name, $alert_name),
				        file_get_contents(get_stylesheet_directory().'/templates/alert-notify.php')
				    );
                    wp_mail($user_email, $sub, $content, $mheaders);
                    // wp_mail('shradha.sohal@xcelance.com', $sub, $content, $mheaders); // for testing
		        }            
		    }

            if($id != ''){
                $response = array(
                    'status' => true,
                    'hook_id' => $id
                    );
                wp_send_json(array($response));
            }else{
                $response = array(
                    'status' => false,
                    'message' => 'Hook id missing'
                    );
                wp_send_json(array($response));
            }
        }
    }else{
        $response = array(
            'status' => false,
            'message' => 'No request data'
            );       
        wp_send_json(array($response));
    }
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'consulting-child/v1', '/w-endpoint', array(
    'methods' => 'POST',
    'callback' => 'alert_endpoint',
  ) );
} );

// cron job function
add_action( 'pc_get_alerts', 'call_webhook_for_alert' );
function call_webhook_for_alert(){
	$myfile = fopen(ABSPATH."/newfile2.txt", "w") or die("Unable to open file!");
	$txt = json_encode($_POST);
	$txt .= json_encode($_GET);
	fwrite($myfile, $txt);
	fclose($myfile);
    // $user_id = 'ad9cb20c-5100-4b8a-95f8-5888101298db'; // test
    // die;
    $users = get_users( array( 'role__in' => array( 'subscriber' ) ) );
    if(count($users) > 0){
        foreach($users as $user){
            $user_id = get_user_meta($user->ID,'api_user_id',true);
            if($user_id != ''){
                // get preauth token 
                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_URL, 'http://efx-dev.stitchcredit.com/api/test/preauth-token/'.$user_id); 
                curl_setopt($ch, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/test/preauth-token/'.$user_id);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $c_res = json_decode($result);
                $token = $c_res->token;
                if($token != ''){
                    // call webhook 
                    $ch1 = curl_init();
                    // curl_setopt($ch1, CURLOPT_URL, 'https://efx-dev.stitchcredit.com/api/test/webhook/send/'.$user_id.'?count=10&type=ACCALERT');
                    curl_setopt($ch1, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/test/webhook/send/'.$user_id.'?count=10&type=ACCALERT');
                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch1, CURLOPT_POST, 1);
                    $headers = array();
                    $headers[] = 'Content-Type: application/json';
                    $headers[] = 'Authorization: Bearer '.$token;
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            }
        }
    }
}

// cron job for monthly report
function pc_monthly_report_cron(){
	$today_date = date("Y-m-d");
	$last_date = date("Y-m-t", strtotime($today_date));
	
	if($today_date != $last_date){
		die();
	}
	global $wpdb;
	$table = $wpdb->prefix.'stitch_alert';
    $users = get_users( array( 'role__in' => array( 'subscriber' ) ) ); // get all users
    if(count($users) > 0){
        foreach($users as $user){
        	$uid = $user->ID;
            $user_id = get_user_meta($user->ID,'api_user_id',true); // filter stitch credit users
            $user_name = $user->display_name;
            $f_name = $user->first_name;
            $user_email = $user->user_email;
            $sub = 'Monthly report';
            // $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
            $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
            $content = str_replace(
		        array('%user_name%'),
		        array($user_name),
		        file_get_contents(get_stylesheet_directory().'/templates/monthly_report.php')
		    );
            if($user_id != ''){
                $checkExists = $wpdb->get_results("SELECT * FROM $table WHERE user_id='$uid'");
				$current_time = date('M-Y');
	        	$m_report[$current_time] = 1;
				if(count($checkExists) > 0){
					// check
					$get_desc = $checkExists[0]->description;
					$rec_id   = $checkExists[0]->id;
					$get_report = $checkExists[0]->monthly_report;				

					if($get_desc != ''){
					    $arr_report = (array)json_decode($get_report);
						$arr_desc = json_decode($get_desc);
						if(!array_key_exists($current_time,$arr_desc)){
							$arr_report[$current_time] = 1;
							$update = $wpdb->update(
				                        $table,
				                        array( 
				                            'monthly_report' => json_encode($arr_report)
				                        ),
				                        array(
				                            'id' => $id
				                        )
				                    );
				            wp_mail($user_email, $sub, $content, $mheaders);
				            wp_mail('shradha.sohal@xcelance.com', $sub, $content, $mheaders); // for testing
							// send mail
						}
					}else{
					    $arr_report = (array)json_decode($get_report);	
						$arr_report[$current_time] = 1;
						$update = $wpdb->update(
			                        $table,
			                        array( 
			                            'monthly_report' => json_encode($arr_report)
			                        ),
			                        array(
			                            'id' => $id
			                        )
			                    );
			            wp_mail($user_email, $sub, $content, $mheaders);
                        // wp_mail('shradha.sohal@xcelance.com', $sub, $content, $mheaders); // for testing
						// send mail
					}
				}else{ 
					$wpdb->insert($table, array(
			                'user_id'   		=> $uid,
			                'monthly_report' 	=> json_encode($m_report),
			                'created_at'   		=> date('Y-m-d h:i:s')
			            ));
			        wp_mail($user_email, $sub, $content, $mheaders);
                    // wp_mail('shradha.sohal@xcelance.com', $sub, $content, $mheaders); // for testing
					// send mail
				}
            }
        }
    }
}
add_action ( 'pc_send_monthly_report', 'pc_monthly_report_cron' );
wp_schedule_event( strtotime( '6am' ), 'daily', 'pc_send_monthly_report' );

// function send_monthly_report() {
//     if ( ! wp_next_scheduled( 'send_monthly_report' ) ) {
//         wp_schedule_event( strtotime( '6am' ), 'daily', 'send_monthly_report' );
//     }
// }
// add_action( 'send_monthly_report', 'call_cron_for_report' );

// get token for admin
function get_token_for_admin($user_id){
	$user_meta = get_user_meta($user_id);
	$member = pms_get_member( $user_id );
	$res = [];
	$subscription_plan_id = "";
	if(isset($member->subscriptions) && !empty($member->subscriptions)){
        $user_plans = $member->subscriptions;
		if(count($user_plans) > 0){
        	foreach ($user_plans as $key => $user_plan) {
        		if($user_plan['status'] == "active"){
	        		$plan_id = $user_plan['subscription_plan_id'];
		            $credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22873","22874","22875");
		            if(in_array($plan_id, $credit_monitoring_plans)){
		            	$subscription_plan_id = $plan_id;
		            	break;
		            }
		        }
        	}
        }
        if($subscription_plan_id  != ""){
        	$same_plan = getSamePlan($subscription_plan_id);
        	if($subscription_plan_id == "7231" || $subscription_plan_id == "22875" || $same_plan == "7231"){
	        	$api_data = array(						    'apikey' => '8e95a79a-7cf8-43ce-85f8-cca0eb970ed9',
						    'secret' => '952c71fc-44fb-4053-98d6-af9eaeaeaf2c'
						);
	        }elseif($subscription_plan_id == "7769" || $subscription_plan_id == "11984" || $subscription_plan_id == "22874" || $same_plan == "11984"){
	        	$api_data = array(
						    'apikey' => '36ec996d-e50a-414f-9059-88801116c528',
						    'secret' => '6a6100f3-f04a-4948-a66e-8047ae2a2922'
						);
	        }elseif($subscription_plan_id == "7770" || $subscription_plan_id == "12039" || $subscription_plan_id == "22873" || $same_plan == "12039"){
	        	$api_data = array(
						    'apikey' => '2459fd6a-565b-4049-87c3-d7e1e634aa10',
						    'secret' => 'a5daacea-4fb8-41f3-aa49-a13f360177b8'
						);
	        }else{
	        	$api_data =array();
	        	return $res;
	        }
        }else{
        	return $res;
        }       
	}	

	if(!isset($api_data['apikey'])){
	    return $res;
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/direct/login');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	$post = array(
	    'apikey' => $api_data['apikey'],
	    'secret' => $api_data['secret']
	);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));

	$headers = array();
	$headers[] = 'Content-Type: application/json';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    echo 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return $result1 = json_decode($result);
}

// delete user and close account in stitch
function my_delete_user( $user_id ) {
    global $wpdb;
    $user_obj = get_userdata( $user_id );
    $user_api_id = get_user_meta($user_id,'api_user_id',true);  
    if($user_api_id != ''){
        $token = get_token_for_admin($user_id);
        if(isset($token->token)){
    	    $user_token = $token->token;  	    
    	    /* close stitch credit account */
    	    $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://efx-dev.stitchcredit.com/api/direct/close-account/'.$user_api_id); // testing
            curl_setopt($ch, CURLOPT_URL, 'https://efx-wgt.stitchcredit.com/api/direct/close-account/'.$user_api_id);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);           
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer '.$user_token;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);          
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
    }
    /* cancel subscription in stripe */
    $pms_settings = get_option( 'pms_payments_settings', array() );
    if(count($pms_settings) > 0){
        $gateway = $pms_settings['default_payment_gateway'];
        if($gateway == 'stripe_intents'){
            if(pms_is_payment_test_mode()){
                $prefix = 'test_api_secret_key';
            }else{
                $prefix = 'api_secret_key';
            }
            $secret_key = $pms_settings['gateways']['stripe'][$prefix];
            $table = $wpdb->prefix.'pms_member_subscriptions';
            $table1 = $wpdb->prefix.'pms_member_subscriptionmeta';
            $result = $wpdb->get_results("SELECT pms_member_subscriptionmeta.meta_value FROM $table AS pms_member_subscriptions INNER JOIN $table1 AS pms_member_subscriptionmeta ON pms_member_subscriptions.id =  pms_member_subscriptionmeta.member_subscription_id  WHERE user_id = '$user_id' AND meta_key = '_stripe_customer_id'");
            if(count($result) > 0 && $secret_key != ''){
                $cust_id = $result[0]->meta_value;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/customers/'.$cust_id);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');              
                curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ':' . '');               
                $result_stripe = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $in_arr = json_decode($result_stripe);
                if(isset($in_arr->subscriptions->data) && count($in_arr->subscriptions->data) > 0){
                    $sub_id = $in_arr->subscriptions->data[0]->id;
                    if($sub_id != ''){
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/subscriptions/'.$sub_id);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                        curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ':' . '');                       

                        $result = curl_exec($ch);
                        if (curl_errno($ch)) {
                            echo 'Error:' . curl_error($ch);
                        }
                        curl_close($ch);
                    }
                }
            }
        }
    }
    /* end cancel subscription in stripe */
}
add_action( 'delete_user', 'my_delete_user' );

add_action( 'elementor_pro/forms/validation', function ( $record, $ajax_handler ) {
    $form_name = $record->get_form_settings( 'form_name' );
    if ( 'Contact Form' !== $form_name && 'Formulario de contacto' !== $form_name && 'coupon_code_form' !== $form_name) {
        return;
    }
    if('Contact Form' === $form_name || 'Formulario de contacto' === $form_name){
	    $fields = $record->get_field( [
	        'id' => 'message',
	    ] );
	    if ( empty( $fields ) ) {
	        return;
	    }
	    $field = current( $fields );
	    $msg = $field['value'];
	    $bHasLink = strpos($msg, 'http') !== false || strpos($msg, 'www.') !== false;
	    if($bHasLink){
	        $ajax_handler->add_error( $field['id'], "Invalid request, please avoid using links in message.");
	    }
	}

	if('coupon_code_form' === $form_name){
		$fields = $record->get_field( [
	        'id' => 'coupon_code',
	    ] );
	    $package = $record->get_field([
	    	'id' => 'sub_package',
	    ]);
	    if ( empty( $fields ) ) {
	        return;
	    }
	    $field = current( $fields );
	    $sub_id = current( $package )['value'];
	    $lang = apply_filters( 'wpml_current_language', null );
	    if(isset($_REQUEST['referrer'])){
	    	$link_explode = explode("?",$_REQUEST['referrer']);
	    	if(isset($link_explode[1])){
	    		if(strpos($link_explode[1], 'lang=') !== false){
	    			$lang_find = '';
			    	$char_explodes = explode("&",$link_explode[1]);
			        if(count($char_explodes) > 0){
			        	foreach($char_explodes as $ce){
			            	if(strpos($ce, 'lang=') !== false){
			                	$lang_find = str_replace('lang=','',$ce);
			                    break;
			                }
			            }
			        }
			        if($lang_find != ''){
			        	if($lang_find == 'en'){
					    	$lang = 'english';
					    }elseif($lang_find == 'es'){
					    	$lang = 'spanish';
					    }
			        }else{
			        	$lang = 'english';
			        }
			    }else{
			    	$lang = 'english';      
			   	}
	    	}else{
	    		$lang = 'english';
	    	}
	    }else{
		    if($lang == 'en'){
		    	$lang = 'english';
		    }elseif($lang == 'es'){
		    	$lang = 'spanish';
		    }
		}

	    $coupon_code = $field['value'];
	    $es_err = 'Código de promoción inválido.';
	    $get_res = getCouponData($coupon_code, $sub_id);
	    if(isset($get_res['old_plan']) && $get_res['old_plan'] != ''){
	    	if($get_res['old_plan'] != $sub_id){
	    		if($lang == 'spanish'){
		    		$ajax_handler->add_error( $field['id'], $es_err);
	    		}else{
		    		$ajax_handler->add_error( $field['id'], "Invalid Promo Code.");
	    		}
	    	}
	    	if($lang !== $get_res['language']){
	    		if($lang == 'spanish'){
		    		$ajax_handler->add_error( $field['id'], $es_err);
	    		}else{
		    		$ajax_handler->add_error( $field['id'], "Invalid Promo Code.");
	    		}
	    	}
	    }else{
	    	if($lang == 'spanish'){
	    		$ajax_handler->add_error( $field['id'], $es_err);
    		}else{
	    		$ajax_handler->add_error( $field['id'], "Invalid Promo Code.");
    		}	    	
	    }
	}
}, 10, 2 );


add_action( 'elementor_pro/forms/new_record', function( $record, $handler ) {
    //make sure its our form
    $form_name = $record->get_form_settings( 'form_name' );
    // Replace MY_FORM_NAME with the name you gave your form
    if ( 'Contact Form' !== $form_name && 'Formulario de contacto' !== $form_name) {
        return;
    }

    if ( 'Contact Form' === $form_name && 'Formulario de contacto' === $form_name){
	    $raw_fields = $record->get( 'fields' );
	    $fields = [];
	    foreach ( $raw_fields as $id => $field ) {
	        $fields[ $id ] = $field['value'];
	    }   
	    
	    $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
	    $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
	    $content = "<h3>New contact form submit.</h3>";
	    $content .= "<p><strong>Name : </strong>".$fields['name']."<br/>";
	    $content .= "<strong>Department : </strong>".$fields['services']."<br/>";
	    $content .= "<strong>Email : </strong>".$fields['email']."<br/>";
	    $content .= "<strong>Phone : </strong>".$fields['phone']."<br/>";
	    $content .= "<strong>Subject : </strong>".$fields['subject']."<br/>";
	    $content .= "<strong>Message : </strong>".$fields['message']."</p>";
	    // send mail according to form value
	    if($fields['services'] == 'Corporate Relations'){
	        wp_mail('corporate@premierconsumer.org','Contact Premier Consumer Credit Counseling®', $content, $mheaders);
	    }elseif($fields['services'] == 'Member Services'){
	        wp_mail('clientquestion@premierconsumer.org','Contact Premier Consumer Credit Counseling®', $content, $mheaders);    
	    }

	    if($fields['services'] == 'Relaciones Corporativas'){
	        wp_mail('corporate@premierconsumer.org','Contact Premier Consumer Credit Counseling®', $content, $mheaders);
	    }elseif($fields['services'] == 'Servicios a los miembros'){
	        wp_mail('clientquestion@premierconsumer.org','Contact Premier Consumer Credit Counseling®', $content, $mheaders);
	    }
	}
}, 10, 2 );

add_action( 'wp_enqueue_scripts', 'pc_custom_assets' );
function pc_custom_assets() {
	/* upgrade link code */
	$upgarde_link = "";
	if(is_user_logged_in()){
		$member = pms_get_member( get_current_user_id() );
		$credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22874","22873","22875");
		if(pms_is_member_of_plan( $credit_monitoring_plans, get_current_user_id() )){
		    $user_plans = $member->subscriptions;
		    if(count($user_plans) > 0){
		    	foreach (array_reverse($user_plans) as $key => $user_plan) {
		    		if(in_array($user_plan['subscription_plan_id'], $credit_monitoring_plans)){
		    			$upgarde_link = esc_url( wp_nonce_url( add_query_arg( array( 'pms-action' => 'upgrade_subscription', 'subscription_id' => $user_plan['id'], 'subscription_plan' => $user_plan['subscription_plan_id'] ), pms_get_current_page_url( true ) ), 'pms_member_nonce', 'pmstkn' ) );
		    			break;
		    		}
		    	}
		    }
		}
	}
	/* upgrade link code end */
    //    wp_register_script( 'custom-child-script', get_stylesheet_directory_uri().'/assets/js/custom.js?n='.time(), array( 'jquery' ) );
    wp_register_script( 'custom-child-script', get_stylesheet_directory_uri().'/assets/js/custom_bundle.js?n='.time(), array( 'jquery' ) );
    wp_enqueue_script( 'custom-child-script' );
    wp_localize_script( 'custom-child-script', 'custom_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'site_url' => home_url() ) );
}
// check user under poverty line or not
add_action( 'wp_ajax_nopriv_check_pguildeline', 'check_pguildeline' );
add_action( 'wp_ajax_check_pguildeline', 'check_pguildeline' );
function check_pguildeline(){
    $person = (int)$_POST['person'];
    $income = (int)$_POST['income'];
    $sb_type = $_POST['type'];
    $additional_pay = $y_income = 0;
    $current_year = date('Y');   

    if($person > 8){
        $args = array(
            'post_type'     => 'povertyguidelines',
            'post_status'   => 'publish',
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => 'persons',
                    'value'     =>  8,
                    'compare'   => '='
                ),
                array(
                    'key'       => 'year',
                    'value'     => $current_year,
                    'compare'   => '='
                )
            )
        );   

        $getPosts = get_posts($args);
        if(count($getPosts) > 0){
            foreach($getPosts as $key => $getPost){
                $additional_pay = $getPost->additional_pay;
                $y_income = $getPost->yearly_income;
            }
        }
        $extra_per = $person - 8;
        $extra = $extra_per * $additional_pay;
        $new_income = $y_income + $extra;
        if($income > $new_income){
            echo json_encode('redirect');die;
        }else{
            // under povery line
            echo json_encode('pg');die;
        }
    }else{
        $args = array(
            'post_type'     => 'povertyguidelines',
            'post_status'   => 'publish',
            'meta_query'    => array(
                'relation'      => 'AND',
                array(
                    'key'       => 'persons',
                    'value'     =>  $person,
                    'compare'   => '='
                ),
                array(
                    'key'       => 'year',
                    'value'     => $current_year, 
                    'compare'   => '='
                )
            )
        );
        $getPosts = get_posts($args);
        if(count($getPosts) > 0){
            foreach($getPosts as $key => $getPost){
                $y_income = $getPost->yearly_income;
            }
        }
        if($income > $y_income){
            // redirect to wavier
            echo json_encode('redirect');die;
        }else{
            // under poverty line
            echo json_encode('pg');die;
        }
    }
}

/* Upload Document to shareFile */
add_action( 'wp_ajax_nopriv_upload_to_sharefile', 'upload_to_sharefile' );
add_action( 'wp_ajax_upload_to_sharefile', 'upload_to_sharefile' );
function upload_to_sharefile(){  
	$user_name = $_POST['name'];
    $files = $_FILES;
    if(isset($_POST['type'])){
        if($_POST['type'] == 'creditor'){
            $c_name = 'CIMBK';
        }else{
            $c_name = 'MIM';
        }
    }else{
        $c_name = '';
    }
    if(count($files) > 0){
        // get token curl
        $username = "jglen@premierconsumer.org";
		$password = "krhxi3tulbqvmvjv";
		$client_id = "QrcY5YmSSi5srUp1u9tkOQfNlZqZ3XlI";
		$client_secret = "Hf9qBHjShAifPnYkBKxwrCqa1ehturC167x5ofCne6MDWZrj";      
        $uri = "https://premierconsumer.sharefile.com/oauth/token";
        $body_data = array("grant_type"=>"password", "client_id"=>$client_id, "client_secret"=>$client_secret,
                  "username"=>$username, "password"=>$password);
        $data = http_build_query($body_data);    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        $curl_response = curl_exec ($ch); 

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error_number = curl_errno($ch);
        $curl_error = curl_error($ch);  
        curl_close ($ch);
        $token = '';
        if ($http_code == 200) {
            $res_data = json_decode($curl_response);
            if(isset($res_data->access_token)){
            	$token = $res_data->access_token;
            }
        }
        // end code
        if($token != ''){
        	$folder_id = 'fo86a48d-eb3b-400c-bc2b-ef0e2d41fb36';
        // 	$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJTaGFyZUZpbGUiLCJzdWIiOiJjZjgwNjY5Zi1iNTVkLTQzN2MtYjk1Yi00ZWM5OTJmMmExOWIiLCJpYXQiOjE2NTA5NDYwNTUsImV4cCI6MTY1MDk3NDg1NSwiYXVkIjoicEc5QmZteUpGbXcyeWQ1RE5mU0MzRXIxY25LUW50c0wiLCJzaGFyZWZpbGU6dG9rZW5pZCI6IjAwT3Q3ajlMeXoxZGkxQnpsY3pDemhEbUZISVNQMnU2JCR4VkN6UnpFTzZtazA2R1o4NnJ1bUJFRnBZbjJ2aXZCcyIsInNjb3BlIjoidjMgdjMtaW50ZXJuYWwiLCJzaGFyZWZpbGU6c3ViZG9tYWluIjoicHJlbWllcmNvbnN1bWVyIiwic2hhcmVmaWxlOmFjY291bnRpZCI6ImEyODE1MzkxLWRkYzEtNDgwNS04MGRkLTVhN2E5YTg0M2U0MSJ9.YY6z-IopHWrUShIaP4HGc6BlkLcZd3Vn6bZSWwVGNhM';
        	$uri = "https://premierconsumer.sf-api.com/sf/v3/Items(".$folder_id.")/Upload";      
        	$headers = array("Authorization: Bearer ".$token);       
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $uri);
        	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        	curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);       
        	$curl_response = curl_exec ($ch);      

        	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        	$curl_error_number = curl_errno($ch);
        	$curl_error = curl_error($ch);       

        	$upload_config = json_decode($curl_response);  
        	if ($http_code == 200) {
        		$i = 1;
        	    foreach($files as $key => $file){
        	        $type = $file['type'];
                    $name = $file['name'];
                    $data = $file['tmp_name'];
                    $filename = pathinfo($name, PATHINFO_FILENAME);
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    if($c_name != ''){
                        $newFilename = $c_name.'-'.$user_name.'-'.$filename.'.'.$extension;
                    }else{
                        $newFilename = $user_name.'-'.$filename.'.'.$extension;
                    }   

                	$post["File".$i] = new CurlFile(realpath($data),$type,$newFilename);
                	$i++;
                }               

        	    curl_setopt ($ch, CURLOPT_URL, $upload_config->ChunkUri);
            	curl_setopt ($ch, CURLOPT_POST, true);
            	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
            	curl_setopt ($ch, CURLOPT_VERBOSE, FALSE);
            	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            	curl_setopt ($ch, CURLOPT_HEADER, true);           

            	$upload_response = curl_exec ($ch);
        	}
        	curl_close ($ch);
        	echo json_encode("success");
        }else{
            echo json_encode('missing_token');die;
        }
    }else{
    	echo json_encode('not_found');die;
    }die;
}

/* Upload Document to shareFile from dashboard */
add_action( 'wp_ajax_nopriv_upload_to_sharefile_dashboard', 'upload_to_sharefile_dashboard' );
add_action( 'wp_ajax_upload_to_sharefile_dashboard', 'upload_to_sharefile_dashboard' );
function upload_to_sharefile_dashboard(){  
	$user_name = $_POST['name'];
    $files = $_FILES;
    if(isset($_POST['type'])){
        if($_POST['type'] == 'dashboard'){
            $c_name = 'Dashboard';
        }
    }else{
        $c_name = '';
    }
    if(count($files) > 0){
        // get token curl
        $username = "jglen@premierconsumer.org";
		$password = "krhxi3tulbqvmvjv";
		$client_id = "QrcY5YmSSi5srUp1u9tkOQfNlZqZ3XlI";
		$client_secret = "Hf9qBHjShAifPnYkBKxwrCqa1ehturC167x5ofCne6MDWZrj";      
        $uri = "https://premierconsumer.sharefile.com/oauth/token";
        $body_data = array("grant_type"=>"password", "client_id"=>$client_id, "client_secret"=>$client_secret,
                  "username"=>$username, "password"=>$password);
        $data = http_build_query($body_data);    

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        $curl_response = curl_exec ($ch); 

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error_number = curl_errno($ch);
        $curl_error = curl_error($ch);  
        curl_close ($ch);
        $token = '';
        if ($http_code == 200) {
            $res_data = json_decode($curl_response);
            if(isset($res_data->access_token)){
            	$token = $res_data->access_token;
            }
        }
        // end code
        if($token != ''){
        	$folder_id = 'fo86a48d-eb3b-400c-bc2b-ef0e2d41fb36';
        	$uri = "https://premierconsumer.sf-api.com/sf/v3/Items(".$folder_id.")/Upload";      
        	$headers = array("Authorization: Bearer ".$token);       
        	$ch = curl_init();
        	curl_setopt($ch, CURLOPT_URL, $uri);
        	curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        	curl_setopt($ch, CURLOPT_VERBOSE, FALSE);
        	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);       
        	$curl_response = curl_exec ($ch);      

        	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        	$curl_error_number = curl_errno($ch);
        	$curl_error = curl_error($ch);       

        	$upload_config = json_decode($curl_response);  
        	if ($http_code == 200) {
        		$i = 1;
        	    foreach($files as $key => $file){
        	        $type = $file['type'];
                    $name = $file['name'];
                    $form_file_name = $file['file_name'];
                    $data = $file['tmp_name'];
                    $filename = pathinfo($name, PATHINFO_FILENAME);
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    if($c_name != ''){
                        $newFilename = $c_name.'-'.$form_file_name.'-'.$filename.'.'.$extension;
                    }else{
                        $newFilename = $form_file_name.'-'.$filename.'.'.$extension;
                    }   

                	$post["File".$i] = new CurlFile(realpath($data),$type,$newFilename);
                	$i++;
                }               

        	    curl_setopt ($ch, CURLOPT_URL, $upload_config->ChunkUri);
            	curl_setopt ($ch, CURLOPT_POST, true);
            	curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
            	curl_setopt ($ch, CURLOPT_VERBOSE, FALSE);
            	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
            	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
            	curl_setopt ($ch, CURLOPT_HEADER, true);           

            	$upload_response = curl_exec ($ch);
        	}
        	curl_close ($ch);
        	/* send email to admin */
		    $user_name = $_POST['name'];
		    $sub = 'New Document on shareFile - Premier Consumer';
            $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
            $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
            $content = str_replace(
                array('%user_name%'),
                array($user_name),
                file_get_contents(get_stylesheet_directory().'/templates/admin_file_upload_notification.php')
            );
            wp_mail('jglen@premierconsumer.org', $sub, $content, $mheaders); // here admin email - shradha.sohal@xcelance.com
        	echo json_encode("success");
        }else{
            echo json_encode('missing_token');die;
        }
    }else{
    	echo json_encode('not_found');die;
    }die;
}

// Hooks near the bottom of profile page (if current user) 
add_action('show_user_profile', 'custom_user_profile_fields');
// Hooks near the bottom of the profile page (if not current user) 
add_action('edit_user_profile', 'custom_user_profile_fields');
function custom_user_profile_fields( $user ) {
    $user_id = $user->id;
    $subscription_plans = array('16336','16637');
    $find = pms_is_member_of_plan( $subscription_plans, $user_id );
    $status = get_user_meta( $user_id, 'verification_status', true );
    if($find && $status != ''){
?>
    <table class="form-table">
        <tr>
            <th>
                <label for="code"><?php _e( 'Poverty Line Verification Status' ); ?></label>
            </th>
            <td>
                <select name="vstatus">
                    <option value="pending" <?php if($status == 'pending') echo "selected"; ?>>Pending</option>
                    <option value="approve" <?php if($status == 'approve') echo "selected"; ?>>Approve</option>
                    <option value="decline" <?php if($status == 'decline') echo "selected"; ?>>Decline</option>
                </select>
            </td>
        </tr>
    </table>
<?php
    }
}

function update_extra_profile_fields($user_id) {
    if ( current_user_can('edit_user',$user_id) ){
        if(isset($_POST['vstatus'])){
        	update_user_meta($user_id, 'verification_status', $_POST['vstatus']);
            // update_user_meta($user_id, 'verification_status', $_POST['vstatus']);
            // if($_POST['vstatus'] == 'approve'){
            // 	$check_register = get_user_meta($user_id,'mim_user_id',true);
            //     if($check_register == ''){
            //         $record = get_user_by( 'id', $user_id );
            //         $user_email = $record->data->user_email;
            //         $first_name = ucfirst(get_user_meta( $user_id, 'first_name', true ));
            //         $last_name = ucfirst(get_user_meta( $user_id, 'last_name', true ));
            //         $current_date = date('d-m-Y');
            //         $courseLanguage = get_user_meta($user_id,'course_language',true);
            //         $courseReason   = get_user_meta($user_id,'course_reason',true);
            //         $BankruptcyDistrict = get_user_meta($user_id,'bankruptcy_district',true);
            //         $BankruptcyNumber   = get_user_meta($user_id,'bankruptcy_number',true);
            //         $phoneNumber   = get_user_meta($user_id,'mobile',true);
            //         $subscription_plan_free = array('16336');
            //         $subscription_plan_paid = array('16370');
            //         $subscription_plan_jpaid = array('19781');
            //         $c_subscription_plan_free = array('16637');
            //         $c_subscription_plan_paid = array('16638');
            //         $c_subscription_plan_jpaid = array('19780');
            //         $account_type = get_user_meta($user_id,'account_type', true);
            //         $spfname = $splname = $spmob = $spmail = "";
            //         $sdata = false;
            //         if($account_type == "joint"){
            //         	$spfname = get_user_meta($user_id, 'sp_fname', true);
            //         	$splname = get_user_meta($user_id, 'sp_lname', true);
            //         	$spmob = get_user_meta($user_id, 'sp_mobile', true);
            //         	$spmail = get_user_meta($user_id, 'sp_email', true);
            //         	$sdata = true;
            //         }
            //         $fee = '0.00';
            //         if(pms_is_member_of_plan( $subscription_plan_free, $user_id )){
            //             $fee = '0.00';
            //             $ctype = 'MIM';
            //             $mail_tmp_title = 'Debtor';
            //             $mail_tmp_t_content = 'Debtor';
            //             $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
            //         }
            //         if(pms_is_member_of_plan( $subscription_plan_paid, $user_id )){
            //             $fee = '50.00';
            //             $ctype = 'MIM';
            //             $mail_tmp_title = 'Debtor';
            //             $mail_tmp_t_content = 'Debtor';
            //             $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
            //         }
            //         if(pms_is_member_of_plan( $subscription_plan_jpaid, $user_id )){
            //             $fee = '75.00';
            //             $ctype = 'MIM';
            //             $mail_tmp_title = 'Debtor';
            //             $mail_tmp_t_content = 'Debtor';
            //             $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
            //         }
            //         if(pms_is_member_of_plan( $c_subscription_plan_free, $user_id )){
            //             $fee = '0.00';
            //             $ctype = 'CIMBK';
            //             $mail_tmp_title = 'Credit Counseling';
            //             $mail_tmp_t_content = 'Prefiling Credit Counseling';
            //             $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
            //         }
            //         if(pms_is_member_of_plan( $c_subscription_plan_paid, $user_id )){
            //             $fee = '50.00';
            //             $ctype = 'CIMBK';
            //             $mail_tmp_title = 'Credit Counseling';
            //             $mail_tmp_t_content = 'Prefiling Credit Counseling';
            //             $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
            //         }
            //         if(pms_is_member_of_plan( $c_subscription_plan_jpaid, $user_id )){
            //             $fee = '75.00';
            //             $ctype = 'CIMBK';
            //             $mail_tmp_title = 'Credit Counseling';
            //             $mail_tmp_t_content = 'Prefiling Credit Counseling';
            //             $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
            //         }

            //         // if($courseLanguage != '' && $courseReason != '' && $BankruptcyDistrict != '' && $BankruptcyNumber != ''){
            //         if($courseLanguage != ''){
            //         	if($sdata){
            //         		$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>'.$ctype.'</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason>'.$courseReason.'</CourseReason><CourseLanguage>'.$courseLanguage.'</CourseLanguage></CourseInformation><StudentInformation><LastName>'.$last_name.'</LastName><FirstName>'.$first_name.'</FirstName><Phone>'.$phoneNumber.'</Phone><Email>'.$user_email.'</Email><Fee>'.$fee.'</Fee><BankruptcyDistrict>'.$BankruptcyDistrict.'</BankruptcyDistrict><BankruptcyNumber>'.$BankruptcyNumber.'</BankruptcyNumber><Together>Y</Together><SpouseLastName>'.ucfirst($splname).'</SpouseLastName><SpouseFirstName>'.ucfirst($spfname).'</SpouseFirstName><SpousePhone>'.$spmob.'</SpousePhone><SpouseEmail>'.$spmail.'</SpouseEmail><SpouseFee>'.$fee.'</SpouseFee></StudentInformation></ACCERequest>';
            //         	}else{
            //             	$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>'.$ctype.'</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason>'.$courseReason.'</CourseReason><CourseLanguage>'.$courseLanguage.'</CourseLanguage></CourseInformation><StudentInformation><LastName>'.$last_name.'</LastName><FirstName>'.$first_name.'</FirstName><Phone>'.$phoneNumber.'</Phone><Email>'.$user_email.'</Email><Fee>'.$fee.'</Fee><BankruptcyDistrict>'.$BankruptcyDistrict.'</BankruptcyDistrict><BankruptcyNumber>'.$BankruptcyNumber.'</BankruptcyNumber></StudentInformation></ACCERequest>';
            //             }
            //             $url = "https://www.acce-online.com/pp/0433/parseuser_xml.php"; // URL
            //             $ch = curl_init($url);
            //             curl_setopt($ch, CURLOPT_POST, true);
            //             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            //             curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlstring=" . $xml);
            //             curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            //             $data = curl_exec($ch);
            //             $xmlResponse = (array)simplexml_load_string($data);
            //             if($xmlResponse['RequestOK'] == 'Y'){
            //                 // send email & send data to MIM
            //                 $b = (array)$xmlResponse['Bankruptcy'];
            //                 $login_id =  $b['UserID'];
            //                 $login_pass = $b['PassWord'];
            //                 update_user_meta($user_id,"mim_user_id",$login_id,false);
            //                 update_user_meta($user_id,"mim_user_password",$login_pass,false);
            //                 if($sdata){
		          //           	$slogin_id = $b['SpouseUserID'];
		          //           	$slogin_pass = $b['SpousePassWord'];
		          //           	update_user_meta($user_id,"mim_sp_user_id",$slogin_id,false);
		          //           	update_user_meta($user_id,"mim_sp_user_password",$slogin_pass,false);
		          //           	update_user_meta($user_id,"account_type","joint",false);
		          //           }
            //                 $sub = $mail_tmp_title.' Education Login - Premier Consumer';
            //                 $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
            //                 $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
            //                 if($sdata){
		          //           	$content = str_replace(
			         //                array('%name%', '%mim_user%', '%mim_pass%', '%mim_suser%', '%mim_spass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
			         //                array($first_name,  $login_id, $login_pass, $slogin_id, $slogin_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
			         //                file_get_contents(get_stylesheet_directory().'/templates/smim_register_user.php')
			         //            );
		          //           }else{
	           //                  $content = str_replace(
	           //                      array('%name%', '%mim_user%', '%mim_pass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
	           //                      array($first_name,  $login_id, $login_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
	           //                      file_get_contents(get_stylesheet_directory().'/templates/mim_register_user.php')
	           //                  );                         
	           //              }

            //                 $send_email = wp_mail($user_email, $sub, $content, $mheaders);
            //             }
            //         }                  
            //         update_user_meta($user_id, 'verification_status', $_POST['vstatus']);
            //     }
            // }elseif($_POST['vstatus'] == 'decline'){
            //     // send email to user
            //     update_user_meta($user_id, 'verification_status', $_POST['vstatus']);
            // }else{
            //     update_user_meta($user_id, 'verification_status', $_POST['vstatus']);
            // }
        }
    }
}
add_action('edit_user_profile_update', 'update_extra_profile_fields');

/* test api*/
function send_email_test(WP_REST_Request $request_data) {
	$user_id = 276;
	$user = get_user_by( 'id', $user_id );
	$name = $user->data->display_name;
	$email = $user->data->user_email;
	$s_mob = get_user_meta($user_id, "sp_mobile", true);
	var_dump($s_mob);
    // echo wp_loginout('/redirect/url/goes/here');die;
    // $user_ID = 220;
    // $user = get_user_by( 'id', $user_ID );
    // var_dump($user);
    die;
}
add_action( 'rest_api_init', function () {
    register_rest_route( 'consulting-child/v1', '/test-api', array(
        'methods' => 'GET',
        'callback' => 'send_email_test',
    ) );
} );
/* custom code for logout */
add_action( 'init', 'logout_check' );
function logout_check() {
    if(isset($_REQUEST['action'])){
        if($_REQUEST['action'] == 'custom-logout'){
            if ( is_user_logged_in() ) {
    			$user = wp_get_current_user();
    			wp_logout();
    			if ( ! empty( $_REQUEST['redirect_to'] ) ) {
    				$redirect_to           = $_REQUEST['redirect_to'];
    				$requested_redirect_to = $redirect_to;
    			} else {
    				$redirect_to = add_query_arg(
    					array(
    						'loggedout' => 'true',
    						'wp_lang'   => get_user_locale( $user ),
    					),
    					wp_login_url()
    				);
    				$requested_redirect_to = '';
    			}
    			$redirect_to = apply_filters( 'logout_redirect', $redirect_to, $requested_redirect_to, $user );
    			wp_safe_redirect( $redirect_to );
    			exit;
            }
        }
    }
}

/* Code to replace logout link */
function pc_custom_nav_link( $items, $args ) 
{
    if($args->menu && ($args->menu->slug == 'top-bar' || $args->menu->slug == 'mobile-menu' || $args->menu->slug == 'top-bar-spanish' || $args->menu->slug == 'mobile-menu-spanish')){
        global $wp;
        $lang = apply_filters( 'wpml_current_language', null );
        if(is_user_logged_in()){
        	$logout_link = wp_logout_url( home_url( $wp->request ) );
	        $logout_link = str_replace('wp-login.php?action=logout','?action=custom-logout',$logout_link);
        	if($lang == 'en'){	            
	            $logoutLink = '<li id="menu-item-8378" class="logout_last menu-item menu-item-type- menu-item-object-logout menu-item-8378"><a href="'.$logout_link.'">Logout</a></li>';
	        }else{
	            $logoutLink = '<li id="menu-item-8378" class="logout_last menu-item menu-item-type- menu-item-object-logout menu-item-8378"><a href="'.$logout_link.'">Cerrar sesión</a></li>';
	        }
            $items .= $logoutLink;
        }
    }
    return $items;
} 
add_filter( 'wp_nav_menu_items', 'pc_custom_nav_link', 10, 2 );

/* disable ssl for email */
add_filter('wp_mail_smtp_custom_options', function( $phpmailer ) {
	$phpmailer->SMTPOptions = array(
		'ssl' => array(
			'verify_peer'       => false,
			'verify_peer_name'  => false,
			'allow_self_signed' => true
		)
	);
	return $phpmailer;
} );


function change_heading_widget_content( $widget_content, $widget ) {
	if ( 'shortcode' === $widget->get_name() ) {		
		$settings = $widget->get_settings();
		if(isset($settings['shortcode']) && !empty($settings['shortcode'])){
			if(strpos($settings['shortcode'], '[pms-register') !== false){
				if(isset($_REQUEST['c']) && $_REQUEST['c'] != ''){
					$creq = $_REQUEST['c'];
					if($creq != ""){
						$exdata = explode("_", $creq);
						$coupon = isset($exdata[0]) ? $exdata[0] : "";
						$sub_id = isset($exdata[1]) ? $exdata[1] : "";
					}
					$getData = getCouponData($coupon, $sub_id);
					$lang = apply_filters( 'wpml_current_language', null );
				    if($lang == 'en'){
				    	$lang = 'english';
				    }elseif($lang == 'es'){
				    	$lang = 'spanish';
				    }
					if(isset($getData['old_plan']) && $getData['old_plan'] != ''){
						// if($lang == $getData['language']){
							if(strpos($settings['shortcode'], $getData['old_plan']) !== false){
								$widget_content = do_shortcode('[pms-register subscription_plans="'.$getData['new_plan'].'"]');
							}
						// }
					}					
				}
			}
		}
	}

	return $widget_content;

}
add_filter( 'elementor/widget/render_content', 'change_heading_widget_content', 10, 2 );


function getCouponData($coupon_code, $sub_id = null){
	$data = array(
		'old_plan' => '',
		'new_plan' => '',
		'language' => ''
	);
	

	if($sub_id != "" && $sub_id !== null){
		$args = array(
			'post_type'       => 'discountcodes',
		    'posts_per_page'   => -1,
		    'post_status'      => 'publish',
		    'meta_query' => array(
		    	'relation' => 'AND',
		        array(
		            'key'     => 'coupon_code',
		            'value'   => $coupon_code,
		            'compare' => '=',
		        ),
		        array(
		            'key'     => 'original_plan_id',
		            'value'   => $sub_id,
		            'compare' => '=',
		        ),
		    ),
		);
	}else{
		$args = array(
			'post_type'       => 'discountcodes',
		    'posts_per_page'   => -1,
		    'post_status'      => 'publish',
		    'meta_query' => array(
		        array(
		            'key'     => 'coupon_code',
		            'value'   => $coupon_code,
		            'compare' => '=',
		        ),
		    ),
		);
	}

	$get_post = get_posts( $args );

	if(count($get_post) > 0){
		foreach($get_post as $gp){
			$postid = $gp->ID;
			$data['old_plan'] = get_post_meta($postid, 'original_plan_id', true);
			$data['new_plan'] = get_post_meta($postid, 'replace_plan_id', true);
			$data['language'] = get_post_meta($postid, 'website_lang', true);
		}
		// print_r($data);
		// die('heer');
	}
	return $data;

}

function getSamePlan($sub_id){
	$same_plan = '';
    $args = array(
        'post_type'       => 'discountcodes',
        'posts_per_page'   => -1,
        'post_status'      => 'publish',
        'meta_query' => array(
            array(
                'key'     => 'replace_plan_id',
                'value'   => $sub_id,
                'compare' => '=',
            ),
        ),
    );

    $get_post = get_posts( $args );
    if(count($get_post) > 0){
        foreach($get_post as $gp){
            $postid = $gp->ID;
            $same_plan = get_post_meta($postid, 'original_plan_id', true);
        }
    }
    return $same_plan;
}

/* area code validation ajax */
add_action( 'wp_ajax_nopriv_check_area_code', 'check_area_code' );
add_action( 'wp_ajax_check_area_code', 'check_area_code' );
function check_area_code(){  
	$areacode = $_POST['areacode'];
    if($areacode != ""){
    	$codes = array(907 => "AK",205 => "AL",256 => "AL",334 => "AL",251 => "AL",659 => "AL",938 => "AL",870 => "AR",501 => "AR",479 => "AR",480 => "AZ",623 => "AZ",928 => "AZ",602 => "AZ",520 => "AZ",840 => "CA",820 => "CA",350 => "CA",279 => "CA",628 => "CA",341 => "CA",925 => "CA",909 => "CA",562 => "CA",661 => "CA",657 => "CA",510 => "CA",650 => "CA",949 => "CA",760 => "CA",415 => "CA",951 => "CA",752 => "CA",831 => "CA",209 => "CA",669 => "CA",408 => "CA",559 => "CA",626 => "CA",442 => "CA",530 => "CA",916 => "CA",707 => "CA",714 => "CA",310 => "CA",323 => "CA",213 => "CA",424 => "CA",747 => "CA",818 => "CA",858 => "CA",619 => "CA",805 => "CA",720 => "CO",303 => "CO",970 => "CO",719 => "CO",983 => "CO",203 => "CT",959 => "CT",475 => "CT",860 => "CT",202 => "DC",302 => "DE",689 => "FL",407 => "FL",239 => "FL",836 => "FL",727 => "FL",321 => "FL",754 => "FL",954 => "FL",352 => "FL",863 => "FL",904 => "FL",386 => "FL",561 => "FL",772 => "FL",786 => "FL",305 => "FL",861 => "FL",941 => "FL",813 => "FL",850 => "FL",448 => "FL",656 => "FL",478 => "GA",770 => "GA",470 => "GA",404 => "GA",706 => "GA",678 => "GA",912 => "GA",229 => "GA",943 => "GA",762 => "GA",808 => "HI",515 => "IA",319 => "IA",563 => "IA",641 => "IA",712 => "IA",208 => "ID",986 => "ID",217 => "IL",779 => "IL",872 => "IL",312 => "IL",773 => "IL",464 => "IL",708 => "IL",815 => "IL",224 => "IL",847 => "IL",618 => "IL",309 => "IL",331 => "IL",630 => "IL",447 => "IL",618 => "IL",765 => "IN",574 => "IN",260 => "IN",219 => "IN",317 => "IN",812 => "IN",930 => "IN",463 => "IN",913 => "KS",785 => "KS",316 => "KS",620 => "KS",327 => "KY",502 => "KY",859 => "KY",606 => "KY",270 => "KY",364 => "KY",504 => "LA",985 => "LA",225 => "LA",318 => "LA",337 => "LA",774 => "MA",508 => "MA",781 => "MA",339 => "MA",857 => "MA",617 => "MA",978 => "MA",351 => "MA",413 => "MA",443 => "MD",410 => "MD",667 => "MD",249 => "MD",969 => "MD",240 => "MD",301 => "MD",207 => "ME",383 => "ME",517 => "MI", 546 => "MI",810 => "MI", 278 => "MI",313 => "MI", 586 => "MI", 248 => "MI", 734 => "MI", 269 => "MI", 906 => "MI", 989 => "MI", 616 => "MI", 231 => "MI", 679 => "MI",947 => "MI", 612 => "MN",320 => "MN",651 => "MN",763 => "MN",952 => "MN",218 => "MN",507 => "MN",636 => "MO",660 => "MO",975 => "MO",816 => "MO",314 => "MO",557 => "MO",573 => "MO",417 => "MO",601 => "MS",662 => "MS",228 => "MS",769 => "MS",406 => "MT",336 => "NC",252 => "NC",984 => "NC",919 => "NC",980 => "NC",910 => "NC",828 => "NC",704 => "NC",743 => "NC",472 => "NC",701 => "ND",402 => "NE",308 => "NE",531 => "NE",603 => "NH",908 => "NJ",848 => "NJ",732 => "NJ",551 => "NJ",201 => "NJ",862 => "NJ",973 => "NJ",609 => "NJ",856 => "NJ",640 => "NJ",505 => "NM",575 => "NM",957 => "NM",702 => "NV",725 => "NV",775 => "NV",315 => "NY",518 => "NY",716 => "NY",585 => "NY",646 => "NY",347 => "NY",718 => "NY",212 => "NY",516 => "NY",917 => "NY",845 => "NY",631 => "NY",607 => "NY",914 => "NY",680 => "NY",838 => "NY",363 => "NY",934 => "NY",929 => "NY",332 => "NY",216 => "OH",330 => "OH",234 => "OH",567 => "OH",419 => "OH",380 => "OH",440 => "OH",740 => "OH",614 => "OH",326 => "OH",513 => "OH",937 => "OH",220 => "OH",513 => "OH",918 => "OK",539 => "OK",580 => "OK",405 => "OK",572 => "OK",503 => "OR",971 => "OR",541 => "OR",458 => "OR",814 => "PA",717 => "PA",570 => "PA",358 => "PA",878 => "PA",835 => "PA",484 => "PA",610 => "PA",445 => "PA",267 => "PA",215 => "PA",724 => "PA",412 => "PA",582 => "PA",223 => "PA",272 => "PA",939 => "PR",787 => "PR",401 => "RI",843 => "SC",854 => "SC",864 => "SC",803 => "SC",839 => "SC",605 => "SD",423 => "TN",629 => "TN",865 => "TN",931 => "TN",615 => "TN",901 => "TN",731 => "TN",254 => "TX",325 => "TX",713 => "TX",940 => "TX",817 => "TX",430 => "TX",903 => "TX",806 => "TX",737 => "TX",512 => "TX",361 => "TX",210 => "TX",936 => "TX",409 => "TX",979 => "TX",972 => "TX",469 => "TX",214 => "TX",682 => "TX",832 => "TX",281 => "TX",830 => "TX",956 => "TX",432 => "TX",915 => "TX",726 => "TX",945 => "TX",346 => "TX",435 => "UT",801 => "UT",385 => "UT",434 => "VA",804 => "VA",757 => "VA",948 => "VA",826 => "VA",703 => "VA",571 => "VA",540 => "VA",276 => "VA",381 => "VA",236 => "VA",802 => "VT",509 => "WA",360 => "WA",564 => "WA",206 => "WA",425 => "WA",253 => "WA",715 => "WI",534 => "WI",920 => "WI",414 => "WI",262 => "WI",608 => "WI",353 => "WI",420 => "WI",304 => "WV",681 => "WV",307 => "WY", 552 =>"WY");
    	if (array_key_exists($areacode,$codes)){
    		echo "exist";
    	}else{
    		echo "not_exist";
    	}
    }else{
    	echo "try_again";
    }
    die;
}

/* TEQTOP - custom code for tabs start */
add_filter( 'pms_member_account_tabs', 'pmsc_change_member_account_tabs', 20, 2 );
function pmsc_change_member_account_tabs( $tabs, $args ) {
	$slang = apply_filters( 'wpml_current_language', null );
	$old_tabs = array_reverse($tabs);
	if($slang == "en"){
	    $old_tabs['documents'] = __( 'Upload Documents', 'paid-member-subscriptions' );
	}else{
	    $old_tabs['documents'] = __( 'Cargar documentos', 'paid-member-subscriptions' );
	    $old_tabs['payments'] = __( 'Mi cuenta', 'paid-member-subscriptions' );
	    $old_tabs['profile'] = __( 'Editar perfil', 'paid-member-subscriptions' );
	    $old_tabs['subscriptions'] = __( 'Suscripciones', 'paid-member-subscriptions' );
	}
	$sort_tabs = array_reverse($old_tabs);
 
    return $sort_tabs;
}
add_filter( 'pms_member_account_logout_tab', '__return_false' );

/* template redirect code - after plugin update */
function pc_custom_redirect() {
	global $wp; 
    if(is_user_logged_in()){
    	$user_id = get_current_user_id();
    	if($wp->request == "clase-de-educacion-para-acreedores" || $wp->request == "register-creditor-education" || $wp->request == "exencion-de-responsabilidad-del-acreedor" ||  $wp->request == "creditor-education-waiver"){
    		if(pms_is_member_of_plan( array("16637","16638","19780"), $user_id )){
    			wp_redirect( home_url( '/dashboard' ) );
                exit();
    		}
    	}
    	if($wp->request == "registrar-la-educacion-del-deudor" || $wp->request == "registrese-deudor-educacion-mas-ondulado" || $wp->request == "register-debtor-education" ||  $wp->request == "register-debtor-education-waiver"){
    		if(pms_is_member_of_plan( array("19781","16370","16336"), $user_id )){
    			wp_redirect( home_url( '/dashboard' ) );
                exit();
    		}
    	}
    }else{
    	if($wp->request == "dashboard"){
    		wp_redirect( home_url( '/login' ) );
    	}
    }
}
add_action( 'template_redirect', 'pc_custom_redirect' );

add_action('wp_logout','auto_redirect_after_logout');

function auto_redirect_after_logout(){
  	wp_safe_redirect( home_url() );
  	exit;
}

function after_new_subscription($data){
	$site_lang = apply_filters( 'wpml_current_language', null );
	$meta = "";
	$sdata = false;
	if(is_user_logged_in()){
		$sub_id = $data['subscription_plans'];
	    $user_id = get_current_user_id();
	    $user = get_user_by( 'id', $user_id );
	    $credit_monitoring_plans = array("12039","11984","7231","17015","19950","19953","22874","22873","22875");
        $free_plans = array("16336","16637");
        $paid_plans = array("19781","16370","19780","16638");
	    if(isset($data["mobile"])){
			update_user_meta($user_id,"mobile",$data["mobile"],false);
	    }
		if(isset($data['person'])){
		    update_user_meta($user_id,"no_of_person",$data["person"],false);
		}
		if(isset($data['clang'])){
		    update_user_meta($user_id,"course_language",$data["clang"],false);
		}
		if(isset($data['creason'])){
		    update_user_meta($user_id,"course_reason",$data["creason"],false);
		}
		if(isset($data['bdistrict'])){
		    update_user_meta($user_id,"bankruptcy_district",$data["bdistrict"],false);
		}
		if(isset($data['bnumber'])){
		    update_user_meta($user_id,"bankruptcy_number",$data["bnumber"],false);
		}
		$account_type = "";
		if(in_array($sub_id, $free_plans)){
			if(isset($data['tclass'])){
				if($data['tclass'] == "single"){
					update_user_meta($user_id,"account_type","single",false);
					$account_type = "single";
				}else{
					update_user_meta($user_id,"account_type","joint",false);				
					$account_type = "joint";
				}
			}
		}elseif(in_array($sub_id, $paid_plans)){
			if($sub_id == "19780" || $sub_id == "19781"){
				$account_type = "joint";
			}else{
				$account_type = "single";
			}
		}
		/* spouse data */
		if(isset($data['sp_fname']) && $data['sp_fname'] != ""){
			update_user_meta($user_id,"sp_fname",$data['sp_fname'],false);
		}
		if(isset($data['sp_lname']) && $data['sp_lname'] != ""){
			update_user_meta($user_id,"sp_lname",$data['sp_lname'],false);
		}
		if(isset($data['sp_mobile']) && $data['sp_mobile'] != ""){
			update_user_meta($user_id,"sp_mobile",$data['sp_mobile'],false);
		}
		if(isset($data['sp_email']) && $data['sp_email'] != ""){
			update_user_meta($user_id,"sp_email",$data['sp_email'],false);
		}
		/* spouse data - end */
		
		if(isset($data['clang']) && isset($data['course_type'])){
			if($sub_id == "16637" || $sub_id == "16336"){
				$fee = '0.00';
			}elseif($sub_id == "19780" || $sub_id == "19781"){
				$fee = '75.00';
			}elseif($sub_id == "16638" || $sub_id == "16370"){
				$fee = '50.00';
			}
			if($account_type == "joint"){
				$sdata = true;
				if(!isset($data['sp_fname']) && !isset($data['sp_lname'])){
					$sfname = get_user_meta($user_id, "sp_fname", true);
					$slname = get_user_meta($user_id, "sp_lname", true);
					$semail = get_user_meta($user_id, "sp_email", true);
					$sphone = get_user_meta($user_id, "sp_mobile", true);
				}else{
					$sfname = $data['sp_fname'];
					$slname = $data['sp_lname'];
					$semail = $data['sp_email'];
					$sphone = $data['sp_mobile'];
				}
			}else{
				$sdata = false;
			}

            $user_email = $user->data->user_email;
            $display_name = explode(' ', $user->data->display_name);
            $first_name = $display_name[0];
            $last_name = $display_name[1];
            $current_date = date('d-m-Y');
            $courseLanguage = $data['clang'];
            $courseReason   = $data['creason'];
            $BankruptcyDistrict = isset($data['bdistrict']) ? $data['bdistrict'] : "";
            $BankruptcyNumber   = isset($data['bnumber']) ? $data['bnumber'] : "";
            $phoneNumber   = isset($data['mobile']) ? $data['mobile'] : get_user_meta($user_id, "mobile", true);
            $courseName = $data['course_type'];
            if($site_lang == "es"){
            	if($courseName == 'CIMBK'){
		            $mail_tmp_title = 'Asesoramiento crediticio';
		            $mail_tmp_t_content = 'Asesoría de crédito antes de la declaración de Bancarrota';
		            $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
		        }else {
		            $mail_tmp_title = 'Deudor';
		            $mail_tmp_t_content = 'Deudor';
		            $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
		        }
            }else{
	            if($courseName == 'CIMBK'){
	                $mail_tmp_title = 'Credit Counseling';
	                $mail_tmp_t_content = 'Prefiling Credit Counseling';
	                $login_link = 'http://www.counselinginmotion.com/studentlogin.php';
	            }else {
	                $mail_tmp_title = 'Debtor';
	                $mail_tmp_t_content = 'Debtor';
	                $login_link = 'https://www.moneyinmotion.us/studentlogin.php';
	            }
	        }
            if($courseLanguage != ''){
            	if($sdata){
            		$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>'.$courseName.'</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason>'.$courseReason.'</CourseReason><CourseLanguage>'.$courseLanguage.'</CourseLanguage></CourseInformation><StudentInformation><LastName>'.$last_name.'</LastName><FirstName>'.$first_name.'</FirstName><Phone>'.$phoneNumber.'</Phone><Email>'.$user_email.'</Email><Fee>'.$fee.'</Fee><BankruptcyDistrict>'.$BankruptcyDistrict.'</BankruptcyDistrict><BankruptcyNumber>'.$BankruptcyNumber.'</BankruptcyNumber><Together>Y</Together><SpouseLastName>'.ucfirst($slname).'</SpouseLastName><SpouseFirstName>'.ucfirst($sfname).'</SpouseFirstName><SpousePhone>'.$sphone.'</SpousePhone><SpouseEmail>'.$semail.'</SpouseEmail><SpouseFee>'.$fee.'</SpouseFee></StudentInformation></ACCERequest>';
            	}else{
                	$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>'.$courseName.'</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason>'.$courseReason.'</CourseReason><CourseLanguage>'.$courseLanguage.'</CourseLanguage></CourseInformation><StudentInformation><LastName>'.$last_name.'</LastName><FirstName>'.$first_name.'</FirstName><Phone>'.$phoneNumber.'</Phone><Email>'.$user_email.'</Email><Fee>'.$fee.'</Fee><BankruptcyDistrict>'.$BankruptcyDistrict.'</BankruptcyDistrict><BankruptcyNumber>'.$BankruptcyNumber.'</BankruptcyNumber></StudentInformation></ACCERequest>';
                }
                $url = "https://www.acce-online.com/pp/0433/parseuser_xml.php"; // URL                 

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlstring=" . $xml);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                $xmlResponse = (array)simplexml_load_string($data);
                if($xmlResponse['RequestOK'] == 'Y'){
                    // send email & send data to MIM
                    $b = (array)$xmlResponse['Bankruptcy'];
                    $login_id =  $b['UserID'];
                    $login_pass = $b['PassWord'];
                    /* 01-dec code start */
                    if($courseName == 'CIMBK'){
                    	$meta = "cim";
                    }else{
                    	$meta = "mim";
                    }
                    update_user_meta($user_id, $meta."_user_id",$login_id,false);
                    update_user_meta($user_id, $meta."_user_password",$login_pass,false);
                    if($sdata){
                    	$slogin_id = $b['SpouseUserID'];
                    	$slogin_pass = $b['SpousePassWord'];
                    	update_user_meta($user_id, $meta."_sp_user_id",$slogin_id,false);
                    	update_user_meta($user_id, $meta."_sp_user_password",$slogin_pass,false);
                    	update_user_meta($user_id, $meta."_account_type","joint",false);
                    }else{
                    	update_user_meta($user_id, $meta."_account_type","single",false);
                    }
                    if($site_lang == "es"){
                    	$sub = $mail_tmp_title.' acceso a la educación - Premier Consumer';
                    }else{
	                    $sub = $mail_tmp_title.' Education Login - Premier Consumer';
                    }
                    $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
                    $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
                    if($site_lang == "es"){
                    	if($sdata){
	                    	$content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%mim_suser%', '%mim_spass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $slogin_id, $slogin_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/smim_register_user_es.php')
		                    );
	                    }else{
		                    $content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/mim_register_user_es.php')
		                    );
		                }
                    }else{
	                    if($sdata){
	                    	$content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%mim_suser%', '%mim_spass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $slogin_id, $slogin_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/smim_register_user.php')
		                    );
	                    }else{
		                    $content = str_replace(
		                        array('%name%', '%mim_user%', '%mim_pass%', '%tmp_title%', '%login_link%', '%tmp_t_content%'),
		                        array($first_name,  $login_id, $login_pass, $mail_tmp_title, $login_link, $mail_tmp_t_content),
		                        file_get_contents(get_stylesheet_directory().'/templates/mim_register_user.php')
		                    );
		                }
		            }
                    $send_email = wp_mail($user_email, $sub, $content, $mheaders);
                }
            }
        }
        send_new_sub_mail_to_admin($data, $meta);
	}
}

function send_new_sub_mail_to_admin($user_data, $type){
	$site_lang = apply_filters( 'wpml_current_language', null );
	$plan_name = $program = "";
	$user_id = get_current_user_id();
	$user = get_user_by( 'id', $user_id );
	$name = $user->data->display_name;
	$email = $user->data->user_email;
	$phone = get_user_meta( $user_id, "mobile", true );
	$household = get_user_meta($user_id, "no_of_person", true);
	$income =  get_user_meta($user_id, "yearly_income", true);
	$reason =  get_user_meta($user_id, "course_reason", true);
	$bnumber =  get_user_meta($user_id, "bankruptcy_number", true);
	$f_name = get_user_meta($user_id, "sp_fname", true);
	$l_name = get_user_meta($user_id, "sp_lname", true);
	$s_email = get_user_meta($user_id, "sp_email", true);
	$s_mob = get_user_meta($user_id, "sp_mobile", true);
	if($s_email !== null && $s_email !== false && $s_email != ""){
		$s_name = $f_name.' '.$l_name;
		$sp_data = true;
	}
	$content = $sub_id = "";
	if(isset($user_data['subscription_plans'])){
		$sub_id = $user_data['subscription_plans'];
		if($sub_id == "7231" || $sub_id == "11984" || $sub_id == "12039" || $sub_id == "17015" || $sub_id == "22874" || $sub_id == "22873" || $sub_id == "22875" || $sub_id == "19950" || $sub_id == "19953"){
			$program = "Credit Monitoring";
		}elseif($sub_id == "16637" || $sub_id == "16638" || $sub_id == "19780"){
			$program = "Pre-Filing Credit Counseling";
		}elseif($sub_id == "16336" || $sub_id == "16370" || $sub_id == "19781"){
			$program = "Post-Filing Debtor Education";
		}
		$plan_info = get_post($sub_id);
		if(isset($plan_info->post_title)){
			$plan_name = $plan_info->post_title;
		}
	}
	if($site_lang == "es"){
		if($plan_name == "Creditor Education Joint Class"){
			$plan_name = "Clase conjunta de formación de acreedores";
		}elseif($plan_name == "Creditor Education Single Class"){
			$plan_name = "Educación del Acreedor Clase Única";
		}elseif($plan_name == "Creditor Education Waiver"){
			$plan_name = "Renuncia a la educación del acreedor";
		}elseif($plan_name == "Debtor Education Joint Class"){
			$plan_name = "Clase conjunta de educación del deudor";
		}elseif($plan_name == "Debtor Education Single Class"){
			$plan_name = "Educación del deudor Clase única";
		}elseif($plan_name == "Debtor Education Waiver"){
			$plan_name = "Renuncia a la educación del deudor";
		}elseif($plan_name == "Subscription Plan Max Advance with 7 day free trial"){
			$plan_name = "Plan de suscripción Max Advance con 7 días de prueba gratuita";
		}elseif($plan_name == "Subscription Plan Max Advanced"){
			$plan_name = "Plan de suscripción Max Advanced";
		}elseif($plan_name == "Subscription Plan Max Pro"){
			$plan_name = "Plan de suscripción Max Pro";
		}elseif($plan_name == "Subscription Plan Max Plus"){
			$plan_name = "Plan de suscripción Max Plus";
		}elseif($plan_name == "Subscription Plan Max Plus with 7 day free trial"){
			$plan_name = "Plan de suscripción Max Plus con 7 días de prueba gratuita";
		}elseif($plan_name == "Subscription Plan Max Pro with 7 day free trial"){
			$plan_name = "Plan de suscripción Max Pro con 7 días de prueba gratuita";
		}

	}
	$sub = 'A New User has registered to your website';
    $mheaders[] = 'From: Premier Consumer <thankyou@premierconsumer.org>';
    $mheaders[] = 'Content-Type: text/html; charset=UTF-8';
    if($sub_id == "7231" || $sub_id == "11984" || $sub_id == "12039" || $sub_id == "17015" || $sub_id == "22874" || $sub_id == "22873" || $sub_id == "22875" || $sub_id == "19950" || $sub_id == "19953"){
    	$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%'),
	        array($name,  $program, $email, $plan_name, $phone),
	        file_get_contents(get_stylesheet_directory().'/templates/cm_register_mailto_admin.php')
	    );
    }elseif($sub_id == "16637"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%user_household%', '%user_income%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $household, $income, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/free_register_mailto_admin.php')
	    );
	}elseif($sub_id == "16336"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%user_household%', '%user_income%', '%course_reason%', '%bnumber%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $household, $income, $reason, $bnumber, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/free_mim_register_mailto_admin.php')
	    );
	}elseif($sub_id == "16638" || $sub_id == "19780"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/paid_register_mailto_admin.php')
	    );
	}elseif($sub_id == "16370" || $sub_id == "19781"){
		$content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%', '%user_phone%', '%course_reason%', '%bnumber%', '%sname%', '%sphone%', '%semail%'),
	        array($name,  $program, $email, $plan_name, $phone, $reason, $bnumber, $s_name, $s_email, $s_mob),
	        file_get_contents(get_stylesheet_directory().'/templates/paid_mim_register_mailto_admin.php')
	    );
	}else{
	    $content = str_replace(
	        array('%user_name%', '%program%', '%user_email%', '%p_plan%'),
	        array($name,  $program, $email, $plan_name),
	        file_get_contents(get_stylesheet_directory().'/templates/register_mail_to_admin.php')
	    );
	}
    $send_email = wp_mail("jglen@premierconsumer.org", $sub, $content, $mheaders);
    // $send_email1 = wp_mail("shradha.sohal@xcelance.com", $sub, $content, $mheaders);
}
/* TEQTOP -  custom code for tabs end */

function custom_query_callback( $query ) {
	$today = date("D");
	$month = date("M");
	$d = date("d");
	$day = date("d-m-Y");

	if($month == "Jan"){
		if($d == "01"){
			$tax_id[] = 168;
		}
		$third_jan_mon = new DateTime('third monday of January');
		$fthird_jan_mon = $third_jan_mon->format('d-m-Y');
		if($day == $fthird_jan_mon){
			$tax_id[] = 171; // Martin Luther King’s Birthday - 171
		}
	}
	if($month == "Feb"){
		$third_feb_mon = new DateTime('third monday of February');
		$fthird_feb_mon = $third_feb_mon->format('d-m-Y');
		if($day == $fthird_feb_mon){
			$tax_id[] = 173; // Washington’s Birthday - 173
		}
	}
	if($month == "May"){
		$last_may_mon = new DateTime('last monday of May');
		$flast_may_mon = $last_may_mon->format('d-m-Y');
		if($day == $flast_may_mon){
			$tax_id[] = 175; // Memorial Day - 175
		}
	}
	if($month == "Jun"){
		if($d == "19"){
			$tax_id[] = 177; // Juneteenth National Independence Day - 177
		}
	}
	if($month == "Jul"){
		if($d == "04"){
			$tax_id[] = 179; // Independence Day - 179
		}
	}
	if($month == "Sep"){
		$first_sep_mon = new DateTime('first monday of September');
		$ffirst_sep_mon = $first_sep_mon->format('d-m-Y');
		if($day == $ffirst_sep_mon){
			$tax_id[] = 181; // Labor Day - 181
		}
	}
	if($month == "Oct"){
		$sec_oct_mon = new DateTime('second monday of October');
		$fsec_oct_mon = $sec_oct_mon->format('d-m-Y');
		if($day == $fsec_oct_mon){
			$tax_id[] = 183; // Columbus Day - 183
		}
	}
	if($month == "Nov"){
		if($d == "11"){
			$tax_id[] = 185; // Veterans’ Day  - 185
		}
		$fourth_nov_thr = new DateTime('fourth Thursday of November');
		$ffourth_nov_thr = $fourth_nov_thr->format('d-m-Y');
		if($day == $ffourth_nov_thr){
			$tax_id[] = 187; // Thanksgiving Day - 187
		}
	}
	if($month == "Dec"){
		if($d == "25"){
			$tax_id[] = 189; // Christmas Day - 189
		}
	}
	
	if($today == "Mon"){
		$tax_id[] = 152;
	}elseif($today == "Tue"){
		$tax_id[] = 153;
	}elseif($today == "Wed"){
		$tax_id[] = 154;
	}elseif($today == "Thu"){
		$tax_id[] = 155;
	}elseif($today == "Fri"){
		$tax_id[] = 156;
	}elseif($today == "Sat"){
		$tax_id[] = 157;
	}elseif($today == "Sun"){
		$tax_id[] = 158;
	}else{
		$tax_id[] = 147;
	}
	$tax_query = array(
					array(
						'taxonomy' => "post_tag",
						'field' => "term_taxonomy_id",
						'terms' => $tax_id
					)
				);
	$query->set( 'tax_query', $tax_query);
}
add_action( 'elementor/query/custom_posts', 'custom_query_callback' );

function custom_query_callback_es( $query ) {
	$today = date("D");
	$month = date("M");
	$d = date("d");
	$day = date("d-m-Y");

	if($month == "Jan"){
		if($d == "01"){
			$tax_id[] = 170;
		}
		$third_jan_mon = new DateTime('third monday of January');
		$fthird_jan_mon = $third_jan_mon->format('d-m-Y');
		if($day == $fthird_jan_mon){
			$tax_id[] = 172; // Martin Luther King’s Birthday - 171
		}
	}
	if($month == "Feb"){
		$third_feb_mon = new DateTime('third monday of February');
		$fthird_feb_mon = $third_feb_mon->format('d-m-Y');
		if($day == $fthird_feb_mon){
			$tax_id[] = 174; // Washington’s Birthday - 173
		}
	}
	if($month == "May"){
		$last_may_mon = new DateTime('last monday of May');
		$flast_may_mon = $last_may_mon->format('d-m-Y');
		if($day == $flast_may_mon){
			$tax_id[] = 176; // Memorial Day - 175
		}
	}
	if($month == "Jun"){
		if($d == "19"){
			$tax_id[] = 178; // Juneteenth National Independence Day - 177
		}
	}
	if($month == "Jul"){
		if($d == "04"){
			$tax_id[] = 180; // Independence Day - 179
		}
	}
	if($month == "Sep"){
		$first_sep_mon = new DateTime('first monday of September');
		$ffirst_sep_mon = $first_sep_mon->format('d-m-Y');
		if($day == $ffirst_sep_mon){
			$tax_id[] = 182; // Labor Day - 181
		}
	}
	if($month == "Oct"){
		$sec_oct_mon = new DateTime('second monday of October');
		$fsec_oct_mon = $sec_oct_mon->format('d-m-Y');
		if($day == $fsec_oct_mon){
			$tax_id[] = 184; // Columbus Day - 183
		}
	}
	if($month == "Nov"){
		if($d == "11"){
			$tax_id[] = 186; // Veterans’ Day  - 185
		}
		$fourth_nov_thr = new DateTime('fourth Thursday of November');
		$ffourth_nov_thr = $fourth_nov_thr->format('d-m-Y');
		if($day == $ffourth_nov_thr){
			$tax_id[] = 188; // Thanksgiving Day - 187
		}
	}
	if($month == "Dec"){
		if($d == "25"){
			$tax_id[] = 190; // Christmas Day - 189
		}
	}
	
	
	if($today == "Mon"){
		$tax_id[] = 159;
	}elseif($today == "Tue"){
		$tax_id[] = 160;
	}elseif($today == "Wed"){
		$tax_id[] = 161;
	}elseif($today == "Thu"){
		$tax_id[] = 162;
	}elseif($today == "Fri"){
		$tax_id[] = 163;
	}elseif($today == "Sat"){
		$tax_id[] = 164;
	}elseif($today == "Sun"){
		$tax_id[] = 165;
	}else{
		$tax_id[] = 149;
	}
	$tax_query = array(
					array(
						'taxonomy' => "post_tag",
						'field' => "term_taxonomy_id",
						'terms' => $tax_id
					)
				);
	$query->set( 'tax_query', $tax_query);
	$query->set( 'orderby', 'rand');
}
add_action( 'elementor/query/custom_posts_es', 'custom_query_callback_es' );


/* Disable Widgets Block Editor */
add_filter( 'use_widgets_block_editor', '__return_false' );

// Meta Box for generate HTML
function wp_pc_add_cta_metabox() {
    add_meta_box(
        'generate_html',
        'Generate HTML', /* This is the title of the metabox */
        'wp_pc_request_cta_html',
        'newsletterhtmlpages', /* post type */
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wp_pc_add_cta_metabox' );

function wp_pc_request_cta_html() {
    global $post;
    wp_nonce_field( 'request_send_post_details', 'request_cta_nonce' );
    ?>
    <style>
    	#generate_html{
    		width:1000px;
    	}
    	#wpseo_meta, #slugdiv, #pms_post_content_restriction{
    		display: none;
    	}
    	a#btn-call-to-action {
		    width: 20%;
		    text-align: center;
		    padding: 5px 20px;
		    font-size: 16px;
		    cursor: pointer;
		}
		div .output-div{
			padding: 10px 10px;
    		border: 1px solid #2271b1;
    		margin-top: 10px;    		
		}
		#preview-html-page {
			float: right;
			width: 20%;
		    padding: 5px;
		    font-size: 16px;
		    cursor: pointer;
		}
		#html-output{
			width: 100%;
		    float: left;
		    position: relative;
		    z-index: 1;
		}
		.hide{
			display: none;
		}
    </style>
    <a href="#" id="btn-call-to-action" class="button button-primary widefat">Generate HTML</a>
    <button id="preview-html-page" type="button" class="button button-primary widefat" style="display: none;">Preview  Newsletter</button>
    <div id="html-output" class="output-div hide">
    </div>
    <div id="html-code-output" class="output-div hide">
    	<pre></pre>
    	<button class="button button-primary copy-text" type="button" style="padding: 5px 20px;">Copy Code</button>
    </div>
    <script>
        jQuery(function($){
            // Handle button click
            $( "#generate_html #btn-call-to-action" ).on("click", function(e){
                e.preventDefault();
                var gbtn = $(this);
                gbtn.text("Generating HTML...");
                var nonce = $(this).parent().find("#request_cta_nonce").val();
                var banner_img = jQuery('div[data-name="banner_image"] input').val();
                var banner_txt = jQuery('div[data-name="banner_text"] input').val();
                var banner_link_txt = jQuery('div[data-name="banner_link_text"] input').val();
                var banner_link = jQuery('div[data-name="banner_link"] input').val();
                var newsletter_top_title = jQuery('div[data-name="newsletter_top_title"] input').val();
                var newsletter_other_language_text = jQuery('div[data-name="newsletter_other_language_text"] input').val();
                var newsletter_image = jQuery('div[data-name="newsletter_image"] input').val();
                var newsletter_title = jQuery('div[data-name="newsletter_title"] input').val();
                var newsletter_description = jQuery('div[data-name="newsletter_description"] textarea').val();
                var newsletter_read_more = jQuery('div[data-name="newsletter_read_more"] input').val();
                var newsletter_link = jQuery('div[data-name="newsletter_link"] input').val();
                var other_language_newsletter_link = jQuery('div[data-name="other_language_newsletter_link"] input').val();
                var article_img = jQuery('div[data-name="article_image"] input').val();
                var article_top_heading = jQuery('div[data-name="article_top_title"] input').val();
                var article_title = jQuery('div[data-name="article_title"] input').val();
                var article_desc = jQuery('div[data-name="article_description"] textarea').val();
                var a_read_more = jQuery('div[data-name="article_read_more_text"] input').val();
                var a_link = jQuery('div[data-name="article_link"] input').val();
                var top_sec_heading = jQuery('div[data-name="top_section_heading"] input').val();
                var top_section_image = jQuery('div[data-name="top_section_image"] input').val();
                var btn_txt = jQuery('div[data-name="button_text"] input').val();
                var top_btn_link = jQuery('div[data-name="button_link"] input').val();
                var qpimg = jQuery('div[data-name="quizz_poll_image"] input').val();
                var qptitle = jQuery('div[data-name="quizz_poll_title"] input').val();
                var qplinktxt = jQuery('div[data-name="quizz_poll_link_text"] input').val();
                var qplink = jQuery('div[data-name="quizz_poll_link"] input').val();
                var edtitle = jQuery('div[data-name="section_title"] input').val();
                var ar_1_t = jQuery('div[data-name="article_1_title"] input').val();
                var ar_2_t = jQuery('div[data-name="article_2_title"] input').val();
                var ar_3_t = jQuery('div[data-name="article_3_title"] input').val();
                var ar_1_link = jQuery('div[data-name="article_1_link"] input').val();
                var ar_2_link = jQuery('div[data-name="article_2_link"] input').val();
                var ar_3_link = jQuery('div[data-name="article_3_link"] input').val();
                var ar_1_desc = jQuery('div[data-name="article_1_description"] textarea').val();
                var ar_2_desc = jQuery('div[data-name="article_2_description"] textarea').val();
                var ar_3_desc = jQuery('div[data-name="article_3_description"] textarea').val();
                var nw_title = jQuery('div[data-name="news_title"] input').val();
                var nw_img = jQuery('div[data-name="news_image"] input').val();
                var nw_desc = jQuery('div[data-name="news_description"] textarea').val();
                var nw_btn_txt = jQuery('div[data-name="button_text"] input').val();
                var nw_link = jQuery('div[data-name="news_link"] input').val();
                $.post(
                    ajaxurl,
                    {
                        action: 'generate_html',
                        nonce: nonce,
                        banner_img: banner_img,
                        banner_txt: banner_txt,
                        banner_link_txt: banner_link_txt,
                        banner_link: banner_link,
                        newsletter_top_title: newsletter_top_title,
                        newsletter_other_language_text: newsletter_other_language_text,
                        newsletter_image: newsletter_image,
                        newsletter_title: newsletter_title,
                        newsletter_description: newsletter_description,
                        newsletter_read_more: newsletter_read_more,
                        newsletter_link: newsletter_link,
                        other_language_newsletter_link: other_language_newsletter_link,
                        article_img: article_img,
                        article_top_heading: article_top_heading,
                        article_title: article_title,
                        article_desc: article_desc,
                        a_read_more: a_read_more,
                        a_link: a_link,
                        top_sec_heading: top_sec_heading,
                        top_section_image: top_section_image,
                        btn_txt: btn_txt,
                        top_btn_link: top_btn_link,
                        qpimg: qpimg,
                        qptitle: qptitle,
                        qplinktxt: qplinktxt,
                        qplink: qplink,
                        edtitle: edtitle,
                        ar_1_t: ar_1_t,
                        ar_2_t: ar_2_t,
                        ar_3_t: ar_3_t,
                        ar_1_link: ar_1_link,
                        ar_2_link: ar_2_link,
                        ar_3_link: ar_3_link,
                        ar_1_desc: ar_1_desc,
                        ar_2_desc: ar_2_desc,
                        ar_3_desc: ar_3_desc,
                        nw_title: nw_title,
                        nw_img: nw_img,
                        nw_desc: nw_desc,
                        nw_btn_txt: nw_btn_txt,
                        nw_link: nw_link,
                    },
                    function( response ){     
	                    gbtn.text("Generate HTML");          
                        if( response == "error"){
                            $("#html-output").text("Something went wrong, try again");
                        }else{
                        	$("#preview-html-page").show();
                			$("#html-code-output pre").text(response);
                			$("#html-output").html(response);
                			$("#html-code-output").removeClass('hide');
                        }
                    }
                );
            });

            $(document).on('click',"#preview-html-page",function(){
            	if($("#html-code-output").is(":visible")){
            		$("#html-output").removeClass('hide');
	            	$("#html-code-output").addClass('hide');
	            	$(this).text("Preview Source Code");
	            }else{
	            	$("#html-output").addClass('hide');
	            	$("#html-code-output").removeClass('hide');
	            	$(this).text("Preview Newsletter");
	            }
            });

            $(".copy-text").click(function(){
            	var strData = $("#html-code-output pre").text();
            	function listener(e) {
    				e.clipboardData.setData("text/html", strData);
    				e.clipboardData.setData("text/plain", strData);
    				e.preventDefault();
  				}
  				document.addEventListener("copy", listener);
  				document.execCommand("copy");
  				document.removeEventListener("copy", listener);
			    // var $tempElement = $("<input>");
		        // $("body").append($tempElement);
		        // $tempElement.val($("#html-code-output pre").text()).select();
		        // document.execCommand("Copy");
		        // $tempElement.remove();
		        $(this).text("Copied!");
		        setTimeout(function () {
                    $(".copy-text").text('Copy Code');
                 }, 3000);
            });
        });
    </script>
    <?php
}

/**
 * Handles CTA AJAX.
 */
function pc_generate_html(){
	if(isset( $_POST['nonce'] )){
	    if ( ! wp_verify_nonce( $_POST['nonce'], 'request_send_post_details' ) ) {
	        wp_die( 'error' );
	    }
	}
    /* top banner section */
    // $banner_img = $_POST['banner_img'];
    // if($banner_img != ""){
	// 	$banner_img = wp_get_attachment_image_url( $banner_img, 'large' );
	// }
    // $banner_text = $_POST['banner_txt'];
    // $banner_link_text = $_POST['banner_link_txt'];
    // $banner_link = $_POST['banner_link'];
    // $ = $_POST[''];
    include_once( get_stylesheet_directory() .'/templates/newsletter_html_template.php');
    wp_die('');
}
add_action( 'wp_ajax_generate_html', 'pc_generate_html' );
add_action( 'wp_ajax_nopriv_generate_html', 'pc_generate_html' );

/* meta box for custom message post type */
function wp_pc_add_ctametabox() {
    add_meta_box(
        'generate_html_code',
        'Generate HTML', /* This is the title of the metabox */
        'wp_pc_request_cta_code',
        'custommessagehtml', /* post type */
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wp_pc_add_ctametabox' );

function wp_pc_request_cta_code() {
    global $post;
    wp_nonce_field( 'request_send_post_details', 'request_cta_nonce' );
    ?>
    <style>
    	#generate_html_code{
    		width:1000px;
    	}
    	#wpseo_meta, #slugdiv, #pms_post_content_restriction{
    		display: none;
    	}
    	a#btn-call-to-action {
		    width: 20%;
		    text-align: center;
		    padding: 5px 20px;
		    font-size: 16px;
		    cursor: pointer;
		}
		div .output-div{
			padding: 10px 10px;
    		border: 1px solid #2271b1;
    		margin-top: 10px;    		
		}
		#preview-html-page {
			float: right;
			width: 20%;
		    padding: 5px;
		    font-size: 16px;
		    cursor: pointer;
		}
		#html-output{
			width: 100%;
		    float: left;
		    position: relative;
		    z-index: 1;
		}
		.hide{
			display: none;
		}
    </style>
    <a href="#" id="btn-call-to-action" class="button button-primary widefat">Generate HTML</a>
    <button id="preview-html-page" type="button" class="button button-primary widefat" style="display: none;">Preview Custom Message</button>
    <div id="html-output" class="output-div hide">
    </div>
    <div id="html-code-output" class="output-div hide">
    	<pre></pre>
    	<button class="button button-primary copy-text" type="button" style="padding: 5px 20px;">Copy Code</button>
    </div>
    <script>
        jQuery(function($){
        	// const urlParams = new URLSearchParams(window.location.search);
        	// const param_p = urlParams.get('post');
        	// if(param_p === null){
        	// 	jQuery('<p>Please save this post before Generate HTML of Custom Message.</p>').insertAfter("#btn-call-to-action");
        	// 	jQuery("#btn-call-to-action").hide();
        	// }
            // Handle button click
            $( "#generate_html_code #btn-call-to-action" ).on("click", function(e){
                e.preventDefault();
                var gbtn = $(this);
                gbtn.text("Generating HTML...");
                var nonce = $(this).parent().find("#request_cta_nonce").val();
                var titleText = jQuery('div[data-name="top_heading"] input').val();
                var msgText = jQuery('div[data-name="message"] textarea').val();

                $.post(
                    ajaxurl,
                    {
                        action: 'generate_html_code',
                        nonce: nonce,
                        title: titleText,
                        msg: msgText
                    },
                    function( response ){     
	                    gbtn.text("Generate HTML");          
                        if( response == "error"){
                            $("#html-output").text("Something went wrong, try again");
                        }else{
                        	$("#preview-html-page").show();
                			$("#html-code-output pre").text(response);
                			$("#html-output").html(response);
                			$("#html-code-output").removeClass('hide');
                        }
                    }
                );
            });

            $(document).on('click',"#preview-html-page",function(){
            	if($("#html-code-output").is(":visible")){
            		$("#html-output").removeClass('hide');
	            	$("#html-code-output").addClass('hide');
	            	$(this).text("Preview Source Code");
	            }else{
	            	$("#html-output").addClass('hide');
	            	$("#html-code-output").removeClass('hide');
	            	$(this).text("Preview Custom Message");
	            }
            });

            $(".copy-text").click(function(){
            	var strData = $("#html-code-output pre").text();
            	function listener(e) {
    				e.clipboardData.setData("text/html", strData);
    				e.clipboardData.setData("text/plain", strData);
    				e.preventDefault();
  				}
  				document.addEventListener("copy", listener);
  				document.execCommand("copy");
  				document.removeEventListener("copy", listener);
		        $(this).text("Copied!");
		        setTimeout(function () {
                    $(".copy-text").text('Copy Code');
                 }, 3000);
            });
        });
    </script>
    <?php
}


/**
 * Handles CTA AJAX - custom Message .
 */
function pc_generate_html_code(){
	if(isset($_POST['nonce'])){
	    if ( ! wp_verify_nonce( $_POST['nonce'], 'request_send_post_details' ) ) {
	        wp_die( 'error' );
	    }
	}
    $title = $_POST['title'];
    $msg = $_POST['msg'];
    include_once( get_stylesheet_directory() .'/templates/custom_msg_template.php');
    wp_die('');
}
add_action( 'wp_ajax_generate_html_code', 'pc_generate_html_code' );
add_action( 'wp_ajax_nopriv_generate_html_code', 'pc_generate_html_code' );

function checkValid($phn){
	$codes = array(907 => "AK",205 => "AL",256 => "AL",334 => "AL",251 => "AL",659 => "AL",938 => "AL",870 => "AR",501 => "AR",479 => "AR",480 => "AZ",623 => "AZ",928 => "AZ",602 => "AZ",520 => "AZ",840 => "CA",820 => "CA",350 => "CA",279 => "CA",628 => "CA",341 => "CA",925 => "CA",909 => "CA",562 => "CA",661 => "CA",657 => "CA",510 => "CA",650 => "CA",949 => "CA",760 => "CA",415 => "CA",951 => "CA",752 => "CA",831 => "CA",209 => "CA",669 => "CA",408 => "CA",559 => "CA",626 => "CA",442 => "CA",530 => "CA",916 => "CA",707 => "CA",714 => "CA",310 => "CA",323 => "CA",213 => "CA",424 => "CA",747 => "CA",818 => "CA",858 => "CA",619 => "CA",805 => "CA",720 => "CO",303 => "CO",970 => "CO",719 => "CO",983 => "CO",203 => "CT",959 => "CT",475 => "CT",860 => "CT",202 => "DC",302 => "DE",689 => "FL",407 => "FL",239 => "FL",836 => "FL",727 => "FL",321 => "FL",754 => "FL",954 => "FL",352 => "FL",863 => "FL",904 => "FL",386 => "FL",561 => "FL",772 => "FL",786 => "FL",305 => "FL",861 => "FL",941 => "FL",813 => "FL",850 => "FL",448 => "FL",656 => "FL",478 => "GA",770 => "GA",470 => "GA",404 => "GA",706 => "GA",678 => "GA",912 => "GA",229 => "GA",943 => "GA",762 => "GA",808 => "HI",515 => "IA",319 => "IA",563 => "IA",641 => "IA",712 => "IA",208 => "ID",986 => "ID",217 => "IL",779 => "IL",872 => "IL",312 => "IL",773 => "IL",464 => "IL",708 => "IL",815 => "IL",224 => "IL",847 => "IL",618 => "IL",309 => "IL",331 => "IL",630 => "IL",447 => "IL",618 => "IL",765 => "IN",574 => "IN",260 => "IN",219 => "IN",317 => "IN",812 => "IN",930 => "IN",463 => "IN",913 => "KS",785 => "KS",316 => "KS",620 => "KS",327 => "KY",502 => "KY",859 => "KY",606 => "KY",270 => "KY",364 => "KY",504 => "LA",985 => "LA",225 => "LA",318 => "LA",337 => "LA",774 => "MA",508 => "MA",781 => "MA",339 => "MA",857 => "MA",617 => "MA",978 => "MA",351 => "MA",413 => "MA",443 => "MD",410 => "MD",667 => "MD",249 => "MD",969 => "MD",240 => "MD",301 => "MD",207 => "ME",383 => "ME",517 => "MI", 546 => "MI",810 => "MI", 278 => "MI",313 => "MI", 586 => "MI", 248 => "MI", 734 => "MI", 269 => "MI", 906 => "MI", 989 => "MI", 616 => "MI", 231 => "MI", 679 => "MI",947 => "MI", 612 => "MN",320 => "MN",651 => "MN",763 => "MN",952 => "MN",218 => "MN",507 => "MN",636 => "MO",660 => "MO",975 => "MO",816 => "MO",314 => "MO",557 => "MO",573 => "MO",417 => "MO",601 => "MS",662 => "MS",228 => "MS",769 => "MS",406 => "MT",336 => "NC",252 => "NC",984 => "NC",919 => "NC",980 => "NC",910 => "NC",828 => "NC",704 => "NC",743 => "NC",472 => "NC",701 => "ND",402 => "NE",308 => "NE",531 => "NE",603 => "NH",908 => "NJ",848 => "NJ",732 => "NJ",551 => "NJ",201 => "NJ",862 => "NJ",973 => "NJ",609 => "NJ",856 => "NJ",640 => "NJ",505 => "NM",575 => "NM",957 => "NM",702 => "NV",725 => "NV",775 => "NV",315 => "NY",518 => "NY",716 => "NY",585 => "NY",646 => "NY",347 => "NY",718 => "NY",212 => "NY",516 => "NY",917 => "NY",845 => "NY",631 => "NY",607 => "NY",914 => "NY",680 => "NY",838 => "NY",363 => "NY",934 => "NY",929 => "NY",332 => "NY",216 => "OH",330 => "OH",234 => "OH",567 => "OH",419 => "OH",380 => "OH",440 => "OH",740 => "OH",614 => "OH",326 => "OH",513 => "OH",937 => "OH",220 => "OH",513 => "OH",918 => "OK",539 => "OK",580 => "OK",405 => "OK",572 => "OK",503 => "OR",971 => "OR",541 => "OR",458 => "OR",814 => "PA",717 => "PA",570 => "PA",358 => "PA",878 => "PA",835 => "PA",484 => "PA",610 => "PA",445 => "PA",267 => "PA",215 => "PA",724 => "PA",412 => "PA",582 => "PA",223 => "PA",272 => "PA",939 => "PR",787 => "PR",401 => "RI",843 => "SC",854 => "SC",864 => "SC",803 => "SC",839 => "SC",605 => "SD",423 => "TN",629 => "TN",865 => "TN",931 => "TN",615 => "TN",901 => "TN",731 => "TN",254 => "TX",325 => "TX",713 => "TX",940 => "TX",817 => "TX",430 => "TX",903 => "TX",806 => "TX",737 => "TX",512 => "TX",361 => "TX",210 => "TX",936 => "TX",409 => "TX",979 => "TX",972 => "TX",469 => "TX",214 => "TX",682 => "TX",832 => "TX",281 => "TX",830 => "TX",956 => "TX",432 => "TX",915 => "TX",726 => "TX",945 => "TX",346 => "TX",435 => "UT",801 => "UT",385 => "UT",434 => "VA",804 => "VA",757 => "VA",948 => "VA",826 => "VA",703 => "VA",571 => "VA",540 => "VA",276 => "VA",381 => "VA",236 => "VA",802 => "VT",509 => "WA",360 => "WA",564 => "WA",206 => "WA",425 => "WA",253 => "WA",715 => "WI",534 => "WI",920 => "WI",414 => "WI",262 => "WI",608 => "WI",353 => "WI",420 => "WI",304 => "WV",681 => "WV",307 => "WY", 552 =>"WY");
	if (array_key_exists($phn,$codes)){
		return true;
	}else{
		return false;
	}
}

/* validate phone number by elementor validator */
function elementor_form_tel_field_validation( $field, $record, $ajax_handler ) {
	// Remove native validation
	$forms_module = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' );
	remove_action( 'elementor_pro/forms/validation/tel', [ $forms_module->field_types['tel'], 'validation' ] );

	// Run your own validation, ex:
	if ( empty( $field['value'] ) ) {
		return;
	}

	if ( $field['id'] != "phone" ) {
		return;
	}

	// Match this format (XXX) XXX-XXXX, e.g. (123) 456-7890
	$first_b = strpos($field['value'], "(");
	$last_b = strpos($field['value'], ")");
	if ($first_b !== false && $first_b == 0 && $last_b !== false && $last_b == 4) {
		$num_val = str_replace('(','',$field['value']);
		$num_val = str_replace(') ','-',$num_val);
		if ( preg_match( '/[0-9]{3}(-?)[0-9]{3}(-?)[0-9]{4}/', $num_val ) !== 1 ) {
			$ajax_handler->add_error( $field['id'], esc_html__( 'Please make sure the phone number is in (XXX) XXX-XXXX format, eg: (123) 456-7890', 'textdomain' ) );
		}else{
			$res_area = substr($num_val, 0, 3);
			if(checkValid($res_area) === false){
				$ajax_handler->add_error( $field['id'], esc_html__( 'Please enter valid area code.', 'textdomain' ) );
			}
		}
	}else{
		$ajax_handler->add_error( $field['id'], esc_html__( 'Please make sure the phone number is in (XXX) XXX-XXXX format, eg: (123) 456-7890', 'textdomain' ) );
	}
}
add_action( 'elementor_pro/forms/validation/text', 'elementor_form_tel_field_validation', 10, 3 );

// create hook for file uploading
add_action('wp_ajax_nopriv_upload_file', 'upload_file_callback');
add_action( 'wp_ajax_upload_file', 'upload_file_callback' );

function upload_file_callback(){
    $file = $_FILES['img'];
    if(isset($_POST['eximg'])){
    	$old_img = (int)$_POST['eximg'];
    	wp_delete_attachment( $old_img, true );
    }
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        return false;
    } else {
        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );
        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        if( 0 < intval( $attachment_id ) ) {
          	echo $attachment_id;
          	exit();
        }
    }
    echo 'error';
    exit();
}

function WebP_upload_mimes( $existing_mimes ) {
    // add WebP to the list of mime types
    $existing_mimes['WebP'] = 'image/WebP';
    // return the array back to the function with our added mime type
    return $existing_mimes;
}
add_filter( 'mime_types', 'WebP_upload_mimes' ); 

//** * Enable preview / thumbnail for WebP image files.*/
function WebP_is_displayable($result, $path) { 
    if ($result === false) { 
        $displayable_image_types = array( IMAGETYPE_WebP ); 
        $info = @getimagesize( $path ); 
        if (empty($info)) { 
            $result = false; 
        } elseif (!in_array($info[2], $displayable_image_types)) { 
            $result = false; 
        } else { 
            $result = true; 
        } 
    } 
    return $result;
}
add_filter('file_is_displayable_image', 'WebP_is_displayable', 10, 2);

// disable stylesheet (example)
function shapeSpace_disable_scripts_styles() {
	if(is_front_page()){
		wp_dequeue_style('poll-maker-ays-admin');
		wp_dequeue_style('bootstrap');
		wp_dequeue_style('font-awesome-5-all');
		wp_dequeue_style('consulting-layout');
		wp_dequeue_style('wp-block-library');

		wp_dequeue_style('wpml-blocks');
		wp_dequeue_style('wpos-slick-style');
	}
	// wp_dequeue_style('consulting-theme-options');
	// wp_dequeue_style('wp-block-library');
	// if ( !is_page('articles')) {
	// 	wp_dequeue_style('pciwgas-publlic-style');
	// }
	// if (is_page('centro-de-aprendizaje')) {
	// 	wp_enqueue_style('pciwgas-publlic-style');
	// }
	// wp_dequeue_style('stm-stm');
}
add_action('wp_enqueue_scripts', 'shapeSpace_disable_scripts_styles', 100);

function defer_parsing_of_js( $url ) {
    if ( is_user_logged_in() ) return $url; //don't break WP Admin
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( strpos( $url, 'jquery.js' ) ) return $url;
    return str_replace( ' src', ' defer src', $url );
}
add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10, 1 );


// function wpb_rand_posts() {          
//       $args = array(     
//       'post_type' => 'post',     
//       'orderby'   => 'rand',     
//       'posts_per_page' => 5,      
//       );     
//       $the_query = new WP_Query( $args );     
//       if ( $the_query->have_posts() ) {     
//       $string = '<ul>';         
//             while ( $the_query->have_posts() ) {         
//             $the_query->the_post();         
//             $string .= '<li><a href="'. get_permalink() .'">'. get_the_title() .'</a></li>';         
//             }         
//             $string .= '</ul>';     
//             /* Restore original Post Data */     
//             wp_reset_postdata();     
//             } else {     
//             $string .= 'no posts found';     
//             }     
//             return $string;  
// }  
// add_shortcode('random-posts','wpb_rand_posts'); 

// add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );

// function my_action_callback() {
// 	echo do_shortcode('[random-posts]'); 
//     die();
// }
