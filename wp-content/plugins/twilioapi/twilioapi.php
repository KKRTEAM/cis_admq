<?php
/**
 * Plugin Name: Twilio API
 * Description: Add twilio calling and messaging  functionality.
 * Version: 1.0
 * Author: CIS
 * Author URI: http://www.cisin.com
 */
include plugin_dir_path(__FILE__) . 'inc/Twilio.php';
include plugin_dir_path(__FILE__) . 'inc/Twilio/Capability.php';

// $myoptions = get_option('twilio_settings');
// $AccountSid = $myoptions['twilio_account_sid'];
// $AuthToken = $myoptions['twilio_auth_token'];
// $appSid = $myoptions['twilio_app_sid'];
//

function twilio_api() {
	wp_enqueue_script('the_twiliojs', '//static.twilio.com/libs/twiliojs/1.2/twilio.min.js');

}
add_action('init', 'twilio_api');


add_action('wp_ajax_sms_action', 'twilio_message');
add_action('wp_ajax_nopriv_sms_action', 'twilio_message');
function twilio_message() {
	$myoptions = get_option('twilio_settings');
// set your AccountSid and AuthToken from www.twilio-message.com/user/account
	$AccountSid = $myoptions['twilio_account_sid'];
	$AuthToken = $myoptions['twilio_auth_token'];
	$twilionumber = $myoptions['twilio_app_twilionumber'];

	$post = get_post($_POST['skillid']);
	$advisor = $post->post_author;
	$advisor_mobile = get_user_meta($advisor, 'mobile_number', true);


		$destnumber = $advisor_mobile;
		$smsmessage = $_POST["message_text"];

		$http = new Services_Twilio_TinyHttp(
			'https://api.twilio.com',
			array('curlopts' => array(
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,
			))
		);
		$client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);

		try {
			$message = $client->account->messages->create(array(
				"From" => $twilionumber,
				"To" => $destnumber,
				"Body" => $smsmessage,
			));
		} catch (Services_Twilio_RestException $e) {
			echo $e->getMessage();
		}


}

add_action('wp_ajax_call_action', 'twilio_call');
function twilio_call() {

	$myoptions = get_option('twilio_settings');
	// set your AccountSid and AuthToken from www.twilio-message.com/user/account
	// $AccountSid = "AC83cb26bcf5ba5aa750d0ffe50e299b22";
	// $AuthToken = "96617fb14d4ee91f4fbef6edf8682ed6";
	$AccountSid = $myoptions['twilio_account_sid'];
	$AuthToken = $myoptions['twilio_auth_token'];

	// put your Twilio Application Sid here
	//$appSid = 'APf8a37ec0049142b87441bd9dc56fa19f';
	$appSid = $myoptions['twilio_app_sid'];

	$post = get_post($_POST['skillid']);
	$advisor = $post->post_author;
	$advisor_mobile = get_user_meta($advisor, 'mobile_number', true);

	// put your default Twilio Client name here
	$clientName = $_POST['skillid'];


	$capability = new Services_Twilio_Capability($AccountSid, $AuthToken);
	$capability->allowClientOutgoing($appSid);
	$capability->allowClientIncoming($clientName);
	$token = $capability->generateToken();
	$return = array(
		'token'	=> $token,
		'mobile'	=> $advisor_mobile
	);
	wp_send_json_success($return);
}


//Shortcode for getting phone call list - Start
add_shortcode( 'get_phone_call_details' , 'get_phone_call_details' );
function get_phone_call_details() {
	$calldetails[] = '';
	$user_id = um_profile_id();
	$mobile_number=get_user_meta($user_id, 'mobile_number', true);
	//echo $mobile_number."<br/>";
	$myoptions = get_option('twilio_settings');
	 $AccountSid = $myoptions['twilio_account_sid'];
	 $AuthToken = $myoptions['twilio_auth_token'];
	 //$appSid = "AP3474e136f42acf5fcc40d20c64d78d12";
	$callerID = $myoptions['twilio_app_twilionumber'];
	$http = new Services_Twilio_TinyHttp(
		'https://api.twilio.com',
		array('curlopts' => array(
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		))
	);


	$client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);
	$calls = $client->account->calls->getIterator(0, 50, array(
		'From' => $callerID,
		));

	$html="<h1>My Phone Calls</h1><br><table class='footable'>";
	$html.="<thead><th>Date-Time</th><th>Call From</th><th>Call To</th><th>Duration(Seconds)</th><th>Credit Used</th><th>Status</th></thead>";
	$html.="<tbody>";
		foreach ($calls as $call) {
			if($call->price==""){ $price=0; }else{ $price=$call->price; }
		$html.="<tr><td>".$call->start_time."</td><td>".$call->from."</td><td>".$call->to."</td><td>".$call->duration."</td><td>".$price ."&nbsp".$call->price_unit."</td><td>".$call->status."</td></tr>";
		}

	$html.="</tbody>";
	$html.="</table>";
	echo $html;
}
//Shortcode for getting phone call list - End

//Shortcode for Getting Advisor Message List - Start
add_shortcode( 'get_phone_messages_details' , 'get_phone_messages_details' );
function get_phone_messages_details() {
	$calldetails[] = '';
	$user_id = um_profile_id();
	$mobile_number=get_user_meta($user_id, 'mobile_number', true);
	//echo $mobile_number."<br/>";
	$myoptions = get_option('twilio_settings');
	$AccountSid = $myoptions['twilio_account_sid'];
	$AuthToken = $myoptions['twilio_auth_token'];
	//$appSid = "AP3474e136f42acf5fcc40d20c64d78d12";
	$callerID = $myoptions['twilio_app_twilionumber'];
	$http = new Services_Twilio_TinyHttp(
		'https://api.twilio.com',
		array('curlopts' => array(
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		))
	);

	$client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);
	$messages = $client->account->messages->getIterator(0, 50, array(
		'From' => $callerID,
	));


	$html="<h1>My Phone Messages</h1><br><table class='footable'>";
	$html.="<thead><th>Date-Time</th></th><th>Message From</th><th>Message To</th><th>Credit Used</th><th>Status</th></thead>";
	$html.="<tbody>";
	foreach ($messages as $message) {
		if($message->price==""){ $message=0; }else{ $price=$message->price; }
		$html.="<tr><td>".$message->date_sent."</td><td>".$message->from."</td><td>".$message->to."</td>"."<td>".$price ."&nbsp".'0.5 Credits'."</td><td>".$message->status."</td></tr>";
	}

	$html.="</tbody>";
	$html.="</table>";
	echo $html;
}
//Shortcode for Getting Advisor Message List - End



add_action('admin_menu', 'twilio_add_admin_menu');
add_action('admin_init', 'twilio_settings_init');

function twilio_add_admin_menu() {

	add_menu_page('Twilio', 'Twilio', 'manage_options', 'twilio', 'twilio_options_page');
	add_submenu_page('twilio', 'Call Details', 'Call History', 'administrator', 'twilio_call', 'twilio_call_details');
}

function twilio_call_details() {
	$myListTable = new My_call_Table();
	echo '<div class="wrap"><h2>My List Table Test</h2>';
	$myListTable->prepare_items();
	$myListTable->display();
	echo '</div>';

}

function twilio_settings_init() {

	register_setting('pluginPage', 'twilio_settings');

	add_settings_section(
		'twilio_pluginPage_section',
		__('API Settings', 'wordpress'),
		'twilio_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'twilio_account_sid',
		__('ACCOUNT SID', 'wordpress'),
		'twilio_account_sid_render',
		'pluginPage',
		'twilio_pluginPage_section'
	);

	add_settings_field(
		'twilio_auth_token',
		__('AUTH TOKEN', 'wordpress'),
		'auth_token_render',
		'pluginPage',
		'twilio_pluginPage_section'
	);

	add_settings_field(
		'twilio_app_sid',
		__('TwiML App SID', 'wordpress'),
		'twilio_app_sid_render',
		'pluginPage',
		'twilio_pluginPage_section'
	);

	add_settings_field(
		'twilio_app_callerid',
		__('CallerID', 'wordpress'),
		'twilio_app_callerid_render',
		'pluginPage',
		'twilio_pluginPage_section'
	);

	add_settings_field(
		'twilio_app_twilionumber',
		__('Twilio Number', 'wordpress'),
		'twilio_app_twilionumber_render',
		'pluginPage',
		'twilio_pluginPage_section'
	);

}

function twilio_account_sid_render() {

	$options = get_option('twilio_settings');
	?>
  <input type='text' name='twilio_settings[twilio_account_sid]' value='<?php echo $options['twilio_account_sid'];?>'>
  <?php

}

function auth_token_render() {

	$options = get_option('twilio_settings');
	?>
  <input type='text' name='twilio_settings[twilio_auth_token]' value='<?php echo $options['twilio_auth_token'];?>'>
  <?php

}

function twilio_app_sid_render() {

	$options = get_option('twilio_settings');
	?>
  <input type='text' name='twilio_settings[twilio_app_sid]' value='<?php echo $options['twilio_app_sid'];?>'>
  <?php

}

function twilio_app_callerid_render() {

	$options = get_option('twilio_settings');
	?>
  <input type='text' name='twilio_settings[twilio_app_callerid]' value='<?php echo $options['twilio_app_callerid'];?>'>
  <?php

}

function twilio_app_twilionumber_render() {

	$options = get_option('twilio_settings');
	?>
  <input type='text' name='twilio_settings[twilio_app_twilionumber]' value='<?php echo $options['twilio_app_twilionumber'];?>'>
  <?php

}

function twilio_settings_section_callback() {

	echo __('Enter API Credentials', 'wordpress');

}

function twilio_options_page() {

	?>
  <form action='options.php' method='post'>

    <h2>Twilio</h2>

    <?php
settings_fields('pluginPage');
	do_settings_sections('pluginPage');
	submit_button();
	?>

  </form>
  <?php

}
