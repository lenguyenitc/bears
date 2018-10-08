<?php
class WPBakeryShortCode_bt_services_carousel extends WPBakeryShortCode {
	
	protected function content( $atts, $content = null ) {

		extract(shortcode_atts(array(
			'css_animation' => '',
			'el_id' => '',
			'el_class' => '',
			
			'rows' => 1,
			'items' => '',
			'margin' => '',
			'loop' => '',
			'autoplay' => '',
			'autoplayhoverpause' => '',
			'smartspeed' => '',
			'nav' => '',
			'dots' => '',
			'nav_dots' => 'nav-dot-default',
			
			'category' => '',
			'posts_per_page' => 10,
			'orderby' => 'none',
			'order' => 'none',
			
			'layout' => 'default',
			'style' => 'icon-top',
			'align' => 'text-left',
			'img_size' => '',
			'excerpt_limit' => 15,
			'excerpt_more' => '.',
			'readmore_text' => 'Read More',
			
			'items_md' => '',
			'items_sm' => '',
			'items_xs' => '',
			'nav_xs' => '',
			'dots_xs' => '',
			
			'css' => ''
			
		), $atts));
		
		$space_class = ( ! empty( $margin ) ) ? 'space'.$margin : 'space0';
		
		$css_class = array(
			$this->getExtraClass( $el_class ) . $this->getCSSAnimation( $css_animation ),
			'bt-element',
			'bt-services-carousel-element',
			$layout,
			$style,
			$nav_dots,
			$space_class,
			apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts )
		);
		
		if($style == 'icon-top') $css_class[] = $align;
		
		$wrapper_attributes = array();
		if ( ! empty( $el_id ) ) {
			$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
		}
		
		/* Owl */
		$owl_attributes = array();
		$owl_attributes['items'] = ( ! empty( $items ) ) ? $items : 4;
		$owl_attributes['margin'] = ( ! empty( $margin ) ) ? (int)$margin : 0;
		$owl_attributes['loop'] = ( ! empty( $loop ) ) ? true : false;
		$owl_attributes['autoplay'] = ( ! empty( $autoplay ) ) ? true : false;
		$owl_attributes['autoplayHoverPause'] = ( ! empty( $autoplayhoverpause ) ) ? true : false;
		$owl_attributes['smartSpeed'] = ( ! empty( $smartspeed ) ) ? (int)$smartspeed : 500;
		$owl_attributes['nav'] = ( ! empty( $nav ) ) ? true : false;
		if ( ! empty( $nav ) ) $owl_attributes['navText'] = array('<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>');
		$owl_attributes['dots']= ( ! empty( $dots ) ) ? true : false;
		
		if($items != 1){
			$items_md = ( ! empty( $items_md ) ) ? $items_md : 3;
			$items_sm = ( ! empty( $items_sm ) ) ? $items_sm : 2;
			$items_xs = ( ! empty( $items_xs ) ) ? $items_xs : 1;
		}else{
			$items_md = $items_sm = $items_xs = 1;
		}
		
		if(! empty( $nav )){
			$nav_xs = ( ! empty( $nav_xs ) ) ? false : true;
		}else{
			$nav_xs = false;
		}
		
		if(! empty( $dots )){
			$dots_xs = ( ! empty( $dots_xs ) ) ? false : true;
		}else{
			$dots_xs = false;
		}
		
		$owl_attributes['responsive'] = array(
			1200 => array(
				'items' => $items
			),
			992 => array(
				'items' => $items_md
			),
			768 => array(
				'items' => $items_sm
			),
			0 => array(
				'items' => $items_xs,
				'nav' => $nav_xs,
				'dots' => $dots_xs
			),
		);
		
		
		$owl_json = json_encode($owl_attributes);
		
		
		/* Query */
		$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
		
		$args = array(
			'posts_per_page' => $posts_per_page,
			'paged' => $paged,
			'orderby' => $orderby,
			'order' => $order,
			'post_type' => 'bt_services',
			'post_status' => 'publish');
		if (isset($category) && $category != '') {
			$cats = explode(',', $category);
			$taxonomy = array();
			foreach ((array) $cats as $cat){
				$taxonomy[] = trim($cat);
			}
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'bt_services_category',
					'field' => 'slug',
					'terms' => $taxonomy
				)
			);
		}
		$wp_query = new WP_Query($args);
		
		$data_script = "jQuery(document).ready(function($) {
							if ($('.bt-services-carousel-element .owl-carousel').length) {
								$('.bt-services-carousel-element .owl-carousel').each(function() {
									$(this).owlCarousel($(this).data('owl'));
								});
							}
						});";
		
		wp_enqueue_script('owl-carousel');
		wp_add_inline_script( 'bears-main', $data_script );
		wp_enqueue_style('owl-carousel');
		
		ob_start();
		if ( $wp_query->have_posts() ) {
		?>
			<div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
				<div class="owl-carousel" data-owl="<?php echo esc_attr($owl_json); ?>">
					<?php
						if($rows == 1){
							while ( $wp_query->have_posts() ) { $wp_query->the_post();
								require get_template_directory().'/framework/elements/services_layouts/'.$layout.'.php';
							}
						}else{
							$post_count = $wp_query->post_count;
							$count = 0;
							while ( $wp_query->have_posts() ) { $wp_query->the_post();
								if($count == 0 || $count%$rows == 0) echo '<div class="bt-items">';
									require get_template_directory().'/framework/elements/services_layouts/'.$layout.'.php';
									$count++;
								if($count == $post_count || $count%$rows == 0) echo '</div>';
							}
						}
					?>
				</div>
			</div>
		<?php
		} else {
			echo 'Post not found!';
		}
		wp_reset_query();
		return ob_get_clean();
	}
}

vc_map(array(
	'name' => esc_html__('Services Carousel', 'bears'),
	'base' => 'bt_services_carousel',
	'category' => esc_html__('BT Elements', 'bears'),
	'icon' => 'bt-icon',
    'params' => array(
		vc_map_add_css_animation(),
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__('Element ID', 'bears'),
			'param_name' => 'el_id',
			'value' => '',
			'description' => esc_html__( 'Enter element ID (Note: make sure it is unique and valid).', 'bears' )
		),
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__('Extra Class', 'bears'),
			'param_name' => 'el_class',
			'value' => '',
			'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'bears' )
		),
		array (
			'type' => 'bt_taxonomy',
			'taxonomy' => 'bt_services_category',
			'heading' => esc_html__( 'Categories', 'bears' ),
			'param_name' => 'category',
			'group' => esc_html__('Data Setting', 'bears'),
			'description' => esc_html__( 'Note: By default, all your projects will be displayed. If you want to narrow output, select category(s) above. Only selected categories will be displayed.', 'bears' )
		),
		array (
			'type' => 'textfield',
			'heading' => esc_html__( 'Count', 'bears' ),
			'param_name' => 'posts_per_page',
			'value' => 10,
			'group' => esc_html__('Data Setting', 'bears'),
			'description' => esc_html__( 'The number of posts to display on each page. Set to "-1" for display all posts on the page.', 'bears' )
		),
		array (
			'type' => 'dropdown',
			'heading' => esc_html__( 'Order by', 'bears' ),
			'param_name' => 'orderby',
			'value' => array (
					'None' => 'none',
					'Title' => 'title',
					'Date' => 'date',
					'ID' => 'ID'
			),
			'group' => esc_html__('Data Setting', 'bears'),
			'description' => esc_html__( 'Select order type.', 'bears' )
		),
		array (
			'type' => 'dropdown',
			'heading' => esc_html__( 'Order', 'bears' ),
			'param_name' => 'order',
			'value' => Array (
					'None' => 'none',
					'ASC' => 'ASC',
					'DESC' => 'DESC'
			),
			'group' => esc_html__('Data Setting', 'bears'),
			'description' => esc_html__( 'Select sorting order.', 'bears' )
		),
		array(
			'type' => 'bt_layout',
			'folder' => 'services',
			'heading' => esc_html__('Layout', 'bears'),
			'param_name' => 'layout',
			'value' => array(
				'Default' => 'default',
				'Layout 1' => 'layout1',
				'Layout 2' => 'layout2',
				'Layout 3' => 'layout3',
				'Layout 4' => 'layout4',
				'Layout 5' => 'layout5',
				'Layout 6' => 'layout6',
				'Layout 7' => 'layout7',
				'Layout 8' => 'layout8',
				'Layout 9' => 'layout9'
			),
			'admin_label' => true,
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__('Select layout of items display in this element.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Style', 'bears'),
			'param_name' => 'style',
			'value' => array(
				'Icon Top' => 'icon-top',
				'Icon Left' => 'icon-left',
				'Icon Right' => 'icon-right'
			),
			'dependency' => array(
				'element'=>'layout',
				'value'=> 'default'
			),
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__('Select layout style in this elment.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Align', 'bears'),
			'param_name' => 'align',
			'value' => array(
				'Left' => 'text-left',
				'Center' => 'text-center',
				'Right' => 'text-right',
			),
			'dependency' => array(
				'element'=>'style',
				'value'=> 'icon-top'
			),
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__('Select layout align in this elment.', 'bears')
		),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Image size', 'bears' ),
			'param_name' => 'img_size',
			'value' => 'full',
			'dependency' => array(
				'element'=>'layout',
				'value'=> array('layout2', 'layout3', 'layout5', 'layout7', 'layout8', 'layout9')
			),
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__( 'Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "full" size.', 'bears' ),
		),
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__('Excerpt Limit', 'bears'),
			'param_name' => 'excerpt_limit',
			'value' => 15,
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__('Please, Enter number excerpt limit of post in this element.', 'bears')
		),
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__('Excerpt More', 'bears'),
			'param_name' => 'excerpt_more',
			'value' => '.',
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__('Please, Enter text excerpt more of post in this element.', 'bears')
		),
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__('Readmore Text', 'bears'),
			'param_name' => 'readmore_text',
			'value' => 'Read More',
			'group' => esc_html__('Item Setting', 'bears'),
			'description' => esc_html__('Please, Enter text of label button readmore in this element.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Items', 'bears'),
			'param_name' => 'rows',
			'value' => array(
				'1 Row' => 1,
				'2 Rows' => 2,
				'3 Rows' => 3
				
			),
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('The number of rows you want to see on the screen.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Items', 'bears'),
			'param_name' => 'items',
			'value' => array(
				'4 Items' => 4,
				'3 Items' => 3,
				'2 Items' => 2,
				'1 Item' => 1
			),
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('The number of items you want to see on the screen.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Margin', 'bears'),
			'param_name' => 'margin',
			'value' => array(
				'0px' => 0,
				'10px' => 10,
				'20px' => 20,
				'30px' => 30
			),
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Margin-right(px) on item.', 'bears')
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('Loop', 'bears'),
			'param_name' => 'loop',
			'value' => '',
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Infinity loop. Duplicate last and first items to get loop illusion.', 'bears')
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('Autoplay.', 'bears'),
			'param_name' => 'autoplay',
			'value' => '',
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Autoplay.', 'bears')
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('AutoplayHoverPause', 'bears'),
			'param_name' => 'autoplayhoverpause',
			'value' => '',
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Pause on mouse hover.', 'bears')
		),
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => esc_html__('SmartSpeed', 'bears'),
			'param_name' => 'smartspeed',
			'value' => 500,
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__( 'Speed Calculate.', 'bears' )
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('Nav', 'bears'),
			'param_name' => 'nav',
			'value' => '',
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Show next/prev buttons.', 'bears')
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('Dots', 'bears'),
			'param_name' => 'dots',
			'value' => '',
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Show dots navigation.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Nav & Dots Style', 'bears'),
			'param_name' => 'nav_dots',
			'value' => array(
				'Default' => 'nav-dots-default',
				'Style 1' => 'nav-dots-style1'
			),
			'group' => esc_html__('Owl Setting', 'bears'),
			'description' => esc_html__('Select nav and dots style display in this element.', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Items Medium Screen', 'bears'),
			'param_name' => 'items_md',
			'value' => array(
				'Auto' => '',
				'4 Items' => 4,
				'3 Items' => 3,
				'2 Items' => 2,
				'1 Item' => 1
			),
			'group' => esc_html__('Responsive', 'bears'),
			'description' => esc_html__('The number of items you want to see on the screen(>=992px and <1199px).', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Items Small Screen', 'bears'),
			'param_name' => 'items_sm',
			'value' => array(
				'Auto' => '',
				'4 Items' => 4,
				'3 Items' => 3,
				'2 Items' => 2,
				'1 Item' => 1
			),
			'group' => esc_html__('Responsive', 'bears'),
			'description' => esc_html__('The number of items you want to see on the screen(>=768px and <992px).', 'bears')
		),
		array(
			'type' => 'dropdown',
			'class' => '',
			'heading' => esc_html__('Items Extra Screen', 'bears'),
			'param_name' => 'items_xs',
			'value' => array(
				'Auto' => '',
				'4 Items' => 4,
				'3 Items' => 3,
				'2 Items' => 2,
				'1 Item' => 1
			),
			'group' => esc_html__('Responsive', 'bears'),
			'description' => esc_html__('The number of items you want to see on the screen(<768px).', 'bears')
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('Disable Nav On Extra Screen', 'bears'),
			'param_name' => 'nav_xs',
			'value' => '',
			'group' => esc_html__('Responsive', 'bears'),
			'description' => esc_html__('Disable next/prev buttons on the screen(<768px).', 'bears')
		),
		array(
			'type' => 'checkbox',
			'class' => '',
			'heading' => esc_html__('Disable Dots On Extra Screen', 'bears'),
			'param_name' => 'dots_xs',
			'value' => '',
			'group' => esc_html__('Responsive', 'bears'),
			'description' => esc_html__('Disable dots navigation on the screen(<768px).', 'bears')
		),
		
		array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'CSS box', 'bears' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Design Options', 'bears' ),
		)
	)
));
