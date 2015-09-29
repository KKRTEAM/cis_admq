<?php
/**
* Template Name: Skills List
*/
global $product
?>
<?php get_header();?>
<?php if (have_posts()): while (have_posts()): the_post();?>
<?php
$user_id = um_profile_id();
$main_content_style = "";
if (vp_metabox('background_settings.hb_content_background_color')) {
	$main_content_style = ' style="background-color: ' . vp_metabox('background_settings.hb_content_background_color') . ';"';
	echo "<style type=\"text/css\">#pre-footer-area:after{border-top-color:" . vp_metabox('background_settings.hb_content_background_color') . "}</style>";
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
				<?php }	?>

					<?php
						$args = array(
						'post_type' => 'product',
						'post_status' => array('publish', 'draft' , 'pending'),
						'author' => $user_id,
						'posts_per_page' => '10',
						'orderby' => 'title',
						'order' => 'DESC',
						);
						$skilllist = new WP_Query($args);
						//echo'<pre>';print_r($skilllist->posts);die;
					?>
					<h1>Skills & Expertise</h1>
					<div class="">
						<a href="<?php echo get_permalink(get_page_by_path('add-skills'));?>" class="hb-button">Add Skill</a>
					</div>
					<div class="table-responsive">
						<table class="footable">
							<thead>
								<tr>
									<th>Category</th>
									<th>Tags</th>
									<th>Description</th>
									<th>Level</th>
									<th>Rate/Hr</th>
									<th>License Copy</th>
									<th>Qualifying Degree/Certification Copy</th>
									<th>Supporting Reference Link</th>
									<th>Status</th>
									<th>Edit</th>
								</tr>
							</thead>
							<tbody>
								<?php
									while ($skilllist->have_posts()) {
									$skilllist->the_post();
									$post_id = $skilllist->post->ID;
									$category = get_the_terms($post_id, 'product_cat');
									$description = get_the_terms($post_id, 'pa_skill_description');
									$level = get_the_terms($post_id, 'pa_skill_level');
									$rate = get_post_meta($post_id, '_regular_price', true);
									$editurl = add_query_arg('skillid', $post_id, get_permalink(get_page_by_path('add-skills')));
									$certificateid = get_post_meta($post_id, 'skillDegree', true);
									$licenseid = get_post_meta($post_id, 'skillLicense', true);
									$supportinglink = get_post_meta($post_id, 'supporting_link', true);
                                    $skill_status = $skilllist->post->post_status;
									//echo'<pre>';print_r($tags);die;
								?>
								<tr>
									<td>
										<?php echo $category[0]->name; ?>
									</td>
									<td>
										<?php the_terms($post_id, 'product_tag');?>
									</td>
									<td>
										<?php echo $description[0]->name;?>
									</td>
									<td>
										<?php echo $skill_level = $level[0]->name;?>
									</td>
									<td>
										<?php echo $rate;?>
									</td>
									<td>
										<?php if ($licenseid != '') {?>
											<a href="<?php echo wp_get_attachment_url($licenseid);?>" target="_blank">License</a>
										<?php } else {echo "NA";} ?>
									</td>
									<td>
										<?php if ($certificateid != '') {?>
											<a href="<?php echo wp_get_attachment_url($certificateid);?>" target="_blank">Certificate</a>
										<?php } else {echo "NA";} ?>
									</td>
									<td>
										<?php echo $supportinglink;?>
									</td>
									<td>
                                        <?php
                                            if($skill_status == 'publish') {
                                                echo 'Active';
                                            } elseif ($skill_status == 'draft') {
                                                echo 'Inactive';
                                            } elseif ($skill_status == 'pending') {
                                                echo 'Verifying Documents';
                                            }
                                        ?>
									</td>
									<td>
										<a href="<?php echo $editurl;?>">Edit</a>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<?php wp_link_pages('before=<div id="hb-page-links">' . __('Pages:', 'hbthemes') . '&after=</div>');?>
					<?php
						if (comments_open() && hb_options('hb_disable_page_comments')) {
							comments_template();
						}
					?>
				</div>
				<!-- END .hb-main-content -->
				<?php if ($sidebar_layout != 'fullwidth') {	?>
				<!-- BEGIN .hb-sidebar -->
				<div class="col-3  hb-equal-col-height hb-sidebar">
					<?php
						if ($sidebar_name && function_exists('dynamic_sidebar')) {
							dynamic_sidebar($sidebar_name);
						}
					?>
				</div>
				<!-- END .hb-sidebar -->
				<?php }	?>
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
