<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    $preset_args = array(

        'numberposts' => -1,
        'post_type' => 'invoicepress',
        'post_status' => 'hidden',
		'orderby' => 'date',
		'order' => 'DESC'

    );

	$ab_presented_to_data = get_posts($preset_args);//get_option('ab_presented_to_data', array());
	//pree($ab_presented_to_data);exit;

?>
<div class="nav-tab-content ab_preset_list_content hide">

<div class="row mt-3">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col"><input type="checkbox" name="invoice_ids[]" value="0" /></th>
                        <th scope="col"><?php esc_html_e('Invoice', 'invoicepress') ?>#</th>
                        <th scope="col"><?php esc_html_e('Date Created', 'invoicepress') ?></th>
                        <th scope="col"><?php esc_html_e('Name', 'invoicepress') ?></th>
                        <th scope="col"><?php esc_html_e('Company', 'invoicepress') ?></th>                    
                        <th scope="col"><?php esc_html_e('E-Mail', 'invoicepress') ?></th>
                        <th scope="col"><?php esc_html_e('Website', 'invoicepress') ?></th>
                        <th scope="col"><?php esc_html_e('Invoice Date', 'invoicepress') ?></th>
                        <th scope="col"><?php esc_html_e('Amount', 'invoicepress') ?></th>
                        <th scope="col" style="width:150px"><?php esc_html_e('Action', 'invoicepress') ?></th>
    
                    </tr>
                </thead>
                
                <tfoot class="thead-dark">
                    <tr>
                        <td scope="col"><input type="checkbox" name="invoice_ids[]" value="0" /></th>
                        <td scope="col"><?php esc_html_e('Invoice', 'invoicepress') ?>#</td>
                        <td scope="col"><?php esc_html_e('Date Created', 'invoicepress') ?></td>
                        <td scope="col"><?php esc_html_e('Name', 'invoicepress') ?></td>
                        <td scope="col"><?php esc_html_e('Company', 'invoicepress') ?></td>                    
                        <td scope="col"><?php esc_html_e('E-Mail', 'invoicepress') ?></td>
                        <td scope="col"><?php esc_html_e('Website', 'invoicepress') ?></td>
                        <td scope="col"><?php esc_html_e('Invoice Date', 'invoicepress') ?></td>
                        <td scope="col"><?php esc_html_e('Amount', 'invoicepress') ?></td>
                        <td scope="col" style="width:150px"><?php esc_html_e('Action', 'invoicepress') ?></td>
    
                    </tr>
                </tfoot>
                <tbody>

                <?php

                    $no_found_str = __('No invoices found.', 'invoicepress');

                    if(!empty($ab_presented_to_data)){
						//pree($ab_presented_to_data);
						//krsort($ab_presented_to_data);
                        $index = 1;
                        foreach ($ab_presented_to_data as $presented_data_obj){
							
							//pree($presented_to_data);
							
							//$id = ab_update_invoice_data($presented_to_data);
							
							//pree($id);
							$email_key = $presented_data_obj->ID;
							
							$presented_data = get_post_meta($presented_data_obj->ID, '_ab_invoice_meta', true);
							$presented_data = (is_array($presented_data)?$presented_data:array());
							$presented_to_data = array_key_exists('presented_to', $presented_data)?$presented_data['presented_to']:array();
							$presented_by_data = array_key_exists('presented_by', $presented_data)?$presented_data['presented_by']:array();
							
							if(empty($presented_to_data) || empty($presented_by_data)){
								continue;
							}
							
							
							//pree($presented_to_data);
							
							$presented_to_data['currency_symbol'] = (array_key_exists('currency_symbol', $presented_to_data)?$presented_to_data['currency_symbol']:'$');
	

                            $sr = $index;
                            $p_name = isset($presented_to_data['name']) ? $presented_to_data['name'] : '';
                            $p_company = isset($presented_to_data['company']) ? $presented_to_data['company'] : '';
                            $p_email = isset($presented_to_data['email']) ? $presented_to_data['email'] : '';
                            $p_vat = isset($presented_to_data['vat']) ? $presented_to_data['vat'] : '';
                            $p_web = isset($presented_to_data['website']) ? $presented_to_data['website'] : '';
							$amount_val = (isset($presented_to_data['amount']) ? array_sum($presented_to_data['amount']) : 0);
							$amount_val = number_format((float)$amount_val, 2, '.', '');
							$p_amount = ($presented_to_data['currency_symbol']).$amount_val;
							$c_date = date(get_option('date_format').' '.get_option('time_format'), strtotime($presented_data_obj->post_date));
							$p_date = isset($presented_to_data['invoice_date']) ? $presented_to_data['invoice_date'] : '';
                            $p_load = esc_html(__('Edit', 'invoicepress'));
                            $p_del = esc_html(__('Delete', 'invoicepress'));
                            $p_load_url = wp_nonce_url(admin_url('admin.php?page=invoicepress-settings&invoice_id='.$email_key), 'ab_preset_action');
?>
                            <tr>
                            <th><input type="checkbox" name="invoice_ids[]" value="<?php echo esc_attr($email_key); ?>" class="invoice-row" /></th>
                            <th><?php echo esc_html($presented_data_obj->ID); ?></th>
							<td><?php echo esc_html($c_date); ?></td>
                            <td><?php echo esc_html($p_name); ?></td>
                            <td><?php echo esc_html($p_company); ?></td>
                            <td><?php echo esc_html($p_email); ?></td>
                            <td><?php echo esc_html($p_web); ?></td>
							<td><?php echo esc_html($p_date); ?></td>
							<td><?php echo esc_html($p_amount); ?></td>
                            <td><a class='btn btn-sm btn-primary' href='<?php echo esc_attr($p_load_url); ?>'><?php echo esc_html($p_load); ?></a><a class='btn btn-sm btn-danger ms-1 text-white ab-preset-del' data-id='<?php echo esc_attr($email_key); ?>'><?php echo esc_html($p_del); ?></a></td></tr>
<?php
                            $index++;
							
							
                        }
                    }else{
?>
<tr><td colspan='10'><div class='alert alert-info text-center'><?php echo esc_html($no_found_str); ?></div></td></tr>
<?php
                    }

                ?>




                </tbody>
            </table>
        </div>
    </div>
</div>

</div>