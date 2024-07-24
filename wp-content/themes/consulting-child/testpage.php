<?php
/* Template Name: Test page */
	get_header();
// 	$cron_jobs = get_option( 'cron' );
// var_dump($cron_jobs);
	die();
	// $user = get_user_by("email", "klthorvaldson@gmail.com");
	// var_dump($user->data->ID);
	// echo "<br>";
	// $all_meta_for_user = get_user_meta($user->data->ID);
	// echo "<pre>";
  	// print_r( $all_meta_for_user );

	// CIM API
	$current_date = date('d-m-Y');
	$xml = '<ACCERequest><RequestID></RequestID><AgencyInformation><AgencyCode>0433-00</AgencyCode><AgencyPswd>yPtAKA</AgencyPswd><AgencyEmail>jglen@premierconsumer.org</AgencyEmail></AgencyInformation><CourseInformation><Program>CIMBK</Program><EnrollmentDate>'.$current_date.'</EnrollmentDate><ClassType>Online</ClassType><CourseReason></CourseReason><CourseLanguage></CourseLanguage></CourseInformation><StudentInformation><LastName>Test</LastName><FirstName>11apr</FirstName><Phone>5036556544</Phone><Email>11apr@temporary-mail.net</Email><Fee>0.00</Fee><BankruptcyDistrict>SD</BankruptcyDistrict><BankruptcyNumber></BankruptcyNumber></StudentInformation></ACCERequest>';

	$url = "https://www.acce-online.com/pp/0433/parseuser_xml.php"; // URL                 

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, "xmlstring=" . $xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $xmlResponse = (array)simplexml_load_string($data);

    if($xmlResponse['RequestOK'] == 'Y'){
    	$b = (array)$xmlResponse['Bankruptcy'];
	    $login_id =  $b['UserID'];
	    $login_pass = $b['PassWord'];
	    echo "Login ID: ".$login_id;
	    echo "<br>";
	    echo "Login PASS: ".$login_pass;
    }else{
    	var_dump($xmlResponse);
    }

 	get_footer();