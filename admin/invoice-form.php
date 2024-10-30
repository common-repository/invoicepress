<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//for($i=1;$i<=999;$i++){	$str = str_split($i, 1);	if(array_sum($str)==9){		echo $i.'<br />';	}}





global $pdf_generated, $in_pr_invoice_link, $in_pr_invoices_dir_url, $in_pr_invoice_path, $in_pr_woocommerce, $in_pr_pro;





$ab_invoice_data = get_option('ab_invoice_data', array());



$ab_presented_to = isset($ab_invoice_data['presented_to']) ? $ab_invoice_data['presented_to'] : array();

$ab_presented_by = isset($ab_invoice_data['presented_by']) ? $ab_invoice_data['presented_by'] : array();

$current_url = (is_admin()?admin_url('admin.php?page=invoicepress-settings'):home_url('/'));

$ab_preset_id = '';


if(isset($_GET['invoice_id'])){



    if (

        ! isset( $_GET['_wpnonce'] )

        || 
		
		! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_GET['_wpnonce'])), 'ab_preset_action' )

    ) {



        esc_html_e('Sorry, your nonce did not verify.','invoicepress');

        exit;



    } else {





            $ab_preset_id = in_pr_sanitize_data($_GET['invoice_id']);



            $ab_presented_data = get_post_meta($ab_preset_id, '_ab_invoice_meta', true);//get_option('ab_presented_to_data', array());
			//in_pr_pree($ab_presented_data);
			$ab_presented_to = array_key_exists('presented_to', $ab_presented_data)?$ab_presented_data['presented_to']:array();
			$ab_presented_by = array_key_exists('presented_by', $ab_presented_data)?$ab_presented_data['presented_by']:array();
			
			
			

    }

}



?>









<div class="nav-tab-content">

<form action="<?php echo esc_attr($current_url); ?>" method="post">
<input type="hidden" name="ab_preset_id" value="<?php echo esc_attr($ab_preset_id); ?>" />
<input type="hidden" name="action" value="<?php echo esc_attr(base64_encode($in_pr_pro?'process-invoicepress':'process')); ?>" />



    <?php wp_nonce_field( 'in_pr_invoice_action', 'in_pr_invoice_field' ); ?>







    <div class="container-fluid mt-3">







        <?php if(($pdf_generated && file_exists($in_pr_invoice_path)) || isset($_GET['pdf-file'])): 
		
		
		if(isset($_GET['pdf-file'])){
			$in_pr_invoice_link = $in_pr_invoices_dir_url.esc_attr($_GET['pdf-file']);			
		}
		
		
?>



        <div class="alert alert-success">



            <strong><?php esc_html_e('Success', 'invoicepress') ?>!</strong> <?php esc_html_e('Invoice generated successfully ', 'invoicepress') ?> <a href="<?php echo esc_attr($in_pr_invoice_link); ?>" target="_blank"><?php esc_html_e('Click here ', 'invoicepress') ?></a> <?php esc_html_e('to download.', 'invoicepress') ?>



        </div>


        <?php endif; ?>











           <div class="row">
           
<?php if($in_pr_woocommerce && is_user_logged_in()) :?>           	
				<div class="col-md-12">

					<div class="search-wc-order-wrapper"><div class="search-wc-order"><label for="wc_order_id"><input value="" type="text" name="wc_order_id" id="wc_order_id" /></label><a title="<?php esc_html_e('Go', 'invoicepress') ?>"><i class="fa-solid fa-circle-chevron-right"></i></a></div></div>

				</div>
<?php endif; ?>                






               <div class="col-md-6">







                   <div class="row mb-3">







                       <div class="col-md-12">



                           <div class="h5"><?php esc_html_e('Presented To', 'invoicepress') ?>:</div>



                       </div>







                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_to_name"><?php esc_html_e('Name', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_to_name" name="ab_invoice_data[presented_to][name]" value="<?php echo isset($ab_presented_to['name']) ? esc_attr($ab_presented_to['name']) : ''; ?>" />



                       </div>



                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_to_company"><?php esc_html_e('Company', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_to_company" name="ab_invoice_data[presented_to][company]" value="<?php echo isset($ab_presented_to['company']) ? esc_attr($ab_presented_to['company']) : ''; ?>" />



                       </div>



                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_to_address"><?php esc_html_e('Address', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <textarea type="text" class="form-control" id="presented_to_address" name="ab_invoice_data[presented_to][address]"><?php echo isset($ab_presented_to['address']) ? esc_attr($ab_presented_to['address']) : ''; ?></textarea>



                       </div>



                   </div>











                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_to_vat"><?php esc_html_e('VAT / UST-IDNR / EU NIP', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_to_vat" name="ab_invoice_data[presented_to][vat]" value="<?php echo isset($ab_presented_to['vat']) ? esc_attr($ab_presented_to['vat']) : ''; ?>" />



                       </div>



                   </div>











                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_to_email"><?php esc_html_e('E-Mail', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="email" class="form-control" id="presented_to_email" name="ab_invoice_data[presented_to][email]" value="<?php echo isset($ab_presented_to['email']) ? esc_attr($ab_presented_to['email']) : ''; ?>" />



                       </div>



                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_to_website"><?php esc_html_e('Website', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="url" class="form-control" id="presented_to_website" name="ab_invoice_data[presented_to][website]" value="<?php echo isset($ab_presented_to['website']) ? esc_attr($ab_presented_to['website']) : ''; ?>" />



                       </div>



                   </div>











               </div>











               <div class="col-md-6">


					





                   <div class="row mb-3">







                       <div class="col-md-12">



                           <div class="h5"><?php esc_html_e('Presented By', 'invoicepress') ?>:</div>



                       </div>







                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_name"><?php esc_html_e('Name', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_by_name" name="ab_invoice_data[presented_by][name]" value="<?php echo isset($ab_presented_by['name']) ? esc_attr($ab_presented_by['name']) : ''; ?>" />



                       </div>



                   </div>











                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_company"><?php esc_html_e('Company', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_by_company" name="ab_invoice_data[presented_by][company]" value="<?php echo isset($ab_presented_by['company']) ? esc_attr($ab_presented_by['company']) : ''; ?>" />



                       </div>



                   </div>



					<div class="row mb-3">

                       <div class="col-md-4">
							
							<?php esc_html_e('Logo', 'invoicepress') ?> <small style="font-size:12px">(<?php esc_html_e('Optional', 'invoicepress') ?>)</small>:
                           
                       </div>
                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_by_logo" name="ab_invoice_data[presented_by][logo]" value="<?php echo isset($ab_presented_by['logo']) ? esc_attr($ab_presented_by['logo']) : ''; ?>" />



                       </div>

                   </div>



                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_address"><?php esc_html_e('Address', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <textarea type="text" class="form-control" id="presented_by_address" name="ab_invoice_data[presented_by][address]"><?php echo isset($ab_presented_by['address']) ? esc_attr($ab_presented_by['address']) : ''; ?></textarea>



                       </div>



                   </div>



                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_vat"><?php esc_html_e('VAT / UST-IDNR / EU NIP', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_by_vat" name="ab_invoice_data[presented_by][vat]" value="<?php echo isset($ab_presented_by['vat']) ? esc_attr($ab_presented_by['vat']) : ''; ?>" />



                       </div>



                   </div>



                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_vat"><?php esc_html_e('E-Mail', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="email" class="form-control" id="presented_by_vat" name="ab_invoice_data[presented_by][email]" value="<?php echo isset($ab_presented_by['email']) ? esc_attr($ab_presented_by['email']) : ''; ?>" />



                       </div>



                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_website"><?php esc_html_e('Website', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="url" class="form-control" id="presented_by_website" name="ab_invoice_data[presented_by][website]" value="<?php echo isset($ab_presented_by['website']) ? esc_attr($ab_presented_by['website']) : ''; ?>" />



                       </div>



                   </div>















                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_owner"><?php esc_html_e('Owner', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_by_owner" name="ab_invoice_data[presented_by][owner]" value="<?php echo isset($ab_presented_by['owner']) ? esc_attr($ab_presented_by['owner']) : ''; ?>" />



                       </div>



                   </div>







                   <div class="row mb-3">



                       <div class="col-md-4">



                           <label for="presented_by_business_type"><?php esc_html_e('Business type', 'invoicepress') ?>:</label>



                       </div>



                       <div class="col-md-8">



                           <input type="text" class="form-control" id="presented_by_business_type" name="ab_invoice_data[presented_by][business_type]" value="<?php echo isset($ab_presented_by['business_type']) ? esc_attr($ab_presented_by['business_type']) : ''; ?>" />



                       </div>



                   </div>























               </div>







           </div>











            <div class="row">



                <div class="col-md-12">



                    <table class="table table-bordered">



                        <thead class="thead-dark">



                        <tr>



                            <th scope="col" class="w-75 text-center"><?php esc_html_e('Products/Services', 'invoicepress') ?></th>



                            <th scope="col" class="text-center"><?php esc_html_e('Amount', 'invoicepress') ?></th>



                        </tr>



                        </thead>



                        <tbody>



                        <tr>



                            <td scope="row" class="w-75 text-center">



                                <textarea type="text" class="form-control" id="presented_to_products" name="ab_invoice_data[presented_to][products][]"><?php echo isset($ab_presented_to['products'][0]) ? esc_attr($ab_presented_to['products'][0]) : ''; ?></textarea>



                            </td>



                            <td class="text-right pt-5">



                                <input type="number" class="form-control" id="presented_to_amount" name="ab_invoice_data[presented_to][amount][]" value="<?php echo isset($ab_presented_to['amount'][0]) ? esc_attr($ab_presented_to['amount'][0]) : '0'; ?>" step="0.01" />



                            </td>



                        </tr>







                        </tbody>



                    </table>



                </div>



            </div>











            <div class="row">



                <div class="col-md-8">







                </div>



                <div class="col-md-4">







                    <table class="table">







                        <tbody>



                        <tr>



                            <td><?php esc_html_e('Subtotal', 'invoicepress') ?>:</td>



                            <td class="text-right subtotal-amount">$00</td>











                        </tr>



                        <tr>



                            <td><?php esc_html_e('Tax', 'invoicepress') ?>:</td>



                            <td class="text-right"><input type="number" class="form-control" id="presented_by_tax" name="ab_invoice_data[presented_to][tax]" value="<?php echo isset($ab_presented_to['tax']) ? esc_attr($ab_presented_to['tax']) : '00'; ?>" /></td>



                        </tr>



                        <tr>



                            <td><?php esc_html_e('TOTAL', 'invoicepress') ?>:</td>



                            <td class="text-right total-amount">$00</td>



                        </tr>







                        </tbody>



                    </table>







                </div>



            </div>









            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="invoice_currency"><?php esc_html_e('Currency', 'invoicepress') ?>:</label>



                    <input type="text" class="form-control" id="invoice_currency" name="ab_invoice_data[presented_to][invoice_currency]" value="<?php echo isset($ab_presented_to['invoice_currency']) ? esc_attr($ab_presented_to['invoice_currency']) : 'USD' ?>" />



                </div>



            </div>

            

            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="currency_symbol"><?php esc_html_e('Currency Symbol', 'invoicepress') ?>:</label>



                    <input type="text" class="form-control" id="currency_symbol" name="ab_invoice_data[presented_to][currency_symbol]" value="<?php echo isset($ab_presented_to['currency_symbol']) ? esc_attr($ab_presented_to['currency_symbol']) : '$' ?>" />



                </div>



            </div>            





            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="invoice_date"><?php esc_html_e('Date', 'invoicepress') ?>:</label>



                    <input type="text" class="form-control" id="invoice_date" name="ab_invoice_data[presented_to][invoice_date]" value="<?php echo isset($ab_presented_to['invoice_date']) ? esc_attr($ab_presented_to['invoice_date']): date('d/m/Y'); ?>" />



                </div>



            </div>



            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="invoice_id"><?php esc_html_e('Invoice ID', 'invoicepress') ?>:</label>



                    <input type="text" class="form-control" id="invoice_id" name="ab_invoice_data[presented_to][invoice_id]" value="<?php echo isset($ab_presented_to['invoice_id']) ? esc_attr($ab_presented_to['invoice_id']): ''; ?>" />



                </div>



            </div>

            

            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="transaction_id"><?php esc_html_e('Transaction ID', 'invoicepress') ?>:</label>



                    <input type="text" class="form-control" id="transaction_id" name="ab_invoice_data[presented_to][transaction_id]" value="<?php echo isset($ab_presented_to['transaction_id'])?esc_attr($ab_presented_to['transaction_id']):''; ?>" />



                </div>



            </div>            

            

            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="invoice_additional_notes"><?php esc_html_e('Additional notes', 'invoicepress') ?>:</label>



                    <textarea type="text" class="form-control" id="invoice_additional_notes" name="ab_invoice_data[presented_to][additional_notes]"><?php echo isset($ab_presented_to['additional_notes']) ? esc_attr($ab_presented_to['additional_notes']) : '' ; ?></textarea>



                </div>



            </div>



            <div class="row mb-3">



                <div class="col-md-6">



                    <label for="invoice_footer_text"><?php esc_html_e('Footer Text', 'invoicepress') ?>:</label>



                    <textarea type="text" class="form-control" id="invoice_footer_text" name="ab_invoice_data[presented_to][footer_text]"><?php echo isset($ab_presented_to['footer_text']) ? esc_attr($ab_presented_to['footer_text']) : '' ; ?></textarea>



                </div>



            </div>











            <div class="row mb-3">



                <div class="col-md-12 format-selection-wrapper">

                

                	<label for="html"><i class="fa-solid fa-file"></i><i class="fa-regular fa-file"></i><input id="html" type="radio" name="format" value="html" /> <?php esc_html_e('HTML?', 'invoicepress') ?></label> &nbsp; <label for="pdf"><i class="fa-solid fa-file-pdf"></i><i class="fa-regular fa-file-pdf"></i><input id="pdf" type="radio" name="format" value="pdf" /> <?php esc_html_e('PDF?', 'invoicepress') ?></label><br /><br />



                    <input type="submit" class="btn btn-primary" name="ab_invoice_print" value="<?php esc_html_e('Save & Print', 'invoicepress') ?>">



                </div>



            </div>















    </div>









</form>

</div>