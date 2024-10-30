<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

/*

Plugin Name: Invoicepress

Plugin URI:

Description: Invoicing with ease. Get Print of your invoice in pdf.

Version: 1.0.0

Author: Fahad Mahmood 

Author URI: http://www.androidbubbles.com

Text Domain: invoicepress

Domain Path: /languages

License: GPL2

This WordPress Plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version. This free software is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this software. If not, see http://www.gnu.org/licenses/gpl-2.0.html.

*/



if(!function_exists('in_pr_pre')){

    function in_pr_pre($data){

        if(isset($_GET['debug'])){

            in_pr_pree($data);

        }

    }

}



if(!function_exists('in_pr_pree')){

    function in_pr_pree($data){

        echo '<pre>';

        print_r($data);

        echo '</pre>';

    }

}




global $in_pr_dir, $in_pr_url, $pdf_generated, $in_pr_invoice, $in_pr_woocommerce, $in_pr_pro, $in_pr_invoices_dir, $in_pr_invoices_dir_url, $in_pr_ts, $in_pr_settings_page, $in_pr_invoice_path;

$in_pr_settings_page = admin_url( 'admin.php?page=invoicepress-settings' );

$in_pr_woocommerce = ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) );

$in_pr_dir = plugin_dir_path(__FILE__);

$in_pr_url = plugin_dir_url(__FILE__);

$upload_dir   = wp_upload_dir();


$basedir = $in_pr_invoices_dir = $upload_dir['basedir'].'/invoicepress/';
$baseurl = $in_pr_invoices_dir_url = $upload_dir['baseurl'].'/invoicepress/';

$mute_file = $in_pr_invoices_dir.'index.php';

if(!is_dir($basedir)){
	@mkdir($basedir);	
}

if(!file_exists($mute_file)){
	@file_put_contents($mute_file, '<?php
// Silence is golden.');
}

$in_pr_pro = file_exists($in_pr_dir.'pro/functions.php');

if($in_pr_pro){
	include_once($in_pr_dir.'pro/functions.php');
}

include_once('inc/functions.php');











$pdf_generated = false;

$in_pr_ts = time();

$in_pr_invoice_link = $baseurl.'invoice-'.$in_pr_ts.'.pdf';

$in_pr_invoice_path = $basedir.'invoice-'.$in_pr_ts.'.pdf';



if(!function_exists('in_pr_add_new_settings_form_call_back')){



    function in_pr_add_new_settings_form_call_back(){



        global  $in_pr_dir;



        ob_start();



        include_once($in_pr_dir.'/admin/invoice-form.php');

        include_once($in_pr_dir.'/admin/invoices-history.php');
		
		

//        include_once($in_pr_dir.'/admin/test-settings-form.php'); //include new form file here as per nav index







        $in_pr_content = ob_get_contents();

        ob_clean();



        echo $in_pr_content;



    }



}









