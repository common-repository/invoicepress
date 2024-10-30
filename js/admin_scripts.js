jQuery(document).ready(function($){

	

    var load_modal = $('#ab_load_modal');



	$('#presented_to_amount, #presented_by_tax').on('blur', function(){
		var currency_symbol = $('#currency_symbol').val();
		var item_price = parseFloat($('#presented_to_amount').val()).toFixed(2);
		
		$('td.subtotal-amount').html(currency_symbol+item_price);
		
		item_price = parseFloat(parseFloat(item_price)+parseFloat($('#presented_by_tax').val())).toFixed(2);
		
		$('td.total-amount').html(currency_symbol+item_price);
	});
	
	setTimeout(function(){
		if($('#presented_by_tax').length>0){
			$('#presented_by_tax').trigger('blur');
		}
	}, 1000);


    $('.in_pr_settings_div a.nav-tab').click(function(){



        $(this).siblings().removeClass('nav-tab-active');

        $(this).addClass('nav-tab-active');

    // , form:not(.wrap.wc_settings_div .nav-tab-content)'

        $('.nav-tab-content').hide();

        $('.nav-tab-content').eq($(this).index()).show();

        window.history.replaceState('', '', in_pr_obj.this_url+'&t='+$(this).index());

        in_pr_obj.in_pr_tab = $(this).index();



    });

	$('body').on('click','input[name^="invoice_ids"]',  function(e){
		
		if($(this).val()==0){
			
			if($(this).is(':checked')){
				$('input[name^="invoice_ids"]').prop('checked', true);
			}else{
				$('input[name^="invoice_ids"]').prop('checked', false);
			}
			
		}
		
	});

    $('body').on('click','.ab-preset-del',  function(e){


		var ab_preset_del_id = {};
		
		$.each($('input[name^="invoice_ids"]:checked'), function(i, v){
			var val = $(this).val();
			
			if(val>0){

				ab_preset_del_id[i] = val;
				
			}
			
		});
		
		var total_selected_rows = Object.keys(ab_preset_del_id).length;

        var del_confirm = confirm(total_selected_rows>1?in_pr_obj.del_confirm_str_multiple:in_pr_obj.del_confirm_str);



        if(!del_confirm){

            return;

        }



        load_modal.show();
		
		

		

        
		
		//console.log(ab_preset_del_id);

        var data = {

            action : 'ab_preset_delete_by_id',

            invoice_id : ab_preset_del_id,

            nonce : in_pr_obj.ab_pdf_action

        }

        $.post(ajaxurl, data, function(response){

            load_modal.hide();

            if(response != 'false'){

                $('.ab_preset_list_content').replaceWith(response);

                $('.ab_preset_list_content').removeClass('hide');

            }

        })

    });

    $('body').on('click', 'div.search-wc-order a', function(e){
		
        var search_id = $('#wc_order_id').val();

		if($.trim(search_id)){
	
			var data = {
	
				action : 'ab_search_wc_order',
	
				search_id : search_id,
	
				nonce : in_pr_obj.ab_pdf_action
	
			}
			$.blockUI({ message: '' });
			$.post(ajaxurl, data, function(response){
	
				response = $.parseJSON(response);
				if(typeof response.order!='undefined'){
					$('#invoice_id').val(response.order.receipt_number);
					$('#transaction_id').val(response.order.transaction_number);
					$('#invoice_date').val(response.order.invoice_date);
					$('#presented_to_email').val(response.order.billing_email);
					$('#presented_to_name').val(response.order.display_name);
					
					$('#presented_to_company').val(response.order.company);
					$('#presented_to_address').val(response.order.address);		
					$('#presented_to_vat').val(response.order.vat);
					$('#presented_to_website').val(response.order.website);
					$('#presented_to_amount').val(response.order.total);			
					
				}
				if($.trim(response.items)!=''){
					$('#presented_to_products').val(response.items);
				}
				
				$.unblockUI();
			});
			
		}

    });	

	
	var availableOrders = [];
	availableOrders = in_pr_obj.availableOrders;
	$('#wc_order_id').autocomplete({
	  source: availableOrders
	});
	
	setTimeout(function(){
		 $('.nav-tab-wrapper .nav-tab:nth-child('+(parseInt(in_pr_obj.in_pr_tab)+1)+')').click();
	}, 1000);


});