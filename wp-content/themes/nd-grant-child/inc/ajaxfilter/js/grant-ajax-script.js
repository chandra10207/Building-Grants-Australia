jQuery(document).ready(function($) {
	
	
$(document).off( 'porto_update_products', 'ul.products');
 $("ul.products").off("porto_update_products");

$('.gt-select-max-min').multipleSelect({
	placeholder:'Select',
});
$('.gt-select-search').multipleSelect({
	placeholder:'Search',
	filterPlaceholder:'Suburb or Postcode',
	filter: true,	
	selectAll: false,
});
$('.gt-select').multipleSelect({
	placeholder:'Select',
	//selectAll: false,
	formatSelectAll: function () {
		return 'Select All'
	}
});

$(document).on('click','#gtToggleSearch',function(e){
	e.preventDefault();
	$(this).toggleClass('gt-m');
	var advancedSearch = $('.gt-advanced-search');
	advancedSearch.toggleClass('gt-show');
});
$(document).on('click','.gt-less-filters',function(e){
	e.preventDefault();
	$('#gtToggleSearch').removeClass('gt-m');
	$('.gt-advanced-search').removeClass('gt-show');
	window.scrollTo(0, 0);
});


$(document).on('click','.gt-woocommerce-ajax-pagination ul li',function(){
	
	if($(this).hasClass('selected') || $(this).hasClass('inactive'))
		return false;
		
	var page = $(this).attr('p');
	var filterForm = $('#gtFilterForm');
	var term_id= filterForm.data('term');
	$('#gtFilterForm #page').val(page);
	var data = {
				'action': 'grant_action',
				'security': ajax_object.security,
				'formData':filterForm.serialize(),
				'term_id': term_id,
				'page': page,
				};

  load_products(page,data);
  
});

$('#gtFilterForm select').on('change', function(e) {
	e.preventDefault();
	var filterForm = $('#gtFilterForm');
	var term_id= filterForm.data('term');
	$('#gtFilterForm #page').val(1);
	var page = 1;
	var data = {
		'action': 'grant_action',
		'security': ajax_object.security,
		'formData':filterForm.serialize(),
		'term_id': term_id,
		'page': page,
	};
	load_products(1,data);
});

$(document).on('click', '.gt-clear-filters,.gt-reset-filters', function (e){
	e.preventDefault();
	var state = $("#state").val();
	var filterForm = $('#gtFilterForm');
	var term_id= filterForm.data('term');
	$('.gt-select-search').multipleSelect('refresh');
	$('.gt-select').multipleSelect('refresh');
	$('.gt-select-max-min').multipleSelect('refresh');
	var data = {
		'action': 'grant_action',
		'security': ajax_object.security,
		'formData':'state='+state,
		'term_id': term_id,
	};
		$('#gtToggleSearch').removeClass('gt-m');
	$('.gt-advanced-search').removeClass('gt-show');
	
	window.scrollTo(0, 0);
	load_products(1,data);
	$('.woocommerce-result-count span').hide();
});

function load_products(page,data){
	
	window.scrollTo(0, 0);
    var woocommerceResultCount = $('.woocommerce-result-count');
    var gtNoResults = $('.gt-no-results');
    var woocommercePagination = $('.gt-woocommerce-ajax-pagination');
	$.ajax({
		    type: "POST",
		    dataType: 'json',
		    url:ajax_object.ajaxurl,
		    beforeSend: function() {
			    $('.archive-products').prepend('<div class="gt-loader"></div>');
			    $('#content').addClass('gt-loading');
			     $('.gt-loader').show();
			 },
			complete: function(){
			     $('.gt-loader').hide();
			     $('#content').removeClass('gt-loading');
			},
		    data:data,
		    success: function(data){
			    var allFilters = data.filters;
			    let queryString = new URLSearchParams(allFilters);
			    let keysForDel = [];
				queryString.forEach((value, key) => {
				  if (value == '') {
				    keysForDel.push(key);
				  }
				  if(key=='page' && value=='1'){
					 keysForDel.push(key);
				  }
				  if(key=='sortby' && value=='menu_order'){
					 keysForDel.push(key);
				  }

				});
				
				keysForDel.forEach(key => {
				  queryString.delete(key);
				});
				

				//jQuery.inArray( "John", arr )
				if(queryString!=''){
					//queryString = queryString);
				    var decodedString = decodeURIComponent(queryString.toString());
				    //history.pushState({}, '', `${location.pathname}?${queryString.toString()}`);
				    //history.pushState({}, '', `${location.pathname}?${decodedString}`);
					var currentURL = window.location.search;
					if(hasQueryParams(currentURL)){
						var grantFilterNames = ['sortby', 'page', 'state', 'suburbs', 'storeys', 'beds', 'baths', 'garage', 'min_price', 'max_price', 'lr', 'feature', 'design', 'p_status', 'rebate_t', 'min_hs', 'max_hs', 'min_ls', 'max_ls', 'min_bw', 'max_bw', 'min_bd', 'max_bd', 'gt_fed', 'gt_nsw', 'gt_qld', 'gt_sa', 'gt_vic', 'gt_wa'];
						
						let currentQueryString = new URLSearchParams(currentURL);
						let curKeysForDel = [];
						currentQueryString.forEach((value, key) => {
							if (value == '') {
								curKeysForDel.push(key);
							}
							if($.inArray( key, grantFilterNames ) != -1){
								curKeysForDel.push(key);
							} 
						});
						curKeysForDel.forEach(key => {
							currentQueryString.delete(key);
						});
						var currentDecodedString = decodeURIComponent(currentQueryString.toString());
						if(currentQueryString!=''){
							history.pushState({}, '', `${location.pathname}?${decodedString}&${currentDecodedString}`);
						}else{
							history.pushState({}, '', `${location.pathname}?${decodedString}`);
						}
					}else{
					   history.pushState({}, '', `${location.pathname}?${decodedString}`);
					}

				}else{
/*
					var currentURL = window.location.search;
					if(hasQueryParams(currentURL)){
						history.pushState({}, '', currentURL);   
					}else{
					history.pushState({}, '', `${location.pathname}`);
					}
*/
					window.history.replaceState({}, '', `${location.pathname}`);
				//$('.gt-reset-filters').hide();
				}
				
			    woocommerceResultCount.html(data.woocommerce_result_count);
				
				if(queryString.has('state')){
					
					var noStateQueryString = queryString;
					noStateQueryString.delete('state');
					
					if(noStateQueryString!=''){
						woocommerceResultCount.append('<span class="gt-reset-filters">Reset Filters</span>');
					}
				}
				
			    if(data.total == 0){
				    woocommerceResultCount.find('.gt-reset-filters').remove();
				    $('<span class="gt-reset-filters">Reset Filters</span>').insertAfter('.gt-no-results');
			    }

			    $('.archive-products').html('');
			    $('.archive-products').html(data.html);
			    woocommercePagination.html('');
			    woocommercePagination.html(data.woocommerce_pagination);		
			    			
			    var productPrices = data.updated_attributes.product_prices;	
			    var houseSizes = data.updated_attributes.house_sizes;	
			    var landSizes = data.updated_attributes.land_sizes;	
			    var blockWidths = data.updated_attributes.block_widths; 
			    var blockDepths = data.updated_attributes.block_depths; 
			       
			    for (const [key, values] of Object.entries(data.updated_attributes)) {
				    if(key.includes('max') || key.includes('min') || key.includes('prices') || key.includes('sizes') || key.includes('widths') || key.includes('depths')){
					  // console.log(`${key} ${values}`); 
					    if(key=='min_prices'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								//console.log(`${key} ${value['num']} ${value['abbr']}`); 
								var frequency = minFrequencyCount(productPrices,v['num']);
								//console.log(frequency.count);
								var $opt = $("<option/>", {
									value: v['num'],
									text: '\u0024' + v['abbr'] + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true,
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
							
						}
						if(key=='max_prices'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = maxFrequencyCount(productPrices,v['num']);
								var $opt = $("<option/>", {
									value: v['num'],
									text: '\u0024' + v['abbr'] + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true,
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
							
						}
						if(key=='min_house_sizes'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = minFrequencyCount(houseSizes,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm\u00B2' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
							
						}
						if(key=='max_house_sizes'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = maxFrequencyCount(houseSizes,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm\u00B2' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
							
						}
						if(key=='min_land_sizes'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = minFrequencyCount(landSizes,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm\u00B2' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
							
						}
						if(key=='max_land_sizes'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = maxFrequencyCount(landSizes,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm\u00B2' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
							
						}
						if(key=='min_block_widths'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = minFrequencyCount(blockWidths,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
						}
						if(key=='max_block_widths'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = maxFrequencyCount(blockWidths,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
						}
						if(key=='min_block_depths'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = minFrequencyCount(blockDepths,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
						}
						if(key=='max_block_depths'){
							$('.' + key + ' > select').find('option:not(:first)').remove();
							$.each(values, function(k,v){
								var frequency = maxFrequencyCount(blockDepths,v);
								var $opt = $("<option/>", {
									value: v,
									text: v + 'm' + '(' + frequency.count + ')',
									disabled: (frequency.count > 0)?false:true
									});
								$('.' + key + ' > select').append($opt).multipleSelect('refresh');
							});
						}

					   
				    }else if(key=='suburbs'){
					    var attributes_with_count = values.reduce((accumulator, value) => {
														return {...accumulator, [value]: (accumulator[value] || 0) + 1};
													}, {});
					   $('.' + key + ' > select').find('option').remove();
						$.each(attributes_with_count, function(val, cnt){
							var postcode = val.match(/(\d+)/);
							var $opt = $("<option/>", {
								value: postcode[0],
								text: val + '(' + cnt + ')',
								disabled: (cnt > 0)?false:true
							});
							$('.' + key + ' > select').append($opt).multipleSelect('refresh');
						});
						
						
					    
				    }else{
					    //console.log(`${key} ${values}`); 
						var attributes_with_count = values.reduce((accumulator, value) => {
														return {...accumulator, [value]: (accumulator[value] || 0) + 1};
													}, {});
						$('.' + key + ' > select').find('option').remove();
						$.each(attributes_with_count, function(val, cnt){
							if(key=="pa_garage"){var carTxt = (val>1)?"Cars":"Car";}
							var $opt = $("<option/>", {
								value: val,
								text: (key=="pa_garage")?val + ' '+carTxt+' '+ '('+ cnt + ')':val + '(' + cnt + ')',
								disabled: (cnt > 0)?false:true
							});
							$('.' + key + ' > select').append($opt).multipleSelect('refresh');
						});
						
					}
			    }

				//Set Selected Values
			    queryString.forEach(function(value, key) {
				    if(key.includes('max') || key.includes('min')){
							//$('select[name="'+key+'"] option[value="'+value+'"]').attr("selected","selected");
							$("#"+key+" option[value='" + value + "']").attr("selected","selected");
							$('.gt-select-max-min').multipleSelect('refresh');
					}else{
						$.each(value.split(","), function(i,e){
							$("#"+key+" option[value='" + e + "']").attr("selected","selected");
						});
						$('.gt-select').multipleSelect('refresh');
						$('.gt-select-search').multipleSelect('refresh');
					}
							
				});
				//$.fn.matchHeight._update()
				$('li.product .product-content').matchHeight();

		    },
		    error: function(errorThrown){
			    console.log(errorThrown);
			}
	});
}

const minFrequencyCount = (arr, num) => {
   return arr.reduce((acc, val) => {
      let {count} = acc;
      if(val >= num){
         count++;
      };
     
      return {count};
   }, {
      count: 0
   });
};

const maxFrequencyCount = (arr, num) => {
   return arr.reduce((acc, val) => {
      let {count} = acc;
      if(val <= num){
         count++;
      };
     
      return {count};
   }, {
       count: 0
   });
};

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

function hasQueryParams(url) {
  return url.includes('?');
}

function insertParam(key, value) {
    key = encodeURIComponent(key);
    value = encodeURIComponent(value);

    // kvp looks like ['key1=value1', 'key2=value2', ...]
    var kvp = document.location.search.substr(1).split('&');
    let i=0;

    for(; i<kvp.length; i++){
        if (kvp[i].startsWith(key + '=')) {
            let pair = kvp[i].split('=');
            pair[1] = value;
            kvp[i] = pair.join('=');
            break;
        }
    }

    if(i >= kvp.length){
        kvp[kvp.length] = [key,value].join('=');
    }

    // can return this or...
    let params = kvp.join('&');

    // reload page with new params
    document.location.search = params;
}
	
/*
	$( document ).on( 'click', '.gt-gridlist-toggle #list', function ( e ) {
		e.preventDefault();
		var $this = $( this );
		if ( $this.hasClass( 'active' ) ) {
			return false;
		}
		$( '.gt-gridlist-toggle #grid, .gt-gridlist-toggle #list' ).removeClass( 'active' );
		$this.addClass( 'active' );
		
		$( '.archive-products ul.products' ).removeClass('grid');
		$( '.archive-products ul.products' ).addClass('list');
	} );
	$( document ).on( 'click', '.gt-gridlist-toggle #grid', function ( e ) {
		e.preventDefault();
		var $this = $( this );
		if ( $this.hasClass( 'active' ) ) {
			return false;
		}
		$( '.gt-gridlist-toggle #grid, .gt-gridlist-toggle #list' ).removeClass( 'active' );
		$this.addClass( 'active' );
		
		$( '.archive-products ul.products' ).removeClass('list');
		$( '.archive-products ul.products' ).addClass('grid');
	} );
*/
	
});

