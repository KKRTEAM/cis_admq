<?php

require_once (get_stylesheet_directory() . '/includes/vendor/autoload.php');
use OpenTok\MediaMode;
use OpenTok\OpenTok;
use OpenTok\Role;

add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
function theme_enqueue_styles() {
	$parent_style = 'parent-style';

	wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array($parent_style));
	wp_enqueue_style('boostrap3 style', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style('intlTelInput', get_stylesheet_directory_uri() . '/css/intlTelInput.css');
//	wp_enqueue_style('flick-ui-theme', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/flick/jquery-ui.css');
//	wp_enqueue_style('jspanel-style', get_stylesheet_directory_uri() . '/css/jquery.jspanel.min.css');
	if (is_page('add-skills')) {
		wp_enqueue_style('tagscss', get_stylesheet_directory_uri() . '/css/jquery.tagsinput.css');
	}

//	wp_enqueue_script('jqueryui-23.js', '//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js');
	wp_enqueue_script('bootstrap3.js', get_stylesheet_directory_uri() . '/scripts/bootstrap.min.js');
//	wp_enqueue_script('jspanel.js', get_stylesheet_directory_uri() . '/scripts/jquery.jspanel.min.js');
	wp_enqueue_script('google-location1', 'https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places');
	wp_enqueue_script('intlTelInput', get_stylesheet_directory_uri() . '/scripts/intlTelInput.js');
	wp_enqueue_script('customscript', get_stylesheet_directory_uri() . '/scripts/customscript.js');
	wp_enqueue_script('customreg.js', get_stylesheet_directory_uri() . '/scripts/customreg.js');

	if (is_page('add-skills')) {
		wp_enqueue_script('tagsinput.js', get_stylesheet_directory_uri() . '/scripts/jquery.tagsinput.js');
		wp_enqueue_script('tags.js', get_stylesheet_directory_uri() . '/scripts/tags.js');
	}
}

/*****************************************************Start - BY CIS**********************************************************************************/
//by pravar
include 'Browser.php';
//end

function add_theme_advisor() {
	$role = get_role('Advisor');
	// create if neccesary
	if (!$role) {
		$role = add_role('Advisor', 'Advisor');
	}

	// add theme specific roles
	$role->add_cap('delete_posts');
	$role->add_cap('delete_published_posts');
	$role->add_cap('edit_posts');
	$role->add_cap('edit_published_posts');
	$role->add_cap('publish_posts');
	$role->add_cap('read');
	$role->add_cap('upload_files');
}
add_action('admin_init', 'add_theme_advisor');

function add_theme_advisee() {
	$role = get_role('Advisee');
	// create if neccesary
	if (!$role) {
		$role = add_role('Advisee', 'Advisee');
	}

	// add theme specific roles
	$role->add_cap('delete_posts');
	$role->add_cap('delete_published_posts');
	$role->add_cap('edit_posts');
	$role->add_cap('edit_published_posts');
	$role->add_cap('publish_posts');
	$role->add_cap('read');
	$role->add_cap('upload_files');
}
add_action('admin_init', 'add_theme_advisee');

// Register a new shortcode: [cr_custom_registration]
add_shortcode('cr_custom_registration', 'custom_registration_shortcode');

// The callback function that will replace [book]
function custom_registration_shortcode($args) {
	ob_start();
	$a = shortcode_atts(array(
		'role' => 'something',
	), $args);
	// echo  $a['role'];
	custom_registration_function($a['role']);
	return ob_get_clean();
}

// Location and browser detection and save in database
function custom_location_and_browser_script() {
	if ((is_user_logged_in() && is_front_page()) || is_page('user')) {
		//wp_enqueue_script('google-location', '//maps.google.com/maps/api/js?sensor=true');
		wp_enqueue_script('location-detect', get_stylesheet_directory_uri() . '/scripts/custlocation.js');
	}

	//echo 'hello';die;
}
add_action('wp_enqueue_scripts', 'custom_location_and_browser_script');

function custom_save_location_and_browser() {
	$user = wp_get_current_user();
	$userlocation = $_POST['longitude'] . ',' . $_POST['latitude'];
	$updateval = update_user_meta($user->ID, 'user_location', $userlocation);

	$browser = new Browser();
	$userbrowser = $browser->getBrowser();
	$userbowserversion = $browser->getVersion();
	//$ismobiledevice = $browser->isMobile();
	$userplatform = $browser->getPlatform();

	update_user_meta($user->ID, 'user_browser_name', $userbrowser);
	update_user_meta($user->ID, 'user_browser_version', $userbowserversion);
	//update_user_meta($user->ID, 'user_is_onmobile', $ismobiledevice);
	update_user_meta($user->ID, 'user_platform', $userplatform);

	exit;
}
add_action('wp_ajax_custom_save_location_and_browser', 'custom_save_location_and_browser');

/* First we need to extend main profile tabs */

/* Then we just have to add content to that tab using this action */
/*
add_action('um_profile_content_mycustomtab_default', 'um_profile_content_mycustomtab_default');
function um_profile_content_mycustomtab_default($args) {
global $ultimatemember;
$userinfo = $ultimatemember->user->profile;
// echo "<pre>";print_r($userinfo);
$role = ucfirst($userinfo['role']);
//$role= $userinfo['role_name'];
//echo $role;
$ultimatemember_uid = $userinfo['ID'];
$user_ID = get_current_user_id();

$phone_Call_message = unserialize($userinfo["phone_Call_pref_13"]);
$phone_Call_pref_13_14 = unserialize($userinfo["phone_Call_pref_13_14"]);
$phone_Call_pref_13_15 = unserialize($userinfo["phone_Call_pref_13_14_15"]);
$phone_Call_pref = unserialize($userinfo["phone_Call_pref"]);
$phone_Call_pref_13_14_23 = unserialize($userinfo["phone_Call_pref_13_14_23"]);
$phone_Call_pref_13_24 = unserialize($userinfo["phone_Call_pref_13_24"]);

$phone_message = $phone_Call_message[0];
$email_notification = $phone_Call_pref_13_14[0];
$web_notification = $phone_Call_pref_13_15[0];
$phone_Call = $phone_Call_pref[0];
$browser_cookie = $phone_Call_pref_13_14_23[0];
$security = $phone_Call_pref_13_24[0];

$skill_cat = unserialize($userinfo["skill_cat"]);

$skills = skills_data();
//echo var_dump($json1);
$html = '
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>

jQuery(function() {

jQuery( "#tabs" ).tabs();
jQuery(".nav-pills > li > a").click( function() {
jQuery(".nav-pills > li.active").removeClass("active");
jQuery(this).parent().addClass("active");
} );
});
</script>
<div id="tabs" class="um-col-1">
<ul class="nav nav-pills">
<li class="active"><a href="#tabs-tab1">Basic Profile</a></li>';
if ($role == 'Advisor' && $user_ID == $ultimatemember_uid) {
$html .= '<li><a href="#tabs-tab2">Calling Preferences</a></li>
<li><a href="#tabs-tab3">Payment Info</a></li>
<li><a href="#tabs-tab4">Skills Table</a></li>';
}

$html .= '</ul>
<div id="tabs-tab1">
<div data-key="user_login" class="um-field um-field-user_login">
<div class="um-field-label">
<label for="user_login">Username</label><div class="um-clear">
</div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["user_login"] . '</div>
</div>
</div>
<div data-key="user_login" class="um-field um-field-first_name">
<div class="um-field-label">
<label for="first_name">First Name</label><div class="um-clear">
</div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["first_name"] . '</div>
</div>
</div>
<div data-key="user_login" class="um-field um-field-last_name">
<div class="um-field-label">
<label for="last_name">Last Name</label><div class="um-clear">
</div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["last_name"] . '</div>
</div>
</div>
<div data-key="user_login" class="um-field um-field-user_email">
<div class="um-field-label">
<label for="user_email">E-mail Address</label><div class="um-clear">
</div>
</div>
<div class="um-field-area">
<div class="um-field-value"><a title="' . $userinfo["user_email"] . '" href="mailto:' . $userinfo["user_email"] . '">' . $userinfo["user_email"] . '</a></div>
</div>
</div>

<div data-key="address" class="um-field um-field-address">
<div class="um-field-label">
<label for="address-250">Address</label><div class="um-clear"></div>
</div><div class="um-field-area">
<div class="um-field-value">' . $userinfo["address"] . '</div>
</div>
</div>';

if ($role == 'Advisor') {
$html .= '<div data-key="mobile_number" class="um-field um-field-mobile_number">
<div class="um-field-label">
<div class="um-field-label-icon"><i class="um-faicon-mobile"></i></div>
<label for="mobile_number-250">Mobile Number</label><div class="um-clear"></div>
</div><div class="um-field-area">
<div class="um-field-value">' . $userinfo["mobile_number"] . '</div>
</div>
</div>

<div data-key="address" class="um-field um-field-dob">
<div class="um-field-label">
<label for="dob-250">Date Of Birth</label><div class="um-clear"></div>
</div><div class="um-field-area">
<div class="um-field-value">' . $userinfo["dob"] . '</div>
</div>
</div>

<div data-key="company_name" class="um-field um-field-company_name">
<div class="um-field-label">
<label for="company_name-250">Company Name</label><div class="um-clear"></div>
</div><div class="um-field-area">
<div class="um-field-value">' . $userinfo["company_name"] . '</div>
</div>
</div>

<div data-key="Company_website" class="um-field um-field-Company_website">
<div class="um-field-label">
<label for="company_website-250">Company Website</label><div class="um-clear"></div>
</div><div class="um-field-area">
<div class="um-field-value">' . $userinfo["Company_website"] . '</div>
</div>
</div>

<div data-key="background_check" class="um-field um-field-background_check">
<div class="um-field-label">
<label for="background_check-250">Background Check</label><div class="um-clear"></div>
</div><div class="um-field-area">
<div class="um-field-value">Clear</div>
</div>
</div>';
}

$html .= '</div>';
if ($role == 'Advisor' && $user_ID == $ultimatemember_uid) {
$html .= '<div id="tabs-tab2">

<div data-key="phone_Call_pref_13" class="um-field um-field-phone_Call_pref_13">
<div class="um-field-label">
<label for="phone_Call_pref_13-250">Allow Phone Messages</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $phone_message . '</div>
</div>
</div>

<div data-key="phone_Call_pref_13_14" class="um-field um-field-phone_Call_pref_13_14">
<div class="um-field-label">
<label for="phone_Call_pref_13_14-250">Allow Email Notifications</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $email_notification . '</div>
</div>
</div>

<div data-key="phone_Call_pref_13_14_15" class="um-field um-field-phone_Call_pref_13_14_15">
<div class="um-field-label">
<label for="phone_Call_pref_13_14_15-250">Allow Web Calls</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $web_notification . '</div>
</div>
</div>

<div data-key="phone_Call_pref" class="um-field um-field-phone_Call_pref">
<div class="um-field-label">
<label for="phone_Call_pref-250">Allow Phone Calls</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $phone_Call . '</div>
</div>
</div>
<div data-key="phone_Call_pref_13_14_23" class="um-field um-field-phone_Call_pref_13_14_23">
<div class="um-field-area">
<div class="um-field-value">' . $browser_cookie . '</div>
</div>
</div>

<div data-key="phone_Call_pref_13_24" class="um-field um-field-phone_Call_pref_13_24">
<div class="um-field-label">
<label for="phone_Call_pref_13_24-250">Allow Secured Background Check</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $security . '</div>
</div>
</div>

</div>

<div id="tabs-tab3">
<div data-key="card_number" class="um-field um-field-card_number">
<div class="um-field-label">
<label for="card_number-250">Card Number</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["card_number"] . '</div>
</div>
</div>

<div data-key="card_cvv" class="um-field um-field-card_cvv">
<div class="um-field-label">
<label for="card_cvv-250">CVV</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["card_cvv"] . '</div>
</div>
</div>

<div data-key="expiration_date_mm" class="um-field um-field-expiration_date_mm">
<div class="um-field-label">
<label for="expiration_date_mm-250">Expiration Month</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["expiration_date_mm"] . '</div>
</div>
</div>

<div data-key="card_expiration_yyyy" class="um-field um-field-card_expiration_yyyy">
<div class="um-field-label">
<label for="card_expiration_yyyy-250">Expiration Year</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["card_expiration_yyyy"] . '</div>
</div>
</div>

</div>

<div id="tabs-tab4">
<div data-key="skill_cat" class="um-field um-field-skill_cat">
<div class="um-field-label">
<label for="skill_cat-250">Skill Category</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">';
//   echo "<pre>";print_r($skills);
if (!empty($skills)) {
foreach ($skills as $skill_cats) {
$html .= $skill_cats->category . ", ";
}
}
$html .= '</div>
</div>
</div>

<div data-key="skill_tags" class="um-field um-field-skill_tags">
<div class="um-field-label">
<label for="skill_tags-250">Skill Tags</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value"><p>';
if (!empty($skills)) {
foreach ($skills as $skill_cats) {
$html .= $skill_cats->tags;
}
}
$html .= '</div>
</div>
</div>
<div data-key="skill_level" class="um-field um-field-skill_level">
<div class="um-field-label">
<label for="skill_level-250">Skill Level</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">';
if (!empty($skills)) {
foreach ($skills as $skill_cats) {
$html .= $skill_cats->skill_level1_5 . ", ";
}
}
$html .= '</div>
</div>
</div>

<div data-key="skill_price" class="um-field um-field-skill_price">
<div class="um-field-label">
<label for="skill_price-250">$/hr Rate</label>
<div class="um-clear"></div>
</div>
<div class="um-field-area">
<div class="um-field-value">' . $userinfo["skill_price"] . '</div>
</div>
</div>

</div id="tab4diveendshere">';
}

$html .= '</div>';

echo $html;
}

add_action('skills_data', 'skills_data');
function skills_data() {
global $wpdb;
$user_ID = get_current_user_id();
//  $query="SELECT * FROM quickqtsadvisorskills WHERE user_id='".$user_ID."'";
$query = "SELECT * FROM quickqtsadvisorskills WHERE user_id='" . $user_ID . "'";
$result = $wpdb->get_results($query);
return $result;
}
 */
add_action('custom_um_profile_tab', 'custom_um_profile_tab');
function custom_um_profile_tab() {
	?>
	<div class="um-account-side uimob340-hide uimob500-hide">
		<?php do_action('custom_um_profile_display_tabs_hook', $args);?>
	</div>
	<?
}

add_action('custom_um_profile_display_tabs_hook','custom_um_profile_display_tabs_hook');
function custom_um_profile_display_tabs_hook() {
	?>

	<?php
}
// Code Added By Anil Starts Here 10-8-15
add_action('complete_profile', 'complete_profile');
function complete_profile() {
	global $ultimatemember;

	$userinfo = $ultimatemember->user->profile;
	$phone_Call_message = unserialize($userinfo["phone_Call_pref_13"]);
	$phone_Call_pref_13_14 = unserialize($userinfo["phone_Call_pref_13_14"]);
	$phone_Call_pref_13_15 = unserialize($userinfo["phone_Call_pref_13_14_15"]);
	$phone_Call_pref = unserialize($userinfo["phone_Call_pref"]);
	$phone_Call_pref_13_14_23 = unserialize($userinfo["phone_Call_pref_13_14_23"]);
	$phone_Call_pref_13_24 = unserialize($userinfo["phone_Call_pref_13_24"]);

	$phone_message = $phone_Call_message[0];
	$email_notification = $phone_Call_pref_13_14[0];
	$web_notification = $phone_Call_pref_13_15[0];
	$phone_Call = $phone_Call_pref[0];
	$browser_cookie = $phone_Call_pref_13_14_23[0];
	$security = $phone_Call_pref_13_24[0];

	$total_array = array(
		"user_login" => $userinfo["user_login"],
		"first_name" => $userinfo["first_name"],
		"last_name" => $userinfo["last_name"],
		"user_email" => $userinfo["user_email"],
		"address" => $userinfo["address"],
		"mobile_number" => $userinfo["mobile_number"],
		"dob" => $userinfo["dob"],
		"company_name" => $userinfo["company_name"],
		"Company_website" => $userinfo["Company_website"],
		"phone_message" => $phone_message,
		"email_notification" => $email_notification,
		"web_notification" => $web_notification,
		"phone_Call" => $phone_Call,
		"browser_cookie" => $browser_cookie,
		"security" => $security,
		"card_number" => $userinfo['card_number'],
		"card_cvv" => $userinfo['cvv'],
		"expiration_date_mm" => $userinfo['month'],
		"card_expiration_yyyy" => $userinfo['year'],
		"skill_price" => $userinfo['skill_price'],
		"bank_name" => $userinfo['bank_name'],
		"bank_routing_id" => $userinfo['bank_routing_id'],
		"bank_account_number" => $userinfo['bank_account_number'],
		"ssn" => $userinfo['ssn'],
	);
	$total_count = count($total_array);

	$complete_count = count(array_filter($total_array));

	$complete_profile = ($complete_count / $total_count) * 100;
	$profile_status = array("card_number" => $userinfo['card_number'],
		"card_cvv" => $userinfo['cvv'],
		"expiration_date_mm" => $userinfo['month'],
		"card_expiration_yyyy" => $userinfo['year'],
		"skill_price" => $userinfo['skill_price'],
		"bank_name" => $userinfo['bank_name'],
		"bank_routing_id" => $userinfo['bank_routing_id'],
		"bank_account_number" => $userinfo['bank_account_number'],
		"ssn" => $userinfo['ssn'],
	);
	$send_notify_message = array("complete_profile" => $complete_profile, "profile_status" => $profile_status);

	return $send_notify_message;
}
// Code Added By Anil Ends Here 10-8-15

// The callback function that will replace [book]
function phone_verification_shortcode() {
	ob_start();
	phone_form_function();
	return ob_get_clean();
}
add_shortcode('phone_verification', 'phone_verification_shortcode');

function phone_verification_advisee_shortcode() {
	ob_start();
	phone_form_advisee_function();
	return ob_get_clean();
}
add_shortcode('phone_verification_advisee', 'phone_verification_advisee_shortcode');

/*
add_action('user_register', 'phone_verification_registration_save', 10, 1);
function phone_verification_registration_save($user_id) {

if (isset($_POST['mobile_number-307'])) {
$phone_number = $_POST['mobile_number-307'];
}
if (isset($_POST['mobile_number-297'])) {
$phone_number = $_POST['mobile_number-297'];
}
$length = 6;
$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);
$randomString = '';
for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, $charactersLength - 1)];
}
$fromnumber = "+17735702743";

if (file_exists(getcwd() . '/twilio-php-master/Services/Twilio.php')) {require_once getcwd() . '/twilio-php-master/Services/Twilio.php';}

//$AccountSid = "AC515db1f5c0ccf32bfbf58d103a1a4a4a";
//$AuthToken = "333be1d5110a0f179065bee78e399122";
//$AccountSid = "ACbb3945ac07d836c7ad06412b124d523d";
//$AuthToken = "225bdfd4c8c09211d0d1a52cf0a5d66c";

$AccountSid = "ACbb3945ac07d836c7ad06412b124d523d"; // Live Credentials
$AuthToken = "225bdfd4c8c09211d0d1a52cf0a5d66c";

$client = new Services_Twilio($AccountSid, $AuthToken);

try {
$message = $client->account->messages->create(array(
"From" => $fromnumber,
"To" => $phone_number,
"Body" => $randomString,
));
//echo "Sent message {$message->sid}";
update_user_meta($user_id, "phone_verify", $randomString);
update_user_meta($user_id, "phone_verify_status", 'awaiting_phone_confirmation');
} catch (Services_Twilio_RestException $e) {
echo $e->getMessage();
}

}
 */
add_action('wp_ajax_phone_verification_code', 'phone_verification_code');
add_action('wp_ajax_nopriv_phone_verification_code', 'phone_verification_code');
function phone_verification_code($user_id) {

	$phone_number = "+" . $_POST['phone_number'];
	$length = 6;
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	//$fromnumber = "+17735702743";
	//$_SESSION['session_phone_number'] = $phone_number;
	//$_SESSION['session_user_id'] = $user_id;
	$myoptions = get_option('twilio_settings');
// set your AccountSid and AuthToken from www.twilio-message.com/user/account
	$AccountSid = $myoptions['twilio_account_sid'];
	$AuthToken = $myoptions['twilio_auth_token'];
	$fromnumber = $myoptions['twilio_app_twilionumber'];

	//if (file_exists('/data/home/krcisinl/public_html/arianadigital/twilio-php-master/Services/Twilio.php')) {require_once '/data/home/krcisinl/public_html/arianadigital/twilio-php-master/Services/Twilio.php';}

	//$AccountSid = "AC515db1f5c0ccf32bfbf58d103a1a4a4a";
	//$AuthToken = "333be1d5110a0f179065bee78e399122";
	//$AccountSid = "ACbb3945ac07d836c7ad06412b124d523d";
	//$AuthToken = "225bdfd4c8c09211d0d1a52cf0a5d66c";

	$http = new Services_Twilio_TinyHttp(
		'https://api.twilio.com',
		array('curlopts' => array(
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		))
	);

	//$client = new Services_Twilio($AccountSid, $AuthToken);
	$client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);

	try {
		$message = $client->account->messages->create(array(
			"From" => $fromnumber,
			"To" => $phone_number,
			"Body" => "Phone Verification Code: " . $randomString,
		));
		//echo "Sent message {$message->sid}";
		//update_user_meta($user_id, "phone_verify", $randomString);
		//update_user_meta($user_id, "phone_verify_status", 'awaiting_phone_confirmation');
		$cookie_name = "verification_code";
		$cookie_value = md5($randomString);
		setcookie($cookie_name, $cookie_value, time() + (30 * 60), "/"); // 30 mins
		echo "1";exit;
	} catch (Services_Twilio_RestException $e) {
		echo $e->getMessage();
	}

}
add_action('wp_ajax_verify_phone', 'ajax_verify_phone'); // Works when login
add_action('wp_ajax_nopriv_verify_phone', 'ajax_verify_phone'); // Works when not login
function ajax_verify_phone($args) {
	// If User Not Login Check Phone Confirmation

	//global $ultimatemember;
	//
	//$userinfo = $ultimatemember->user->profile;
	//
	//$phone = $userinfo['mobile_number'];
	//$user_id = $userinfo['ID'];

	if (isset($_COOKIE['verification_code'])) {
		if (md5($_POST['verification_code']) == $_COOKIE['verification_code']) {

			echo "1";
			$phone_status_name = "phone_status";
			$phone_verification_status = "verified";
			//setcookie($cookie_name, "", time() - (30 * 60), "/"); // 30 mins
			setcookie($phone_status_name, $phone_verification_status, time() + (30 * 60), "/"); // 30 mins
			exit;
		} else {
			$phone_status_name = "phone_status";
			$phone_verification_status = "unverified";
			setcookie($phone_status_name, $phone_verification_status, time() + (30 * 60), "/"); // 30 mins
			exit;
		}

	} else {
		echo "0";exit;
	}

}
add_action('wp_ajax_resend_activation', 'resend_activation');
add_action('wp_ajax_nopriv_ajax_resend_activation', 'resend_activation'); // Works when not login
function resend_activation($args) {

	global $ultimatemember;

	$userinfo = $ultimatemember->user->profile;
	//$user_id = $userinfo['ID'];

	//$phone_number = $userinfo['mobile_number'];
	$phone_number = "+" . $_POST['phone_number'];
	$length = 6;
	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	//$fromnumber = "+17735702743";
	//$homepath = get_home_path();
	//if (file_exists('/data/home/krcisinl/public_html/arianadigital/twilio-php-master/Services/Twilio.php')) {require_once '/data/home/krcisinl/public_html/arianadigital/twilio-php-master/Services/Twilio.php';}
	//if (file_exists('/data/home/krcisinl/public_html/arianadigital/twilio-php-master/Services/Twilio.php')) {require_once '/data/home/krcisinl/public_html/arianadigital/twilio-php-master/Services/Twilio.php';}
	//$AccountSid = "AC515db1f5c0ccf32bfbf58d103a1a4a4a";
	//$AuthToken = "333be1d5110a0f179065bee78e399122";
	//$AccountSid = "ACbb3945ac07d836c7ad06412b124d523d";
	//$AuthToken = "225bdfd4c8c09211d0d1a52cf0a5d66c";

	$myoptions = get_option('twilio_settings');
// set your AccountSid and AuthToken from www.twilio-message.com/user/account
	$AccountSid = $myoptions['twilio_account_sid'];
	$AuthToken = $myoptions['twilio_auth_token'];
	$fromnumber = $myoptions['twilio_app_twilionumber'];

	$http = new Services_Twilio_TinyHttp(
		'https://api.twilio.com',
		array('curlopts' => array(
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		))
	);

	//$client = new Services_Twilio($AccountSid, $AuthToken);
	$client = new Services_Twilio($AccountSid, $AuthToken, "2010-04-01", $http);

	try {
		$message = $client->account->messages->create(array(
			"From" => $fromnumber,
			"To" => $phone_number,
			"Body" => "Phone Verification Code: " . $randomString,
		));
		//echo "Sent message {$message->sid}";
		$cookie_name = "verification_code";
		$cookie_value = md5($randomString);
		setcookie($cookie_name, $cookie_value, time() + (30 * 60), "/"); // 86400 = 1 day
		echo "1";exit;
		//update_user_meta($user_id, "phone_verify", $randomString);
		//update_user_meta($user_id, "phone_verify_status", 'awaiting_phone_confirmation');
	} catch (Services_Twilio_RestException $e) {
		echo $e->getMessage();
	}

	exit;
}
/*
// The callback function that will replace [book]
function ssn_verification_shortcode() {
ob_start();
ssn_form_function();
return ob_get_clean();
}
add_shortcode('ssn_verification', 'ssn_verification_shortcode');

add_action('wp_ajax_ssn_verification', 'ajax_ssn_verification');
function ajax_ssn_verification($args) {

define('CHECKR_API_KEY', '75bd18c80fbd2542694ef612de4b6f2655f0d3fa');
global $ultimatemember;
$userinfo = $ultimatemember->user->profile;
$dob = str_replace("/", "-", $userinfo["dob"]);
$zip_code = get_user_meta($user_id, "zip_code", true);
$user_id = $userinfo["ID"];
$candidate_params = [
"first_name" => $userinfo["first_name"],
"last_name" => $userinfo["last_name"],
"dob" => $dob,
"phone" => $userinfo["mobile_number"],
"email" => $userinfo["username"],
"ssn" => $_POST["ssn_no"],
"zipcode" => $zip_code,
];
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.checkr.io/v1/candidates');
curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($candidate_params));

$json = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
//echo "status:" . $http_status . "\n" . $json . "\n\n";
$response = json_decode($json);
$id = $response->id; // candidate id

if ($id != "") {
// echo $user_id;

update_user_meta($user_id, 'candidate_id', $id);
$report_params = [
"candidate_id" => $id,
"package" => "tasker_standard",
];
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.checkr.io/v1/reports');
curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($report_params));
$json = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
//echo "status:" . $http_status . "\n" . $json;
$report_response = json_decode($json);
//echo "report_id:" . $report_response->id;
//$ssn_status = 1;
//add_user_meta($user_id, 'ssn_status', $ssn_status, false);
//add_user_meta($user_id, 'candidate_id', $candidate_id, false);
// echo $user_id;
if ($report_response->id) {
add_user_meta($user_id, 'ssn_status', 'verified', false);
echo "Your SSN is verified";exit;
} else {
add_user_meta($user_id, 'ssn_status', 'unverified', false);
echo "Your SSN is unverified";exit;
}
} else {

echo $response->error;exit;
}
exit;
}

add_action('um_after_new_user_register', 'submit_ssn', 10, 1);

function submit_ssn($user_id) {

$first_name = $_POST['first_name-307'];
$last_name = $_POST['last_name-307'];
$dob = str_replace("/", "-", $_POST['dob-307']);
$phone = $_POST['mobile_number-307'];
$email = $_POST['username-307'];
$ssn = $_POST['ssn-307'];
if ($_POST['role'] == 'advisor') {
define('CHECKR_API_KEY', '6c4d8959a106710d5d814a98bbbec51260cfd5b0');
$candidate_id = getCandidateId($first_name, $last_name, $dob, $phone, $email, $ssn);

$report_params = [
"candidate_id" => $candidate_id,
"package" => "tasker_standard",
];
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.checkr.io/v1/reports');
curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($report_params));
$json = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
echo "status:" . $http_status . "\n" . $json;
$response = json_decode($json);
echo "report_id:" . $response->id;
$ssn_status = 1;
add_user_meta($user_id, 'ssn_status', $ssn_status, false);
add_user_meta($user_id, 'candidate_id', $candidate_id, false);

}

}
function getCandidateId($first_name, $last_name, $dob, $phone, $email, $ssn) {
define('CHECKR_API_KEY', '6c4d8959a106710d5d814a98bbbec51260cfd5b0');

$candidate_params = [
"first_name" => $first_name,
"last_name" => $last_name,
"dob" => $dob,
"phone" => $phone,
"email" => $email,
"ssn" => $ssn,
];
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'https://api.checkr.io/v1/candidates');
curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($candidate_params));

$json = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
//echo "status:" . $http_status . "\n" . $json . "\n\n";
$response = json_decode($json);
$id = $response->id;

return $id;
}
 */

//Added on 7 aug 15 by pravar - Start

/***
 ***  @profile header cover
 ***/
add_action('custom_um_profile_header_cover_area', 'custom_um_profile_header_cover_area', 9);
function custom_um_profile_header_cover_area($args) {
	global $ultimatemember;

	if ($args['cover_enabled'] == 1) {

		$default_cover = um_get_option('default_cover');

		$overlay = '<span class="um-cover-overlay">
        <span class="um-cover-overlay-s">
          <ins>
            <i class="um-faicon-picture-o"></i>
            <span class="um-cover-overlay-t">' . __('Change your cover photo', 'ultimatemember') . '</span>
          </ins>
        </span>
      </span>';

		?>
		<div id = "alert_placeholder"></div>
		<div class="um-cover <?php if (um_profile('cover_photo') || ($default_cover && $default_cover['url'])) {
			echo 'has-cover';
		}
		?>" data-user_id="<?php echo um_profile_id();?>" data-ratio="<?php echo $args['cover_ratio'];?>">

			<?php

			if ($ultimatemember->fields->editing) {

				$items = array(
					'<a href="#" class="um-manual-trigger" data-parent=".um-cover" data-child=".um-btn-auto-width">' . __('Change cover photo', 'ultimatemember') . '</a>',
					'<a href="#" class="um-reset-cover-photo" data-user_id="' . um_profile_id() . '">' . __('Remove', 'ultimatemember') . '</a>',
					'<a href="#" class="um-dropdown-hide">' . __('Cancel', 'ultimatemember') . '</a>',
				);

				echo $ultimatemember->menu->new_ui('bc', 'div.um-cover', 'click', $items);

			}
			?>

			<?php $ultimatemember->fields->add_hidden_field('cover_photo');?>

			<?php echo $overlay;?>

			<div class="um-cover-e">

				<?php if (um_profile('cover_photo')) {
					?>

					<?php

					if ($ultimatemember->mobile->isMobile()) {
						if ($ultimatemember->mobile->isTablet()) {
							echo um_user('cover_photo', 1000);
						} else {
							echo um_user('cover_photo', 300);
						}
					} else {
						echo um_user('cover_photo', 1000);
					}

					?>

				<?php } elseif ($default_cover && $default_cover['url']) {

					$default_cover = $default_cover['url'];

					echo '<img src="' . $default_cover . '" alt="" />';

				} else {

					if (!isset($ultimatemember->user->cannot_edit)) {?>

						<a href="#" class="um-cover-add um-manual-trigger" data-parent=".um-cover" data-child=".um-btn-auto-width"><span class="um-cover-add-i"><i class="um-icon-plus um-tip-n" title="<?php _e('Upload a cover photo', 'ultimatemember');?>"></i></span></a>

					<?php }

				}
				?>

			</div>

		</div>

		<?php

	}

}

/***
 ***  @profile header
 ***/
add_action('custom_um_profile_header', 'custom_um_profile_header', 9);
function custom_um_profile_header($args) {
	global $ultimatemember;
	$user_id = get_current_user_id();
	$userrole = get_user_meta($user_id, 'role', true);

	$classes = null;

	if (!$args['cover_enabled']) {
		$classes .= ' no-cover';
	}

	$default_size = str_replace('px', '', $args['photosize']);

	$overlay = '<span class="um-profile-photo-overlay">
      <span class="um-profile-photo-overlay-s">
        <ins>
          <i class="um-faicon-camera"></i>
        </ins>
      </span>
    </span>';

	?>
	<div class="um-header<?php echo $classes;?>">

		<?php do_action('um_pre_header_editprofile', $args);?>

		<div class="um-profile-photo" data-user_id="<?php echo um_profile_id();?>">

			<a href="<?php echo um_user_profile_url();?>" class="um-profile-photo-img" title="<?php echo um_user('display_name');?>"><?php echo $overlay . get_avatar(um_user('ID'), $default_size);?></a>


			<?php

			if (!isset($ultimatemember->user->cannot_edit)) {

				$ultimatemember->fields->add_hidden_field('profile_photo');

				if (!um_profile('profile_photo')) {
					// has profile photo

					$items = array(
						'<a href="#" class="um-manual-trigger" data-parent=".um-profile-photo" data-child=".um-btn-auto-width">' . __('Upload photo', 'ultimatemember') . '</a>',
						'<a href="#" class="um-dropdown-hide">' . __('Cancel', 'ultimatemember') . '</a>',
					);

					echo $ultimatemember->menu->new_ui('bc', 'div.um-profile-photo', 'click', $items);

				} else if ($ultimatemember->fields->editing == true) {

					$items = array(
						'<a href="#" class="um-manual-trigger" data-parent=".um-profile-photo" data-child=".um-btn-auto-width">' . __('Change photo', 'ultimatemember') . '</a>',
						'<a href="#" class="um-reset-profile-photo" data-user_id="' . um_profile_id() . '" data-default_src="' . um_get_default_avatar_uri() . '">' . __('Remove photo', 'ultimatemember') . '</a>',
						'<a href="#" class="um-dropdown-hide">' . __('Cancel', 'ultimatemember') . '</a>',
					);

					echo $ultimatemember->menu->new_ui('bc', 'div.um-profile-photo', 'click', $items);

				}

			}

			?>

		</div>

		<div class="um-profile-meta">

			<div class="um-main-meta">

				<?php if ($args['show_name']) {
					?>
					<div class="um-name">

						<a href="<?php echo um_user_profile_url();?>" title="<?php echo um_user('display_name');?>"><?php echo um_user('display_name');?></a>

						<?php do_action('um_after_profile_name_inline', $args);?>
						<?php if ($ultimatemember->fields->editing == true) {
							?>
							<?php if ($userrole == 'advisee') {?>
								<a href="javascript:void(0);" class="hb-button" id="um-upgrade">Upgrade to become an Advisor</a>
							<?php }
							?>
						<?php }
						?>

					</div>
				<?php }
				?>

				<div class="um-clear"></div>

				<?php do_action('um_after_profile_header_name_args', $args);?>
				<?php do_action('um_after_profile_header_name');?>

			</div>

			<?php if (isset($args['metafields']) && !empty($args['metafields'])) {?>
				<div class="um-meta">

					<?php echo $ultimatemember->profile->show_meta($args['metafields']);?>

				</div>
			<?php }
			?>

			<?php if ($ultimatemember->fields->viewing == true && um_user('description') && $args['show_bio']) {?>

				<div class="um-meta-text"><?php echo um_filtered_value('description');?></div>

			<?php } else if ($ultimatemember->fields->editing == true && $args['show_bio']) {
				?>

				<div class="um-meta-text">
            <textarea placeholder="<?php _e('Tell us a bit about yourself...', 'ultimatemember');?>" name="<?php echo 'description-' . $args['form_id'];?>" id="<?php echo 'description-' . $args['form_id'];?>"><?php if (um_user('description')) {echo um_user('description');}
				?></textarea>

					<?php if ($ultimatemember->fields->is_error('description')) {
						echo $ultimatemember->fields->field_error($ultimatemember->fields->show_error('description'), true);}
					?>

				</div>

			<?php }
			?>

			<div class="um-profile-status <?php echo um_user('account_status');?>">
				<span><?php printf(__('This user account status is %s', 'ultimatemember'), um_user('account_status_name'));?></span>
			</div>

		</div><div class="um-clear"></div>

	</div>


	<?php
}
//Added by pravar - Start
function custom_change_advisee_to_advisor() {
	$user = wp_get_current_user();
	$upgrade = $_POST['upgrade'];

	if (isset($upgrade)) {
		$upgrade = update_user_meta($user->ID, 'role', 'advisor');
		wp_logout();
		return 1;
	}

	exit;
}
add_action('wp_ajax_custom_change_advisee_to_advisor', 'custom_change_advisee_to_advisor');

add_action('um_after_new_user_register', 'authorize_add_profile', 10, 2);
function authorize_add_profile($user_id, $args) {

	if ($args['role'] == 'advisee') {

		// Added By Anil for Phone Verification status
		if (isset($_COOKIE['phone_status'])) {
			update_user_meta($user_id, 'phone_status', $_COOKIE['phone_status'], false);
		} else {
			update_user_meta($user_id, 'phone_status', 'unverified', false);
		}

		$response_array = add_adv_profile($user_id, $args);
		//print_r($response_array);die;
		if ($response_array['response'] === 'Ok') {
			update_user_meta($user_id, 'authorize_net_customerid', $response_array['new_customer_id']);
			update_user_meta($user_id, 'authorize_net_paymentProfileId', $response_array['paymentProfileId']);
			$cc = $args['credit-card-number'];
			$modifiedcc = 'XXXX-XXXX-XXXX-' . substr($cc, -4);
		} else {
			if ($user_id) {
				wp_delete_user($user_id);
				wp_redirect(home_url());exit;
			} else {
				wp_redirect(home_url());exit;
			}
		}
	}
	if ($args['role'] == 'advisor') {
		if (isset($_COOKIE['phone_status'])) {
			update_user_meta($user_id, 'phone_status', $_COOKIE['phone_status'], false);
		} else {
			update_user_meta($user_id, 'phone_status', 'unverified', false);
		}
	}
}
//Delete sensitive Credit Card info
add_action('um_after_user_is_approved', 'um_custom_update_ccdetails', 99);
function um_custom_update_ccdetails($user_id) {
	$userrole = get_user_meta($user_id, 'role', true);
	if ($userrole == 'advisee') {
		$cc = get_user_meta($user_id, 'credit-card-number', true);
//  print_r($cc);die(' hello');
		$modifiedcc = 'XXXX-XXXX-XXXX-' . substr($cc, -4);
		update_user_meta($user_id, 'credit-card-number', $modifiedcc);
		delete_user_meta($user_id, 'cvv');
		delete_user_meta($user_id, 'month');
		delete_user_meta($user_id, 'year');
	}

	//for calender creation on register.

	if ($userrole == 'advisor') {
		global $wpdb;
		$sql = 'INSERT INTO `' . $wpdb->prefix . 'cpabc_appointment_calendars' . '` (conwer,`form_structure`,`' . 'id' . '`,`' . 'title' . '`,`' . 'uname' . '`,`' . 'passwd' . '`,`' . 'lang' . '`,`' . 'cpages' . '`,`' . 'ctype' . '`,`' . 'msg' . '`,`' . 'workingDates' . '`,`' . 'restrictedDates' . '`,`' . 'timeWorkingDates0' . '`,`' . 'timeWorkingDates1' . '`,`' . 'timeWorkingDates2' . '`,`' . 'timeWorkingDates3' . '`,`' . 'timeWorkingDates4' . '`,`' . 'timeWorkingDates5' . '`,`' . CPABC_TDEAPP_CONFIG_TIMEWORKINGDATES6 . '`,`' . 'caldeleted' . '`) ' .
			' VALUES(' . $user_id . ',"' . esc_sql('[[{"name":"email","index":0,"title":"Email","ftype":"femail","userhelp":"","csslayout":"","required":true,"predefined":"","size":"medium"},{"name":"subject","index":1,"title":"Subject","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium"},{"name":"message","index":2,"size":"large","required":true,"title":"Message","ftype":"ftextarea","userhelp":"","csslayout":"","predefined":""}],[{"title":"","description":"","formlayout":"top_aligned"}]]') . '","","cal1","Calendar Item 1","","ENG","1","3","Please, select your appointment.","1,2,3,4,5","","","9:0,10:0,11:0,12:0,13:0,14:0,15:0,16:0","9:0,10:0,11:0,12:0,13:0,14:0,15:0,16:0","9:0,10:0,11:0,12:0,13:0,14:0,15:0,16:0","9:0,10:0,11:0,12:0,13:0,14:0,15:0,16:0","9:0,10:0,11:0,12:0,13:0,14:0,15:0,16:0","","0");';
		//echo $sql ;die('hello');
		$wpdb->query($sql);
	}
}

//For age verification
//add_action('um_submit_form_errors_hook', 'dob', 100);

function dob($args) {
	global $ultimatemember;
	$birthday = DateTime::createFromFormat('Y/m/d', $args['dob']);
	$diff = $birthday->diff(new DateTime());

	//print_r($diff);
	//echo $diff->y;die;
	if (!isset($args['dob']) && ($diff->y < 16)) {
		exit(wp_redirect(add_query_arg('err', 'This site requires +16yrs')));
		$ultimatemember->form->add_error($key, __('This site requires +16yrs'));
	}

}

/*
add_action('um_custom_field_validation_dob','dob_validation_function', 10,2); //Adds function to the UM validation hook.

function dob_validation_function( $key, $array ) {
//  echo 'd0b';die;
global $ultimatemember;
$birthday = DateTime::createFromFormat('Y/m/d', $args['dob']);
$diff = $birthday->diff(new DateTime());
if (isset($args['dob']) && ($diff->y < 16)) {
$ultimatemember->form->add_error($key, __('This site requires +16yrs') );
}
}
 */
add_action('um_custom_field_validation_phone_valid', 'phone_valid', 10, 2);
function phone_valid($key, $array) {
	global $ultimatemember;
	if (!isset($_COOKIE['phone_status']) && $_COOKIE['phone_status'] != "verified") {
		$ultimatemember->form->add_error($key, __('Please verify Your Phone number'));
	}
}

//Custom Validation for Credit card.
add_action('um_custom_field_validation_verify_ccnumber', 'um_custom_field_validation_verify_ccnumber', 10, 3);
function um_custom_field_validation_verify_ccnumber($key, $array, $args) {
	global $ultimatemember;
	if (ctype_digit($args[$key]) || strlen($args[$key]) == 16) {
		//$credit_card_number = str_replace('-', '', $credit_card_number);
		$map = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9,
			0, 2, 4, 6, 8, 1, 3, 5, 7, 9);
		$sum = 0;
		$credit_card_number = $args[$key];
		$last = strlen($credit_card_number) - 1;
		for ($i = 0; $i <= $last; $i++) {
			$sum += $map[$credit_card_number[$last - $i] + ($i & 1) * 10];
		}
		if ($sum % 10 != 0) {
			$ultimatemember->form->add_error($key, __('This is not a valid credit card number'));
			// return 'This is not a valid credit card number';
		}
	} else {
		$ultimatemember->form->add_error($key, __('This is not a valid credit card number'));
	}
}

//check updated profile fields for escalate actions
add_action('um_user_after_updating_profile', 'profile_update_check', 10, 1);

function profile_update_check($changes) {

	global $ultimatemember;
	$userinfo = $ultimatemember->user->profile;
	$user_id = um_profile_id();
	$userrole = get_user_meta($user_id, 'role', true);

	if ($userrole == 'advisor') {
		if(isset($changes['mobile_number'])){
			if (isset($_COOKIE['phone_status'])) {
				update_user_meta($user_id, 'phone_status', $_COOKIE['phone_status'], false);
			} else {
				update_user_meta($user_id, 'phone_status', 'unverified', false);
			}
		}
		$response_array = add_advisor_profile($changes);
		if ($response_array['response'] === 'Ok') {

			if ($changes['bank_routing_id'] != "") {
				$bank_routing_id = 'XXXXXX' . substr($changes['bank_routing_id'], -3);
				$bank_routing_id_new = substr($bank_routing_id, 0, 4);
				$bank_account_number = 'XXXXXXXXXXX' . substr($changes['bank_account_number'], -4);
				update_user_meta($user_id, 'bank_routing_id', $bank_routing_id);
				update_user_meta($user_id, 'bank_account_number', $bank_account_number);
			}
			//$bank_routing_id_old = get_user_meta($user_id, 'bank_routing_id', true);
			//$bank_account_number_old=substr($bank_account_number, 0, 4);
			if ($changes['credit-card-number']) {
				$cc = $changes['credit-card-number'];
				$modifiedcc = 'XXXX-XXXX-XXXX-' . substr($cc, -4);
				update_user_meta($user_id, 'credit-card-number', $modifiedcc);
				delete_user_meta($user_id, 'cvv');
				delete_user_meta($user_id, 'month');
				delete_user_meta($user_id, 'year');
			}

			update_user_meta($user_id, 'authorize_net_customerid', $response_array['new_customer_id']);
			update_user_meta($user_id, 'authorize_net_paymentProfileId', $response_array['paymentProfileId']);
			update_user_meta($user_id, 'authorize_net_bank_paymentProfileId', $response_array['paymentProfileId2']);

			delete_option("um_cache_userdata_{$user_id}");
		} else {
			//echo "Error me";die;
			//$redirect_url = $_SERVER['HTTP_REFERER'];
			//wp_redirect($redirect_url);

			//wp_delete_user($user_id);
			//wp_redirect(add_query_arg('err', 'invalid_bankrouting_info'));
		}
		if ($changes['ssn'] != "") {
			$dob = str_replace("/", "-", $changes["dob"]);
			$user_id = um_profile_id();
			//echo $user_id;
			$email = get_user_meta($user_id, 'username', true);
			$candidateid = getCandidateId($changes['first_name'], $changes['last_name'], $dob, $changes['mobile_number'], $email, $changes['ssn'], $changes['zip_code']);
			$report = getssnreport($candidateid);
			//  echo "<pre>";print_r($report);die;
			if ($report == 0) {
				$ssn_new = 'XXX-XXX-' . substr($changes['ssn'], -4);
				update_user_meta($user_id, 'ssn', $ssn_new, false);
				update_user_meta($user_id, 'background_check_status', 'Pending', false);
			} else {
				$ssn_new = 'XXX-XXX-' . substr($changes['ssn'], -4);
				update_user_meta($user_id, 'ssn', $ssn_new, false);
				update_user_meta($user_id, 'background_check_status', 'Clear', false);
			}
		}

	}
}

add_action('um_user_after_updating_profile', 'did_user_change_cc', 10, 1);
function did_user_change_cc($changes) {
	$user_id = um_profile_id();
	$userrole = get_user_meta($user_id, 'role', true);
	if ($userrole == 'advisee') {
		global $ultimatemember;

		$savedcc = get_user_meta($user_id, 'credit-card-number', true);
		// echo 'savedcc - '.$savedcc ;
		// echo 'changedcc - '. $changes['credit-card-number'];die;

		//if (isset($changes['credit-card-number']) && $savedcc != $changes['credit-card-number']) {
		//die('inside');
		$response_array = add_adv_profile($user_id, $changes);

		if ($response_array['response'] == 'Ok') {
			$cc = get_user_meta($user_id, 'credit-card-number', true);
			//print_r($cc);
			$modifiedcc = 'XXXX-XXXX-XXXX-' . substr($cc, -4);

			update_user_meta($user_id, 'credit-card-number', $modifiedcc);
			delete_user_meta($user_id, 'cvv');
			delete_user_meta($user_id, 'month');
			delete_user_meta($user_id, 'year');
			delete_option("um_cache_userdata_{$user_id}");
		}

	}

}

add_action('getCandidateId', 'getCandidateId');
function getCandidateId($first_name, $last_name, $dob, $phone, $email, $ssn, $zipcode) {
	define('CHECKR_API_KEY', '6c4d8959a106710d5d814a98bbbec51260cfd5b0');

	$candidate_params = [
		"first_name" => $first_name,
		"last_name" => $last_name,
		"dob" => $dob,
		"phone" => $phone,
		"email" => $email,
		"ssn" => $ssn,
		"zipcode" => $zipcode,
	];
//print_r($candidate_params);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api.checkr.io/v1/candidates');
	curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($candidate_params));

	$json = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
//echo "status:" . $http_status . "\n" . $json . "\n\n";
	$response = json_decode($json);
	$id = $response->id;
	//echo "id=" . $id;
	return $id;
}
add_action('getssnreport', 'getssnreport');
function getssnreport($candidate_id) {
	$report_params = [
		"candidate_id" => $candidate_id,
		"package" => "tasker_standard",
	];
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'https://api.checkr.io/v1/reports');
	curl_setopt($curl, CURLOPT_USERPWD, CHECKR_API_KEY . ":");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($report_params));
	$json = curl_exec($curl);
	$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	//echo "status:" . $http_status . "\n" . $json;
	$report_response = json_decode($json);
	// echo "<pre>";print_r($report_response);
	$returnarr = array("http_status" => $http_status, 'report_response' => $report_response);
	//return $returnarr;
	return $http_status;

}

//2nd Phase -Start

//Add custom quesry string variable in global wordpress variable list.
function add_query_vars_filter($vars) {
	$vars[] = "skillid";
	return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');

//change upload Directory
add_filter('upload_dir', 'my_upload_dir');

function my_upload_dir($upload) {
	$upload['subdir'] = '/ultimatemember' . '/' . um_profile_id();
	$upload['path'] = $upload['basedir'] . $upload['subdir'];
	$upload['url'] = $upload['baseurl'] . $upload['subdir'];
	return $upload;
}

//add products as skills from frontend form - Start
function save_skills_as_woocommerce_products() {
	// echo '<pre>';
	// print_r($_POST);die;
	global $wpdb;
	$skillid = get_query_var('skillid');

	$user_id = um_profile_id();
	$display_name = um_user('display_name');

	$product_cat = get_term($_POST['skillCategory'], 'product_cat');

	if (isset($_POST['skillStatus'])) {
		switch ($_POST['skillStatus']) {
			case 0:
				$product_status = 'draft';
				break;

			case 1:
				$product_status = 'publish';
				break;
		}
	}

	$post = array(
		'ID' => $skillid,
		'post_author' => $user_id,
		'post_content' => '',
		'post_status' => $product_status,
		'post_title' => $display_name . '-' . $product_cat->slug,
		'post_parent' => '',
		'post_type' => "product",
	);

	$post_id = wp_insert_post($post); //add skill post.
	$logtxt = "PrdctID: $post_id\n";


	wp_set_object_terms($post_id, $product_cat->slug, 'product_cat');
	wp_set_object_terms($post_id, 'simple', 'product_type');

	$firstname = get_user_meta($user_id, 'first_name', true);
	$lastname = get_user_meta($user_id, 'last_name', true);
	$username = get_user_meta($user_id, 'username', true);
	$mobile = get_user_meta($user_id, 'mobile_number', true);
	$address = get_user_meta($user_id, 'address', true);
	$country = get_user_meta($user_id, 'countries', true);
	$state = get_user_meta($user_id, 'state', true);
	$city = get_user_meta($user_id, 'city', true);
	$zip = get_user_meta($user_id, 'zip_code', true);
	$compname = get_user_meta($user_id, 'company_name', true);
	$compurl = get_user_meta($user_id, 'Company_website', true);
	$background_check = get_user_meta($user_id, 'background_check_status', true);
	$license_check = '';
	$certification_check = '';
	$phone_messages = get_user_meta($user_id, 'allow_phone_message', true);
	$email_notifications = get_user_meta($user_id, 'allow_email_notification', true);
	$web_calls = get_user_meta($user_id, 'allow_web_call', true);
	$phone_calls = get_user_meta($user_id, 'allow_phone_call', true);
	$profile_status = get_user_meta($user_id, 'role', true) . '' . get_user_meta($user_id, 'account_status', true);

	$advisor_skill_count = count_user_posts( $user_id , 'product' );
	$current_date = date('my');

	$advisor_location = get_user_meta($user_id, 'user_location', true);

	//get advisor calender
	$calendar_id = $wpdb->get_var('SELECT id FROM quickqtscpabc_appointment_calendars WHERE conwer=' . $user_id);
	if (isset($skillid)) {
		$skills_id = $skillid;
	} else {
		$skills_id = $post_id;
	}

	//Store skill description from POST to variable.
	$skilldescription = trim($_POST['skillDesc']);

	$thedata = array(
		'pa_first_name' => array(
			'name' => 'pa_first_name',
			'value' => $firstname,
			'is_visible' => '1',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_last_name' => array(
			'name' => 'pa_last_name',
			'value' => $lastname,
			'is_visible' => '1',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_email_address' => array(
			'name' => 'pa_email_address',
			'value' => $username,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_mobile_number' => array(
			'name' => 'pa_mobile_number',
			'value' => $mobile,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_full_address' => array(
			'name' => 'pa_full_address',
			'value' => $address,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_country' => array(
			'name' => 'pa_country',
			'value' => $country,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_state' => array(
			'name' => 'pa_state',
			'value' => $state,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_city' => array(
			'name' => 'pa_city',
			'value' => $city,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_zip' => array(
			'name' => 'pa_zip',
			'value' => $zip,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_company_name' => array(
			'name' => 'pa_company_name',
			'value' => $compname,
			'is_visible' => '1',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_company_url' => array(
			'name' => 'pa_company_url',
			'value' => $compurl,
			'is_visible' => '1',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_background-check' => array(
			'name' => 'pa_background-check',
			'value' => $background_check,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_license_check' => array(
			'name' => 'pa_license_check',
			'value' => $license_check,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_certification_check' => array(
			'name' => 'pa_certification_check',
			'value' => $certification_check,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_username' => array(
			'name' => 'pa_username',
			'value' => $username,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_skill_description' => array(
			'name' => 'pa_skill_description',
			'value' => $skilldescription,
			'is_visible' => '1',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_skill_level' => array(
			'name' => 'pa_skill_level',
			'value' => $_POST['skillLevel'],
			'is_visible' => '1',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_phone_messages' => array(
			'name' => 'pa_phone_messages',
			'value' => $phone_messages,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_email_notifications' => array(
			'name' => 'pa_email_notifications',
			'value' => $email_notifications,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_web_calls' => array(
			'name' => 'pa_web_calls',
			'value' => $web_calls,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_phone_calls' => array(
			'name' => 'pa_phone_calls',
			'value' => $phone_calls,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_profile_status' => array(
			'name' => 'pa_profile_status',
			'value' => $profile_status,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_user_id' => array(
			'name' => 'pa_user_id',
			'value' => $user_id,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_calendar_id' => array(
			'name' => 'pa_calendar_id',
			'value' => $calendar_id,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_skills_id' => array(
			'name' => 'pa_skills_id',
			'value' => $skills_id,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
		'pa_advisor_location' => array(
			'name' => 'pa_advisor_location',
			'value' => $advisor_location,
			'is_visible' => '0',
			'is_variation' => '1',
			'is_taxonomy' => '1',
		),
	);

	update_post_meta($post_id, '_product_attributes', $thedata);

	$firstname_termexist = term_exists($firstname, 'pa_first_name'); //Check if term exists.
	if ($firstname_termexist == 0 && $firstname_termexist == null) { //if term not exists
		$first_name_termid = wp_insert_term($firstname, 'pa_first_name'); //insert term
		wp_set_object_terms($post_id, $first_name_termid, 'pa_first_name'); //Set skill term
	} else {
		$first_name_termid = get_term_by('name', $firstname, 'pa_first_name');
		wp_set_object_terms($post_id, $first_name_termid->term_id, 'pa_first_name');
	}

	$lastname_termexist = term_exists($lastname, 'pa_last_name'); //Check if term exists.
	if ($lastname_termexist == 0 && $lastname_termexist == null) { //if term not exists
		$last_name_termid = wp_insert_term($lastname, 'pa_last_name'); //insert term
		wp_set_object_terms($post_id, $last_name_termid, 'pa_last_name'); //Set skill term
	} else {
		$last_name_termid = get_term_by('name', $lastname, 'pa_last_name');
		wp_set_object_terms($post_id, $last_name_termid->term_id, 'pa_last_name');
	}

	$username_termexist = term_exists($username, 'pa_email_address'); //Check if term exists.
	if ($username_termexist == 0 && $username_termexist == null) { //if term not exists
		$email_address_termid = wp_insert_term($username, 'pa_email_address'); //insert term
		wp_set_object_terms($post_id, $email_address_termid, 'pa_email_address'); //Set skill term
	} else {
		$email_address_termid = get_term_by('name', $username, 'pa_email_address');
		wp_set_object_terms($post_id, $email_address_termid->term_id, 'pa_email_address');
	}

	$mobile_termexist = term_exists($mobile, 'pa_mobile_number'); //Check if term exists.
	if ($mobile_termexist == 0 && $mobile_termexist == null) { //if term not exists
		$mobile_number_termid = wp_insert_term($mobile, 'pa_mobile_number'); //insert term
		wp_set_object_terms($post_id, $mobile_number_termid, 'pa_mobile_number'); //Set skill term
	} else {
		$mobile_number_termid = get_term_by('name', $mobile, 'pa_mobile_number');
		wp_set_object_terms($post_id, $mobile_number_termid->term_id, 'pa_mobile_number');
	}

	$address_termexist = term_exists($address, 'pa_full_address'); //Check if term exists.
	if ($address_termexist == 0 && $address_termexist == null) { //if term not exists
		$full_address_termid = wp_insert_term($address, 'pa_full_address'); //insert term
		wp_set_object_terms($post_id, $address, 'pa_full_address'); //Set skill term
	} else {
		$full_address_termid = get_term_by('name', $address, 'pa_full_address');
		wp_set_object_terms($post_id, $full_address_termid->term_id, 'pa_full_address');
	}

	$country_termexist = term_exists($country, 'pa_country'); //Check if term exists.
	if ($country_termexist == 0 && $country_termexist == null) { //if term not exists
		$country_termid = wp_insert_term($country, 'pa_country'); //insert term
		wp_set_object_terms($post_id, $country, 'pa_country'); //Set skill term
	} else {
		$country_termid = get_term_by('name', $country, 'pa_country');
		wp_set_object_terms($post_id, $country_termid->term_id, 'pa_country');
	}

	$state_termexist = term_exists($state, 'pa_state'); //Check if term exists.
	if ($state_termexist == 0 && $state_termexist == null) { //if term not exists
		$state_termid = wp_insert_term($state, 'pa_state'); //insert term
		wp_set_object_terms($post_id, $state, 'pa_state'); //Set skill term
	} else {
		$state_termid = get_term_by('name', $state, 'pa_state');
		wp_set_object_terms($post_id, $state_termid->term_id, 'pa_state');
	}

	$city_termexist = term_exists($city, 'pa_state'); //Check if term exists.
	if ($city_termexist == 0 && $city_termexist == null) { //if term not exists
		$city_termid = wp_insert_term($city, 'pa_city'); //insert term
		wp_set_object_terms($post_id, $city, 'pa_city'); //Set skill term
	} else {
		$city_termid = get_term_by('name', $city, 'pa_city');
		wp_set_object_terms($post_id, $city_termid->term_id, 'pa_city');
	}

	$zip_termexist = term_exists($zip, 'pa_zip'); //Check if term exists.
	if ($zip_termexist == 0 && $zip_termexist == null) { //if term not exists
		$zip_termid = wp_insert_term($zip, 'pa_zip'); //insert term
		wp_set_object_terms($post_id, $zip, 'pa_zip'); //Set skill term
	} else {
		$zip_termid = get_term_by('name', $zip, 'pa_zip');
		wp_set_object_terms($post_id, $zip_termid->term_id, 'pa_zip');
	}

	$background_check_termexist = term_exists($background_check, 'pa_background-check'); //Check if term exists.
	if ($background_check_termexist == 0 && $background_check_termexist == null) { //if term not exists
		$background_check_termid = wp_insert_term($background_check, 'pa_background-check'); //insert term
		wp_set_object_terms($post_id, $background_check_termid, 'pa_background-check'); //Set skill term
	} else {
		$background_check_termid = get_term_by('name', $background_check, 'pa_background-check');
		wp_set_object_terms($post_id, $background_check_termid->term_id, 'pa_background-check');
	}

	$compname_termexist = term_exists($compname, 'pa_company_name'); //Check if term exists.
	if ($compname_termexist == 0 && $compname_termexist == null) { //if term not exists
		$company_name_termid = wp_insert_term($compname, 'pa_company_name'); //insert term
		wp_set_object_terms($post_id, $company_name_termid, 'pa_company_name'); //Set skill term
	} else {
		$company_name_termid = get_term_by('name', $compname, 'pa_company_name');
		wp_set_object_terms($post_id, $company_name_termid->term_id, 'pa_company_name');
	}

	$compurl_termexist = term_exists($compurl, 'pa_company_url'); //Check if term exists.
	if ($compurl_termexist == 0 && $compurl_termexist == null) { //if term not exists
		$compurl_termid = wp_insert_term($compurl, 'pa_company_url'); //insert term
		wp_set_object_terms($post_id, $compurl_termid, 'pa_company_url'); //Set skill term
	} else {
		$compurl_termid = get_term_by('name', $compurl, 'pa_company_url');
		wp_set_object_terms($post_id, $compurl_termid->term_id, 'pa_company_url');
	}

	$username_termexist = term_exists($username, 'pa_username'); //Check if term exists.
	if ($username_termexist == 0 && $username_termexist == null) { //if term not exists
		$username_termid = wp_insert_term($username, 'pa_username'); //insert term
		wp_set_object_terms($post_id, $username_termid, 'pa_username'); //Set skill term
	} else {
		$username_termid = get_term_by('name', $username, 'pa_username');
		wp_set_object_terms($post_id, $username_termid->term_id, 'pa_username');
	}

	$user_id_termexist = term_exists($user_id, 'pa_user_id'); //Check if term exists.
	if ($user_id_termexist == 0 && $user_id_termexist == null) { //if term not exists
		$user_id_termid = wp_insert_term($user_id, 'pa_user_id'); //insert term
		wp_set_object_terms($post_id, $user_id_termid, 'pa_user_id'); //Set skill term
	} else {
		$user_id_termid = get_term_by('name', $user_id, 'pa_user_id');
		wp_set_object_terms($post_id, $user_id_termid->term_id, 'pa_user_id');
	}

	$skillDesc_termexist = term_exists($skilldescription, 'pa_skill_description'); //Check if term exists.
	if ($skillDesc_termexist == 0 && $skillDesc_termexist == null) { //if term not exists
		$skillDesc_termid = wp_insert_term($skilldescription, 'pa_skill_description'); //insert term
		wp_set_object_terms($post_id, $skillDesc_termid, 'pa_skill_description'); //Set skill term
	} else {
		$skillDesc_termid = get_term_by('name', $skilldescription, 'pa_skill_description');
		wp_set_object_terms($post_id, $skillDesc_termid->term_id, 'pa_skill_description');
	}

	$skillLevel_termexist = term_exists($_POST['skillLevel'], 'pa_skill_level'); //Check if term exists.
	if ($skillLevel_termexist == 0 && $skillLevel_termexist == null) { //if term not exists
		$skillLevel_termid = wp_insert_term($_POST['skillLevel'], 'pa_skill_level'); //insert term
		wp_set_object_terms($post_id, $skillLevel_termid, 'pa_skill_level'); //Set skill term
	} else {
		$skillLevel_termid = get_term_by('name', $_POST['skillLevel'], 'pa_skill_level');
		wp_set_object_terms($post_id, $skillLevel_termid->term_id, 'pa_skill_level');
	}

	$skillCategory_termexist = term_exists($_POST['skillCategory'], 'pa_skills_id'); //Check if term exists.
	if ($skillCategory_termexist == 0 && $skillCategory_termexist == null) { //if term not exists
		$skillCategory_termid = wp_insert_term($_POST['skillCategory'], 'pa_skills_id'); //insert term
		wp_set_object_terms($post_id, $skillCategory_termid, 'pa_skills_id'); //Set skill term
	} else {
		$skillCategory_termid = get_term_by('name', $_POST['skillCategory'], 'pa_skills_id');
		wp_set_object_terms($post_id, $skillCategory_termid->term_id, 'pa_skills_id');
	}

	$phone_messages_termexist = term_exists($phone_messages, 'pa_phone_messages'); //Check if term exists.
	if ($phone_messages_termexist == 0 && $phone_messages_termexist == null) { //if term not exists
		$phone_messages_termid = wp_insert_term($phone_messages, 'pa_phone_messages'); //insert term
		wp_set_object_terms($post_id, $phone_messages_termid, 'pa_phone_messages'); //Set skill term
	} else {
		$phone_messages_termid = get_term_by('name', $phone_messages, 'pa_phone_messages');
		wp_set_object_terms($post_id, $phone_messages_termid->term_id, 'pa_phone_messages');
	}

	$email_notifications_termexist = term_exists($email_notifications, 'pa_email_notifications'); //Check if term exists.
	if ($email_notifications_termexist == 0 && $email_notifications_termexist == null) { //if term not exists
		$email_notifications_termid = wp_insert_term($email_notifications, 'pa_email_notifications'); //insert term
		wp_set_object_terms($post_id, $email_notifications_termid, 'pa_email_notifications'); //Set skill term
	} else {
		$email_notifications_termid = get_term_by('name', $email_notifications, 'pa_email_notifications');
		wp_set_object_terms($post_id, $email_notifications_termid->term_id, 'pa_email_notifications');
	}

	$web_calls_termexist = term_exists($web_calls, 'pa_web_calls'); //Check if term exists.
	if ($web_calls_termexist == 0 && $web_calls_termexist == null) { //if term not exists
		$web_calls_termid = wp_insert_term($web_calls, 'pa_web_calls'); //insert term
		wp_set_object_terms($post_id, $web_calls_termid, 'pa_web_calls'); //Set skill term
	} else {
		$web_calls_termid = get_term_by('name', $web_calls, 'pa_web_calls');
		wp_set_object_terms($post_id, $web_calls_termid->term_id, 'pa_web_calls');
	}

	$phone_calls_termexist = term_exists($phone_calls, 'pa_phone_calls'); //Check if term exists.
	if ($phone_calls_termexist == 0 && $phone_calls_termexist == null) { //if term not exists
		$phone_calls_termid = wp_insert_term($phone_calls, 'pa_phone_calls'); //insert term
		wp_set_object_terms($post_id, $phone_calls_termid, 'pa_phone_calls'); //Set skill term
	} else {
		$phone_calls_termid = get_term_by('name', $phone_calls, 'pa_phone_calls');
		wp_set_object_terms($post_id, $phone_calls_termid->term_id, 'pa_phone_calls');
	}

	$profile_status_termexist = term_exists($profile_status, 'pa_profile_status'); //Check if term exists.
	if ($profile_status_termexist == 0 && $profile_status_termexist == null) { //if term not exists
		$profile_status_termid = wp_insert_term($profile_status, 'pa_profile_status'); //insert term
		wp_set_object_terms($post_id, $profile_status_termid, 'pa_profile_status'); //Set skill term
	} else {
		$profile_status_termid = get_term_by('name', $profile_status, 'pa_profile_status');
		wp_set_object_terms($post_id, $profile_status_termid->term_id, 'pa_profile_status');
	}

	$calendar_id_termexist = term_exists($calendar_id, 'pa_calendar_id'); //Check if term exists.
	if ($calendar_id_termexist == 0 && $calendar_id_termexist == null) { //if term not exists
		$calendar_id_termid = wp_insert_term($calendar_id, 'pa_calendar_id'); //insert term
		wp_set_object_terms($post_id, $calendar_id_termid, 'pa_calendar_id'); //Set skill term
	} else {
		$calendar_id_termid = get_term_by('name', $calendar_id, 'pa_calendar_id');
		wp_set_object_terms($post_id, $calendar_id_termid->term_id, 'pa_calendar_id');
	}

	$skills_id_termexist = term_exists($skills_id, 'pa_skills_id'); //Check if term exists.
	if ($skills_id_termexist == 0 && $skills_id_termexist == null) { //if term not exists
		$skills_id_termid = wp_insert_term($skills_id, 'pa_skills_id'); //insert term
		wp_set_object_terms($post_id, $skills_id_termid, 'pa_skills_id'); //Set skill term
	} else {
		$skills_id_termid = get_term_by('name', $skills_id, 'pa_skills_id');
		wp_set_object_terms($post_id, $skills_id_termid->term_id, 'pa_skills_id');
	}

	$advisor_location_termexist = term_exists($advisor_location, 'pa_advisor_location'); //Check if term exists.
	if ($advisor_location_termexist == 0 && $advisor_location_termexist == null) { //if term not exists
		$advisor_location_termid = wp_insert_term($advisor_location, 'pa_advisor_location'); //insert term
		wp_set_object_terms($post_id, $advisor_location_termid, 'pa_advisor_location'); //Set skill term
	} else {
		$advisor_location_termid = get_term_by('name', $skills_id, 'pa_advisor_location');
		wp_set_object_terms($post_id, $advisor_location_termid->term_id, 'pa_advisor_location');
	}

	$skilltags = $_POST['skillTags'];
	$skilltagsarray = explode(',', $skilltags);
	wp_set_object_terms($post_id, $skilltagsarray, 'product_tag');

	update_post_meta($post_id, '_visibility', 'visible');
	update_post_meta($post_id, '_stock_status', 'instock');
	update_post_meta($post_id, 'total_sales', '0');
	update_post_meta($post_id, '_downloadable', 'no');
	update_post_meta($post_id, '_virtual', 'yes');
	update_post_meta($post_id, '_regular_price', $_POST['skillRate']);
	update_post_meta($post_id, '_sale_price', $_POST['skillRate']);
	update_post_meta($post_id, '_purchase_note', "");
	update_post_meta($post_id, '_featured', "no");
	update_post_meta($post_id, '_weight', "");
	update_post_meta($post_id, '_length', "");
	update_post_meta($post_id, '_width', "");
	update_post_meta($post_id, '_height', "");
	update_post_meta($post_id, '_sku', "$firstname-$advisor_skill_count-$current_date");
	update_post_meta($post_id, '_sale_price_dates_from', "");
	update_post_meta($post_id, '_sale_price_dates_to', "");
	update_post_meta($post_id, '_price', $_POST['skillRate']);
	update_post_meta($post_id, '_sold_individually', "yes");
	update_post_meta($post_id, '_manage_stock', "yes");
	update_post_meta($post_id, '_backorders', "no");
	update_post_meta($post_id, '_stock', "1");

	//add supporting link
	if (get_post_meta($post_id, 'supporting_link', true)) {
		update_post_meta($post_id, 'supporting_link', $_POST['skillSupportLink']);
	} else {
		add_post_meta($post_id, 'supporting_link', $_POST['skillSupportLink']);
	}

	//add Skill Image of the advisor -Start
	if (!has_post_thumbnail($post_id)) {

		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;

		$profilepic = get_user_meta($user_id, 'profile_photo', true);

		if ($profilepic != '') {
			$thumb_url = get_site_url() . "/wp-content/uploads/ultimatemember/" . $user_id . "/" . get_user_meta($user_id, 'profile_photo', true);
		} else {
			$thumb_url = get_site_url() . "/wp-content/uploads/ultimatemember/no-image.png";
		}

		// Download file to temp location
		$tmp = download_url($thumb_url);
		//print_r($tmp);die;
		// Set variables for storage
		// fix file name for query strings
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $thumb_url, $matches);
		$file_array['name'] = basename($matches[0]);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if (is_wp_error($tmp)) {
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] = '';
			$logtxt .= "Error: download_url error - $tmp\n";
		} else {
			$logtxt .= "download_url: $tmp\n";
		}
		if (!function_exists('media_handle_upload')) {
			require_once ABSPATH . "wp-admin" . '/includes/image.php';
			require_once ABSPATH . "wp-admin" . '/includes/file.php';
			require_once ABSPATH . "wp-admin" . '/includes/media.php';
		}
		//use media_handle_sideload to upload img:
		$thumbid = media_handle_sideload($file_array, $post_id, 'gallery desc');

		// If error storing permanently, unlink
		if (is_wp_error($thumbid)) {
			@unlink($file_array['tmp_name']);
			//return $thumbid;
			$logtxt .= "Error: media_handle_sideload error - $thumbid\n";
		} else {
			$logtxt .= "ThumbID: $thumbid\n";
		}

		set_post_thumbnail($post_id, $thumbid);
	}
	//add Skill Image of the advisor -End

	//Handle Certificate  Upload. -Start
	if ($_FILES['skillDegree']['error'] === UPLOAD_ERR_OK) {

		if (!function_exists('wp_handle_upload')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		// check filetype is pdf and return if not
		$file_type = $_FILES['skillDegree']['type'];

		if ($file_type != 'application/pdf') {
			return;
		}

		// upload the file
		$overrides = array('test_form' => false);
		$skilldeg = $_FILES["skillDegree"];

		$file = wp_handle_upload($skilldeg, $overrides);
		if ($file && !isset($file['error'])) {
			//echo "File is valid, and was successfully uploaded.\n";
			//var_dump( $file);
			if (isset($file['file'])) {
				// Gather data for attachment
				$title = str_replace(".pdf", "", $_FILES["skillDegree"]['name']);
				$attachment = array(
					'post_mime_type' => $file_type,
					'post_title' => addslashes($title),
					'post_content' => '',
					'post_status' => 'inherit',
					'post_parent' => $post_id,
				);
				//create attachment & update metadata
				$attach_id = wp_insert_attachment($attachment, $file['file']);
				// Before we update the post meta, trash any previously uploaded pdfs for this post.
				$existing_skillDegree = (int) get_post_meta($post_id, 'skillDegree', true);
				if (is_numeric($existing_skillDegree)) {
					wp_delete_attachment($existing_skillDegree);
				}
				// Now, update the post meta to associate the new pdf with the post
				update_post_meta($post_id, "skillDegree", $attach_id);
			}
		} else {
			/**
			 * Error generated by _wp_handle_upload()
			 * @see _wp_handle_upload() in wp-admin/includes/file.php
			 */
			echo $file['error'];
			//die;
		}

	}
	//Handle Certificate  Upload. -End

	//Handle Licence Upload. -Start
	if ($_FILES['skillLicense']['error'] === UPLOAD_ERR_OK) {

		if (!function_exists('wp_handle_upload')) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}
		// check filetype is pdf and return if not
		$file_type = $_FILES['skillLicense']['type'];

		if ($file_type != 'application/pdf') {
			return;
		}

		// upload the file
		$overrides = array('test_form' => false);
		$skilldeg = $_FILES["skillLicense"];

		$file = wp_handle_upload($skilldeg, $overrides);
		if ($file && !isset($file['error'])) {
			//echo "File is valid, and was successfully uploaded.\n";
			//var_dump( $file);
			if (isset($file['file'])) {
				// Gather data for attachment
				$title = str_replace(".pdf", "", $_FILES["skillLicense"]['name']);
				$attachment = array(
					'post_mime_type' => $file_type,
					'post_title' => addslashes($title),
					'post_content' => '',
					'post_status' => 'inherit',
					'post_parent' => $post_id,
				);
				//create attachment & update metadata
				$attach_id = wp_insert_attachment($attachment, $file['file']);
				// Before we update the post meta, trash any previously uploaded pdfs for this post.
				$existing_skillLicense = (int) get_post_meta($post_id, 'skillLicense', true);
				if (is_numeric($existing_skillLicense)) {
					wp_delete_attachment($existing_skillLicense);
				}
				// Now, update the post meta to associate the new pdf with the post
				update_post_meta($post_id, "skillLicense", $attach_id);
			}
		} else {
			/**
			 * Error generated by _wp_handle_upload()
			 * @see _wp_handle_upload() in wp-admin/includes/file.php
			 */
			echo $file['error'];
			//die;
		}

	}

	//Handle Licence Upload. -End
	wp_redirect(get_permalink(get_page_by_path('mynd-skills')));
	exit;
}
//add products as skills from frontend form - End

//WebRTC Functionality - Start
function WebRTC_advisor_login() {
	$current_user = wp_get_current_user();
	$user_role = get_user_meta($current_user->ID,'role',true);
	if ($user_role == 'advisor') {

		$myoptions = get_option('tokbox_settings');
		$apiKey = $myoptions['tokbox_account_api_key'];
		$apiSecret =$myoptions['tokbox_secret'];


		$opentok = new OpenTok($apiKey, $apiSecret);

		$session = $opentok->createSession();

		$sessionId = $session->getSessionId();

		add_user_meta($current_user->ID, 'webrtcSessionID', $sessionId, true);
	}



}
add_action( 'um_on_login_before_redirect', 'WebRTC_advisor_login',10 );

function WebRTC_advisor_logout() {
	$current_user = wp_get_current_user();
	$user_role = get_user_meta($current_user->ID,'role',true);
	if($user_role == 'advisor') {
		delete_user_meta( $current_user->ID, 'webrtcSessionID' );
	}
}
add_action('clear_auth_cookie', 'WebRTC_advisor_logout');


function WebRTC_advisor_generate_token() {
	$current_user = wp_get_current_user();
	$myoptions = get_option('tokbox_settings');
	$apiKey = $myoptions['tokbox_account_api_key'];
	$apiSecret =$myoptions['tokbox_secret'];

	$advisor_sessionID = get_user_meta( $current_user->ID, 'webrtcSessionID',true );
//echo $advisor_sessionID;die;


	$opentok = new OpenTok($apiKey, $apiSecret);

	$token = $opentok->generateToken($advisor_sessionID,array(
		'role'       => 'publisher',
		'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
		'data'       => (string)$current_user->ID
	));
	//echo 'token - '.$token;
	$return = array(
		'token'	=> $token,
		'apiKey'	=> $apiKey,
		'sessionId' => $advisor_sessionID
	);
	wp_send_json_success($return);
}
add_action('wp_ajax_advisor_gen_token', 'WebRTC_advisor_generate_token');

function call_duration(){
	die("Call Duration Function Called");
}
add_action('wp_ajax_call_duration','call_duration');

function web_call_start() {
	global $wpdb;
	$wpdb->show_errors     = true;
	$wpdb->suppress_errors = false;

	$advisee_user_id = $_POST['userid'];
	$start_time = $_POST['start_time'];
	$session_connection_id = $_POST['session_connection_id'];
	$stream_connection_id = $_POST['stream_connection_id'];

	$advisee_row = $wpdb->get_row( "SELECT * FROM quickqtsvideo_call_log WHERE stream_connection_id='".$session_connection_id."'" );

	if($advisee_row->stream_connection_id != $session_connection_id){
		$advisee_user_id = $_POST['userid'];
		if($wpdb->insert('quickqtsvideo_call_log',array(
				'advisee_userid'=> $advisee_user_id,
				'session_connection_id'=>$session_connection_id,
				'stream_connection_id'=>$stream_connection_id,
				'video-call-start-time'=>$start_time
			))===FALSE){

			echo "Error";
			if ($wpdb->last_error) {
				die('error=' . var_dump($wpdb->last_query) . ',' . var_dump($wpdb->error));
			}
			$wpdb->print_error();

		}
		else {
			echo "Start Time  successfully added, row ID is ".$wpdb->insert_id;

		}
		die();
	} else {
		$advisor_user_id = $_POST['userid'];
		if($wpdb->update('quickqtsvideo_call_log',array(
				'advisor_userid'=> $advisor_user_id,
				// 'session_connection_id'=>$session_connection_id,
				// 'stream_connection_id'=>$stream_connection_id,
				// 'video-call-start-time'=>$start_time
			),array(
				'stream_connection_id' => $session_connection_id
			))===FALSE){

			echo "Error";
			if ($wpdb->last_error) {
				die('error=' . var_dump($wpdb->last_query) . ',' . var_dump($wpdb->error));
			}
			$wpdb->print_error();

		}
		else {
			echo "Start Time  successfully added, row ID is ".$wpdb->insert_id;

		}
		die();
		echo "already inserted";
	}
}
add_action('wp_ajax_web_call_start','web_call_start');

function web_call_end() {
	global $wpdb;

	$user_id = get_current_user_id();
	$end_time = $_POST['end_time'];
	$session_connection_id = $_POST['session_connection_id'];
	$stream_connection_id = $_POST['stream_connection_id'];

	$advisee_row = $wpdb->get_row( "SELECT * FROM quickqtsvideo_call_log WHERE stream_connection_id='".$session_connection_id."'" ); //advisee disconnect
	$advisor_row = $wpdb->get_row( "SELECT * FROM quickqtsvideo_call_log WHERE stream_connection_id='".$stream_connection_id."'" ); //advisor disconnect

	if ($advisee_row->stream_connection_id == $session_connection_id) {
		$sql = "UPDATE quickqtsvideo_call_log SET `video-call-end-time`='".$end_time."'"."WHERE `stream_connection_id`='".$session_connection_id."'";
		echo '1'.$sql;
		$result = $wpdb->query($sql);
		print_r($result);
		die();
	} elseif($advisor_row->session_connection_id == $session_connection_id) {
		$sql = "UPDATE quickqtsvideo_call_log SET `video-call-end-time`='".$end_time."'"."WHERE `session_connection_id`='".$session_connection_id."'";
		echo '2'.$sql;
		$result = $wpdb->query($sql);
		print_r($result);
		die();
	}
}
add_action('wp_ajax_web_call_end','web_call_end');

function store_last_login($current_user) {
	global $current_user;

	//  echo get_currentuserinfo();
	$user = $current_user->user_login;
	update_user_meta($current_user->ID, 'last_login', current_time('mysql', 1));
	//  die();
}
add_action('um_on_login_before_redirect', 'store_last_login', 10, 2);

function offline_users() {
	$current_user = wp_get_current_user();
	delete_user_meta( $current_user->ID, 'last_login' );
}
add_action('clear_auth_cookie', 'offline_users');


//WebRTC Functionality - End

//Approve advisor documents functionality - Start
//Add meta box for showing attachment -Start
function document_add_meta_box() {

    $screens = array( 'post', 'product' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'myplugin_sectionid',
            'Advisor Document',
            'view_document',
            $screen
        );
    }
}
add_action( 'add_meta_boxes', 'document_add_meta_box' );

function view_document( $post ) {

    $value = get_post_meta( $post->ID, 'document_approve', true );

    $degree  = get_post_meta( $post->ID, 'skillDegree', true );
    $license = get_post_meta( $post->ID, 'skillLicense', true );

    if ($degree == '') {
        $document = $license;
    } else {
        $document = $degree;
    }

    $level_obj = get_the_terms($post->ID, 'pa_skill_level');
    $level = $level_obj[0]->slug;

    if($level == 'licensed-to-practice' || $level == 'certified' ) {
        echo '<label for="myplugin_new_field">';
        _e('Document Uploaded', 'myplugin_textdomain');
        echo '</label> ';
        echo '<a href="' . wp_get_attachment_url($document) . '" target="_blank">View Document</a><br>';

        wp_nonce_field('doc_approve_meta_box_data', 'doc_approve_meta_box_nonce');
        if($value == 'yes'){
            $app = 'checked';
        }

        echo '<label for="document_approval">';
        _e( 'Approve Document', 'myplugin_textdomain' );
        echo '</label> ';
        echo '<input type="checkbox" id="document_approval" name="document_approval" value="yes" '.$app.' />';
    }


}

function save_doc_approve_meta_box_data( $post_id ) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['doc_approve_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['doc_approve_meta_box_nonce'], 'doc_approve_meta_box_data' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( isset( $_POST['post_type'] ) && 'product' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'manage_options', $post_id ) ) {
            return;
        }

    } else {

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['document_approval'] ) ) {
        return;
    }

    // Sanitize user input.
    $my_data = sanitize_text_field( $_POST['document_approval'] );

    // Update the meta field in the database.
    update_post_meta( $post_id, 'document_approve', $my_data );
}
add_action( 'save_post', 'save_doc_approve_meta_box_data' );
//Add meta box for showing attachment -End
//Approve advisor documents functionality - End

//Remove Add to Cart Button - Start
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
//Remove Add to Cart Button- end


//2nd Phase -End

//3rd Phase -Start

/* Add Communication tab for video, phone and messages in profile page - Start */

//Video Tab - Start
add_filter('um_profile_tabs', 'add_custom_video_call_log_profile_tab', 1000);
function add_custom_video_call_log_profile_tab($video) {

	$video['video_call_log'] = array(
		'name' => 'My Video Calls',
		'icon' => 'um-favicon-comments',
	);
	return $video;
}

add_action('um_profile_content_video_call_log_default', 'um_profile_content_video_call_log_default');
function um_profile_content_video_call_log_default( $args ) {
	echo do_shortcode('[get_video_call_details]');
}
//Video Tab - End

//Phone Tab - Start
add_filter('um_profile_tabs', 'add_custom_phone_call_log_profile_tab', 1000);
function add_custom_phone_call_log_profile_tab($phone) {

	$phone['phone_call_log'] = array(
		'name' => 'My Phone Calls',
		'icon' => 'um-favicon-comments',
	);
	return $phone;
}

add_action('um_profile_content_phone_call_log_default', 'um_profile_content_phone_call_log_default');
function um_profile_content_phone_call_log_default( $args ) {
	echo do_shortcode('[get_phone_call_details]');
}
//Phone Tab - End

//Message Tab - Start
add_filter('um_profile_tabs', 'add_custom_phone_message_log_profile_tab', 1000);
function add_custom_phone_message_log_profile_tab($message) {

	$message['phone_message_log'] = array(
		'name' => 'My Phone Messages',
		'icon' => 'um-favicon-comments',
	);
	return $message;
}

add_action('um_profile_content_phone_message_log_default', 'um_profile_content_phone_message_log_default');
function um_profile_content_phone_message_log_default( $args ) {
	echo do_shortcode('[get_phone_messages_details]');
}
//Message Tab - End

/* Add Communication tab for video, phone and messages in profile page - End */

//3rd Phase -End

//Added by pravar - End

/*********************************************************End- By CIS******************************************************************************************/
