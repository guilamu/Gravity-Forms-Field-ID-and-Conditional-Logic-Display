<?php
/**
 * Plugin Name: Gravity Forms Field ID and Conditional Logic Display
 * Description: Display field IDs and conditional logic dependencies in the Gravity Forms editor with live updates and clickable badges
 * Version: 0.9.3
 * Text Domain: gf-field-id-cond-display
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'GF_FIELD_ID_COND_VERSION', '1.0.0' );
define( 'GF_FIELD_ID_COND_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GF_FIELD_ID_COND_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin textdomain for translations
 */
function gf_field_id_cond_load_textdomain() {
    load_plugin_textdomain(
        'gf-field-id-cond-display',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
}
add_action( 'plugins_loaded', 'gf_field_id_cond_load_textdomain' );

/**
 * Enqueue admin styles - DUAL APPROACH for reliability
 */
function gf_field_id_cond_enqueue_styles() {
    $current_screen = get_current_screen();

    // Only load on Gravity Forms editor pages
    if ( $current_screen && strpos( $current_screen->id, 'gf_edit_forms' ) !== false ) {
        wp_enqueue_style(
            'gf-field-id-cond-display',
            GF_FIELD_ID_COND_PLUGIN_URL . 'assets/css/gf-field-id-cond-display.css',
            array(),
            GF_FIELD_ID_COND_VERSION
        );
    }
}
// Hook with EARLY priority (5 instead of default 10)
add_action( 'admin_enqueue_scripts', 'gf_field_id_cond_enqueue_styles', 5 );

/**
 * Fallback: Output CSS directly in admin_head if enqueue doesn't work
 */
function gf_field_id_cond_inline_css_fallback() {
    $current_screen = get_current_screen();

    // Only on Gravity Forms editor pages
    if ( $current_screen && strpos( $current_screen->id, 'gf_edit_forms' ) !== false ) {
        $css_url = GF_FIELD_ID_COND_PLUGIN_URL . 'assets/css/gf-field-id-cond-display.css';
        echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '?ver=' . GF_FIELD_ID_COND_VERSION . '" type="text/css" media="all" />' . "\n";
    }
}
add_action( 'admin_head', 'gf_field_id_cond_inline_css_fallback', 1 );

/**
 * Output translations and plugin URL INLINE before loading external JavaScript
 * This is the same approach as the original single-file plugin (which worked!)
 */
add_action( 'gform_editor_js', function() {
    ?>
    <script type="text/javascript">
    // Translations object - loaded BEFORE external JS file
    var gfFieldIdCondTranslations = {
        currentTime: <?php echo json_encode( __( 'Current Time', 'gf-field-id-cond-display' ) ); ?>,
        currentDate: <?php echo json_encode( __( 'Current Date', 'gf-field-id-cond-display' ) ); ?>,
        yesterday: <?php echo json_encode( __( 'Yesterday', 'gf-field-id-cond-display' ) ); ?>,
        tomorrow: <?php echo json_encode( __( 'Tomorrow', 'gf-field-id-cond-display' ) ); ?>,
        lastWeek: <?php echo json_encode( __( 'Last Week', 'gf-field-id-cond-display' ) ); ?>,
        nextWeek: <?php echo json_encode( __( 'Next Week', 'gf-field-id-cond-display' ) ); ?>,
        lastMonth: <?php echo json_encode( __( 'Last Month', 'gf-field-id-cond-display' ) ); ?>,
        nextMonth: <?php echo json_encode( __( 'Next Month', 'gf-field-id-cond-display' ) ); ?>,
        lastYear: <?php echo json_encode( __( 'Last Year', 'gf-field-id-cond-display' ) ); ?>,
        nextYear: <?php echo json_encode( __( 'Next Year', 'gf-field-id-cond-display' ) ); ?>,
        field: <?php echo json_encode( __( 'field', 'gf-field-id-cond-display' ) ); ?>,
        thisField: <?php echo json_encode( __( 'This field', 'gf-field-id-cond-display' ) ); ?>,
        willBeDisplayedIf: <?php echo json_encode( __( 'will be displayed if', 'gf-field-id-cond-display' ) ); ?>,
        willBeHiddenIf: <?php echo json_encode( __( 'will be hidden if', 'gf-field-id-cond-display' ) ); ?>,
        isEmpty: <?php echo json_encode( __( 'is empty', 'gf-field-id-cond-display' ) ); ?>,
        isNotEmpty: <?php echo json_encode( __( 'is not empty', 'gf-field-id-cond-display' ) ); ?>,
        hasConditionalLogic: <?php echo json_encode( __( 'Has conditional logic', 'gf-field-id-cond-display' ) ); ?>,
        usedInConditionalLogic: <?php echo json_encode( __( 'Used in conditional logic', 'gf-field-id-cond-display' ) ); ?>,
        usedAsConditionIn: <?php echo json_encode( __( 'Used as condition in', 'gf-field-id-cond-display' ) ); ?>,
        allConditionsMustBeMet: <?php echo json_encode( __( 'All conditions must be met', 'gf-field-id-cond-display' ) ); ?>,
        anyConditionCanBeMet: <?php echo json_encode( __( 'Any condition can be met', 'gf-field-id-cond-display' ) ); ?>,
        operators: {
            is: <?php echo json_encode( __( 'is', 'gf-field-id-cond-display' ) ); ?>,
            isnot: <?php echo json_encode( __( 'is not', 'gf-field-id-cond-display' ) ); ?>,
            greaterThan: <?php echo json_encode( __( 'is greater than', 'gf-field-id-cond-display' ) ); ?>,
            lessThan: <?php echo json_encode( __( 'is less than', 'gf-field-id-cond-display' ) ); ?>,
            greaterThanOrEqual: <?php echo json_encode( __( 'is greater than or equal to', 'gf-field-id-cond-display' ) ); ?>,
            lessThanOrEqual: <?php echo json_encode( __( 'is less than or equal to', 'gf-field-id-cond-display' ) ); ?>,
            contains: <?php echo json_encode( __( 'contains', 'gf-field-id-cond-display' ) ); ?>,
            startsWith: <?php echo json_encode( __( 'starts with', 'gf-field-id-cond-display' ) ); ?>,
            endsWith: <?php echo json_encode( __( 'ends with', 'gf-field-id-cond-display' ) ); ?>,
            isIn: <?php echo json_encode( __( 'is in', 'gf-field-id-cond-display' ) ); ?>,
            isNotIn: <?php echo json_encode( __( 'is not in', 'gf-field-id-cond-display' ) ); ?>
        }
    };

    // Pass plugin URL to JavaScript for the randomize.png image
    var gfFieldIdCondPluginUrl = '<?php echo GF_FIELD_ID_COND_PLUGIN_URL; ?>';
    </script>
    <?php
}, 5 ); // Priority 5 - runs BEFORE the script enqueue (priority 10)

/**
 * Enqueue external JavaScript file
 */
function gf_field_id_cond_enqueue_scripts() {
    $current_screen = get_current_screen();

    // Only load on Gravity Forms editor pages
    if ( $current_screen && strpos( $current_screen->id, 'gf_edit_forms' ) !== false ) {
        wp_enqueue_script(
            'gf-field-id-cond-display',
            GF_FIELD_ID_COND_PLUGIN_URL . 'assets/js/gf-field-id-cond-display.js',
            array( 'jquery' ),
            GF_FIELD_ID_COND_VERSION,
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'gf_field_id_cond_enqueue_scripts', 10 );


/**
 * Display field IDs and conditional logic badges in form editor
 */
add_filter( 'gform_field_content', function( $content, $field ) {
    if ( ! GFCommon::is_form_editor() ) {
        return $content;
    }

    // Build the initial badges HTML with a container
    $badges = sprintf( '<span class="gw-field-badges" data-field-id="%d">', $field->id );
    $badges .= sprintf( '<span class="gw-inline-field-id">%s</span>', 
        sprintf( esc_html__( 'ID: %d', 'gf-field-id-cond-display' ), $field->id ) 
    );
    $badges .= '</span>';

    // Insert badges after label or legend
    $search = '<\/label>|<\/legend>';
    $replace = sprintf( '\0 %s', $badges );
    $content = preg_replace( "/$search/", $replace, $content, 1 );

    return $content;
}, 10, 2 );
