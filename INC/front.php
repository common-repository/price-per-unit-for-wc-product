<?php

/*
* Display PPU options on shop page and product single page
*/
function ppusunarc_product_prefix_suffix( $price, $instance ){

	global $woocommerce;

	$ppusunarc_prefix       	= (get_option('ppusunarc_prefix')) ? get_option('ppusunarc_prefix') : '';
	$ppusunarc_suffix       	= (get_option('ppusunarc_suffix')) ? get_option('ppusunarc_suffix') : '';
	$ppusunarc_add_row_css      = (get_option('ppusunarc_add_row_css')) ? get_option('ppusunarc_add_row_css') : '';
	$ppusunarc_custom_unit      = (get_option('ppusunarc_custom_unit')) ? get_option('ppusunarc_custom_unit') : '';
	$ppusunarc_cart_page       	= (get_option('ppusunarc_cart_page')) ? get_option('ppusunarc_cart_page') : '';


	if (is_null($instance)){
		global $product;
	}else{
		$product = $instance;
	}


	if ($ppusunarc_add_row_css == 'yes') {
	  $sunarc_css = 'style="font-style: italic;font-size: 0.8em;"';
	}
	else{
	  $sunarc_css = '';
	}

	if (empty($ppusunarc_custom_unit) || $ppusunarc_custom_unit == 'automatic') {
	  $sunarc_unit = '/'.get_option('woocommerce_weight_unit');
	}
	else{
	  $sunarc_unit = $ppusunarc_custom_unit;
	}

	$product_type = $product->get_type();

	if ($product_type == 'simple') {
	    if ($product->is_on_sale()) {
	        $price_text = floatval($product->get_price());
	        if (!empty($price_text)) {
	            $price_text = wc_get_price_to_display($product, array('price' => $price_text));
	        }
	    }
	    else{
	      $price_text = $product->get_regular_price();
	    }


	    if ($product->get_weight()) {
	      $ppu = $price_text / $product->get_weight();
	      $default  = $price;
	      $price    = $ppusunarc_prefix.' ';
	      $price    .= $default;
	      $price    .= ' '.$ppusunarc_suffix;
	      if ($product->is_on_sale()) {
	          $price_text = floatval($product->get_regular_price());
	          if (!empty($price_text)) {
	              $regular_price1 = wc_get_price_to_display($product, array('price' => $price_text));
	              $regular_price = $regular_price1 / $product->get_weight();
	          }
	          $price    .= '<br><del><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'. wc_price($regular_price) . '/'.get_option('woocommerce_weight_unit').'</bdi></span></del><ins><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'.wc_price($ppu).$sunarc_unit.'</bdi></span></ins>';
	      }
	      else{
	        $price    .= '<br><ins><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'.wc_price($ppu).$sunarc_unit.'</bdi></span></ins>';
	      }
	      
	      return $price;
	    }
	    else{
	      $default  = $price;
	      $price    = $ppusunarc_prefix.' ';
	      $price    .= $default;
	      $price    .= ' '.$ppusunarc_suffix;
	      return $price;
	    }

	 }

	if ($product_type == 'variable') {

		$variations = $product->get_available_variations();
		$num_of_variants = count($variations);
		if ($num_of_variants > 0) {
		    $parent_product_weight = $product->get_weight();
		    foreach($variations as $value){
		        $var_id = $value['variation_id'];
		        
		        $units=!empty($value['weight']) ? $value['weight'] : $parent_product_weight;
		        
		        if(!empty($units) && !empty($value['display_price'])){
		            $variable_recalc_prices[]= $value['display_price'] / floatval($units);
		        }
		    }
		    if (isset($variable_recalc_prices) && !empty($variable_recalc_prices)) {
		        asort($variable_recalc_prices);
		        $variable_price_min = reset($variable_recalc_prices);
		       
		        $variable_price_min = round($variable_price_min,$wc_decimals);
		        
		        $recalc_price = wc_price($variable_price_min);
		        
			    $variable_price_max = end($variable_recalc_prices);
			    
			    $variable_price_max = round($variable_price_max,$wc_decimals);
			    
			    if ($variable_price_min !== $variable_price_max) {
			        $recalc_price .= '–' . wc_price($variable_price_max);
			    }
		    }
		}


		if ($recalc_price) {
			$default  = $price;
			$price    = $ppusunarc_prefix.' ';
			$price    .= $default;
			$price    .= ' '.$ppusunarc_suffix;

			$price    .= '<br><ins><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'.$recalc_price.$sunarc_unit.'</bdi></span></ins>';


			return $price;
	    }
	    else{
	      $default  = $price;
	      $price    = $ppusunarc_prefix.' ';
	      $price    .= $default;
	      $price    .= ' '.$ppusunarc_suffix;
	      return $price;
	    }

	}

return $price;

}
add_filter( 'woocommerce_get_price_html', 'ppusunarc_product_prefix_suffix', 10, 2 );



/*
* Display PPU options on cart page
*/
function ppusunarc_custom_cart_price($price_val, $product_data, $cart_key){
	global $woocommerce;
	$ppusunarc_prefix       	= (get_option('ppusunarc_prefix')) ? get_option('ppusunarc_prefix') : '';
	$ppusunarc_suffix       	= (get_option('ppusunarc_suffix')) ? get_option('ppusunarc_suffix') : '';
	$ppusunarc_add_row_css      = (get_option('ppusunarc_add_row_css')) ? get_option('ppusunarc_add_row_css') : '';
	$ppusunarc_custom_unit      = (get_option('ppusunarc_custom_unit')) ? get_option('ppusunarc_custom_unit') : '';
	$ppusunarc_cart_page       	= (get_option('ppusunarc_cart_page')) ? get_option('ppusunarc_cart_page') : '';

	if($ppusunarc_cart_page == 'add'){

	$product = wc_get_product($product_data['product_id']);
	
	if ($ppusunarc_add_row_css == 'yes') {
	  $sunarc_css = 'style="font-style: italic;font-size: 0.8em;"';
	}
	else{
	  $sunarc_css = '';
	}

	if (empty($ppusunarc_custom_unit) || $ppusunarc_custom_unit == 'automatic') {
	  $sunarc_unit = '/'.get_option('woocommerce_weight_unit');
	}
	else{
	  $sunarc_unit = $ppusunarc_custom_unit;
	}

	$product_type = $product->get_type();

	if ($product_type == 'simple') {

		if ($product->is_on_sale()) {
		    $price_text = floatval($product->get_price());
		    if (!empty($price_text)) {
		        $price_text = wc_get_price_to_display($product, array('price' => $price_text));
		    }
		}
		else{
		  $price_text = $product->get_regular_price();
		}

		
		
		if ($product->get_weight()) {
		  $ppu = $price_text / $product->get_weight();
		  $default  = $price_val;
		  $price    .= $default;
		  if ($product->is_on_sale()) {
		      $price_text = floatval($product->get_regular_price());
		      if (!empty($price_text)) {
		          $regular_price1 = wc_get_price_to_display($product, array('price' => $price_text));
		          $regular_price = $regular_price1 / $product->get_weight();
		      }
		      $price    .= '<br><del><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'. wc_price($regular_price) . '/'.get_option('woocommerce_weight_unit').'</bdi></span></del><ins><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'.wc_price($ppu).$sunarc_unit.'</bdi></span></ins>';
		  }
		  else{
		    $price    .= '<br><ins><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'.wc_price($ppu).$sunarc_unit.'</bdi></span></ins>';
		  }
		  
		  echo $price;
		}
		else{
		  $default  = $price;
		  //$price    = $ppusunarc_prefix.' ';
		  $price    .= $default;
		  //$price    .= ' '.$ppusunarc_suffix;
		  //print_r($price);die;
		  echo $price;
		}

	  }
	  elseif($product_type == 'variable'){
	  	$variations = $product->get_available_variations();
	  	$num_of_variants = count($variations);
	  	if ($num_of_variants > 0) {
	  	    $parent_product_weight = $product->get_weight();
	  	    foreach($variations as $value){
	  	        $var_id = $value['variation_id'];
	  	        if($product_data['variation_id'] == $var_id){
		  	        $units=!empty($value['weight']) ? $value['weight'] : $parent_product_weight;
		  	        
		  	        if(!empty($units) && !empty($value['display_price'])){
		  	            $variable_recalc_prices[]= $value['display_price'] / floatval($units);
		  	        }
	  	        }
	  	    }
	  	    if (isset($variable_recalc_prices) && !empty($variable_recalc_prices)) {
	  	        asort($variable_recalc_prices);
	  	        $variable_price_min = reset($variable_recalc_prices);
	  	       
	  	        $variable_price_min = round($variable_price_min,$wc_decimals);
	  	        
	  	        $recalc_price = wc_price($variable_price_min);
	  	        
  	            $variable_price_max = end($variable_recalc_prices);
  	            
  	            $variable_price_max = round($variable_price_max,$wc_decimals);
  	            
  	            if ($variable_price_min !== $variable_price_max) {
  	                $recalc_price .= '–' . wc_price($variable_price_max);
  	            }
	  	        
	  	    }
	  	}

	  	if ($recalc_price) {
	  	      $default  = $price_val;
	  	      //$price    = $ppusunarc_prefix.' ';
	  	      $price    .= $default;
	  	      //$price    .= ' '.$ppusunarc_suffix;
	  	      
	  	      $price    .= '<br><ins><span '.$sunarc_css.' class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"></span>'.$recalc_price.$sunarc_unit.'</bdi></span></ins>';
	  	      
	  	      
	  	      echo $price;
	  	    }
	  	    else{
	  	      $default  = $price_val;
	  	      //$price    = $ppusunarc_prefix.' ';
	  	      $price    .= $default;
	  	      //$price    .= ' '.$ppusunarc_suffix;
	  	      //print_r($price);die;
	  	      echo $price;
	  	    }
	  }
	  else{
	  	echo $price_val;
	  }

	}
	else{
		echo $price_val;
	}
	
}
add_filter('woocommerce_cart_item_price', 'ppusunarc_custom_cart_price', 10, 3);