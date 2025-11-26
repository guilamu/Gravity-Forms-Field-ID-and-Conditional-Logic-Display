<?php
/**
 * Plugin Name: Gravity Forms Field ID and Conditional Logic Display
 * Description: Display field IDs and conditional logic dependencies in the Gravity Forms editor with live updates and clickable badges
 * Version: 0.9
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
 * Display field IDs and conditional logic badges in form editor
 */
add_filter( 'gform_field_content', function( $content, $field ) {
    if ( ! GFCommon::is_form_editor() ) {
        return $content;
    }

    static $_gw_inline_field_id_style;
    if ( ! $_gw_inline_field_id_style ) {
        $content .= '
        <style>
            .gw-inline-field-id {
                background-color: #ecedf8;
                border: 1px solid #d5d7e9;
                border-radius: 40px;
                font-size: 0.6875rem;
                font-weight: 600;
                padding: 0.1125rem 0.4625rem;
                margin-bottom: 0.5rem;
                display: inline-block;
                vertical-align: middle;
                margin-right: 0.25rem;
            }
            .gw-cond-separator {
                color: #374151;
                font-size: 0.6875rem;
                line-height: 1;
                margin: 0 0.15rem;
                display: inline-block;
                vertical-align: middle;
                font-weight: 700;
                padding-bottom: 0.5rem;
            }
            .gw-cond-field-id {
                background-color: #fff4e6;
                border: 1px solid #ffb347;
                border-radius: 40px;
                font-size: 0.6875rem;
                font-weight: 600;
                padding: 0.1125rem 0.4625rem;
                margin-bottom: 0.5rem;
                display: inline-block;
                vertical-align: middle;
                margin-right: 0.25rem;
                cursor: pointer;
                position: relative;
                transition: all 0.2s ease;
                z-index: 9999;
            }
            .gw-cond-field-id:hover {
                background-color: #ffe4cc;
                border-color: #ff9f2e;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                z-index: 99999999;
            }
            .gw-cond-field-id-any {
                background-color: #e6f7ed;
                border: 1px solid #47c274;
            }
            .gw-cond-field-id-any:hover {
                background-color: #d1f2dd;
                border-color: #3ab366;
            }
            .gw-logic-type-all {
                background-color: #fff4e6;
                border: 1px solid #ffb347;
                border-radius: 3px;
                position: relative;
                cursor: help;
                z-index: 9999;
            }
            .gw-logic-type-any {
                background-color: #e6f7ed;
                border: 1px solid #47c274;
                border-radius: 3px;
                position: relative;
                cursor: help;
                z-index: 9999;
            }
            .gw-logic-type-all:hover,
            .gw-logic-type-any:hover {
                z-index: 99999999;
            }
            .gw-logic-type-all:hover::after,
            .gw-logic-type-any:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background-color: #333;
                color: #fff;
                padding: 0.5rem 0.75rem;
                border-radius: 4px;
                white-space: nowrap;
                font-size: 0.75rem;
                margin-bottom: 0.5rem;
                z-index: 99999999 !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                pointer-events: none;
            }
            .gw-logic-type-all:hover::before,
            .gw-logic-type-any:hover::before {
                content: "";
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                border: 6px solid transparent;
                border-top-color: #333;
                z-index: 99999999 !important;
                pointer-events: none;
            }
            .gw-cond-field-id:hover::after {
                content: attr(data-tooltip);
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background-color: #333;
                color: #fff;
                padding: 0.5rem 0.75rem;
                border-radius: 4px;
                white-space: nowrap;
                font-size: 0.75rem;
                margin-bottom: 0.5rem;
                z-index: 99999999 !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                pointer-events: none;
            }
            .gw-cond-field-id:hover::before {
                content: "";
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                border: 6px solid transparent;
                border-top-color: #333;
                z-index: 99999999 !important;
                pointer-events: none;
            }
        </style>';
        $_gw_inline_field_id_style = true;
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

/**
 * Add JavaScript for conditional logic badges
 */
add_action( 'gform_editor_js', function() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Translations object
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
            isEmpty: <?php echo json_encode( __( 'is empty', 'gf-field-id-cond-display' ) ); ?>,
            isNotEmpty: <?php echo json_encode( __( 'is not empty', 'gf-field-id-cond-display' ) ); ?>,
            hasConditionalLogic: <?php echo json_encode( __( 'Has conditional logic', 'gf-field-id-cond-display' ) ); ?>,
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

        // Special field identifiers mapping (GravityPerks Conditional Logic Dates, etc.)
        var specialFieldLabels = {
            '_gpcld_current_time': gfFieldIdCondTranslations.currentTime,
            '_gpcld_current_date': gfFieldIdCondTranslations.currentDate,
            '_gpcld_yesterday': gfFieldIdCondTranslations.yesterday,
            '_gpcld_tomorrow': gfFieldIdCondTranslations.tomorrow,
            '_gpcld_last_week': gfFieldIdCondTranslations.lastWeek,
            '_gpcld_next_week': gfFieldIdCondTranslations.nextWeek,
            '_gpcld_last_month': gfFieldIdCondTranslations.lastMonth,
            '_gpcld_next_month': gfFieldIdCondTranslations.nextMonth,
            '_gpcld_last_year': gfFieldIdCondTranslations.lastYear,
            '_gpcld_next_year': gfFieldIdCondTranslations.nextYear
        };

        // Function to get field label or admin label by field ID
        function getFieldDisplayLabel(fieldId) {
            if (typeof fieldId === 'string' && specialFieldLabels[fieldId]) {
                return specialFieldLabels[fieldId];
            }

            if (typeof form === 'undefined') return gfFieldIdCondTranslations.field + ' ' + fieldId;

            var field = GetFieldById(fieldId);
            if (field) {
                return field.adminLabel || field.label || gfFieldIdCondTranslations.field + ' ' + fieldId;
            }
            return gfFieldIdCondTranslations.field + ' ' + fieldId;
        }

        // Function to open conditional logic settings for a field
        function openConditionalLogicSettings(fieldId) {
            var field = GetFieldById(fieldId);
            if (!field) return;

            var $field = $('#field_' + fieldId);
            if ($field.length) {
                $field.trigger('click');

                setTimeout(function() {
                    var $condLogicButton = $('.conditional_logic_accordion__toggle_button');

                    if ($condLogicButton.length) {
                        var $accordion = $condLogicButton.closest('.conditional_logic_accordion');
                        var isOpen = $accordion && $accordion.hasClass('conditional_logic_accordion--open');

                        if (!isOpen) {
                            $condLogicButton[0].click();
                        }

                        setTimeout(function() {
                            $condLogicButton[0].scrollIntoView({ 
                                behavior: 'smooth', 
                                block: 'nearest' 
                            });
                        }, 100);
                    }
                }, 300);
            }
        }

        // Function to update conditional logic badges
        function updateConditionalBadges(specificFieldId) {
            if (typeof form === 'undefined') return;

            var selector = specificFieldId 
                ? '.gw-field-badges[data-field-id="' + specificFieldId + '"]'
                : '.gw-field-badges';

            $(selector).each(function() {
                var $container = $(this);
                var fieldId = parseInt($container.data('field-id'));
                var field = GetFieldById(fieldId);

                if (!field) return;

                $container.find('.gw-cond-field-id, .gw-cond-separator, .gw-inline-field-id:not(:first)').remove();

                if (field.conditionalLogic && field.conditionalLogic.rules && field.conditionalLogic.rules.length > 0) {
                    var rules = field.conditionalLogic.rules;
                    var logicType = field.conditionalLogic.logicType || 'all';

                    var currentFieldLabel = field.adminLabel || field.label || gfFieldIdCondTranslations.thisField;

                    var separator = $('<span></span>')
                        .addClass('gw-cond-separator')
                        .attr('title', gfFieldIdCondTranslations.hasConditionalLogic)
                        .html('→');
                    $container.append(separator);

                    var operatorMap = {
                        'is': gfFieldIdCondTranslations.operators.is,
                        'isnot': gfFieldIdCondTranslations.operators.isnot,
                        '>': gfFieldIdCondTranslations.operators.greaterThan,
                        '<': gfFieldIdCondTranslations.operators.lessThan,
                        '>=': gfFieldIdCondTranslations.operators.greaterThanOrEqual,
                        '<=': gfFieldIdCondTranslations.operators.lessThanOrEqual,
                        'contains': gfFieldIdCondTranslations.operators.contains,
                        'starts_with': gfFieldIdCondTranslations.operators.startsWith,
                        'ends_with': gfFieldIdCondTranslations.operators.endsWith,
                        'greater_than': gfFieldIdCondTranslations.operators.greaterThan,
                        'less_than': gfFieldIdCondTranslations.operators.lessThan,
                        'is_in': gfFieldIdCondTranslations.operators.isIn,
                        'is_not_in': gfFieldIdCondTranslations.operators.isNotIn
                    };

                    var badgeClass = 'gw-cond-field-id';
                    if (logicType.toLowerCase() === 'any') {
                        badgeClass += ' gw-cond-field-id-any';
                    }

                    rules.forEach(function(rule) {
                        var condFieldId = rule.fieldId;
                        var operator = rule.operator || 'is';
                        var value = rule.value;

                        var fieldLabel = getFieldDisplayLabel(condFieldId);
                        var operatorDisplay = operatorMap[operator] || operator;

                        var tooltip = currentFieldLabel + ' ' + gfFieldIdCondTranslations.willBeDisplayedIf + ' ' + fieldLabel + ' ' + operatorDisplay;

                        if (typeof value === 'undefined' || value === null || value === '') {
                            if (operator === 'is') {
                                tooltip = currentFieldLabel + ' ' + gfFieldIdCondTranslations.willBeDisplayedIf + ' ' + fieldLabel + ' ' + gfFieldIdCondTranslations.isEmpty;
                            } else if (operator === 'isnot') {
                                tooltip = currentFieldLabel + ' ' + gfFieldIdCondTranslations.willBeDisplayedIf + ' ' + fieldLabel + ' ' + gfFieldIdCondTranslations.isNotEmpty;
                            }
                        } else {
                            var escapedValue = $('<div/>').text(value).html();
                            tooltip += ' ' + escapedValue;
                        }

                        var badgeText = 'COND: ' + condFieldId;

                        var badge = $('<span></span>')
                            .addClass(badgeClass)
                            .attr('data-tooltip', tooltip)
                            .attr('data-field-id', fieldId)
                            .text(badgeText);

                        $container.append(badge);
                    });

                    if (rules.length > 1) {
                        var logicTypeDisplay = logicType.toUpperCase();
                        var logicTypeTooltip = logicType === 'all' 
                            ? gfFieldIdCondTranslations.allConditionsMustBeMet
                            : gfFieldIdCondTranslations.anyConditionCanBeMet;
                        var logicTypeClass = 'gw-inline-field-id gw-logic-type-' + logicType.toLowerCase();
                        var logicBadge = $('<span></span>')
                            .addClass(logicTypeClass)
                            .attr('data-tooltip', logicTypeTooltip)
                            .text(logicTypeDisplay);
                        $container.append(logicBadge);
                    }
                }
            });

            $('.gw-cond-field-id').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var fieldId = parseInt($(this).attr('data-field-id'));
                openConditionalLogicSettings(fieldId);
            });
        }

        setTimeout(updateConditionalBadges, 500);

        $(document).on('gform_load_field_settings', function(event, field, form) {
            setTimeout(function() {
                updateConditionalBadges();
            }, 100);
        });

        if (typeof gform !== 'undefined' && gform.addAction) {
            gform.addAction('gform_post_set_field_property', function(property, field, value, prevValue) {
                if (property === 'conditionalLogic' || property === 'enableConditionalLogic') {
                    updateConditionalBadges(field.id);
                }

                if (property === 'label' || property === 'adminLabel') {
                    updateConditionalBadges();
                }
            });
        }

        $(document).on('gform_field_conditional_logic_updated', updateConditionalBadges);

        $(document).on('click', '.gform-settings-panel__header-icon--close', function() {
            setTimeout(updateConditionalBadges, 300);
        });

        if (typeof gform !== 'undefined' && gform.addAction) {
            gform.addAction('gform_post_conditional_logic_field_action', function() {
                setTimeout(updateConditionalBadges, 100);
            });
        }

        var observer = new MutationObserver(function(mutations) {
            var shouldUpdate = false;
            mutations.forEach(function(mutation) {
                if (mutation.target.classList && 
                    (mutation.target.classList.contains('gform-settings-panel__content') ||
                     mutation.target.classList.contains('gfield_conditional_logic_rules_container'))) {
                    shouldUpdate = true;
                }
            });
            if (shouldUpdate) {
                setTimeout(updateConditionalBadges, 200);
            }
        });

        var formEditorTarget = document.querySelector('#gform_fields, .gform-form-editor');
        if (formEditorTarget) {
            observer.observe(formEditorTarget, {
                childList: true,
                subtree: true,
                attributes: false
            });
        }
    });
    </script>
    <?php
}, 10 );
