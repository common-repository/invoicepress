<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $in_pr_dir;

if(!class_exists('Dompdf\Dompdf')){



    require_once ($in_pr_dir.'lib/dompdf/vendor/autoload.php');


}



use Dompdf\Dompdf;

use Dompdf\Options;



//abin

	if(!function_exists('in_pr_sanitize_data')){			
		function in_pr_sanitize_data( $input ) {		
				if(is_array($input)){				
					$new_input = array();			
					foreach ( $input as $key => $val ) {
						$new_input[ $key ] = (is_array($val)?in_pr_sanitize_data($val):sanitize_text_field( $val ));
					}					
				}else{
					$new_input = sanitize_text_field($input);
				}		
				if(!is_array($new_input)){		
					if(stripos($new_input, '@') && is_email($new_input)){
						$new_input = sanitize_email($new_input);
					}		
					if(stripos($new_input, 'http') || wp_http_validate_url($new_input)){
						$new_input = sanitize_url($new_input);
					}		
				}
				return $new_input;
		}			
	}
	





if(!function_exists('in_pr_admin_scripts')){



	function in_pr_admin_scripts() {

		global $in_pr_woocommerce, $pdf_generated, $in_pr_invoice_path, $in_pr_settings_page;

		$pages_array = array('invoicepress-settings');



		if(isset($_GET['page']) && in_array($_GET['page'], $pages_array)){

	
			
			$is_process_invoicepress = (isset($_POST['action']) && $_POST['action']==base64_encode('process-invoicepress'));
	
			if($is_process_invoicepress){
				wp_enqueue_style( 'invoicepress-invoice-template', plugins_url('css/invoice-template.css', dirname(__FILE__)), array(), date('mhi') );
			}



			wp_enqueue_style( 'bootstrap', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), date('mhi') );

			wp_enqueue_style('invoicepress-admin', plugins_url('css/admin.css', dirname(__FILE__)), array(), time());
			
			wp_enqueue_style('invoicepress-common', plugins_url('css/common.css', dirname(__FILE__)), array(), time());


			




			wp_enqueue_script(

				'invoicepress-admin-scripts',

				plugins_url('js/admin_scripts.js', dirname(__FILE__)),

				array('jquery'),
				
				time()

			);
			
			wp_enqueue_script(

				'invoicepress-common-scripts',

				plugins_url('js/common.js', dirname(__FILE__)),

				array('jquery'),
				
				time()

			);
			
			wp_enqueue_script('blockUI',plugins_url('js/jquery.blockUI.js', dirname(__FILE__)),array('jquery'),time());


			$translation_array = array(

				'this_url' => $in_pr_settings_page,
				'in_pr_tab' => (isset($_GET['t'])?in_pr_sanitize_data($_GET['t']):'0'),
                'ab_pdf_action' => wp_create_nonce('ab_pdf_action'),
                'del_confirm_str' => esc_html(__('Do you want to delete this item?', 'invoicepress')),
				'del_confirm_str_multiple' => esc_html(__('Do you want to delete selected items?', 'invoicepress')),
				'availableOrders' => array(),
				'pdf_generated' => $pdf_generated,
				'in_pr_invoice_path' => file_exists($in_pr_invoice_path)?basename($in_pr_invoice_path):'',

			);
			//pree($translation_array);
			//pree($_GET);
			if($in_pr_woocommerce && is_user_logged_in()){
				
				$valid_orders = get_posts(
				
					array(
						'author'        =>  get_current_user_id(),
						'numberposts' => -1,
						'post_type' => 'shop_order',
						'post_status' => 'any',
						'orderby' => 'date',
						'order' => 'DESC'
					)
					
				);
	
				if(!empty($valid_orders)){
					$currency = get_woocommerce_currency();
					$users = array();
					foreach($valid_orders as $valid_order){
						
						$order_meta = get_post_meta($valid_order->ID);
						$_order_total = array_key_exists('_order_total', $order_meta)?$order_meta['_order_total'][0]:0;
						$_customer_user = array_key_exists('_customer_user', $order_meta)?$order_meta['_customer_user'][0]:'';
						$customer_name = '';
						
						if($_customer_user && $_customer_user>0){
							if(!array_key_exists($_customer_user, $users)){
								$data = get_user_by('id', $_customer_user);
								
								if(is_object($data)){
									$customer_name = $data->data->display_name;
									$users[$_customer_user] = $data;
								}
							}elseif(array_key_exists($_customer_user, $users)){						
								$data = $users[$_customer_user];
								$customer_name = $data->data->display_name;
							}
						}
						
						$translation_array['availableOrders'][] = $valid_order->ID.' ('.$currency.' '.$_order_total.') '.$customer_name;
					}
				}

			}


			wp_localize_script( 'invoicepress-admin-scripts', 'in_pr_obj', $translation_array );


			wp_enqueue_style( 'fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)), array(), time() );
			
			wp_enqueue_script(
				'fontawesome',
				plugins_url('js/fontawesome.min.js', dirname(__FILE__)),
				array('jquery'),
				time()
			);	
			
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			
			
			

		}



	}



}



add_action( 'admin_enqueue_scripts', 'in_pr_admin_scripts');

add_action('wp_enqueue_scripts', 'in_pr_front_scripts');



if(!function_exists('in_pr_front_scripts')){



    function in_pr_front_scripts(){


		
		$is_process_invoicepress = (isset($_POST['action']) && $_POST['action']==base64_encode('process-invoicepress'));

		if($is_process_invoicepress){
			wp_enqueue_style( 'invoicepress-invoice-template', plugins_url('css/invoice-template.css', dirname(__FILE__)), array(), date('mhi') );
		}
		
		
		//in_pr_pree($QUERY_STRING);exit;
		
		wp_enqueue_style( 'invoicepress-front', plugins_url('css/bootstrap.min.css', dirname(__FILE__)), array(), date('mhi') );
		
		wp_enqueue_style('invoicepress-common', plugins_url('css/common.css', dirname(__FILE__)), array(), time());
		
		
		
		
		wp_enqueue_script(
		
		  'invoicepress-common-scripts',
		
		  plugins_url('js/common.js?t='.time(), dirname(__FILE__)),
		
		  array('jquery')
		
		);
		
		wp_enqueue_script(
		
		  'invoicepress-front-scripts',
		
		  plugins_url('js/scripts.js?t='.time(), dirname(__FILE__)),
		
		  array('jquery')
		
		);
		
		
		
		$translation_array = array(
		
		
		
		  'ajax_url' => admin_url( 'admin-ajax.php' ),
		  'home_url' => '',
		
		
		
		);
		
		
		//in_pr_pree($_SERVER);exit;
		
		wp_localize_script( 'invoicepress-front-scripts', 'in_pr_obj', $translation_array );

		wp_enqueue_style( 'fontawesome', plugins_url('css/fontawesome.min.css', dirname(__FILE__)), array(), time() );
			
		wp_enqueue_script(
			'fontawesome',
			plugins_url('js/fontawesome.min.js', dirname(__FILE__)),
			array('jquery'),
			time()
		);	


      }

}





if(is_admin()) {

	add_action('admin_menu', 'in_pr_admin_menu');

}

if(!function_exists('in_pr_admin_menu')){


    function in_pr_admin_menu() {

		global $in_pr_woocommerce;
		
		$title = 'Invoicepress';
		$icon = '<span title="'.esc_html(__('Invoicing with ease!', 'invoicepress')).'" style="color:gold;">&#128976;</span>';
		
		if($in_pr_woocommerce){

	        $page = add_submenu_page('woocommerce', $title, $title.' '.$icon, 'manage_woocommerce', 'invoicepress-settings', 'in_pr_admin_settings_callback' );
			
		}else{
			
			add_options_page($title, $title.' '.$icon, 'publish_pages', 'invoicepress-settings', 'in_pr_admin_settings_callback');
			
		}

    }







}



if(!function_exists('in_pr_admin_settings_callback')){



	function in_pr_admin_settings_callback(){



		global $in_pr_dir;



		require_once($in_pr_dir.'admin/admin-settings.php');



	}



}





if(!function_exists('in_pr_generate_qrcode')){



    function in_pr_generate_qrcode($qr_content) {



        global $in_pr_dir, $in_pr_url, $in_pr_invoices_dir, $in_pr_ts, $in_pr_invoices_dir_url;





        if(!class_exists('WMQRcode')){

            require_once ($in_pr_dir.'lib/phpqrcode.php');

        }





        $tempDir = $in_pr_invoices_dir;

        $url = $in_pr_invoices_dir_url;





        $fileName = 'qrcode-'.$in_pr_ts.'.png';



        $pngAbsoluteFilePath = $tempDir.$fileName;



        WMQRcode::png($qr_content, $pngAbsoluteFilePath,QR_ECLEVEL_L,10);



        //echo '<img src="'.$url.$fileName.'" />';

        return $url.$fileName;



    }

}



add_action('admin_init', 'in_pr_print_invoice_pdf');

add_action('admin_head', 'in_pr_admin_head');

function in_pr_admin_head(){
	
	wp_add_inline_style( 'invoicepress-admin-head-scripts', '#menu-posts-invoicepress,#menu-plugins{ display:none !important;	}' );

}


function in_pr_process_invoicepress(){
	
		if ( !isset( $_POST['ab_invoice_print'] )) return;

		//in_pr_pree($_POST);exit;

        global $in_pr_dir, $pdf_generated, $in_pr_invoice_path;



        ob_start();
		
		
			
	    include_once($in_pr_dir . 'inc/invoice-template.php');
		




        $pdf_content = ob_get_contents();


        ob_clean();

		//echo $pdf_content;exit;
		//in_pr_pree($_POST);exit;

		if(!isset($_POST['format']) || (isset($_POST['format']) && $_POST['format']=='html')){
        	echo $pdf_content;exit;
		}



        $options = new Options();

        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($pdf_content);



        // (Optional) Setup the paper size and orientation

        $dompdf->setPaper('A3');



        // Render the HTML as PDF

        $dompdf->render();

        // Output the generated PDF to Browser

//        $dompdf->stream();

	    $output = $dompdf->output();
		
		//in_pr_pree($in_pr_invoice_path);exit;
		
		$content = @file_put_contents($in_pr_invoice_path, $output);
		
		/*if(!file_exists($in_pr_invoice_path)){
			$f = fopen($in_pr_invoice_path, 'w+');
			$content = fwrite($f, $output);
			fclose($f);
		}else{
		    
		}*/



	    if($content !== false){

		    $pdf_generated = true;

	    }
		
		return $pdf_generated;
	
}


if(!function_exists('in_pr_print_invoice_pdf')){



    function in_pr_print_invoice_pdf(){

		$labels = array(
			'name'                  => esc_html(_x( 'Invoices', 'Post type general name', 'invoicepress' )),
			'singular_name'         => esc_html(_x( 'Invoice', 'Post type singular name', 'invoicepress' )),
			'menu_name'             => esc_html(_x( 'Invoices', 'Admin Menu text', 'invoicepress' )),
			'name_admin_bar'        => esc_html(_x( 'Invoice', 'Add New on Toolbar', 'invoicepress' )),
			'add_new'               => esc_html(__( 'Add New', 'invoicepress' )),
			'add_new_item'          => esc_html(__( 'Add New Invoice', 'invoicepress' )),
			'new_item'              => esc_html(__( 'New Invoice', 'invoicepress' )),
			'edit_item'             => esc_html(__( 'Edit Invoice', 'invoicepress' )),
			'view_item'             => esc_html(__( 'View Invoice', 'invoicepress' )),
			'all_items'             => esc_html(__( 'All Invoices', 'invoicepress' )),
			'search_items'          => esc_html(__( 'Search Invoices', 'invoicepress' )),
			'parent_item_colon'     => esc_html(__( 'Parent Invoices:', 'invoicepress' )),
			'not_found'             => esc_html(__( 'No invoices found.', 'invoicepress' )),
			'not_found_in_trash'    => esc_html(__( 'No invoices found in Trash.', 'invoicepress' )),
			'featured_image'        => esc_html(_x( 'Invoice Cover Image', 'Overrides the "Featured Image" phrase for this post type. Added in 4.3', 'invoicepress' )),
			'set_featured_image'    => esc_html(_x( 'Set cover image', 'Overrides the "Set featured image" phrase for this post type. Added in 4.3', 'invoicepress' )),
			'remove_featured_image' => esc_html(_x( 'Remove cover image', 'Overrides the "Remove featured image" phrase for this post type. Added in 4.3', 'invoicepress' )),
			'use_featured_image'    => esc_html(_x( 'Use as cover image', 'Overrides the "Use as featured image" phrase for this post type. Added in 4.3', 'invoicepress' )),
			'archives'              => esc_html(_x( 'Invoice archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'invoicepress' )),
			'insert_into_item'      => esc_html(_x( 'Insert into invoice', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'invoicepress' )),
			'uploaded_to_this_item' => esc_html(_x( 'Uploaded to this invoice', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'invoicepress' )),
			'filter_items_list'     => esc_html(_x( 'Filter invoices list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'invoicepress' )),
			'items_list_navigation' => esc_html(_x( 'Invoices list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'invoicepress' )),
			'items_list'            => esc_html(_x( 'Invoices list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'invoicepress' )),
		);
	
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'invoicepress' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		);
	
		register_post_type( 'invoicepress', $args );
	

        // instantiate and use the dompdf class



		
		$pdf_generated = in_pr_process_invoicepress();
		
		//in_pr_pree($pdf_generated);exit;
		
		if($pdf_generated){
			global $in_pr_settings_page, $in_pr_invoice_path;
			
			$in_pr_invoice_path_file = basename($in_pr_invoice_path);
			
			wp_redirect($in_pr_settings_page.'&pdf-file='.$in_pr_invoice_path_file);exit;
		}
        






    }

}



if(!function_exists('in_pr_update_invoice_data')){

    function in_pr_update_invoice_data($presented_to=array(), $presented_by=array()){
		
        $email = isset($presented_to['email']) ? $presented_to['email'] : '';
		
		$id = isset($presented_to['ID']) ? $presented_to['ID'] : 0;
		
		$post_data = array('post_title'=>$presented_to['transaction_id'], 'post_name'=>$presented_to['invoice_id'], 'post_content'=>$presented_to['address'], 'post_type'=>'invoicepress', 'post_status'=>'hidden');
		//pree($data);exit;
		
		if(is_user_logged_in()){
			$post_data['post_author'] = get_current_user_id();
		}
		
		if($id){
			$post_data['ID'] = $id;
			$id = wp_update_post($post_data);
		}else{			
			$id = wp_insert_post($post_data);
		}
		
		
		if($id){
			$data['presented_to'] = $presented_to;
			$data['presented_by'] = $presented_by;
			
			update_post_meta($id, '_ab_invoice_meta', $data);
		}
		return $id;
		
        if(!$email) {return;}

        $email_key  = '_'.$email;
        $ab_presented_to_data = get_option('ab_presented_to_data', array());


        $ab_presented_to_data[$email_key] = $data;
		
		

        return update_option('ab_presented_to_data', $ab_presented_to_data);



    }
}



add_action('wp_ajax_ab_search_wc_order', 'in_pr_search_wc_order');
if(!function_exists('in_pr_search_wc_order')){

    function in_pr_search_wc_order(){

        global $in_pr_dir;
		
		$ret = array(
			'order' => array('total'=>0, 'transaction_number'=>'', 'receipt_number'=>'', 'invoice_date'=>'', 'billing_email'=>'', 'display_name'=>'', 'first_name'=>'', 'last_name'=>'', 'company'=>'', 'address'=>'', 'vat'=>'', 'website'=>''),
			'items' => array()
		);

        if(isset($_POST['search_id'])){

            if (
                ! isset( $_POST['nonce'] )
                || ! wp_verify_nonce( sanitize_text_field( wp_unslash (  $_POST['nonce'])), 'ab_pdf_action' )
            ) {

                esc_html_e('Sorry, your nonce did not verify.','invoicepress');

                exit;

            } else {


                $search_id = in_pr_sanitize_data($_POST['search_id']);
				$search_id_arr = explode(' ', $search_id);
				$search_id = trim(current($search_id_arr));
				if(is_numeric($search_id) && $search_id>0){
					$order_data = wc_get_order($search_id);
					if(is_object($order_data) && !empty($order_data)){
						$user = $order_data->get_user();
						$first_name   = $user->first_name;
						$last_name    = $user->last_name;
						$display_name = $user->display_name;

						$ret['order']['billing_email'] = $order_data->get_billing_email();
						$ret['order']['invoice_date'] = date('d/m/Y', strtotime($order_data->get_date_created()));
						$ret['order']['receipt_number'] = get_post_meta($search_id, 'receipt_number', true);
						$ret['order']['transaction_number'] = get_post_meta($search_id, 'transaction_number', true);
						
						$ret['order']['display_name'] = $display_name;
						$ret['order']['first_name'] = $first_name;
						$ret['order']['last_name'] = $last_name;
						
						$website = explode('@', $ret['order']['billing_email']);
						
						$ret['order']['address'] = get_post_meta($search_id, '_billing_address_index', true);
						$ret['order']['company'] = get_post_meta($search_id, '_billing_company', true);
						$ret['order']['vat'] = get_post_meta($search_id, '_vat', true);						
						$ret['order']['website'] = 'https://'.end($website);
													
						
						$ret['order']['total'] = $order_data->get_total();
						
						foreach($order_data->get_items() as $item_key=>$item_data){
							$ret['items'][] = $item_data->get_name();
						}
					}
				}
				
				$ret['items'] = implode(', ', $ret['items']);
			

            }
        }
		//pree($ret);
		echo wp_json_encode($ret);

        wp_die();

    }
}

add_action('wp_ajax_ab_preset_delete_by_id', 'in_pr_preset_delete_by_id');
if(!function_exists('in_pr_preset_delete_by_id')){

    function in_pr_preset_delete_by_id(){

        global $in_pr_dir;


        if(isset($_POST['invoice_id']) && is_array($_POST['invoice_id']) && count($_POST['invoice_id'])>0){

            if (
                ! isset( $_POST['nonce'] )
                || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'])), 'ab_pdf_action' )
            ) {

                esc_html_e('Sorry, your nonce did not verify.','invoicepress');

                exit;

            } else {


                $ab_preset_id_arr = in_pr_sanitize_data($_POST['invoice_id']);
				
				//in_pr_pree($ab_preset_id);exit;
                //$ab_presented_to_data = get_option('ab_presented_to_data', array());
				//$ab_presented_to_data = get_post_meta($ab_preset_id, '_ab_invoice_meta', true);
				
				if(is_array($ab_preset_id_arr) && count($ab_preset_id_arr)>0){
					foreach($ab_preset_id_arr as $ab_preset_id){
						if ( FALSE === get_post_status( $ab_preset_id ) ) {
							echo 'false';
						}else{
							wp_delete_post($ab_preset_id);
						}
					}
				}

              
					
             
				ob_start();

				include_once($in_pr_dir.'/admin/invoices-history.php');

				$ob_content = ob_get_contents();

				ob_end_clean();

				echo $ob_content;

			

            }
        }

        wp_die();

    }
}


function in_pr_invoice_items_link_to_Admin( $wp_admin_bar ) {
	$args = array(
		'id'    => 'ab_invoices',
		'title' => 'Invoices',
		'href'  => get_bloginfo('wpurl').'/wp-admin/admin.php?page=invoicepress-settings',
		'meta'  => array( 'class' => 'ab_invoices' )
	);
	$wp_admin_bar->add_node( $args );		
}

add_action( 'admin_bar_menu', 'in_pr_invoice_items_link_to_Admin', 999 );	
