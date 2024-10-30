<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $in_pr_url, $in_pr_pro;

?>

<div class="wrap in_pr_settings_div">



    <h2 class="nav-tab-wrapper">

        <a class="nav-tab nav-tab-active"><?php echo (isset($_GET['invoice_id'])?'<i class="fa-solid fa-pen-to-square"></i> '.esc_html(__("Edit", 'invoicepress')):'<i class="fa-solid fa-plus"></i> '.esc_html(__("New", 'invoicepress'))).' '.esc_html(__("Invoice", 'invoicepress')); ?></a>

        <a class="nav-tab"><i class="fa-solid fa-database"></i> <?php esc_html_e("Invoices History", 'invoicepress'); ?></a>
        
        <a class="nav-tab"><i class="fa-solid fa-gears"></i> <?php esc_html_e("Advanced Features", 'invoicepress'); ?></a>

    </h2>





    <?php

        if(function_exists('in_pr_add_new_settings_form_call_back')){

            in_pr_add_new_settings_form_call_back();

        }

    ?>
    
    <div class="nav-tab-content advanced_features_tab_content hide">
        
        <div class="row mt-3">
            <div class="col-md-12">
        
            <?php
        
                if(function_exists('in_pr_advanced_features_tab')){
        
                    in_pr_advanced_features_tab();
        
                }else{
?>					
<div class="alert alert-warning" role="alert">
  <?php esc_html_e('Advanced features are available in the premium version.', 'invoicepress'); ?>
</div>					
<?php
				}
        
            ?>
                            
			</div>
		</div>
	</div>            
    
    
    <div class="modal" id="ab_load_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="ajax_load_modalLabel" >

        <div class="modal-dialog" role="document" style="max-width: 50px;">

            <div class="modal-content" style="margin-top: 45vh; width: max-content">



                <img src="<?php echo esc_attr($in_pr_url); ?>images/loader.gif" style="width: 50px; height: 50px"/>



            </div>

        </div>

    </div>





</div>














