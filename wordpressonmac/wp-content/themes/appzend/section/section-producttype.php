<?php
if (!class_exists('woocommerce')) {
    return;
}

if( !function_exists('appzend_product_type_query')):
function appzend_product_type_query($product_type, $product_number = 5){
    $product_args = array();
    if($product_type == 'latest_product'){
        $product_label_custom = esc_html__('New', 'appzend');
        $product_args = array(
            'post_type' => 'product',
            'posts_per_page' => $product_number
        );
    }
    elseif($product_type == 'upsell_product'){
        $product_args = array(
            'post_type'         => 'product',
            'posts_per_page'    => 10,
            'meta_key'          => 'total_sales',
            'orderby'           => 'meta_value_num',
            'posts_per_page'    => $product_number
        );
    }
    elseif($product_type == 'feature_product'){
        $product_args = array(
            'post_type'        => 'product',  
            'tax_query' => array(
                    'relation' => 'AND',      
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN'
                )
            ), 
            'posts_per_page'   => $product_number   
        );
    }
    elseif($product_type == 'on_sale'){
        $product_args = array(
        'post_type'      => 'product',
        'meta_query'     => array(
            'relation' => 'OR',
            array( // Simple products type
                'key'           => '_sale_price',
                'value'         => 0,
                'compare'       => '>',
                'type'          => 'numeric'
            ),
            array( // Variable products type
                'key'           => '_min_variation_sale_price',
                'value'         => 0,
                'compare'       => '>',
                'type'          => 'numeric'
            )
        ));
    }
    return $product_args;
}
endif;
$prod_type = array(
    'latest_product'  => esc_html__('Latest Product', 'appzend'),
    'upsell_product'  => esc_html__('UpSell Product', 'appzend'),
    'feature_product' => esc_html__('Feature Product', 'appzend'),
    'on_sale'         => esc_html__('On Sale Product', 'appzend'),
);

$product_types      = get_theme_mod( 'appzend_producttype_category',"latest_product,upsell_product,feature_product,on_sale");
$product_types      = explode(',', $product_types);
$product_number     = get_theme_mod( 'appzend_producttype_no_of_product', 4 );
$column_number      = get_theme_mod( 'appzend_producttype_column', 3 );
$tab_style          = get_theme_mod( 'appzend_producttype_layout', 'tab_styleone' );
$layout             = get_theme_mod( 'appzend_producttype_category_view', 'grid' );
$css = array();
$css[] = 'product-hover-style1 woocommerce'; 
$css[] = 'producttype-wrap section-wrap';
$css[] = 'layout-'.esc_attr( $layout );
$type = ''; //get_theme_mod('appzend_producttype_bg_type');
$bg_video = ''; // get_theme_mod("appzend_producttype_bg_video", '1IaZy0sDLu0');

if( $type == "video-bg" &&  $bg_video):
    $video_data = 'data-property="{videoURL:\'' . $bg_video . '\', mobileFallbackImage:\'https://img.youtube.com/vi/' . $bg_video . '/maxresdefault.jpg\'}"';
else: 
    $video_data = '';
endif;
?>
<section id="cl-producttype-section" 
    class="cl-producttype-section cl-section  <?php echo implode(' ', $css); ?>" 
    <?php echo $video_data; ?>>
    <div class="cl-section-wrap"> 
        <div class="container">
            <div class="product-list-area section-content text-center"> 
                <?php get_appzend_headline('appzend_producttype'); ?>
                <?php if( count($product_types) != 1) : ?>
                    <div class="sparkletabs tabsblockwrap <?php echo esc_attr( $tab_style ); ?> clearfix">
                        <ul class="sparkletablinks">
                            <?php
                                if(!empty($product_types)){
                                    foreach ($product_types as $key => $storecat_id) {
                                    ?>
                                        <li><a class="btn btn-primary" id="<?php echo esc_attr( $storecat_id); ?>" data-noajax="1" href="#<?php echo esc_attr( $storecat_id); ?>"><?php echo esc_html( $prod_type[$storecat_id] ); ?></a></li>
                                    <?php
                                    }
                                }
                            ?>
                        </ul>


                        <div class="sparkletablinkscontent">
                            <div class="tabscontentwrap">
                                <div class="sparkletabproductarea">
                                    <?php 
                                    $count = 1;
                                    if( $product_types )
                                    foreach ($product_types as $key => $val) : ?>
                                        <ul id="<?php echo esc_attr($val); ?>" class="storeproductlist tabsproduct tab-content products <?php echo esc_attr($val); ?> grid grid-<?php echo esc_attr( $column_number ); ?> <?php if($layout == 'slider'){ echo esc_attr('storeslider owl-carousel'); } ?>" data-col="<?php echo esc_attr( $column_number ); ?>" data-style="<?php echo esc_attr( $layout ); ?>" <?php if( $count != 1): ?> style="display:none" <?php endif; ?>>
                                            <?php $count++;
                                                $query = new WP_Query(appzend_product_type_query($val, $product_number));
                                                if($query->have_posts()) { while($query->have_posts()) { $query->the_post();
                                            ?>
                                                <?php wc_get_template_part( 'content', 'product' ); ?>
                                            <?php } } wp_reset_postdata(); ?>
                                        </ul>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</section>