<?php
// Add a custom column to admin product list
function product_variations_total_quantity_column( $columns ) {
    $columns['size_qty'] = __("Stock Overview", "woocommerce");

    return $columns;
}
add_filter( 'manage_edit-product_columns', 'product_variations_total_quantity_column', 10, 1 );

// Display the data for this cutom column on admin product list
function product_variations_total_quantity_values( $column, $post_id ) {
    if( $column === 'size_qty' ) {
		$product = wc_get_product($post_id);

		if($product->is_type( 'variable' )){
			foreach ( $product->get_children() as $child_id ) {
				$i = 0;
				$variation = wc_get_product( $child_id );
				$re = '/\w*[A-Za-zΑ-Ωα-ωίϊΐόάέύϋΰήώΧρ]+:\w*/m'; //for greek letters
				$str = $variation->attribute_summary;
				$result = preg_replace($re, "", $str);
				if(($variation->attribute_summary) && ($variation->manage_stock == 'yes')){
					echo $result . ': ' . $variation->stock_quantity . '</br>';
				}

				if(($variation->attribute_summary) && ($variation->manage_stock == 'no')){
					echo $result . ': ' . $variation->stock_status . '</br>';
				}
			}
		}

		if($product->is_type( 'simple' )){
			if($product->manage_stock == 'yes'){
				echo 'Stock: ' . $product->stock_quantity; 
			}else{
				echo 'Stock: ' . $product->stock_status;
			}
		}
    }
}
add_action( 'manage_product_posts_custom_column', 'product_variations_total_quantity_values', 10, 2 );

//admin page css
function custom_changes_css() {
    echo '<style>
    	.column-size_qty{
        width: 180px;
    }
    </style>'; 
}
add_action('admin_head', 'custom_changes_css');