<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



		global $in_pr_dir, $in_pr_url;





		if ( isset( $_POST['ab_invoice_print'] )) {





        if (

        ! isset( $_POST['in_pr_invoice_field'] )

        || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['in_pr_invoice_field'])), 'in_pr_invoice_action' )

        ) {



            esc_html_e('Sorry, your nonce did not verify.','invoicepress');

            exit;



        } else {






			//pree($_POST['ab_invoice_data']);
            $ab_invoice_data = isset($_POST['ab_invoice_data']) ? in_pr_sanitize_data($_POST['ab_invoice_data']) : array();
			$ab_preset_id = isset($_POST['invoice_id']) ? in_pr_sanitize_data($_POST['invoice_id']) : 0;
			$additional_notes = '';

			$ab_invoice_data['presented_to']['additional_notes'] = ($ab_invoice_data['presented_to']['additional_notes']?$ab_invoice_data['presented_to']['additional_notes']:$additional_notes);
			
			


			//pree($ab_invoice_data);//exit;



            //update_option('ab_invoice_data', $ab_invoice_data);





//			pree($ab_invoice_data);exit;





            $presented_to = isset($ab_invoice_data['presented_to']) ? $ab_invoice_data['presented_to'] : array();

            $presented_by = isset($ab_invoice_data['presented_by']) ? $ab_invoice_data['presented_by'] : array();

			//in_pr_pree($presented_by);exit;

            $products = isset($presented_to['products']) ? $presented_to['products'] : array();

            $amount = isset($presented_to['amount']) ? $presented_to['amount'] : array();

            $tax = isset($presented_to['tax']) ? $presented_to['tax'] : 0;

            $tax = $tax ? (int)$tax : 0;
			
			$additional_notes_extra = '';

            $additional_notes = isset($presented_to['additional_notes']) ? $presented_to['additional_notes'] : 0;

            $footer_text = trim($presented_to['footer_text']) ? stripslashes($presented_to['footer_text']) : '';



            $custom_logo_id = get_theme_mod( 'custom_logo' );

            $custom_logo_image = wp_get_attachment_image_src( $custom_logo_id , 'full' );

            

			

			$currency_symbol = isset($presented_to['currency_symbol']) ? $presented_to['currency_symbol'] : '';

			

			$invoice_currency = isset($presented_to['invoice_currency']) ? $presented_to['invoice_currency'] : '';

			

			$invoice_id = isset($presented_to['invoice_id']) ? $presented_to['invoice_id'] : '';

			

			$transaction_id = isset($presented_to['transaction_id']) ? $presented_to['transaction_id'] : '';

			

			$invoice_date = isset($presented_to['invoice_date']) ? $presented_to['invoice_date'] : '';

			

			

			$presented_to['email'] = (isset($presented_to['email']) ? $presented_to['email'] : '');





			$presented_to['ID'] = $ab_preset_id;

			if(is_user_logged_in()){
				in_pr_update_invoice_data($presented_to, $presented_by);
			}
			//pree($presented_to);exit;


        }



    }





?>

<?php wp_head(); ?>

    <div class="continer m-2 h-auto" style="border: 1px solid #c1392b;">

        

        <div class="row mx-2">

            <div class="col-12">

				<div class="d-inline-block mt-5" style="width: 24%;">
                    <a href="<?php echo esc_attr(trim($presented_by['website'])!=''?str_replace(array('https://', 'http://'), '//', $presented_by['website']):''); ?>" target="_blank" class="d-inline-block logo-section">
                    	<?php if(trim($presented_by['logo'])!=''): ?>
                        <img src="<?php echo esc_attr($presented_by['logo']); ?>" />
                        <?php endif; ?>
                    </a>
                </div>

                <div class="d-inline-block text-end mt-5" style="width: 75%;">

                    <img src="<?php echo esc_attr(in_pr_generate_qrcode('Dated: '.$invoice_date.', Email: '.$presented_to['email'].', '.implode(' / ', $products))); ?>" border="0" title="" style="width: 200px; height: 200px; margin-top:40px;"/>

                </div>

            </div>

        </div>



        <div class="ab_invoice_text mt-3 p-3 text-center" style="clear: both">Invoice</div>



        <div class="row mx-2 mb-3">

            <div class="col-12">



                    <div class="ab_unique_id" style="font-size: 25px"><?php if(is_numeric($invoice_id)){ ?>#AB-<?php echo esc_html($invoice_id); }else{ echo esc_html($invoice_id); } ?></div>



                    <div class="ab_unique_id"><?php if(is_numeric($transaction_id)){ ?>PayPal Unique Transaction ID #<?php echo esc_html($transaction_id); }else{ echo esc_html($transaction_id); } ?></div>



                    <div class="ab_unique_id">Dated: &nbsp; &nbsp; <?php echo esc_html($invoice_date); ?></div>



            </div>

        </div>



        <div class="row mx-2 mt-5 mb-3">



            <div class="col-12">

                <div class="d-inline-block" style="width: 49%;  vertical-align: top;">

                    <div  style="width: 95%">



                        <div class="ab_row" style="font-size:18px;">Presented to:</div>



                        <div class="ab_row" style="color:#5c5c5c; font-size:25px; font-weight:bold;">

                                <?php echo isset($presented_to['name']) ? esc_html($presented_to['name']) : '';  ?>

                        </div>



                        <div class="ab_row">

                            <div class="ab_title">Company:</div>

                            <div class="ab_value"><?php echo isset($presented_to['company']) ? esc_html($presented_to['company']) : '';  ?></div>

                        </div>





                        <div class="ab_row">

                            <div class="ab_title">Address:</div>

                            <div class="ab_value"><?php echo isset($presented_to['address']) ? esc_html($presented_to['address']) : '';  ?></div>

                        </div>





                        <div class="ab_row">

                            <div class="ab_title">E-Mail:</div>

                            <div class="ab_value"><?php echo isset($presented_to['email']) ? esc_html($presented_to['email']) : '';  ?></div>

                        </div>



                        <div class="ab_row">

                            <div class="ab_title">Website:</div>

                            <div class="ab_value"><a href="<?php echo isset($presented_to['website']) ? esc_attr($presented_to['website']) : '';  ?>" style="color:#5c5c5c; text-decoration:none;" target="_blank"><?php echo isset($presented_to['website']) ? esc_html($presented_to['website']) : '';  ?></a></div>

                        </div>



                        <div class="ab_row">

                        	<div class="">VAT / UST-IDNR / EU NIP: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo isset($presented_to['vat']) ? esc_html($presented_to['vat']) : '';  ?></div>

<!--                            <div class="ab_value">--><?php //echo isset($presented_to['vat']) ? $presented_to['vat'] : '';  ?><!--</div>-->

						</div>                            





                    </div>

                </div>

                <div class="d-inline-block" style="width: 49%; border-left: solid 1px #f1f1f1; vertical-align: top;">



                    <div style="width: 95%; margin-left: 5%;">



                        <div class="ab_row" style="font-size:18px;">Presented by:</div>



                        <div class="ab_row" style="color:#5c5c5c; font-size:25px; font-weight:bold;"><?php echo isset($presented_by['name']) ? esc_html($presented_by['name']) : '';  ?></div>



                        <div class="ab_row">

                            <div class="ab_title">Company:</div>

                            <div class="ab_value"><?php echo isset($presented_by['company']) ? esc_html($presented_by['company']) : '';  ?></div>

                        </div>



                        <div class="ab_row">

                            <div class="ab_title">Address:</div>

                            <div class="ab_value"><?php echo isset($presented_by['address']) ? esc_html($presented_by['address']) : '';  ?></div>

                        </div>







                        <div class="ab_row">

                            <div class="ab_title">E-Mail:</div>

                            <div class="ab_value"><a href="mailto:<?php echo isset($presented_by['email']) ? esc_attr($presented_by['email']) : '';  ?>" style="color:#5c5c5c; text-decoration:none;"><?php echo isset($presented_by['email']) ? esc_html($presented_by['email']) : '';  ?></a></div>

                        </div>



                        <div class="ab_row">

                            <div class="ab_title">Website:</div>

                            <div class="ab_value"><a href="<?php echo isset($presented_by['website']) ? esc_attr($presented_by['website']) : '';  ?>" style="color:#5c5c5c; text-decoration:none;" target="_blank"><?php echo isset($presented_by['website']) ? esc_html($presented_by['website']) : '';  ?></a></div>

                        </div>







                        <div class="ab_row">

                            <div class="ab_title">Owner:</div>

                            <div class="ab_value"><?php echo isset($presented_by['owner']) ? esc_html($presented_by['owner']) : '';  ?></div>

                        </div>



                        <div class="ab_row">

                            <div class="ab_title">Business type:</div>

                            <div class="ab_value"><?php echo isset($presented_by['business_type']) ? esc_html($presented_by['business_type']) : '';  ?></div>

                        </div>





                        <div class="ab_row">

                        	<div class="">VAT / UST-IDNR / EU NIP: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo isset($presented_by['vat']) ? esc_html($presented_by['vat']) : '';  ?></div>


						</div>  





                    </div>



                </div>

            </div>





        </div>



        <div class="row mx-3 mt-0">

            <div class="col-12">

                <table border="0" cellspacing="0" cellpadding="10" bordercolor="#ffffff" style="width:100%; border-right:1px solid #efefef; border-left:1px solid #efefef; font-size:14px; text-align:center;">



                    <tr style="background-color:#424242; color:#ffffff;">



                        <td valign="middle" width="80%">Products/Services</td>

                        <td valign="middle" style="border-left:1px solid #5e5e5e;" width="20%"><?php echo esc_html($invoice_currency); ?> <br>

                            (Amount)</td>



                    </tr>



                    <tr style="color:#424242;">



                        <?php



                        if(!empty($products)){



                            $subtotal = 0;



                            foreach ($products as $index => $product){



                                $current_amount = isset($amount[$index]) ?  $amount[$index] : 0;

                                $current_amount = $current_amount ?  (float)$current_amount : 0;



                                $subtotal += $current_amount;







                                ?>



                                <td valign="middle" style="border-bottom:1px solid #efefef;"><a href="#." style="color:#424242; text-decoration:none;"><?php echo esc_html($product); ?></a></td>

                                <td valign="middle" style="border-left:1px solid #efefef; border-bottom:1px solid #efefef;"><?php echo isset($amount[$index]) ?  esc_html($currency_symbol.$amount[$index]) : '' ; ?></td>





                                <?php



                            }

                        }



                        ?>



                    </tr>



                </table>

            </div>

        </div>



        <div class="row mx-3 mt-2">

            <div class="col-12">



                <div class="d-inline-block float-right mb-5" style="width: 45%;">



                    <div   style="font-size:14px; width: 100%;">



                        <div class="subtotal_row">

                            <div class="d-inline-block subtotal_head" >Subtotal:</div>

                            <div class="d-inline-block subtotal_val text-center"><?php echo esc_html($currency_symbol.number_format((float)$subtotal, 2, '.', '')); ?></div>

                        </div>



                        <div class="subtotal_row">

                            <div  class="d-inline-block subtotal_head">Tax:</div>

                            <div  class="d-inline-block subtotal_val text-center"><?php echo esc_html($currency_symbol.number_format((float)$tax, 2, '.', '')); ?></div>

                        </div>



                        <div class="subtotal_row">

                            <div class="d-inline-block subtotal_head" >TOTAL:</div>

                            <div class="d-inline-block subtotal_val text-center"><?php echo esc_html($currency_symbol.number_format((float)($subtotal + $tax), 2, '.', '')); ?></div>

                        </div>





                    </div>



                </div>





            </div>

        </div>



        <div class="row mx-3 mb-5" style="clear: both">

            <div class="col-12">

                <div style="font-weight:bold;"><?php if(trim($additional_notes)): ?>Additional notes: <?php else: echo '&nbsp;&nbsp;&nbsp;'; endif; ?></div>

                <div><?php if(trim($additional_notes)): ?><?php echo esc_html(nl2br($additional_notes)).'<hr />'.$additional_notes_extra; ?> <?php else: echo '&nbsp;&nbsp;&nbsp;'; endif; ?></div>

            </div>

        </div>



        <div class="row mx-3 mb-2">

            <div class="col-12 text-right">

                <?php echo esc_html($footer_text); ?>

            </div>

        </div>



        <div style="height: 3rem; background-color: #c1392b " ></div>



    </div>


