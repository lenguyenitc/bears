<?php
	global $bears_options;
	$page_options = function_exists("fw_get_db_post_option")?fw_get_db_post_option(get_the_ID(), 'page_options'):array();
	
	$footer_class = 'bt-footer bt-footer-v6';
	
	$f6_fixed = (isset($bears_options['f6_fixed'])&&$bears_options['f6_fixed'])?$bears_options['f6_fixed']:'';
	if(isset($page_options['footer_fixed'])&&$page_options['footer_fixed']){$f6_fixed = '';}
	if($f6_fixed){
		$footer_class .= ' bt-stick';
	}
	
	$container_class = (isset($bears_options['f6_fullwidth'])&&$bears_options['f6_fullwidth'])?'fullwidth':'container';
	if(isset($page_options['footer_fullwidth'])&&$page_options['footer_fullwidth']){ $container_class = 'container'; }
	
	$f6_before_footer_top = (isset($bears_options['f6_before_footer_top'])&&$bears_options['f6_before_footer_top'])?$bears_options['f6_before_footer_top']:'';
	
	$f6_footer_top = (isset($bears_options['f6_footer_top'])&&$bears_options['f6_footer_top'])?$bears_options['f6_footer_top']:'';
	if(isset($page_options['footer_top'])&&$page_options['footer_top']){ $f6_footer_top = ''; }
	
	$f6_footer_top_columns = (isset($bears_options['f6_footer_top_columns'])&&$bears_options['f6_footer_top_columns'])?$bears_options['f6_footer_top_columns']:4;
	switch ($f6_footer_top_columns) {
        case 4:
            $f6_footer_top_col_1_class = $f6_footer_top_col_2_class = $f6_footer_top_col_3_class = $f6_footer_top_col_4_class = 'col-sm-6 col-md-3';
            break;
		case 3:
            $f6_footer_top_col_1_class = $f6_footer_top_col_2_class = $f6_footer_top_col_3_class = $f6_footer_top_col_4_class = 'col-md-4';
            break;	
		case 2:
            $f6_footer_top_col_1_class = $f6_footer_top_col_2_class = $f6_footer_top_col_3_class = $f6_footer_top_col_4_class = 'col-md-6';
            break;
		case 1:
            $f6_footer_top_col_1_class = $f6_footer_top_col_2_class = $f6_footer_top_col_3_class = $f6_footer_top_col_4_class = 'col-md-12';
            break;
		default :
			$f6_footer_top_col_1_class = $f6_footer_top_col_2_class = $f6_footer_top_col_3_class = $f6_footer_top_col_4_class = 'col-sm-6 col-md-3';
			break;
	}
	if((isset($bears_options['f6_footer_top_columns_class'])&&$bears_options['f6_footer_top_columns_class'])){
		$f6_footer_top_col_1_class = (isset($bears_options['f6_footer_top_col_1_class'])&&$bears_options['f6_footer_top_col_1_class'])?$bears_options['f6_footer_top_col_1_class']:'col-sm-6 col-md-3';
		$f6_footer_top_col_2_class = (isset($bears_options['f6_footer_top_col_2_class'])&&$bears_options['f6_footer_top_col_2_class'])?$bears_options['f6_footer_top_col_2_class']:'col-sm-6 col-md-3';
		$f6_footer_top_col_3_class = (isset($bears_options['f6_footer_top_col_3_class'])&&$bears_options['f6_footer_top_col_3_class'])?$bears_options['f6_footer_top_col_3_class']:'col-sm-6 col-md-3';
		$f6_footer_top_col_4_class = (isset($bears_options['f6_footer_top_col_4_class'])&&$bears_options['f6_footer_top_col_4_class'])?$bears_options['f6_footer_top_col_4_class']:'col-sm-6 col-md-3';
	}
	
	$f6_footer_bottom_columns = (isset($bears_options['f6_footer_bottom_columns'])&&$bears_options['f6_footer_bottom_columns'])?$bears_options['f6_footer_bottom_columns']:2;
	switch ($f6_footer_bottom_columns) {
		case 2:
            $f6_footer_bottom_col_1_class = $f6_footer_bottom_col_2_class = 'col-md-6';
            break;
		case 1:
            $f6_footer_bottom_col_1_class = $f6_footer_bottom_col_2_class = 'col-md-12';
            break;
		default :
			$f6_footer_bottom_col_1_class = $f6_footer_bottom_col_2_class = 'col-md-6';
			break;
	}
	if((isset($bears_options['f6_footer_bottom_columns_class'])&&$bears_options['f6_footer_bottom_columns_class'])){
		$f6_footer_bottom_col_1_class = (isset($bears_options['f6_footer_bottom_col_1_class'])&&$bears_options['f6_footer_bottom_col_1_class'])?$bears_options['f6_footer_bottom_col_1_class']:'col-md-6';
		$f6_footer_bottom_col_2_class = (isset($bears_options['f6_footer_bottom_col_2_class'])&&$bears_options['f6_footer_bottom_col_2_class'])?$bears_options['f6_footer_bottom_col_2_class']:'col-md-6';
	}
	
?>
<footer id="bt_footer" class="<?php echo esc_attr($footer_class); ?>">
	<!-- Start Footer Top -->
	<?php if($f6_footer_top){ ?>
		<div class="bt-footer-top">
			<div class="bt-overlay"></div>
			<div class="<?php echo esc_attr($container_class); ?>">
				<?php if($f6_footer_top){ ?>
					<div class="bt-before-footer-top">
						<div class="row">
							<div class="col-md-12">
								<?php
									if(isset($bears_options['f6_before_footer_top_content'])&&$bears_options['f6_before_footer_top_content']){
										foreach($bears_options['f6_before_footer_top_content'] as $sidebar_id){
											dynamic_sidebar( $sidebar_id );
										}
									}
								?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="<?php echo esc_attr($f6_footer_top_col_1_class); ?>">
						<div class="bt-content">
							<?php
								if(isset($bears_options['f6_footer_top_col_1'])&&$bears_options['f6_footer_top_col_1']){
									foreach($bears_options['f6_footer_top_col_1'] as $sidebar_id){
										dynamic_sidebar( $sidebar_id );
									}
								}
							?>
						</div>
					</div>
					<?php if($f6_footer_top_columns > 1){ ?>
						<div class="<?php echo esc_attr($f6_footer_top_col_2_class); ?>">
							<div class="bt-content">
								<?php
									if(isset($bears_options['f6_footer_top_col_2'])&&$bears_options['f6_footer_top_col_2']){
										foreach($bears_options['f6_footer_top_col_2'] as $sidebar_id){
											dynamic_sidebar( $sidebar_id );
										}
									}
								?>
							</div>
						</div>
					<?php } ?>
					<?php if($f6_footer_top_columns > 2){ ?>
						<div class="<?php echo esc_attr($f6_footer_top_col_3_class); ?>">
							<div class="bt-content">
								<?php
									if(isset($bears_options['f6_footer_top_col_3'])&&$bears_options['f6_footer_top_col_3']){
										foreach($bears_options['f6_footer_top_col_3'] as $sidebar_id){
											dynamic_sidebar( $sidebar_id );
										}
									}
								?>
							</div>
						</div>
					<?php } ?>
					<?php if($f6_footer_top_columns > 3){ ?>
						<div class="<?php echo esc_attr($f6_footer_top_col_4_class); ?>">
							<div class="bt-content">
								<?php
									if(isset($bears_options['f6_footer_top_col_4'])&&$bears_options['f6_footer_top_col_4']){
										foreach($bears_options['f6_footer_top_col_4'] as $sidebar_id){
											dynamic_sidebar( $sidebar_id );
										}
									}
								?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php } ?>
	<!-- End Footer Top -->
	<!-- Start Footer Bottom -->
	<div class="bt-footer-bottom">
		<div class="<?php echo esc_attr($container_class); ?>">
			<div class="row">
				<div class="<?php echo esc_attr($f6_footer_bottom_col_1_class); ?>">
					<div class="bt-content">
						<?php
							if(isset($bears_options['f6_footer_bottom_col_1'])&&$bears_options['f6_footer_bottom_col_1']){
								foreach($bears_options['f6_footer_bottom_col_1'] as $sidebar_id){
									dynamic_sidebar( $sidebar_id );
								}
							}
						?>
					</div>
				</div>
				<?php if($f6_footer_bottom_columns > 1){ ?>
					<div class="<?php echo esc_attr($f6_footer_bottom_col_2_class); ?>">
						<div class="bt-content">
							<?php
								if(isset($bears_options['f6_footer_bottom_col_2'])&&$bears_options['f6_footer_bottom_col_2']){
									foreach($bears_options['f6_footer_bottom_col_2'] as $sidebar_id){
										dynamic_sidebar( $sidebar_id );
									}
								}
							?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<!-- End Footer Bottom -->
</footer>
