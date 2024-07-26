<?php
add_action( 'rest_api_init', function () {
    register_rest_route( 'consulting-child/v1', '/w-endpoint', array(
      'methods' => 'POST',
      'callback' => 'alert_endpoint',
    ) );
  } );


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

    /*
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
    */
    $data = $request_data->get_params();

    //wp_mail('matrosovdream@gmail.com', 'New Notification - Premier Consumer', print_r($data, true));

    /*
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die();
    */

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