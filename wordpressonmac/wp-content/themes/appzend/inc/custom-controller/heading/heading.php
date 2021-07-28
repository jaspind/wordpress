<?php
/**
 * Customizer Control: AppZend_Customize_Heading
 *
 * @subpackage  Controls
 * @since       1.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'AppZend_Customize_Heading' ) ) :
    /**
     * Custom Heading Control
     */
    class AppZend_Customize_Heading extends WP_Customize_Control {
        public $type = 'heading';
        /**
		 * enqueue css and scrpts
		 *
		 * @since  1.0.0
		 */
		public function enqueue() {
            wp_enqueue_style('appzend-heading-control', get_template_directory_uri() . '/inc/custom-controller/heading/heading.css', array(), '1.0.0');
        }
        public function render_content() {
            if (!empty($this->label)) :
                ?>
                <h3 class="appzend-accordion-section-title"><?php echo esc_html($this->label); ?></h3>
                <?php
            endif;
            if ($this->description) {
                ?>
                <span class="description customize-control-description">
                    <?php echo wp_kses_post($this->description); ?>
                </span>
                <?php
            }
        }
    }
endif;