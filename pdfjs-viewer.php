<?php

/**
 * Plugin Name: Simple PDF Viewer
 * Plugin URI: https://condixia.com
 * Author: Mahim Zaman (Condixia)
 * Author URI: https://mahimzaman.com
 * Description: Display PDFs using PDF.js via shortcode.
 * Version: 1.0
 */

if (! defined('ABSPATH')) {
    exit;
}

class SPV_PDFJS_Viewer
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('pdfjs_viewer', [$this, 'render_viewer']);
    }

    public function enqueue_assets()
    {

        // PDF.js CDN
        wp_enqueue_script(
            'pdfjs-lib',
            'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'spv-pdf-viewer',
            plugin_dir_url(__FILE__) . 'assets/js/pdf-viewer.js',
            ['pdfjs-lib'],
            '1.0',
            true
        );

        wp_enqueue_style(
            'spv-pdf-viewer',
            plugin_dir_url(__FILE__) . 'assets/css/pdf-viewer.css',
            [],
            '1.0'
        );
    }

    public function render_viewer($atts)
    {

        $atts = shortcode_atts(
            [
                'url' => '',
                'height' => '600'
            ],
            $atts
        );

        if (empty($atts['url'])) {
            return '<p>No PDF URL provided.</p>';
        }

        ob_start();
?>
        <div class="spv-pdf-wrapper" data-pdf="<?php echo esc_url($atts['url']); ?>">
            <canvas class="spv-pdf-canvas"></canvas>

            <div class="spv-controls">
                <button id="spv-prev">Prev</button>
                <button id="spv-next">Next</button>
            </div>
        </div>
<?php
        return ob_get_clean();
    }
}

new SPV_PDFJS_Viewer();
