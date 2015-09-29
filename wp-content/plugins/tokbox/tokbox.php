<?php
/**
 * Plugin Name: TokBox API
 * Description: Add tokbox webRTC  functionality.
 * Version: 1.0
 * Author: CIS
 * Author URI: http://www.cisin.com
 */

include plugin_dir_path(__FILE__) . 'inc/autoload.php';

use OpenTok\MediaMode;
use OpenTok\OpenTok;
use OpenTok\Role;


add_action('init', 'tokbox_api');
function tokbox_api() {
    wp_enqueue_script('the_tokboxjs', '//static.opentok.com/webrtc/v2.2/js/opentok.min.js');

}


add_action('wp_ajax_webrtc_action', 'tokbox');
function tokbox() {

    $current_user = wp_get_current_user();
    $skillid = $_POST['skillid'];
    $myoptions = get_option('tokbox_settings');
    $apiKey = $myoptions['tokbox_account_api_key'];
    $apiSecret =$myoptions['tokbox_secret'];



    //get advisor id
    $skill = get_post($skillid);
    $advisor_id = $skill->post_author;
    $advisor_session_id = get_user_meta($advisor_id,'webrtcSessionID',true);


    $opentok = new OpenTok($apiKey, $apiSecret);

    // Set some options in a token
    $token = $opentok->generateToken($advisor_session_id,array(
        'role'       => 'publisher',
        'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
        'data'       => (string)$current_user->ID
    ));

    $return = array(
        'apiKey'	=> $apiKey,
        'sessionId'	=> $advisor_session_id,
        'token'     => $token
    );
    wp_send_json_success($return);

}


//Add Admin menu and its pages
add_action('admin_menu', 'tokbox_add_admin_menu');
add_action('admin_init', 'tokbox_settings_init');

function tokbox_add_admin_menu() {

    add_menu_page('Tokbox', 'Tokbox', 'manage_options', 'tokbox', 'tokbox_options_page');
    add_submenu_page('tokbox', 'Call Details', 'Call History', 'administrator', 'tokbox_call', 'tokbox_call_details');
}

function tokbox_call_details() {
    $myListTable = new My_call_Table();
    echo '<div class="wrap"><h2>My List Table Test</h2>';
    $myListTable->prepare_items();
    $myListTable->display();
    echo '</div>';

}

function tokbox_settings_init() {

    register_setting('tokboxPluginPage', 'tokbox_settings');

    add_settings_section(
        'tokbox_pluginPage_section',
        __('API Settings', 'wordpress'),
        'tokbox_settings_section_callback',
        'tokboxPluginPage'
    );

    add_settings_field(
        'tokbox_account_sid',
        __('API KEY', 'wordpress'),
        'tokbox_account_api_key_render',
        'tokboxPluginPage',
        'tokbox_pluginPage_section'
    );

    add_settings_field(
        'tokbox_auth_token',
        __('SECRET', 'wordpress'),
        'tokbox_secret_render',
        'tokboxPluginPage',
        'tokbox_pluginPage_section'
    );
}

function tokbox_account_api_key_render() {

    $options = get_option('tokbox_settings');
    ?>
    <input type='text' name='tokbox_settings[tokbox_account_api_key]' value='<?php echo $options['tokbox_account_api_key'];?>'>
    <?php

}

function tokbox_secret_render() {

    $options = get_option('tokbox_settings');
    ?>
    <input type='text' name='tokbox_settings[tokbox_secret]' value='<?php echo $options['tokbox_secret'];?>'>
    <?php

}

function tokbox_settings_section_callback() {

    echo __('Enter API Credentials', 'wordpress');

}

function tokbox_options_page() {

    ?>
    <form action='options.php' method='post'>

        <h2>Twilio</h2>

        <?php
        settings_fields('tokboxPluginPage');
        do_settings_sections('tokboxPluginPage');
        submit_button();
        ?>

    </form>
    <?php

}

//Shortcode for Getting Video Call log and display - Start
add_shortcode( 'get_video_call_details' , 'get_video_call_details' );
function get_video_call_details() {
    global $wpdb;
    $userid = get_current_user_id();
    $role = get_user_meta($userid, 'role', true);
    if($role == 'advisor') {
        $current_role = 'advisor_userid';
    } else {
        $current_role = 'advisee_userid';
    }
    $q = 'SELECT * FROM quickqtsvideo_call_log WHERE '.$current_role.'='.$userid;
    $video_calls = $wpdb->get_results($q);


    $html="<h1>My Video Calls</h1><br><table class='footable'>";
    $html.="<thead><th>Date-Time</th></th><th>Video Call From</th><th>Video Call To</th><th>Duration</th><th>Price</th></thead>";
    $html.="<tbody>";
    foreach ($video_calls as $videocall) {
        $datetime_start = new DateTime($videocall->video_call_start_time);
        $datetime_end = new DateTime($videocall->video_call_end_time);
        $interval = $datetime_start->diff($datetime_end);
        $call_seconds = $interval->format('%S');

        $html .= "<tr><td>" . $videocall->video_call_start_time . "</td><td>" . get_user_meta($videocall->advisee_userid,'username',true) . "</td><td>" .get_user_meta($videocall->advisor_userid,'username',true) . "</td><td>"  .$interval->format('%S Seconds'). "</td><td>" .round($call_seconds/60, PHP_ROUND_HALF_UP).' Credits'. "</td></tr>";
    }

    $html.="</tbody>";
    $html.="</table>";
    echo $html;
}
//Shortcode for Getting Video Call log and display  - End