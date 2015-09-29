<div class="um <?php echo $this->get_class($mode);?> um-<?php echo $form_id;?>">

	<div class="um-form">

		<?php do_action('um_profile_before_header', $args);?>

		<?php if (um_is_on_edit_profile()) {?><form method="post" action=""><?php }
?>

			<?php do_action('custom_um_profile_header_cover_area', $args);?>

			<?php do_action('custom_um_profile_header', $args);?>

			<?php do_action('um_profile_navbar', $args);?>

			<?php

$nav = $ultimatemember->profile->active_tab;
$subnav = (get_query_var('subnav')) ? get_query_var('subnav') : 'default';

print "<div class='um-profile-body $nav $nav-$subnav'>";
	// Code Added By Anil Starts Here 10-8-15
	$userinfo = $ultimatemember->user->profile;
	$ultimatemember_uid = $userinfo['ID'];
	$user_ID = get_current_user_id();
	
	$userrole = get_user_meta($user_ID, 'role', true);
	$cardnumber = get_user_meta($user_ID, 'credit-card-number', true);
	$cardnumber_new=substr($cardnumber,0,4);
	
	$bank_account_number=get_user_meta($user_ID, 'bank_account_number', true);
	$bank_account_number_new=substr($bank_account_number,0,4);
	
	$ssn_status=get_user_meta($user_ID, 'background_check_status', true);
	
	$phone_status = get_user_meta($user_ID, 'phone_status', true);
	
	$customerId = get_user_meta($user_ID, 'authorize_net_customerid', true);
	
	if($ultimatemember_uid==$user_ID && $userrole=='advisor'){
	$complete_profile=complete_profile();
		//echo "<pre>";print_r($complete_profile);
		echo "<h2 style='color:orange'>Your Profile Is ".ceil($complete_profile['complete_profile'])."% Completed</h2>";
		
		//echo "<pre>";print_r($complete_profile['profile_status']);
		if($complete_profile['profile_status']['card_number']!=""
		   && $complete_profile['profile_status']['bank_name']!=""
		   && $complete_profile['profile_status']['bank_routing_id']!=""
		   && $complete_profile['profile_status']['bank_account_number']!=""
		   && $complete_profile['profile_status']['ssn']!=""){	
		
		echo "<h2 style='color:red'>Congratulations! Your Advisor Profile is Active.</h2>";
		}else{
			echo "<h2 style='color:red'>You are a Partial Active Advisor</h2>";
		}
	}
				// Code Added By Anil Ends Here 10-8-15
// Custom hook to display tabbed content
do_action("um_profile_content_{$nav}", $args);
do_action("um_profile_content_{$nav}_{$subnav}", $args);

print "</div>";

?>

		<?php if (um_is_on_edit_profile()) {?></form><?php }
?>

	</div>
	<?php if($userrole=='advisor'){?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			
			var card_number="<?php echo $cardnumber_new;?>";
			var bank_account_number="<?php echo $bank_account_number_new;?>";
			var ssn_status="<?php echo $ssn_status;?>";
			var phone_status="<?php echo $phone_status;?>";
			//var customerId="<?php echo $customerId;?>";
			//if (customerId=="") {
			//	jQuery('.um-field-um_block_374_13').css("display","none");
			//	jQuery('.um-field-um_spacing_250_32').css("display","none");
			//	jQuery('.um-field-credit-card-number').css("display","none");
			//	jQuery('.um-field-cvv').css("display","none");
			//	jQuery('.um-field-month').css("display","none");
			//	jQuery('.um-field-year').css("display","none");
			//}
			if (phone_status=='verified') {
				jQuery('.um-field-mobile_number').append("<a href='javascript:void(0)' class='change_phone'>Change Phone</a>");
				jQuery('#mobile_number-250').attr('disabled','disabled');
				jQuery('.phone_ver_advisor').css('display','none');
			}
			jQuery(".change_phone").click(function(){
				jQuery('#mobile_number-250').removeAttr('disabled');
				jQuery('.phone_ver_advisor').css('display','block');
			});
			if (card_number=="XXXX") {
				
				jQuery('#credit-card-number-250').attr('disabled','disabled');
				jQuery('.um-field-credit-card-number').append("<a href='javascript:void(0)' class='change_card'>Change</a>");
				jQuery('#cvv-250').attr('disabled','disabled');
				jQuery('#month').attr('disabled','disabled');
				jQuery('#year').attr('disabled','disabled');
				jQuery('.um-field-cvv').css("display","none");
				jQuery('.um-field-month').css("display","none");
				jQuery('.um-field-year').css("display","none");
			}
			jQuery(".change_card").click(function(){
				jQuery('#credit-card-number-250').removeAttr('disabled');
				jQuery('.um-field-cvv').css("display","block");
				jQuery('.um-field-month').css("display","block");
				jQuery('.um-field-year').css("display","block");
				jQuery('#cvv-250').removeAttr('disabled');
				jQuery('#month').removeAttr('disabled');
				jQuery('#year').removeAttr('disabled');
			});
			
			if (bank_account_number=="XXXX") {
				jQuery('#bank_name-250').attr('disabled','disabled');
				jQuery('#bank_routing_id-250').attr('disabled','disabled');
				jQuery('#bank_account_number-250').attr('disabled','disabled');
			}
			
			if (ssn_status=='Pending' || ssn_status=='Clear') {
				jQuery('#ssn-250').attr('disabled','disabled');		
			}
			
			
			
		});
	</script>
	<?php }?>
	<?php if($userrole=='advisee'){?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			var card_number="<?php echo $cardnumber_new;?>";
			var phone_status="<?php echo $phone_status;?>";
			
			if (phone_status=='verified') {
				jQuery('.um-field-mobile_number').append("<a href='javascript:void(0)' class='change_phone'>Change Phone</a>");
				jQuery('#mobile_number-374').attr('disabled','disabled');
				jQuery('.phone_ver_advisee').css('display','none');
			}
			jQuery(".change_phone").click(function(){
				jQuery('#mobile_number-374').removeAttr('disabled');
				jQuery('.phone_ver_advisee').css('display','block');
			});
			
			if (card_number=="XXXX") {
				
				jQuery('#credit-card-number-374').attr('disabled','disabled');
				jQuery('#cvv-374').attr('disabled','disabled');
				jQuery('#month').attr('disabled','disabled');
				jQuery('#year').attr('disabled','disabled');
				jQuery('.um-field-credit-card-number').append("<a href='javascript:void(0)' class='change_card'>Change</a>");
				jQuery('.um-field-cvv').css("display","none");
				jQuery('.um-field-month').css("display","none");
				jQuery('.um-field-year').css("display","none");
			}
			jQuery(".change_card").click(function(){
				jQuery('#credit-card-number-374').removeAttr('disabled');
				jQuery('.um-field-cvv').css("display","block");
				jQuery('.um-field-month').css("display","block");
				jQuery('.um-field-year').css("display","block");
				jQuery('#cvv-374').removeAttr('disabled');
				jQuery('#month').removeAttr('disabled');
				jQuery('#year').removeAttr('disabled');
			});
		});
	</script>
	<?php }?>

</div>
