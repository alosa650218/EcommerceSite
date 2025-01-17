<?php

/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access











function product_designer_get_product_list()
	{
		
		$product_designer_posts_per_page = get_option('product_designer_posts_per_page');
		if(empty($product_designer_posts_per_page))	
			{
				$posts_per_page = get_option('posts_per_page');
			}
		else
			{
				$posts_per_page = $product_designer_posts_per_page;
			}
		
		
		$args_tshirt = array(
			
			'post_type' => 'product',
			'post_status' => 'publish',		
			'meta_key' => 'product_designer_front_img',
			'meta_value' => '',
			'meta_compare' => '!=',
			
			'posts_per_page' => $posts_per_page,
			'paged' => get_query_var( 'paged' ),
			
			);
		global $tshirt_query;
		$tshirt_query = new WP_Query( $args_tshirt );	

		$html = '';
		$html .= product_designer_get_product_cat();
		
		
		$html.='<div class="product-list">';

		if($tshirt_query->have_posts()): while($tshirt_query->have_posts()): $tshirt_query->the_post();
		
			$product_designer_front_img = get_post_meta(get_the_ID(),'product_designer_front_img',true);
			$product_designer_back_img = get_post_meta(get_the_ID(),'product_designer_back_img',true);

			if(!empty($product_designer_front_img))
				{
				$html.= '<img front-img="'.$product_designer_front_img.'" back-img="'.$product_designer_back_img.'" product-id="'.get_the_ID().'" src="'.$product_designer_front_img.'"/>';

				}
		
		endwhile; 
		wp_reset_postdata();
		endif;
		$html.='</div>';
		$html.='<div class="product-load-more" per_page="'.$posts_per_page.'" offset="'.$posts_per_page.'">Load More</div>';

		return $html;

	}

function product_designer_get_product_list_ajax()
	{
		if(isset($_POST['offset'])) $offset = (int)$_POST['offset'];
		if(isset($_POST['product_terms'])) $product_terms = (int)$_POST['product_terms'];		
		
		$product_designer_posts_per_page = get_option('product_designer_posts_per_page');
		if(empty($product_designer_posts_per_page))	
			{
				$posts_per_page = get_option('posts_per_page');
			}
		else
			{
				$posts_per_page = $product_designer_posts_per_page;
			}
		
		$product_designer_taxonomy = 'product_cat';
		
		
		if($product_terms=='all')
			{
				$args_tshirt = array(
					
					'post_type' => 'product',
					'post_status' => 'publish',
					'meta_key' => 'product_designer_front_img',
					'meta_value' => '',
					'meta_compare' => '!=',
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					
					);
			}
		else
			{
				$args_tshirt = array(
					
					'post_type' => 'product',
					'post_status' => 'publish',
					'meta_key' => 'product_designer_front_img',
					'meta_value' => '',
					'meta_compare' => '!=',
					'tax_query' => array(
						array(
							   'taxonomy' => $product_designer_taxonomy,
							   'field' => 'id',
							   'terms' => $product_terms,
						)
					),
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					
					);
			}
		

		global $tshirt_query;
		$tshirt_query = new WP_Query( $args_tshirt );	
		
		$html = '';
if($tshirt_query->have_posts()){
	 while($tshirt_query->have_posts()): $tshirt_query->the_post();
		
			$product_designer_front_img = get_post_meta(get_the_ID(),'product_designer_front_img',true);
			$product_designer_back_img = get_post_meta(get_the_ID(),'product_designer_back_img',true);

			if(!empty($product_designer_front_img))
				{
				$html.= '<img front-img="'.$product_designer_front_img.'" back-img="'.$product_designer_back_img.'" product-id="'.get_the_ID().'" src="'.$product_designer_front_img.'"/>';

				}
		
		endwhile; 
		wp_reset_postdata();
		
		}
		else{ ?>
<script>
jQuery(document).ready(function($)
	{
		
		$('.product-load-more').css('background','#ff5337');
		$('.product-load-more').prop('disabled', true);
		$('.product-load-more').css('cursor', 'not-allowed');

	})
</script>
<?php
		}
		echo $html;
		die();
		
	
	}
add_action('wp_ajax_product_designer_get_product_list_ajax', 'product_designer_get_product_list_ajax');
add_action('wp_ajax_nopriv_product_designer_get_product_list_ajax', 'product_designer_get_product_list_ajax');





function product_designer_product_list_by_cat_ajax()
	{
		if(isset($_POST['offset'])) $offset = (int)$_POST['offset'];
		if(isset($_POST['product_terms'])) $product_terms = $_POST['product_terms'];		
		
		$product_designer_posts_per_page = get_option('product_designer_posts_per_page');
		
		if(empty($product_designer_posts_per_page))	
			{
				$posts_per_page = get_option('posts_per_page');
			}
		else
			{
				$posts_per_page = $product_designer_posts_per_page;
			}
		
		
		$product_designer_taxonomy = 'product_cat';
		
		
		if($product_terms == 'all')
			{
				$args_tshirt = array(
					
					'post_type' => 'product',
					'post_status' => 'publish',
					'meta_key' => 'product_designer_front_img',
					'meta_value' => '',
					'meta_compare' => '!=',
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					
					);	
			}
		else
			{
				$args_tshirt = array(
					
					'post_type' => 'product',
					'post_status' => 'publish',
					'meta_key' => 'product_designer_front_img',
					'meta_value' => '',
					'meta_compare' => '!=',
					'tax_query' => array(
						array(
							   'taxonomy' => $product_designer_taxonomy,
							   'field' => 'id',
							   'terms' => $product_terms,
						)
					),
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					
					);
			}
		

			
			
		global $tshirt_query;
		$tshirt_query = new WP_Query( $args_tshirt );	
		
		$html = '';
		if($tshirt_query->have_posts()){
			 while($tshirt_query->have_posts()): $tshirt_query->the_post();
				
					$product_designer_front_img = get_post_meta(get_the_ID(),'product_designer_front_img',true);
					$product_designer_back_img = get_post_meta(get_the_ID(),'product_designer_back_img',true);
		
					if(!empty($product_designer_front_img))
						{
						$html.= '<img front-img="'.$product_designer_front_img.'" back-img="'.$product_designer_back_img.'" product-id="'.get_the_ID().'" src="'.$product_designer_front_img.'"/>';
		
						}
				
				endwhile; 
				wp_reset_postdata();
		
		}
		else{ 
		
		$html.= __('No product for design.', product_designer_textdomain);
		
		
		?>
<script>
jQuery(document).ready(function($)
	{
		
		$('.product-load-more').css('background','#ff5337');
		$('.product-load-more').prop('disabled', true);
		$('.product-load-more').css('cursor', 'not-allowed');

	})
</script>
<?php
		}
		echo $html;
		die();
		
	
	}
add_action('wp_ajax_product_designer_product_list_by_cat_ajax', 'product_designer_product_list_by_cat_ajax');
add_action('wp_ajax_nopriv_product_designer_product_list_by_cat_ajax', 'product_designer_product_list_by_cat_ajax');






function product_designer_get_product_cat()
	{
		$args=array(
		  'orderby' => 'name',
		  'order' => 'ASC',
		  'taxonomy' => 'product_cat',
		  );
		$html = '';
		$html .= __('Categories:', product_designer_textdomain).' <select class="product-cat">';
		$html .= '<option value="all">'.__('All', product_designer_textdomain).'</option>';
		$categories = get_categories($args);
		foreach($categories as $category){
			
			$html .= '<option value='.$category->cat_ID.'>'.$category->cat_name.'</option>';	
		
		}
				
		$html .= '</select>';
		
		$html .= '<span class="product-cat-loading">'.__('Loading.', product_designer_textdomain).'</span>';		
		
		
		return $html;
	
	}
	
	

function product_designer_get_sticker_cat()
	{
		$args=array(
		  'orderby' => 'name',
		  'order' => 'ASC',
		  'taxonomy' => 'td_sticker_cat',
		  );
		$html = '';
		$html .= 'Categories: <select class="sticker-cat">';
		
		$html .= '<option value="all">'.__('All', product_designer_textdomain).'</option>';
		
		$categories = get_categories($args);
		
		foreach($categories as $category){
			
			$html .= '<option value='.$category->cat_ID.'>'.$category->cat_name.'</option>';	
		
		}
				
		$html .= '</select>';
		$html .= '<span class="sticker-cat-loading">'.__('Loading.', product_designer_textdomain).'</span>';	
		
		return $html;
	
	}
	
function product_designer_get_sticker_list_ajax()
	{
		if(isset($_POST['offset'])) $offset = (int)$_POST['offset'];
		if(isset($_POST['sticker_terms'])) $sticker_terms = (int)$_POST['sticker_terms'];		
		
		$product_designer_posts_per_page = get_option('product_designer_posts_per_page');
		if(empty($product_designer_posts_per_page))	
			{
				$posts_per_page = get_option('posts_per_page');
			}
		else
			{
				$posts_per_page = $product_designer_posts_per_page;
			}
		
		
		if($sticker_terms == 'all')
			{
				$args_tshirt = array(
					
					'post_type' => 'td_sticker',
					'post_status' => 'publish',
					'meta_key' => '_thumbnail_id',
					'meta_value' => '',
					'meta_compare' => '!=',
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					);
			}
		else
			{
				$args_tshirt = array(
					
					'post_type' => 'td_sticker',
					'post_status' => 'publish',
					'meta_key' => '_thumbnail_id',
					'meta_value' => '',
					'meta_compare' => '!=',
					'tax_query' => array(
						array(
							   'taxonomy' => 'td_sticker_cat',
							   'field' => 'id',
							   'terms' => $sticker_terms,
						)
					),
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					);
			}
		
		

			
			
		global $tshirt_query;
		$tshirt_query = new WP_Query( $args_tshirt );	

		$html = '';


		if($tshirt_query->have_posts()){ while($tshirt_query->have_posts()): $tshirt_query->the_post();
		

			$sticker_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

			if(!empty($sticker_url))
				{
					$html.= '<img stickerid="'.get_the_ID().'" src="'.$sticker_url.'"/>';

				}
		
		endwhile;
		wp_reset_postdata();
		}
		else
			{
			?>
			<script>
            jQuery(document).ready(function($)
                {
                    
                    $('.sticker-load-more').css('background','#ff5337');
                    $('.sticker-load-more').prop('disabled', true);
                    $('.sticker-load-more').css('cursor', 'not-allowed');
            
                })
            </script>
            <?php
			}
		echo $html;
		die();
	
	}	
add_action('wp_ajax_product_designer_get_sticker_list_ajax', 'product_designer_get_sticker_list_ajax');
add_action('wp_ajax_nopriv_product_designer_get_sticker_list_ajax', 'product_designer_get_sticker_list_ajax');





	
function product_designer_get_sticker_list_by_cat_ajax()
	{
		if(isset($_POST['offset'])) $offset = (int)$_POST['offset'];
		if(isset($_POST['sticker_terms'])) $sticker_terms = $_POST['sticker_terms'];		
		
		$product_designer_posts_per_page = get_option('product_designer_posts_per_page');
		if(empty($product_designer_posts_per_page))	
			{
				$posts_per_page = get_option('posts_per_page');
			}
		else
			{
				$posts_per_page = $product_designer_posts_per_page;
			}
		
		
		if($sticker_terms == 'all')
			{
				$args_tshirt = array(
					
					'post_type' => 'td_sticker',
					'post_status' => 'publish',
					'meta_key' => '_thumbnail_id',
					'meta_value' => '',
					'meta_compare' => '!=',
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					);
			}
		else
			{
				$args_tshirt = array(
					
					'post_type' => 'td_sticker',
					'post_status' => 'publish',
					'meta_key' => '_thumbnail_id',
					'meta_value' => '',
					'meta_compare' => '!=',
					'tax_query' => array(
						array(
							   'taxonomy' => 'td_sticker_cat',
							   'field' => 'id',
							   'terms' => $sticker_terms,
						)
					),
					'posts_per_page' => $posts_per_page,
					'offset' => $offset,
					);
			}
		
		

			
			
		global $tshirt_query;
		$tshirt_query = new WP_Query( $args_tshirt );	

		$html = '';


		if($tshirt_query->have_posts()){ while($tshirt_query->have_posts()): $tshirt_query->the_post();
		

			$sticker_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

			if(!empty($sticker_url))
				{
					$html.= '<img stickerid="'.get_the_ID().'" src="'.$sticker_url.'"/>';

				}
		
		endwhile;
		wp_reset_postdata();
		}
		else
			{
				$html.= __('No sticker for this category.', product_designer_textdomain);
			?>
			<script>
            jQuery(document).ready(function($)
                {
                    
                    $('.sticker-load-more').css('background','#ff5337');
                    $('.sticker-load-more').prop('disabled', true);
                    $('.sticker-load-more').css('cursor', 'not-allowed');
            
                })
            </script>
            <?php
			}
		echo $html;
		die();
	
	}	
add_action('wp_ajax_product_designer_get_sticker_list_by_cat_ajax', 'product_designer_get_sticker_list_by_cat_ajax');
add_action('wp_ajax_nopriv_product_designer_get_sticker_list_by_cat_ajax', 'product_designer_get_sticker_list_by_cat_ajax');








function product_designer_get_sticker_list()
	{
		$product_designer_posts_per_page = get_option('product_designer_posts_per_page');
		if(empty($product_designer_posts_per_page))	
			{
				$posts_per_page = get_option('posts_per_page');
			}
		else
			{
				$posts_per_page = $product_designer_posts_per_page;
			}
		
		$args_tshirt = array(
			
			'post_type' => 'td_sticker',
			'post_status' => 'publish',
			'meta_key' => '_thumbnail_id',
			'meta_value' => '',
			'meta_compare' => '!=',
			'posts_per_page' => $posts_per_page,
			'paged' => get_query_var( 'paged' ),
			);
		global $tshirt_query;
		$tshirt_query = new WP_Query( $args_tshirt );	

		$html = '';
		$html .= product_designer_get_sticker_cat();	
		$html .= product_designer_upload_sticker();	
		$html .='<div class="sticker-list">';

		if($tshirt_query->have_posts()): while($tshirt_query->have_posts()): $tshirt_query->the_post();
		

			$sticker_url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) );

			if(!empty($sticker_url))
				{
					$html.= '<img stickerid="'.get_the_ID().'" src="'.$sticker_url.'"/>';

				}
		
		endwhile;
		wp_reset_postdata();
		endif;
		$html.='</div>';
		
		$html.='<div class="sticker-load-more" per_page="'.$posts_per_page.'" offset="'.$posts_per_page.'">Load More</div>';	
		

		

		return $html;

	}






function product_designer_upload_sticker()
	{
		$product_designer_allow_sticker_upload = get_option( 'product_designer_allow_sticker_upload' );
		$product_designer_sticker_size = get_option( 'product_designer_sticker_size' );
		if(empty($product_designer_sticker_size))
			{
				$product_designer_sticker_size = intval(2);
			}
		else
			{
				$product_designer_sticker_size = intval($product_designer_sticker_size);
			}
		$html = '';
		$html .= '<div id="sticker-upload-container">';
		$html .= '<span class="loading">'.__('loading', product_designer_textdomain).'</span>';	

		$html .= '<a title="'.sprintf(__('filetype: (gif, png), max size: %s', product_designer_textdomain), $product_designer_sticker_size.'Mb').'" id="sticker-uploader" class="sticker_button" href="#">'.__('Upload', product_designer_textdomain).'</a>
		<div id="sticker-upload-imagelist"></div></div>';
		
		if($product_designer_allow_sticker_upload=='user')
			{
				if(product_designer_is_user_logged())
					{
						return $html;
					}
			}
		else if($product_designer_allow_sticker_upload=='visitor' )
			{

				return $html;

			}			
		else
			{
				return '';
			}
		
		
		
		
		
	}




function product_designer_is_user_logged()
{
	if(is_user_logged_in())
		{
			return true;
		}
	else
		{
			return false;
		}
	
}


function product_designer_init_session()
	{
	  session_start();
	}

add_action('init', 'product_designer_init_session', 1);




function product_designer_save_session() {
	
	
	$side = $_POST['side'];
	$product_id = $_POST['product_id'];
	$url = $_POST['url'];
	
	$_SESSION['product_id'] = $product_id;
	
	
	
	 if($side == "front")
		{
			$_SESSION['product_designer_front'] = $url;
		}
	else if($side == "back")
		{
			$_SESSION['product_designer_back'] = $url;
		}

			
			
			
			

			
			
	$uniqid = uniqid();
	$img = $url;
	//$img = str_replace('data:image/png;base64,', '', $img);
	//$img = str_replace(' ', '+', $img);
	//$data = base64_decode($img);
	//$file = product_designer_plugin_dir.'tshirt/'. $uniqid . '.png';
	//$file = $product_designer_dir.'/'. $uniqid . '.png';
	
	//$uploaded_file = wp_handle_upload($data, array('test_form' => false));
	
	//$success = file_put_contents($file, $data); 	


		



		$upload_dir       = wp_upload_dir();

		// @new
		$upload_path      = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;

		//$img = $_POST['imageData'];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);

		$decoded          = base64_decode($img) ;

		$filename         = 'attachment.png';

		$hashed_filename  = time() . '_' . $filename;

		// @new
		$image_upload     = file_put_contents( $upload_path . $hashed_filename, $decoded );

		//HANDLE UPLOADED FILE
		if( !function_exists( 'wp_handle_sideload' ) ) {

		  require_once( ABSPATH . 'wp-admin/includes/file.php' );

		}

		// Without that I'm getting a debug error!?
		if( !function_exists( 'wp_get_current_user' ) ) {

		  require_once( ABSPATH . 'wp-includes/pluggable.php' );

		}

		// @new
		$file             = array();
		$file['error']    = '';
		$file['tmp_name'] = $upload_path . $hashed_filename;
		$file['name']     = $hashed_filename;
		$file['type']     = 'image/png';
		$file['size']     = filesize( $upload_path . $hashed_filename );

		// upload file to server
		// @new use $file instead of $image_upload
		$file_return      = wp_handle_sideload( $file, array( 'test_form' => false ) );

		$filename = $file_return['file'];
		$attachment = array(
		 'post_mime_type' => $file_return['type'],
		 'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
		 'post_content' => '',
		 'post_status' => 'inherit',
		 'guid' => $wp_upload_dir['url'] . '/' . basename($filename)
		 );
		
		$attach_id = wp_insert_attachment( $attachment, $filename );
		$img_url = wp_get_attachment_url( $attach_id );
		
		$attach_data = wp_generate_attachment_metadata($attach_id, $img_url);
		wp_update_attachment_metadata($attach_id, $attach_data);

		//var_dump($attach_id);

	
		
	 if($side == "front")
		{
			$_SESSION['product_designer_front_img'] = $img_url;
		}
	else if($side == "back")
		{
			$_SESSION['product_designer_back_img'] = $img_url;
		}
		
		
		
		
			
	die();
	
	}

add_action('wp_ajax_product_designer_save_session', 'product_designer_save_session');
add_action('wp_ajax_nopriv_product_designer_save_session', 'product_designer_save_session');



function product_designer_font_list()
	{
		$product_designer_fonts = get_option( 'product_designer_fonts' );	
		
		if(!empty($product_designer_fonts))
			{
				
				$product_designer_fonts = explode(",",$product_designer_fonts);
				
				$google_fonts = $product_designer_fonts;
			}
		else
			{
				$google_fonts = array(
									'Open Sans',		
									'Shadows Into Light',
									'Josefin Slab',
									'Arvo',						
									'Lato',						
									'Vollkorn',						
									'Abril Fatface',
									'Ubuntu',						
									'PT Sans',						
									'Old Standard TT',	
									'Droid Sans',
									'Anivers',						
									'Junction',						
									'Fertigo',	
									'Aller',							
									'Audimat',							
									'Delicious',
									'Prociono',						
									'Fontin',						
									'Fontin-Sans',						
									'Chunkfive',					
												
									);
			}
		

			
		$html = '';
		$html .= '<select class="sticker-text-font-name">';			
		foreach($google_fonts as $font)
			{
				$html .= '<option value="'.$font.'" >'.$font.'</option>';

			}
		$html .= '</select>';
		
			
		$fonts_script = '';
		foreach($google_fonts as $font)
			{
				$fonts_script .= '"'.str_replace(' ','+',$font).'::latin",';
			}
			
			
			
			
			
		$html .= '
			<script type="text/javascript">
			  WebFontConfig = {
				google: { families: [ '.$fonts_script.' ] }
			  };
			  (function() {
				var wf = document.createElement("script");
				wf.src = ("https:" == document.location.protocol ? "https" : "http") +
				  "://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js";
				wf.type = "text/javascript";
				wf.async = "true";
				var s = document.getElementsByTagName("script")[0];
				s.parentNode.insertBefore(wf, s);
			  })(); 
			  </script>';
			
			
			
			
			
			
		return $html;	
							
		
	  
	}



//cart_item_data

	add_filter( 'woocommerce_add_cart_item_data', 'product_designer_add_cart_item_data', 10, 2 );
	function product_designer_add_cart_item_data( $cart_item_meta, $product_id ) {
		global $woocommerce;

		//var_dump($cart_item_meta);

		if ( (!empty( $_POST['product_designer_front_img'] )) || (!empty( $_POST['product_designer_back_img'] )) ){
			
			$cart_item_meta['product_designer_front_img'] = $_POST['product_designer_front_img'];
			$cart_item_meta['product_designer_back_img'] = $_POST['product_designer_back_img'];
			
			}
		
			
			

		return $cart_item_meta;
	}

	add_filter( 'woocommerce_get_cart_item_from_session', 'product_designer_get_cart_item_from_session' , 10, 2 );
	function product_designer_get_cart_item_from_session( $cart_item, $values ) {

		if ( (!empty( $values['product_designer_front_img'] )) || (!empty( $values['product_designer_back_img'] )) ) {
			
			$cart_item['product_designer_front_img'] = $values['product_designer_front_img'];
			$cart_item['product_designer_back_img'] = $values['product_designer_back_img'];
		}
		

		return $cart_item;
	}
	
	
	
	
	
	add_filter( 'woocommerce_get_item_data',  'product_designer_get_item_data' , 10, 2 );
	function product_designer_get_item_data( $item_data, $cart_item ) {
		
	// at cart page, checkout page
		if ( (!empty( $cart_item['product_designer_front_img']) ) || (!empty( $cart_item['product_designer_back_img']) ))
		{
		
			$item_data[] = array(
				'name'    => __( 'Front Image', product_designer_textdomain ),
				'value'   => $cart_item['product_designer_front_img'],
				'display' => '<img  width="30" src="'.$cart_item['product_designer_front_img'].'" />'
			);
			
			$item_data[] = array(
				'name'    => __( 'Back Image', product_designer_textdomain ),
				'value'   => $cart_item['product_designer_back_img'],
				'display' => '<img width="30" src="'.$cart_item['product_designer_back_img'].'" />'
			);	
		}		

		return $item_data;
	}
	
	
	
	
	
	add_filter( 'woocommerce_add_cart_item', 'product_designer_add_cart_item' , 10, 1 );
	function product_designer_add_cart_item( $cart_item ) {
		if ( (!empty( $cart_item['product_designer_front_img'] )) || (!empty( $cart_item['product_designer_back_img'] ))) 
		{

		}

		return $cart_item;
	}
	
	
	
	
	add_action( 'woocommerce_add_order_item_meta',  'product_designer_add_order_item_meta' , 10, 2 );
	function product_designer_add_order_item_meta( $item_id, $cart_item ) {
	 
	 //order completed page. & admin
	 
		if ( (!empty( $cart_item['product_designer_front_img'] )) ||  (!empty( $cart_item['product_designer_back_img'] )))
			{
				 woocommerce_add_order_item_meta( $item_id, 'product_designer_front_img', $cart_item['product_designer_front_img'] );
				 woocommerce_add_order_item_meta( $item_id, 'product_designer_back_img', $cart_item['product_designer_back_img'] ); 
			}		 
			 
	}
	
	
	
	
	
	
	
	
	
	
	
	function product_designer_share_plugin()
		{
			
			?>
<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwordpress.org%2Fplugins%2Ftshirt-designer%2F&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=true&amp;share=true&amp;height=80&amp;appId=652982311485932" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:80px;" allowTransparency="true"></iframe>
            
            <br />
            <!-- Place this tag in your head or just before your close body tag. -->
            <script src="https://apis.google.com/js/platform.js" async defer></script>
            
            <!-- Place this tag where you want the +1 button to render. -->
            <div class="g-plusone" data-size="medium" data-annotation="inline" data-width="300" data-href="<?php echo product_designer_share_url; ?>"></div>
            
            <br />
            <br />
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo product_designer_share_url; ?>" data-text="<?php echo product_designer_plugin_name; ?>" data-via="ParaTheme" data-hashtags="WordPress">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



            <?php
			
			
			
		
		
		}
	
	
	
	

/////////////////////////////

