<?php				
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// PROCESS 
function woopei_processData(){
	
	if($_SERVER['REQUEST_METHOD'] == 'POST' && current_user_can('administrator')  && !$_POST['finalupload'] ){
	
		check_admin_referer( 'excel_upload' );
		check_ajax_referer( 'excel_upload' );	
				
		$filename = $_FILES["file"]["tmp_name"];
		
		if($_FILES["file"]["size"] > 0 ){
		IF($_FILES["file"]["type"] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
			//INCLUDE phpExcel
			include( plugin_dir_path( __FILE__ ) .'/Classes/PHPExcel/IOFactory.php');

			try {
				$objPHPExcel = PHPExcel_IOFactory::load($filename);
			} catch(Exception $e) {
				die('Error loading file "'.pathinfo($filename,PATHINFO_BASENAME).'": '.$e->getMessage());
			}
			$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet
				
			$rownumber=1;
			$row = $objPHPExcel->getActiveSheet()->getRowIterator($rownumber)->current();
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
		
			 print "<div style='overflow:hidden;min-height:400px;width:100%;'>
			 <form method='POST' style='overflow:hidden;min-height:400px;width:100%;' id ='woo_process' action= ".admin_url( 'admin.php?page=woo-product-importer' ).">";
			 print "<p style='font-style:italic'>DATA MAPPING: Drag and drop excel columns on the right to product properties on the left.</p>'";
				print "<div style='float:right;width:50%'>";
					print "<h3>EXCEL COLUMNS</h3><p>";
					foreach ($cellIterator as $cell) {
						//getValue
						echo "<input type='button' class='draggable' style='min-width:200px' key ='".sanitize_text_field($cell->getColumn())."' value='". sanitize_text_field($cell->getValue()) ."' />  <br/>";
					}				
				print "</p></div>";
				print "<div style='float:left;width:50%'>";
				
				print "<h3>PRODUCT FIELDS</h3>";
				echo "<p>TITLE:  <input type='text' name='post_title' required readonly class='droppable' placeholder='Drop here column' /></p>";
				echo "<p>DESCRIPTION: <input type='text' name='post_content' required readonly class='droppable' placeholder='Drop here column'  /></p>";
				$post_meta=array('_sku','_weight','_regular_price','_sale_price','_stock');
				foreach($post_meta as $meta){
					echo "<p>".strtoupper(str_replace('_',' ',esc_attr($meta) )).": <input type='text' style='min-width:200px' name='".esc_attr($meta)."' required readonly class='droppable' placeholder='Drop here column'  /></p>";
				}
				echo "<p>IMAGE: <input type='text' style='min-width:200px' name='image' required readonly class='droppable' placeholder='Drop here column'  /></p>";
				
					print "<h3>PRODUCT TAXONOMIES</h3>";
					$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );			
					foreach( $taxonomy_objects as $voc){
					if($voc->name != 'product_type' &&  !strstr($voc->name, 'pa') ){
						echo "<p>". strtoupper(str_replace('_',' ',esc_attr($voc->name))). " <input type='text' style='min-width:200px' name='".esc_attr($voc->name)."' required readonly class='droppable' placeholder='Drop here column' key /></p>";
					}
				   }	
				   print "<input type='hidden' name='finalupload' value='1' />";
				   ?><?php wp_nonce_field('excel_process','secNonce'); ?><?php
				   
					submit_button('Upload','primary','check');
				print "</div>";				
			print "</form></div>";
			
			move_uploaded_file($_FILES["file"]["tmp_name"], plugin_dir_path( __FILE__ ).'import.xlsx');

		} else   "<h3>". _e('Invalid File:Please Upload Excel File')."</h3>";	
		}
	}
	
	if($_POST['finalupload'] && current_user_can('administrator')){
			
		check_admin_referer( 'excel_process','secNonce' );
		check_ajax_referer( 'excel_process' ,'secNonce');				

		$filename = plugin_dir_path( __FILE__ ).'import.xlsx';

		include( plugin_dir_path( __FILE__ ) .'/Classes/PHPExcel/IOFactory.php');
		$objPHPExcel = PHPExcel_IOFactory::load($filename);
		$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$data = count($allDataInSheet);  // Here get total count of row in that Excel sheet		

		if(!empty($_POST['post_title'])){
		
			for($i=2;$i<=$data;$i++){
									
				//SANITIZE AND VALIDATE title and description				
				$title = sanitize_text_field($allDataInSheet[$i][$_POST['post_title']]);
				$content = sanitize_text_field($allDataInSheet[$i][$_POST['post_content']]);					
									
				//check if post exists
					if(post_exists($title)){
						//update 
						$id = post_exists($title);
						$post = array(
							'ID' 		   => $id,
							'post_title'   => $title,
							'post_content' => $content,
							'post_status'  => 'publish',
							'post_excerpt' => "",
							'post_name'    => $title,
							'post_type'    => 'product'
						 );							
						wp_update_post($post, $wp_error );
						print "<h3>". $title . " already exists. Updated...</h3>";				
							
						// ---------  ISUES: UPDATE TITLE  - DESCRIPTION - SLUNG!!!!?????  ----------- // 				
					}else{
						$post = array(
							'post_title'   => $title,
							'post_content' => $content,
							'post_status'  => 'publish',
							'post_excerpt' => "",
							'post_name'    => $title,
							'post_type'    => 'product'
						 );						
						print "<h3>". $title . " created...</h3>";
						//create
						$id = wp_insert_post( $post, $wp_error );
						wp_set_object_terms ($id,'simple','product_type');
					}
					//IMAGE
					if(has_post_thumbnail($id)){
						//do nothing
						print "For {$title}, image already exists, you need to upgrade to <a target='_blank' href='http://webdeveloping.gr/product/woo-product-excel-importer-premium/'>Woo Product Importer premium</a> for changing images massively.</a>.<br/>";
					}else{
						if(!filter_var(sanitize_text_field($allDataInSheet[$i][$_POST['image']]), FILTER_VALIDATE_URL) === false){
							$image = sanitize_text_field($allDataInSheet[$i][$_POST['image']]);
							require_once(ABSPATH . 'wp-admin/includes/file.php');
							require_once(ABSPATH . 'wp-admin/includes/media.php');
							// Download file to temp location
							$tmp = download_url( $image );
							preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $image, $matches);
							$file_array['name'] = basename($matches[0]);
							$file_array['tmp_name'] = $tmp;

							if ( is_wp_error( $tmp ) ) {
								@unlink($file_array['tmp_name']);
								$file_array['tmp_name'] = '';
							}
							$thumbid = media_handle_sideload( $file_array, $id, 'gallery desc' );
								if ( is_wp_error($thumbid) ) {
									@unlink($file_array['tmp_name']);
								}
							set_post_thumbnail($id, $thumbid);										
						}else{
							$image = '';
							print "For {$title} image you need to enter a url where the image is located.<br/>";
						}
					}

					//IMPORT - UPDATE POST META
					
					//SANITIZE AND VALIDATE meta data
					$sale_price = (int)$allDataInSheet[$i][$_POST['_sale_price']];					
					if ( !$sale_price  && !empty($allDataInSheet[$i][$_POST['_sale_price']]) ) {
					  $sale_price = '';
					  print "For sale price of {$title} you need numbers entered.<br/>";
					}
					$regular_price = (int)$allDataInSheet[$i][$_POST['_regular_price']];
					if ( !$regular_price  && !empty($allDataInSheet[$i][$_POST['_regular_price']])) {
					  $regular_price = '';
					  print "For regular price of {$title} you need numbers entered.<br/>";
					}
					$sku = (int)$allDataInSheet[$i][$_POST['_sku']];					
					if ( !$sku && !empty($_POST['_sku']) ) {
					  $sku = '';
					  print "For sku of {$title} you need numbers entered.<br/>";
					}
					$weight = (int)$allDataInSheet[$i][$_POST['_weight']];					
					if ( !$weight  && !empty($_POST['_weight']) ) {
					  $weight = '';
					  print "For weight of {$title} you need numbers entered.<br/>";
					}	
					$stock = (int)$allDataInSheet[$i][$_POST['_stock']];					
					if ( !$stock  && !empty($_POST['_stock']) ) {
					  $stock = '';
					  print "For stock of {$title} you need numbers entered.<br/>";
					}
					
					update_post_meta( $id, '_sku', $sku );
					update_post_meta( $id, '_weight',$weight );
					update_post_meta( $id, '_regular_price', $regular_price );
					update_post_meta( $id, '_sale_price', $sale_price );
					update_post_meta( $id, '_stock', $stock );
					update_post_meta( $id, '_visibility', 'visible' );
					update_post_meta( $id, '_price', $sale_price );
					//manage stock
					if( !empty($stock) ){
						update_post_meta( $id, '_stock_status', 'instock');
						update_post_meta( $id, '_manage_stock', 'yes');
					}					
					
					//TAXONOMIES
					
					$taxonomy_objects = get_object_taxonomies( 'product', 'objects' );			
					foreach( $taxonomy_objects as $voc){
						if($voc->name != 'product_type'   && !strstr($voc->name, 'pa') ){
						
							$taxToImport = sanitize_text_field($allDataInSheet[$i][$_POST[$voc->name]]);
							
							wp_set_object_terms( $id,$taxToImport,$voc->name,true); //true is critical to append the values
							$attrVal[$voc->name] = $taxToImport;
				
							// GET ALL ASSIGNED TERMS AND ADD PARENT FOR PRODUCT_CAT TAXONOMY!!!  
							$terms = wp_get_post_terms($id, $voc->name );
							foreach($terms as $term){
							while($term->parent != 0 && !has_term( $term->parent, $voc->name, $post )){
								// move upward until we get to 0 level terms
								wp_set_object_terms($id, array($term->parent), $voc->name, true);
								$term = get_term($term->parent, $voc->name);
								 }
							  }							
							
						}			
					}// end for each taxonomy																	
			}
		}else print "<h3 style='color:red' >No title selected for your products.</h3>";
		unlink($filename);
	}	
}
 
?>