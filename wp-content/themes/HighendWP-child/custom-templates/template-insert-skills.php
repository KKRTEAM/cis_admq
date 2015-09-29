<?php
/**
 * @package WordPress
 * @subpackage Highend
 */
/**
 * Template Name: Add Skills Page
 */

if (isset($_GET['skillid'])) {
	$skillid = $_GET['skillid'];
    $skill_product = get_post($skillid);
}


//Check if form is submitted.
if (isset($_POST['submitted']) && isset($_POST['post_nonce_field'])
	&& wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
	save_skills_as_woocommerce_products();
}
?>
<?php get_header();?>
<?php if (have_posts()): while (have_posts()): the_post();?>
		<?php
		$main_content_style = "";
		if (vp_metabox('background_settings.hb_content_background_color')) {
			$main_content_style = ' style="background-color: ' .
			vp_metabox('background_settings.hb_content_background_color') . ';"';
			echo "<style type=\"text/css\">#pre-footer-area:after{border-top-color:"
			. vp_metabox('background_settings.hb_content_background_color') . "}
			</style>";
		}
		?>
		<!-- BEGIN #main-content -->
		<div id="main-content"<?php echo $main_content_style;?>>

		<div class="container">

		<?php
		$sidebar_layout = vp_metabox('layout_settings.hb_page_layout_sidebar');
		$sidebar_name = vp_metabox('layout_settings.hb_choose_sidebar');

		if ($sidebar_layout == "default" || $sidebar_layout == "") {
			$sidebar_layout = hb_options('hb_page_layout_sidebar');
			$sidebar_name = hb_options('hb_choose_sidebar');
		}

		if (vp_metabox('misc_settings.hb_onepage')) {
			$sidebar_layout = 'fullwidth';
		}

		if (class_exists('bbPress')) {
			if (is_bbpress()) {
				$sidebar_layout = 'fullwidth';
			}
		}
		?>

		<div class="row <?php echo $sidebar_layout;?> main-row">

		<div id="page-<?php the_ID();?>" <?php post_class();?>>

		<!-- BEGIN .hb-main-content -->
		<?php if ($sidebar_layout != 'fullwidth') {?>
		<div class="col-9 hb-equal-col-height hb-main-content">
		<?php } else {?>
		<div class="col-12 hb-main-content">
		<?php }
?>
		<?php /*Content Start*/?>
		<h1>Add Skills</h1>
		<div class="errorTxt"></div>
		<form action="" id="skillPostForm" method="POST"
		enctype="multipart/form-data" novalidate="novalidate">

		<fieldset>
		<label for="skillCategory"><?php _e('Skill Category:', 'framework')?></label>
		<?php
		$choosecatmsg = __('Choose A Category', 'skillCategory');

		$userid = get_current_user_id();
		$getuserskillcount = count_user_posts($userid, 'product');

		if ($getuserskillcount >= 0) {
			$args = array(
				'post_type' => 'product',
				'author' => $userid,
				'order' => 'ASC',
			);
			$skills = query_posts($args);
			wp_reset_query();

			foreach ($skills as $skill) {
				$skill_id = $skill->ID;
				$skillinfo = wp_get_object_terms($skill_id, 'product_cat');
				foreach ($skillinfo as $skill) {
					$excat = $skill->term_id;
				}
				$alselectedcats[] = $excat;
			}
			$excluded_cat = implode(',', $alselectedcats);
		} else {
			$excluded_cat = '';
		}

		if (isset($_GET['skillid'])) {
			$skillinfo = wp_get_object_terms($skillid, 'product_cat');
			foreach ($skillinfo as $skill) {
				$selectcat = $skill->term_id;
			}

			if (($key = array_search($selectcat, $alselectedcats)) !== false) {
				unset($alselectedcats[$key]);
			}
			$excluded_cat = implode(',', $alselectedcats);
		}

		wp_dropdown_categories('show_option_none=' . $choosecatmsg .'&option_none_value=0&taxonomy=product_cat&hide_empty=0&name=skillCategory&orderby=name&hierarchical=1&class=skillCategory required&id=skillCategory&value_field=id&selected=' . $selectcat . '&exclude=' . $excluded_cat);
		?>
	  </fieldset>

	  <fieldset>
	      <label for="skillTags"><?php _e('Tags:', 'framework')?></label>
	      <?php
		if (isset($skillid)) {
			$tagsobj = wp_get_object_terms($skillid, 'product_tag');
			foreach ($tagsobj as $tag) {
				$tags[] = $tag->name;
			}
			if ($tags != '') {
				$tags1 = implode(",", $tags);
			} else {
				$tags1 = '';
			}
		}
		?>
    <input name="skillTags" id="skillTags"
    placeholder="Add a tag and press enter."
    value = "<?php if (isset($skillid)) {echo $tags1;}?>" class="required" />
</fieldset>

<fieldset>
	<label for="skillDesc"><?php _e('Description:', 'framework')?></label>
	<?php
		if (isset($skillid)) {
			$descobj = wp_get_object_terms($skillid, 'pa_skill_description');
			foreach ($descobj as $desc) {
				$desc1 = $desc->name;
			}
		}
	?>
	<textarea name="skillDesc" id="skillDesc" rows="4" cols="10" class=""><?php echo $desc1; ?></textarea>
</fieldset>

<fieldset>
	<label for="skillLevel"><?php _e('Level:', 'framework')?></label>
	<select name="skillLevel" id="skillLevel" class="required">
    <?php
		if (isset($skillid)) {
			$skillcobj = get_the_terms($skillid, 'pa_skill_level');

			foreach ($skillcobj as $skill1) {
				$skill = $skill1->name;

			}
		} else {
			$skill = '';
		}
		?>
      <option value="" disabled selected>Select Expertise</option>
		  <option <?php if ($skill == 'Certified Professional') {
			echo 'selected';
		}
		?> value="Certified Professional">Certified Professional</option>
			<option <?php if ($skill == 'Licensed To Practice') {
			echo 'selected';
		}
		?> value="Licensed To Practice">Licensed To Practice</option>
			<option <?php if ($skill == 'On-The-Job Experience') {
			echo 'selected';
		}
		?> value="On-The-Job Experience">On-The-Job Experience</option>
			<option <?php if ($skill == 'Spare-Time Activity/Interest') {
			echo 'selected';
		}
		?> value="Spare-Time Activity/Interest">Spare-Time Activity/Interest</option>
			<option <?php if ($skill == 'Free Advise') {
			echo 'selected';
		}
		?> value="Free Advise">Free Advise</option>
		</select>
	</fieldset>
	<fieldset id="skillDegree">
		<label for="skillDegree"><?php _e('Qualifying Degree/Certification Copy:', 'framework')?></label>
		<input type="file" name="skillDegree" id="skillDegree" />
	</fieldset>
  <fieldset id="skillLicense">
		<label for="skillLicense"><?php _e('License Copy:', 'framework')?></label>
		<input type="file" name="skillLicense" id="skillLicense" />
	</fieldset>
  <fieldset>
		<label for="skillRate"><?php _e('Rate/Hr:', 'framework')?></label>
		<input type="text" name="skillRate" id="skillRate" class="required"
		value="<?php if (isset($skillid))
		{echo get_post_meta($skillid, '_sale_price', true);}
?>" />
	</fieldset>
	<fieldset>
		<label for="skillSupportLink"><?php _e('Supporting Reference Link:',
		 'framework')?></label>
		<input type="text" name="skillSupportLink" id="skillSupportLink"
		value="<?php if (isset($skillid))
		 {echo get_post_meta($skillid, 'supporting_link', true);}
?>"  />
	</fieldset>
	<fieldset>
		<label for="skillSupportLink"><?php _e('Skill Status:', 'framework')?></label>
		<select name="skillStatus" id="skillStatus" class="required">
			<option value="" disabled selected>Select Skill Status</option>
			<option value="1" <?php if ($skill_product->post_status == 'publish') {echo 'selected';}?>>Active</option>
			<option value="0" <?php if (($skill_product->post_status == 'draft') || ($skill_product->post_status == 'pending')) {echo 'selected';}?>>Inactive</option>
		</select>
	</fieldset>
  <fieldset>
      <input type="hidden" name="submitted" id="submitted" value="true" />
			<?php wp_nonce_field('post_nonce', 'post_nonce_field');?>
      <button type="submit" class="hb-button"><?php _e('Add Skill', 'framework')?></button>
	  <a class="hb-button" href="javascript:history.go(-1)" onMouseOver="self.status=document.referrer;return true">CANCEL</a>
  </fieldset>
</form>
<?php if ($postTitleError != '') {?>
  <span class="error"><?php echo $postTitleError;?></span>
  <div class="clearfix"></div>
<?php }
?>
<?php /*Content End*/?>
<?php wp_link_pages('before=<div id="hb-page-links">' . __('Pages:', 'hbthemes')
 . '&after=</div>');?>
<?php if (comments_open() && hb_options('hb_disable_page_comments')) {
			comments_template();
		}
		?>
</div>
<!-- END .hb-main-content -->

<?php if ($sidebar_layout != 'fullwidth') {
?>
<!-- BEGIN .hb-sidebar -->
<div class="col-3  hb-equal-col-height hb-sidebar">
<?php
if ($sidebar_name && function_exists('dynamic_sidebar')) {
dynamic_sidebar($sidebar_name);
}

?>
</div>
<!-- END .hb-sidebar -->
<?php }
?>

</div>
<!-- END #page-ID -->

</div>
<!-- END .row -->

</div>
<!-- END .container -->

</div>
<!-- END #main-content -->

<?php endwhile;endif;?>
<?php get_footer();?>
