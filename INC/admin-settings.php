<?php
/*
* Add general options section in wc setting product tab
*/
function ppusunarc_add_general_options_section($sections)
    {
        $sections['price-per-unit-for-wc-product'] = esc_html__('Price per unit for wc product', 'price-per-unit-for-wc-product');
        return $sections;
    }
add_filter('woocommerce_get_sections_products', 'ppusunarc_add_general_options_section');



/*
* Add general options in price per unit section
*/
function ppusunarc_general_options($settings, $current_section){
    if ($current_section == 'price-per-unit-for-wc-product'){
        $custom_settings = array(
            array(
                'id' => 'ppusunarc_title',
                'name' => esc_html__('General options', 'price-per-unit-for-wc-product'),
                'desc' => '',
                'type' => 'title',
            ),
            array(
                'id' => 'ppusunarc_prefix',
                'name' => esc_html__('Price prefix', 'price-per-unit-for-wc-product'),
                'desc' => esc_html__("This text will be shown before every price text.", 'price-per-unit-for-wc-product'),
                'placeholder' => esc_html__('Example "From"', 'price-per-unit-for-wc-product'),
                'type' => 'text',
                'default' => '',
                'desc_tip' => true,
            ),
            array(
                'id' => 'ppusunarc_suffix',
                'name' => esc_html__('Price suffix', 'price-per-unit-for-wc-product'),
                'desc' => esc_html__("This text will be shown after every price text.", 'price-per-unit-for-wc-product'),
                'placeholder' => esc_html__('Example "Without Tax"', 'price-per-unit-for-wc-product'),
                'type' => 'text',
                'default' => '',
                'desc_tip' => true,
            ),
            array(
                'id' => 'ppusunarc_add_row_css',
                'name' => esc_html__('Styled price per unit', 'price-per-unit-for-wc-product'),
                'desc' => esc_html__('Price per unit will be displayed in italics with slightly smaller font size.', 'price-per-unit-for-wc-product'),
                'type' => 'checkbox',
                'default' => 'no',
                'desc_tip' => false,
            ),
            array(
                'id' => 'ppusunarc_custom_unit',
                'name' => esc_html__('Add custom unit', 'price-per-unit-for-wc-product'),
                'desc' => esc_html__("This will be shown just after price per unit.", 'price-per-unit-for-wc-product'),
                'css' => '',
                'class' => 'wc-enhanced-select',
                'type' => 'select',
                'default' => '',
                'options' => array(
                    'automatic' => esc_attr__('Automatic text - takes unit settings from product', 'price-per-unit-for-wc-product'),
                    '/g' => esc_attr__('/g', 'price-per-unit-for-wc-product'),
                    '/kg' => esc_attr__('/kg', 'price-per-unit-for-wc-product'),
                    '/lbs' => esc_attr__('/lbs', 'price-per-unit-for-wc-product'),
                    '/oz' => esc_attr__('/oz', 'price-per-unit-for-wc-product'),
                ),
                'desc_tip' => true,
            ),
            array(
                'id' => 'ppusunarc_cart_page',
                'name' => esc_html__('How to show price per unit on cart page', 'price-per-unit-for-wc-product'),
                'desc' => esc_html__('Behaviour of price per unit on cart page.', 'price-per-unit-for-wc-product'),
                'css' => '',
                'class' => 'wc-enhanced-select',
                'type' => 'select',
                'default' => '',
                'options' => array(
                    'not' => esc_attr__('Do not show price per unit', 'price-per-unit-for-wc-product'),
                    'add' => esc_attr__('Show price per unit always', 'price-per-unit-for-wc-product'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'ppusunarc_plugin_options'),
        );
        return $custom_settings;
    } else {
        return $settings;
    }
}
add_filter('woocommerce_get_settings_products', 'ppusunarc_general_options', 10, 2);