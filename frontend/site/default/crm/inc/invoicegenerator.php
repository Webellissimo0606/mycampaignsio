<?php switch ($settings['unitseparator']) {
case ',':$aSep = '.';
	break;
case '.':$aSep = ',';
default:
	break;
}?>
<?php switch ($settings['unitseparator']) {
case ',':
	$aDec = ',';
	break;
case '.':
default:
	$aDec = '.';
	break;
}?>
<script>
function Invoice_Create(settings) {
	"use strict";
	var defaults = {
		currency: 'USD',
		edit: true,
	};
	var $me, a, v;
	$me = this;
	$('.table-total').propery_select({
		success: function () {
			calculate_total();
		},
		delete: function () {
			calculate_total();
		}
	});
	$('.invoice-extra').propery_button();
	settings = $.extend(defaults, settings);
	/* Main Currency Icon  */
	var main_currency_icon = settings.currency.toLowerCase();
	var init_editable_unit = function () {
		$('.input-unit-editable').editable({
			title: 'Enter any Unit',
			success: function (response, newValue) {
				$('input', $(this).parent()).val(newValue);
			},
		}).on('hidden', function () {
			$(this).removeClass('editable-unsaved');
		});
	};
	<?php $products = $this->Products_Model->get_all_products();?>
	var products = [
		<?php foreach ($products as $product) {
	?>{"value": "<?php echo $product['id'] ?>","label": "<?php echo $product['productname'] ?>","code": "<?php echo $product['code'] ?>","description": "<?php echo $product['description'] ?>","sale_price": "<?php switch ($settings['unitseparator']) {case ',':echo number_format($product['sale_price'], 2, ',', '.');
		break;case '.':echo number_format($product['sale_price'], 2, '.', ',');
		break;}?>","vat": "<?php switch ($settings['unitseparator']) {
	case ',':echo number_format($product['vat'], 2, ',', '.');
		break;case '.':echo number_format($product['vat'], 2, '.', ',');
		break;}?>"},<?php }?>
	];
	var init_autocomplate = function () {
		$('input.autocomplate-product').autocomplete({
			minLength: 0,
			source: products,
			select: function (event, ui) {
			var $itemrow = $(this).closest('tr');
				$itemrow.find(".input-product").val(ui.item.label);
				$itemrow.find(".input-code").val(ui.item.code);
				$itemrow.find(".input-item-description").val(ui.item.description);
				$itemrow.find(".input-price").val(ui.item.sale_price);
				$itemrow.find(".price-post").val(ui.item.sale_price);
				$itemrow.find(".input-vat").val(ui.item.vat);
				$itemrow.find(".input-productcode").val(ui.item.value);
				$('.input-price').focus();
				$('.input-price').keyup();
				return false;
			}
		});
	};

	var load_line = function load_line(a,v,all) {
        //alert(v.data);
        if($('td:eq(0) .input-product-id',a).val() != v.id) {
            if(all) $('td:eq(0) .input-product',a).val(v.value);
            $('td:eq(0) .input-product-id',a).val(v.id);
            $('td:eq(0) .input-code',a).val(v.code);

            $('.input-price',a).autoNumeric('set',v[settings.type+'_price']);
            $('.input-vat-vat',a).val(v.vat);

            if(v[settings.type+'_extravatone']>0) {
                $('.input-vat-extravatone',a).autoNumeric('set',v[settings.type+'_extravatone']);
                $('li a[data-value='+v[settings.type+'_extravatone_type']+']',$('.input-vat-extravatone',a).next()).click();
                $('.select-properties li a[data-name=extravatone]').click();
            }
            if(v['extravattwo']>0) {
                $('.input-vat-extravattwo',a).autoNumeric('set',v['extravattwo']);
                $('.select-properties li a[data-name=extravattwo]').click();
            }
            if(!v.unit) v.unit='Unit';
            //$('.input-unit',a).val(v.unit).prev().html(v.unit);
            $('.input-unit-editable').editable('setValue',v.unit).next().val(v.unit);
            $('.input-amount',a).autoNumeric('set',v.amount ? v.amount : 1);
            $('td:eq(0) .input-discount-price',a).val(0);
            $('.dropdown-currency li a[data-name='+v[settings.type+'_currency_id']+']',a).click();
            calculate_line_total(a);
        }
    }

	/* Parse Float Helper */
	var pf = function (a) {
		return parseFloat(a);
	};
	/* Set Money Value Helper */
	var set_money = function (cls, val) {
		$(cls).autoNumeric('set', val);
	};
	var get_rate = function (name, line) {
		v = pf($(name, line).val());
		return get_rate_status(name, line) == 1 ? (v > 0 ? v : 0) : 0;
	};
	var get_rate_type = function (name, line) {
		return $('.input-discount-type', $(name, line).parent()).val();
	};
	var get_rate_status = function (name, line) {
		a = $('.input-status', $(name, line).parents('.property')).val();
		return a;
	};
	/* Calculates Currenct Line */
	var calculate_line_total = function (line) {
		var amount = $('.input-amount', line).autoNumeric('get');
		var price = $('.input-price', line).autoNumeric('get');
		$('.price-post', line).val(price);
		var vat_rate = pf($('.input-vat-vat', line).val());
		var discount_rate = get_rate('.input-discount-rate', line);
		var discount_type = get_rate_type('.input-discount-rate', line);
		var price_discounted = discount_type === 'rate' ? price * (100 - discount_rate) / 100 : (price - discount_rate / amount);
		var vat = (price_discounted) * vat_rate / 100;
		var total = (price_discounted + vat) * amount;
		$('.input-total', line).autoNumeric('set', total.toFixed(2));
		$('.input-price-discounted', line).val(price_discounted);
		$('.input-total-real', line).val(total);
		$('.input-vat-vat-total', line).val(amount * vat);
		calculate_total();
	};
	/* Calculate Line From Total */
	var calculate_line_unit = function (line) {
		var total = $('.input-total', line).autoNumeric('get');
		var amount = $('.input-amount', line).autoNumeric('get');
		var vat_rate = pf($('.input-vat', line).val());
		var extravatone_rate = get_rate('.input-vat-extravatone', line);
		var extravattwo_rate = get_rate('.input-vat-extravattwo', line);
		var discount_rate = get_rate('.input-discount-rate', line);
		var discount_type = get_rate_type('.input-discount-rate', line);
		var price_with_extravatone = total * 100 / (100 + vat_rate + extravattwo_rate) / amount;
		var price_discounted = extravatone_rate ? (get_rate_type('.input-vat-extravatone', line) === 'rate' ? price_with_extravatone * 100 / (extravatone_rate + 100) : price_with_extravatone - extravatone_rate) : price_with_extravatone;
		var price = discount_type === 'rate' ? price_discounted * 100 / (100 - discount_rate) : (price_discounted + discount_rate);
		var extravatone = price_with_extravatone - price_discounted;
		var extravattwo = price_with_extravatone * extravattwo_rate / 100;
		var vat = price_with_extravatone * vat_rate / 100;
		price = price.toFixed(4);
		price_discounted = price_discounted.toFixed(4);
		var dec = parseInt((price * 10000) % 100) > 0 ? 4 : 2;
		$('.input-price', line).autoNumeric('destroy');
		$('.input-price', line).autoNumeric({
			aSep: '<?php echo $aSep ?>',
			aDec: '<?php echo $aDec ?>',
			mDec: dec
		});
		$('.input-price-discounted', line).val(price_discounted);
		$('.input-total-real', line).val(total);
		$('.input-vat-extravatone-total', line).val(amount * extravatone);
		$('.input-vat-extravattwo-total', line).val(amount * extravattwo);
		$('.input-vat-vat-total', line).val(amount * vat);
		$('.input-price', line).autoNumeric('set', price);
		calculate_total();
	};
	/* Calculate Total Values */
	var calculate_total = function () {
		var sub_total = 0;
		var sub_total_discounted = 0;
		var vat_total = 0;
		var extravatone_total = 0;
		var extravattwo_total = 0;
		var grant_total = 0;
		var has_line_discount = 0;
		$('.table-invoice > tbody > tr.line').map(function () {
			var L = $(this);
			var total = pf($('.input-total-real', L).val());
			var vat = pf($('.input-vat-vat-total', L).val());
			var extravatone = pf($('.input-vat-extravatone-total', L).val());
			var extravattwo = pf($('.input-vat-extravattwo-total', L).val());
			var price = $('.input-price', L).autoNumeric('get');
			var price_discounted = pf($('.input-price-discounted', L).val());
			var amount = $('.input-amount', L).autoNumeric('get');
			var rate = pf($('.input-rate', L).val());
			sub_total += price * amount * rate;
			//line_discount_total += (price - price_discounted) * amount * rate;
			sub_total_discounted += price_discounted * amount * rate;
			extravatone_total += extravatone * rate;
			vat_total += vat * rate;
			extravattwo_total += extravattwo * rate;
			grant_total += total * rate;
			if (get_rate('.input-discount-rate', L) > 0) {
				has_line_discount++;
			}
		});
		if (!grant_total) {
			grant_total = 0;
		}
		var sub_discount_rate = get_rate('.input-sub-discount', $(document));
		var sub_discount_status = get_rate_status('.input-sub-discount', $(document));
		var sub_discount = 0;
		if (sub_discount_rate > 0) {
			var sub_discount_rate = get_rate_type('.input-sub-discount', $(document)) === 'rate' ? (100 - get_rate('.input-sub-discount', $(document))) / 100 : (sub_total_discounted - get_rate('.input-sub-discount', $(document))) / sub_total_discounted;
			extravatone_total *= sub_discount_rate;
			vat_total *= sub_discount_rate;
			extravattwo_total *= sub_discount_rate;
			grant_total *= sub_discount_rate;
			sub_discount = sub_total_discounted * (1 - sub_discount_rate);
			//discounted_vat = vat_total * sub_discount / sub_total_discounted;
			//grant_total -= discounted_vat + sub_discount;
			//vat_total -= discounted_vat;
		}
		var stoppage_total = 0;
		if (get_rate_status('.line-stoppage', $(document)) === 1) {
			stoppage_total = (sub_total_discounted - sub_discount) * pf($('.input-stoppage-rate').val()) / 100;
			grant_total -= stoppage_total;
		}
		var withholding_total = 0;
		if (get_rate_status('.line-withholding', $(document)) === 1) {
			withholding_total = vat_total * pf($('.input-withholding-rate').val()) / 100;
			grant_total -= withholding_total;
		}
		set_money('.table-total .sub-total .money-format', sub_total, main_currency_icon);
		set_money('.table-total .line-discount .money-format', '-' + (Math.abs(sub_total - sub_total_discounted).toFixed(2)), main_currency_icon);
		set_money('.table-total .gross-total .money-format', sub_total_discounted - sub_discount, main_currency_icon);
		set_money('.table-total .vat-total', vat_total, main_currency_icon);
		set_money('.table-total .extravatone-total', extravatone_total, main_currency_icon);
		set_money('.table-total .extravattwo-total', extravattwo_total, main_currency_icon);
		set_money('.table-total .line-stoppage .money-format', stoppage_total, main_currency_icon);
		set_money('.table-total .line-withholding .money-format', withholding_total, main_currency_icon);
		set_money('.table-total .grant-total', grant_total, main_currency_icon);
		$('.input-sub-total').val(sub_total);
		$('.input-line-discount').val(sub_total - sub_total_discounted);
		$('.input-sub-discount-total').val(sub_discount);
		$('.input-vat-total').val(vat_total);
		$('.input-extravatone-total').val(extravatone_total);
		$('.input-extravattwo-total').val(extravattwo_total);
		$('.input-stoppage-total').val(stoppage_total);
		$('.input-withholding-total').val(withholding_total);
		$('.input-grant-total').val(grant_total);
		sub_discount_status > 0 || has_line_discount > 0 ? $('.table-total .sub-totals').addClass('discounted') && $('.gross-total').show() : $('.gross-total').hide() && $('.table-total .sub-totals').removeClass('discounted');
		has_line_discount ? $('.table-total .line-discount').show() : $('.table-total .line-discount').hide();
	};
	//Status Chance
	$('input[name=statusid]').change(function () {
		if (!$(this).is(':checked')) return;
		if ($(this).val() == '2') {
			$('.toggle-cash,.toggle-payment,.toggle-odemeler-liste,.toggle-target-total').show();
			$('.toggle-user,.toggle-due,.toggle-payment-back').hide();
			$('select[name=accountid]').change();
		} else if ($(this).val() == '3') {
			$('.toggle-due').show();
			$('.toggle-cash,.toggle-user,.toggle-payment,.toggle-payment-back,.toggle-target-total').hide();
		} else {
			$('.toggle-user,.toggle-payment-back').show();
			$('.toggle-cash,.toggle-due,.toggle-payment,.toggle-target-total').hide();
		}
	});
	/* Initilize Creator */
	var init = function()
    {
        $('.money-format').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 2});

        $('.table-invoice .line').propery_select({
            success : function(line){
                calculate_line_total(line.parents('tr'));
            },
            delete : function(line){
                calculate_line_total(line.parents('tr'));
            }
        });


        if(settings.edit)
        {
            init_autocomplate();
            $('.filter-money').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 2});
            $('.currency-item-label,.currency-item-edit').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 4});
            $('.filter-number').numeric();
            if((settings.payment_count==0) || settings.copy) $('input[name=statusid]').change();
        }
        else
        {
            $('#add-line').click();
            $('input[name=statusid]').change();
        }


        $('.payment-currency-label,.payment-currency-edit').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 4, mDec:4});
        $('.payment-total-label,.payment-total-edit').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 2});

        init_editable_unit();
        calculate_total();
    }

	/* Deletes Invoice Line */
	$(document).on('click', 'a.delete-line', function () {
		$(this).parents('tr').remove();
		calculate_total();
		return false;
	});
	/* Adds New Line */
	$('#add-line').click(function(){
        $('.table-invoice > tbody .sample-line').before($('.table-invoice tbody tr.sample-line').clone().show().removeClass('sample-line').addClass('line'));
        //$('.table-invoice > tbody .sample-line').before($('.table-invoice tbody tr.sample-line-detail').clone().removeClass('sample-line-detail'));
        init_autocomplate();
        $('.filter-money').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 2});
        $('.currency-item-label,.currency-item-edit').autoNumeric({aSep : '<?php echo $aSep ?>', aDec : '<?php echo $aDec ?>',aPad : 4});
        $('.filter-number').numeric();

        $('.table-invoice > tbody .sample-line').prev().propery_select({
            success : function(line){
                calculate_line_total(line.parents('tr'));
            },
            delete : function(line){
                calculate_line_total(line.parents('tr'));
            }
        });

        init_editable_unit();

        return false;
    });






    /**
     * on line total changes change
     */
    $(document).on('keyup','.input-total',function(e){
        var charCode = e.which || e.keyCode; // for cross-browser compatibility
        if (!((charCode === 9) || (charCode === 16))) calculate_line_unit($(this).parents('tr'));
    });






    /**
     * on parameter changes change line total
     */
    $(document).on('keyup','.input-vat,.input-price,.input-amount,input-rate',function(e){
        var charCode = e.which || e.keyCode; // for cross-browser compatibility
        if (!((charCode === 9) || (charCode === 16))) calculate_line_total($(this).parents('tr'));
    });




    /**
     * on parameter changes total
     */
    $(document).on('keyup','.input-sub-discount',function(e){
        var charCode = e.which || e.keyCode; // for cross-browser compatibility
        if (!((charCode === 9) || (charCode === 16))) calculate_total();
    });







    /**
     * calculate line total on discount rate change
     */
    $(document).on('keyup','.input-discount-rate',function(){
        calculate_line_total($(this).parents('tr'));
    });






    /**
     * calculate line total on discount type change
     */
    $(document).on('click','.dropdown-discount-type',function(){
        if(!$(this).hasClass('calculate-total')) calculate_line_total($(this).parents('tr'));
        calculate_total();
    });





    /**
     * change line currency
     */
    $(document).on('click','.dropdown-currency li a',function(){
        calculate_line_total($(this).parents('tr'));
        calculate_total();
        $('.icon-currency',$(this).parents('tr')).attr('class','fa fa-'+$(this).attr('data-name').toLowerCase());
        //$('i',$(this).parents('td').prev().prev()).attr('class','fa fa-'+$(this).attr('data-name').toLowerCase());
    });










    /**
     * auto new line
     */
    $(document).on('keydown','.on-tab-add-line',function(e){
        if(e.which == 9 && !e.shiftKey && !$(this).parents('tr').next().next().hasClass('line')) {
            $('#add-line').click();
        }
    });





    /**
     * get unit item changes
     */
    $(document).on('keyup','.editable',function(){
        $(this).next().val($(this).html());
    });


    $(document).on('click','.currency-item-label,.payment-currency-label,.payment-total-label',function(){
        $(this).hide();
        $(this).next().css('display','inline-block');
        $(this).next().trigger('focus');
    });


    $(document).on('keyup','.currency-item-edit',function(){
        var k = $(this).autoNumeric('get') ? $(this).autoNumeric('get') : 0;
        var c = $(this).parents('.currency-item').attr('data-currency');
        $('.input-currency[value='+c+']').next().val(k);
        $('.dropdown-currency li a[data-name='+c+']').attr('data-exchange',k);
        calculate_total();
    });



    $(document).on('blur','.currency-item-edit,.payment-currency-edit,.payment-total-edit',function(){
        $(this).hide();
        $(this).prev().show();
        $(this).prev().html($(this).val());
    });



    $(document).on('keyup','.currency-item-edit,.payment-currency-edit',function(a){ if(a.keyCode == 13) $(this).blur(); });

    $(document).on('keyup','.payment-currency-edit',function(){
        calculate_payment_total();
    });

    $(document).on('keyup','.payment-total-edit',function(){
        calculate_payment_rate();
    });

    var calculate_payment_total = function(){
        me = $('.payment-currency-edit');
        cur = me.autoNumeric('get') ? me.autoNumeric('get') : 0;
        gt = $('.input-grant-total').val();
        $('.payment-total-label,.payment-total-edit',me.parent().parent()).autoNumeric('set',gt / cur);
    };

    var calculate_payment_rate = function(){
        me = $('.payment-total-edit');
        cur = me.autoNumeric('get') ? me.autoNumeric('get') : 0;
        gt = $('.input-grant-total').val();
        $('.payment-currency-label,.payment-currency-edit',me.parent().parent()).autoNumeric('set',gt / cur);
    };

    $(document).on('click','.table-invoice .property .delete',function(){
        //calculate_line_total($(this).parents('tr'));
    });

    $(document).on('keyup','.table-invoice .delete-on-delete',function(a){
        if(a.keyCode == 8 && !$(this).val()) $('.delete',$(this).parent().parent()).click();
    })






	init();
}
</script>
