<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
?>
<div id="videocontainer"></div>
<?php if ( vp_metabox('misc_settings.hb_onepage') && !vp_metabox('misc_settings.hb_disable_navigation')) { ?>
	<ul id="hb-one-page-bullets"></ul>
<?php } ?>

<?php if ( hb_options('hb_to_top_button') && !is_page_template('page-blank.php') ) { ?>
<!-- Back to Top Button -->
<a id="to-top"><i class="<?php echo hb_options('hb_back_to_top_icon'); ?>"></i></a>
<!-- END #to-top -->
<?php } ?>

<?php if ( !is_page_template('page-blank.php') && hb_options('hb_enable_quick_contact_box') ) { ?>
<!-- BEGIN #contact-panel -->
<aside id="contact-panel">
	<h4 class="hb-focus-color"><?php echo hb_options('hb_quick_contact_box_title'); ?></h4>
	<p><?php echo hb_options('hb_quick_contact_box_text'); ?></p>

	<form id="contact-panel-form">
		<p><input type="text" placeholder="<?php _e('Name', 'hbthemes'); ?>" name="hb_contact_name" id="hb_contact_name_id" class="required requiredField" tabindex="33"/></p>
		<p><input type="email" placeholder="<?php _e('Email', 'hbthemes'); ?>" name="hb_contact_email" id="hb_contact_email_id" class="required requiredField" tabindex="34"/></p>
		<p><input type="text" placeholder="<?php _e('Subject', 'hbthemes'); ?>" name="hb_contact_subject" id="hb_contact_subject_id"/></p>
		<p><textarea placeholder="<?php _e('Your message...', 'hbthemes'); ?>" name="hb_contact_message" id="hb_contact_message_id" class="required requiredField" tabindex="35"></textarea></p>
		<a href="#" id="hb-submit-contact-panel-form" class="hb-button no-three-d hb-push-button hb-asbestos hb-small-button">
			<span class="hb-push-button-icon">
				<i class="hb-moon-paper-plane"></i>
			</span>
			<span class="hb-push-button-text"><?php echo hb_options('hb_quick_contact_box_button_title'); ?></span>
		</a>
		<input type="hidden" id="success_text" value="<?php _e('Message Sent!', 'hbthemes'); ?>"/>
	</form>

</aside>
<!-- END #contact-panel -->

<!-- BEGIN #message-panel -->
<aside id="message-panel">
	<!--<a class="close_sms" href="javascript:void(0)" style="float: right;font-size: 20px;">X</a>-->
	<h4 class="hb-focus-color"><?php echo "Send Message"; ?> <span id="log_sms"></span></h4>
	<form id="message-panel-form">
		<p><input type="text" placeholder="<?php _e('Message', 'hbthemes'); ?>" name="twilio_message" id="twilio_message_id"  class="required requiredField" tabindex="36" /></p>
		<p><input type="hidden" class="msgskillid" /></p>
		<a href="#" id="hb-submit-message-panel-form" class="hb-button no-three-d hb-push-button hb-asbestos hb-small-button">
			<span class="hb-push-button-icon">
				<i class="hb-moon-paper-plane"></i>
			</span>
			<span class="hb-push-button-text"><?php echo "Send Message"; ?></span><div id="log_sms" class="hb-push-button-text"></div>
		</a>
		<input type="hidden" id="success_text" value="<?php _e('Message Sent!', 'hbthemes'); ?>"/>
	</form>
</aside>
<!-- End #message-panel -->

<!-- BEGIN #calling-panel -->
<aside id="calling-panel">
	<!--<a class="close_call" href="javascript:void(0)" style="float: right;font-size: 20px;">X</a>-->
	<h4 class="hb-focus-color"><?php echo "Call Advisor"; ?></h4>
	<div id="calling-panel-form">
		<p><div id="log_call" style="float: left;"></div> &nbsp; is Ready to Call.</p>
		<p><input type="hidden" class="msgskillid" /></p>
		<a href="#" id="hb-submit-startcalling" ><img src="<?php echo get_stylesheet_directory_uri() ?>/images/phone_green.png"></a>
		<a href="#" id="hb-submit-endcalling" ><img src="<?php echo get_stylesheet_directory_uri() ?>/images/phone_red.png"></a>
	</div>
</aside>
<!-- End #calling-panel -->

<!-- BEGIN #webrtc-panel -->
<aside id="webrtc-panel">
	<h4 class="hb-focus-color"><?php echo "Web Call Advisor"; ?></h4>
	<div id="webrtc-panel-form">
		<p><div id="log">Ready to Call.</div></p>
		<p><input type="hidden" class="msgskillid" /></p>
		<a href="#" id="hb-submit-startwebrtc" ><img src="<?php echo get_stylesheet_directory_uri() ?>/images/video_green.png"></a>
		<a href="#" id="hb-submit-endwebrtc" ><img src="<?php echo get_stylesheet_directory_uri() ?>/images/video_red.png"></a>
	</div>
	<script type="text/javascript">
	jQuery("#test").click(function(){
		jQuery('#myModal').modal('show');
	});

	</script>
</aside>
<!-- End #webrtc-panel -->

<!-- BEGIN #advisor-webrtc-panel -->
<aside id="advisor-webrtc-panel">
	<h4 class="hb-focus-color"><?php echo "Web Call Advisor"; ?></h4>
	<div id="advisor-webrtc-panel-form">
		<p><div id="log">Ready to Recieve Call.</div></p>
		<a href="#" id="hb-submit-advisor-startwebrtc" ><img src="<?php echo get_stylesheet_directory_uri() ?>/images/video_green.png"></a>
		<a href="#" id="hb-submit-advisor-endwebrtc" ><img src="<?php echo get_stylesheet_directory_uri() ?>/images/video_red.png"></a>
	</div>
</aside>
<!-- End #advisor-webrtc-panel -->

<!-- BEGIN #advisee-advisor-webrtc-lightbox -->
<div id="open-video-dialog" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <h3>Streaming Video</h3>
      	<p><div id="stream-video"></div></p>
      	<h3>My Video</h3>
        <p><div id="own-video"></div></p>
        <p><div id="stream-screen"></div></p>
         <!-- <button type="button" id="close-call">Stop Publishing</button> -->
      	<a href="javascript:void(0);" id="close-call"><img src="<?php echo get_stylesheet_directory_uri() ?>/images/video_red.png" style="width:25px;"></a>
      	<a href="javascript:void(0);" id="start-screen-share" class="btn btn-default">Start Screen Share</a>
      	<a href="javascript:void(0);" id="stop-screen-share" class="btn btn-default">Stop Screen Share</a>
      </div>
      <div class="modal-footer">
        <!-- <button  type="button" class="btn btn-default" data-dismiss="modal" id="all-close">Close</button> -->

      </div>
    </div>

  </div>
</div>
<script>

</script>
<!-- End #advisee-advisor-webrtc-lightbox -->

<!-- BEGIN #hb-contact-button -->
<a id="contact-button"><i class="hb-moon-envelop"></i></a>
<!-- END #hb-contact-button -->
<!-- BEGIN #advisor-webrtc-button -->
<?php
	$current_user = wp_get_current_user();
	$currentuser_role = get_user_meta( $current_user->ID, 'role', true);
	if($currentuser_role == 'advisor'){	?>
<a id="advisor-webrtc-button"><i class="icon-video-camera"></i></a>
	<?php } ?>
<!-- END #advisor-webrtc-button -->
<!-- BEGIN #hb-contact-button -->
<!--<a id="message-button"><i class="hb-moon-brain"></i></a>-->
<!-- END #hb-contact-button -->
<?php } ?>

<?php
	// Google Analytics from Theme Options
	$google_analytics_code = hb_options('hb_analytics_code');
	if ($google_analytics_code){
		echo $google_analytics_code;
	}

	// Custom Script from Theme Options
	$custom_script_code = hb_options('hb_custom_script');
	if ($custom_script_code){
		echo '<script type="text/javascript">' . $custom_script_code . '</script>';
	}
?>

<?php
if ( basename(get_page_template()) != 'page-presentation-fullwidth.php' ){
 if ( !is_page_template('page-blank.php') && (hb_options('hb_enable_pre_footer_area') && vp_metabox('layout_settings.hb_pre_footer_callout') == "default" || hb_options('hb_enable_pre_footer_area') && vp_metabox('layout_settings.hb_pre_footer_callout') == "" || vp_metabox('layout_settings.hb_pre_footer_callout') == "show" ) ) { ?>
<?php
	$button_icon="";
	$button_target="";
	if ( hb_options('hb_pre_footer_button_icon') ) {
		$button_icon = '<i class="' . hb_options("hb_pre_footer_button_icon") . '"></i>';
	}

	if (hb_options('hb_pre_footer_button_target') == '_blank'){
		$button_target = ' target="_blank"';
	}
?>
<!-- BEGIN #pre-footer-area -->
<div id="pre-footer-area">
	<div class="container">
		<span class="pre-footer-text"><?php echo hb_options('hb_pre_footer_text'); ?></span>
		<?php if (hb_options('hb_pre_footer_button_text')) { ?><a href="<?php echo hb_options('hb_pre_footer_button_link'); ?>"<?php echo $button_target; ?> class="hb-button hb-large-button"><?php echo $button_icon; echo hb_options('hb_pre_footer_button_text'); ?></a><?php } ?>
	</div>
</div>
<!-- END #pre-footer-area -->
<?php } } ?>
<?php
if ( basename(get_page_template()) != 'page-presentation-fullwidth.php' ){
if (
	( ( hb_options('hb_enable_footer_widgets') && (vp_metabox('layout_settings.hb_footer_widgets') == "default" || vp_metabox('layout_settings.hb_footer_widgets') == "" )) || vp_metabox('layout_settings.hb_footer_widgets') == "show" ) &&
	!is_page_template('page-blank.php')
) { ?>

<?php
	$footer_bg_image = "";
	$footer_bg = "";
if ( hb_options('hb_enable_footer_background' ) ) {
	$footer_bg_image = " background-image";
}

if ( hb_options("hb_footer_bg_image") ){
	$footer_bg_image = " footer-bg-image";
	$footer_bg = ' style="background-image: url('. hb_options("hb_footer_bg_image") .')"';
}

?>

<!-- BEGIN #footer OPTION light-style -->
<footer id="footer" class="dark-style<?php echo $footer_bg_image; ?>" <?php echo $footer_bg; ?>>

	<!-- BEGIN .container -->
	<div class="container">
		<div class="row footer-row">

	<?php
	$hb_footer_class = array(
		'style-1'=>array('1'=>'col-3', '2'=>'col-3', '3'=>'col-3', '4'=>'col-3'),
		'style-2'=>array('1'=>'col-3', '2'=>'col-3', '3'=>'col-6', '4'=>'hidden'),
		'style-3'=>array('1'=>'col-6', '2'=>'col-3', '3'=>'col-3', '4'=>'hidden'),
		'style-4'=>array('1'=>'col-3', '2'=>'col-6', '3'=>'col-3', '4'=>'hidden'),
		'style-5'=>array('1'=>'col-4', '2'=>'col-4', '3'=>'col-4', '4'=>'hidden'),
		'style-6'=>array('1'=>'col-8', '2'=>'col-4', '3'=>'hidden', '4'=>'hidden'),
		'style-7'=>array('1'=>'col-4', '2'=>'col-8', '3'=>'hidden', '4'=>'hidden'),
		'style-8'=>array('1'=>'col-6', '2'=>'col-6', '3'=>'hidden', '4'=>'hidden'),
		'style-9'=>array('1'=>'col-3', '2'=>'col-9', '3'=>'hidden', '4'=>'hidden'),
		'style-10'=>array('1'=>'col-9', '2'=>'col-3', '3'=>'hidden', '4'=>'hidden'),
		'style-11'=>array('1'=>'col-12', '2'=>'hidden', '3'=>'hidden', '4'=>'hidden'),
	);

	$hb_footer_style = hb_options('hb_footer_layout');
	if ( !$hb_footer_style ) {
		$hb_footer_style = 'style-1';
	}

	$separator_class = "";
	if (!hb_options('hb_enable_footer_separators')){
		$separator_class = " no-separator";
	}

	for( $i = 1 ; $i <= 4 ; $i++ ){
		echo '<div class="' . $hb_footer_class[$hb_footer_style][$i] . ' widget-column' . $separator_class . '">';
		dynamic_sidebar('Footer ' . $i);
		echo '</div>';
	}

	?>
		</div>
	</div>
	<!-- END .container -->

</footer>
<!-- END #footer -->
<?php } } ?>

<?php
if ( basename(get_page_template()) != 'page-presentation-fullwidth.php' ){
if ( hb_options('hb_enable_footer_copyright') && !is_page_template('page-blank.php') ) { ?>
<!-- BEGIN #copyright-wrapper -->
<div id="copyright-wrapper" class="<?php echo hb_options('hb_copyright_style'); ?> <?php echo hb_options('hb_copyright_color_scheme'); if ( hb_options("hb_footer_bg_image") ){ echo 'footer-bg-image'; } ?> clearfix"> <!-- Simple copyright opcija light style opcija-->

	<!-- BEGIN .container -->
	<div class="container">

		<!-- BEGIN #copyright-text -->
		<div id="copyright-text">
			<p><?php echo do_shortcode(hb_options('hb_copyright_line_text')); ?>

			<?php
				if (hb_options('hb_enable_backlink')){
					echo ' <a href="http://www.mojomarketplace.com/store/hb-themes?r=hb-themes&utm_source=hb-themes&utm_medium=textlink&utm_campaign=themesinthewild">Theme by HB-Themes.</a>';
				}
			?>

			</p>
		</div>
		<!-- END #copyright-text -->

		<?php
		if ( has_nav_menu ('footer-menu') ) {
			wp_nav_menu( array ( 'theme_location' => 'footer-menu' , 'container_id' => 'footer-menu', 'container_class'=> 'clearfix', 'menu_id'=>'footer-nav', 'menu_class' => '', 'walker' =>  new hb_custom_walker) );
		}
		?>

	</div>
	<!-- END .container -->

</div>
<!-- END #copyright-wrapper -->
<?php } } ?>

<?php if ( ( is_singular('post') && hb_options('hb_blog_enable_next_prev') ) || (is_singular('portfolio') && hb_options('hb_portfolio_enable_next_prev') ) || ( is_singular('team') && hb_options('hb_staff_enable_next_prev') ) ) { ?>
	<nav class="hb-single-next-prev">
	<?php
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	?>

	<?php if ( !empty($prev_post) ) { ?>
	<a href="<?php echo get_permalink($prev_post->ID); ?>" title="<?php echo $prev_post->post_title; ?>" class="hb-prev-post">
		<i class="hb-moon-arrow-left-4"></i>
		<span class="text-inside"><?php _e('Prev','hbthemes'); ?></span>
	</a>
	<?php } ?>

	<?php if ( !empty($next_post) ) { ?>
	<a href="<?php echo get_permalink($next_post->ID); ?>" title="<?php echo $next_post->post_title; ?>" class="hb-next-post">
		<i class="hb-moon-arrow-right-5"></i>
		<span class="text-inside"><?php _e('Next','hbthemes'); ?></span>
	</a>
	<?php } ?>

</nav>
<!-- END LINKS -->
<?php } ?>

</div>
<!-- END #main-wrapper -->

</div>
<!-- END #hb-wrap -->

<?php if ( hb_options('hb_side_section') ) { ?>
	<div id="hb-side-section">
		<a href="#" class="hb-close-side-section"><i class="hb-icon-x"></i></a>
		<?php if ( is_active_sidebar( 'hb-side-section-sidebar' ) ) {
			dynamic_sidebar('hb-side-section-sidebar');
		} else {
			echo '<p class="aligncenter" style="margin-top:30px;">';
			_e('Please add widgets to this widgetized area ("Side Panel Section") in Appearance > Widgets.', 'hbthemes');
			echo '</p>';
		} ?>
	</div>
<?php } ?>


<?php if ( hb_options('hb_search_style') == 'hb-modern-search') { ?>
	<!-- BEGIN #fancy-search -->
	<div id="modern-search-overlay">
		<a href="#" class="hb-modern-search-close"><i class="hb-icon-x"></i></a>

		<div class="table-middle hb-modern-search-content">
			<p><?php _e('Type and press Enter to search', 'hbthemes'); ?></p>

			<form method="get" id="hb-modern-form" action="<?php echo home_url( '/' ); ?>" novalidate="" autocomplete="off">
				<input type="text" value="" name="s" id="hb-modern-search-input" autocomplete="off">
			</form>
		</div>

	</div>
<?php } ?>

<!-- BEGIN #hb-modal-overlay -->
<div id="hb-modal-overlay"></div>
<!-- END #hb-modal-overlay -->
<!--<script type='text/javascript' src='http://arianadigital.kr.cisinlive.com/wp-content/themes/HighendWP/scripts/customreg.js?ver=4.2.3'></script>-->
<?php wp_footer(); ?>

</body>
<!-- END body -->

</html>
<!-- END html -->
