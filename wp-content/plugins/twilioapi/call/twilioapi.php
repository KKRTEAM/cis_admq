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

function twilio_message() {
	$myoptions = get_option('twilio_settings');
// set your AccountSid and AuthToken from www.twilio-message.com/user/account
	$AccountSid = $myoptions['twilio_account_sid'];
	$AuthToken = $myoptions['twilio_auth_token'];

	if (isset($_POST["send-msg"])) {
		$destnumber = $_POST["twilio_number"];
		$smsmessage = $_POST["twilio_message"];

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
				"From" => "201-340-0223",
				"To" => $destnumber,
				"Body" => $smsmessage,
			));
		} catch (Services_Twilio_RestException $e) {
			echo $e->getMessage();
		}
	}

}
add_action('init', 'twilio_message');

function twilio_sms_form_fields($att) {
	wp_enqueue_script('the_jaqueryui', 'http://code.jquery.com/ui/1.10.1/jquery-ui.js');
	wp_enqueue_script('the_showsmsformjs', plugins_url('/assets/js/showsmsform.js', __FILE__));
	wp_enqueue_style('stylepopcss', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css');

	ob_start();?>
  <a class="btn sms-button" href="javascript:void(0);" id="opensms-form">Connect Via SMS/MMS</a>
  <div id="sms-form" style="display:none;">
    <h3 class="twilio_header"><?php _e('Send SMS');?></h3>

    <?php
// show any error messages after form submission
	twilio_show_error_messages();?>

    <form id="twilio_sms_form" name="twilio_sms_form" class="twilio_form" action="" method="POST">
      <fieldset>
       <!--  <p> -->
          <!-- <label for="twilio_number"><?php _e('Number');?></label> -->
          <input name="twilio_number" id="twilio_number" value="<?php echo $att['number'];?>" type="hidden"/>
       <!--  </p>
         <p> -->
          <label for="twilio_message"><?php _e('Message');?></label>
          <input name="twilio_message" id="twilio_message" class="required" type="text"/>
        </p>
        <p>
          <input type="hidden" name="">
          <input name="send-msg" type="submit" value="<?php _e('Send SMS');?>"/>
        </p>
      </fieldset>
    </form>
    </div>
  <?php
return ob_get_clean();
}

// user login form
function twilio_sms_form($att) {
	$output = twilio_sms_form_fields($att);

	return $output;
}
add_shortcode('twilio_sms', 'twilio_sms_form');

// used for tracking error messages
function twilio_errors() {
	static $wp_error; // Will hold global variable safely
	return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function twilio_show_error_messages() {
	if ($codes = twilio_errors()->get_error_codes()) {
		echo '<div class="pippin_errors">';
		// Loop error codes and display errors
		foreach ($codes as $code) {
			$message = twilio_errors()->get_error_message($code);
			echo '<span class="error"><strong>' . __('Error') . '</strong>: ' . $message . '</span><br/>';
		}
		echo '</div>';
	} else {
		if (isset($_POST["send-msg"])) {
			echo '<div class="twilio_success">Message Successfully sent' . '</div>';
		}
	}
}

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

	// put your default Twilio Client name here
	$clientName = 'jenny';

	// get the Twilio Client name from the page request parameters, if given
	if (isset($_REQUEST['client'])) {
		$clientName = $_REQUEST['client'];
	}

	$capability = new Services_Twilio_Capability($AccountSid, $AuthToken);
	$capability->allowClientOutgoing($appSid);
	$capability->allowClientIncoming($clientName);
	$token = $capability->generateToken();

	wp_enqueue_script('the_twiliojs', '//static.twilio.com/libs/twiliojs/1.2/twilio.min.js');
	wp_enqueue_style('stylecss', plugins_url('/assets/css/style.css', __FILE__));

	wp_enqueue_script('the_calljs', plugins_url('/assets/js/call.js', __FILE__));
	wp_localize_script('the_calljs', 'appSetting', array(
		'token' => $token,
		'client' => $clientName,
	));
}
add_action('init', 'twilio_call');

function twilio_call_form_fields($att) {
	wp_enqueue_script('the_jaqueryui', 'http://code.jquery.com/ui/1.10.1/jquery-ui.js');
	wp_enqueue_script('the_showcallformjs', plugins_url('/assets/js/showcallform.js', __FILE__));
	wp_enqueue_style('stylepopcss', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css');

	ob_start();?>
    <a class="btn sms-button" href="javascript:void(0);" id="opencall-form">Connect Via Phone</a>
  <div id="call-form" style="display:none;">
    <button class="call" onclick="call_twl();">
        Call
    </button>

    <button class="hangup" onclick="hangup();">
        Hangup
    </button>

    <input type="hidden" id="number" name="number"value="<?php echo $att['number'];?>"    />

    <div id="log">Loading pigeons...</div>
    <ul id="people"/>
    </div>
    <?php
return ob_get_clean();
}

// user login form
function twilio_call_form($att) {
	if (is_user_logged_in()) {
		$output = twilio_call_form_fields($att);
	} else {
		$output = '';
	}

	return $output;
}
add_shortcode('twilio_call', 'twilio_call_form');

if (!class_exists('WP_List_Table')) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class My_call_Table extends WP_List_Table {
	function get_columns() {
		$columns = array(
			'callsid' => 'Call SID',
			'to' => 'To',
			'From' => 'FROM',
		);
		return $columns;
	}

	function prepare_items() {
		$calldetails[] = '';
		$AccountSid = "AC83cb26bcf5ba5aa750d0ffe50e299b22";
		$AuthToken = "96617fb14d4ee91f4fbef6edf8682ed6";
		$http = new Services_Twilio_TinyHttp(
			'https://api.twilio.com',
			array('curlopts' => array(
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,
			))
		);
		$client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);
		foreach ($client->account->calls as $call) {
			$calldetails['callsid'] = $call->sid;
			$calldetails['to'] = $call->to;
			$calldetails['From'] = $call->from;
		}

		$example_data = array(
			array('callsid' => 'Quarter Share', 'to' => 'Nathan Lowell',
				'From' => '978-0982514542'));

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = array();
		$this->_column_headers = array($columns, $hidden, $sortable);
		$this->items = $example_data;
	}

	// function column_default($item, $column_name) {
	// 	switch ($column_name) {
	// 		case 'callsid':
	// 		case 'to':
	// 		case 'From':
	// 			return $item[$column_name];
	// 		default:
	// 			return print_r($item, true); 	//Show the whole array for troubleshooting purposes
	// 	}
	// }
}

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
