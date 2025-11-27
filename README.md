# Gravity Forms Field ID and Conditional Logic Display
![Plugin Screenshot](https://github.com/guilamu/Gravity-Forms-Field-ID-and-Conditional-Logic-Display/blob/main/Screenshot.png)

Display field IDs and conditional logic dependencies in the Gravity Forms editor with live updates, clickable badges, and bidirectional dependency tracking.

## Features

- **Field ID Badges**: Display field IDs inline with field labels in the form editor
- **Conditional Logic Badges (DEPENDS ON)**: Show which fields are referenced in conditional logic rules (dependencies)
- **Reverse Dependencies (USED BY)**: Show which fields use the current field as a condition
- **Bidirectional Tracking**: See both forward and backward conditional logic relationships at a glance
- **Collapsible Badge Groups**: Click arrows to hide/show DEPENDS ON or USED BY badges independently
- **Live Updates**: Badges update automatically when you modify conditional logic settings
- **Clickable Badges**: Click COND badges to jump directly to the conditional logic settings
- **Tooltips**: Hover over badges for detailed information in plain English (or French)
- **Visual Logic Type Indicators**: ALL/ANY badges show the logic type for multiple conditions
- **Multilingual**: Fully translatable (English and French included)

## Installation

1. Upload the `gf-field-id-cond-display` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit any Gravity Form to see the field IDs and conditional logic badges

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher
- Gravity Forms plugin (any recent version)

## Usage

Once activated, the plugin automatically displays:

- **ID badges** next to each field label showing the field ID
- **Right arrow (→)** when a field has conditional logic (DEPENDS ON)
- **Left arrow (←)** when a field is used in other fields' conditional logic (USED BY)
- **COND badges** showing field dependencies in both directions
- **ALL/ANY badges** when multiple conditions are configured

### Interactions

- **Hover** over COND badges to see the full conditional logic rule in plain language
- **Click** on COND badges to open the conditional logic settings for the referenced field
- **Click** on the **right arrow (→)** to toggle visibility of DEPENDS ON badges
- **Click** on the **left arrow (←)** to toggle visibility of USED BY badges
- **Hover** over ALL/ANY badges to see their meaning
- **Hover** over arrow icons for quick tooltips

## Understanding Badge Directions

### DEPENDS ON (→)
Shows fields that **this field depends on**. For example:
- Field 1 has `→ COND: 2`
- Meaning: Field 1 will be displayed/hidden based on Field 2's value

### USED BY (←)
Shows fields that **depend on this field**. For example:
- Field 2 has `← COND: 1`
- Meaning: Field 2 is used as a condition in Field 1's logic

## Badge Types

### Field ID Badge
- **Display**: `ID: 1`
- **Color**: Light blue/purple
- **Purpose**: Shows the field's unique identifier
- **Always visible**: Cannot be collapsed

### DEPENDS ON Badges (→)
- **Display**: Right arrow followed by `COND: 4` (where 4 is the referenced field ID)
- **Color**: Orange (for ALL logic) or Green (for ANY logic)
- **Purpose**: Shows which fields control this field's visibility
- **Interactive**: Click badge to open that field's conditional logic settings
- **Collapsible**: Click the arrow to hide/show these badges
- **Tooltip**: Displays full rule in natural language (e.g., "This field will be displayed if Field Name is Value")

### USED BY Badges (←)
- **Display**: Left arrow followed by `COND: 1` (where 1 is the field ID that uses this field)
- **Color**: Blue
- **Purpose**: Shows which fields use this field as a condition in their logic
- **Interactive**: Click badge to open that field's conditional logic settings
- **Collapsible**: Click the arrow to hide/show these badges independently from DEPENDS ON
- **Tooltip**: Displays which field uses this as a condition (e.g., "Used as condition in: Field Name")

### Logic Type Badges
- **Display**: `ALL` or `ANY`
- **Color**: Orange (ALL) or Green (ANY) - matches COND badge color
- **Purpose**: Indicates whether all or any conditions must be met
- **Tooltip**: "All conditions must be met" or "Any condition can be met"
- **Note**: Only appears when a field has multiple conditional logic rules

## Practical Example

If you have:
- **Field 1**: Email address
- **Field 2**: Phone number (conditional: show if Field 1 is not empty)
- **Field 3**: Preferred contact (conditional: show if Field 1 is not empty OR Field 2 is not empty)

You will see:
- **Field 1**: `ID: 1` ← `COND: 2` ← `COND: 3`
  - Field 1 is used as a condition in both Field 2 and Field 3
- **Field 2**: `ID: 2` → `COND: 1` ← `COND: 3`
  - Field 2 depends on Field 1, and is used as a condition in Field 3
- **Field 3**: `ID: 3` → `COND: 1` → `COND: 2` `ANY`
  - Field 3 depends on both Field 1 and Field 2 with ANY logic

## Translations

The plugin is translation-ready and includes:

- English (default)
- French (Français)

### Adding Your Own Translation

1. Use the `.pot` file in `/languages/` as a template
2. Create a `.po` file for your language (e.g., `gf-field-id-cond-display-de_DE.po` for German)
3. Translate all strings
4. Compile to `.mo` using Poedit or msgfmt: msgfmt -o gf-field-id-cond-display-de_DE.mo gf-field-id-cond-display-de_DE.po
5. Place both `.po` and `.mo` files in the `/languages/` folder

## Changelog

### Version 0.9.3 - 2025-11-27

#### New Features
- **Bidirectional Dependency Tracking**: Added USED BY badges showing which fields use the current field as a condition
- **Independent Toggle Controls**: Click arrows to collapse/expand DEPENDS ON or USED BY badges independently
- **Visual Direction Indicators**: Mirrored arrows (→ for FROM, ← for TO) clearly indicate dependency direction
- **Color-Coded Badges**: Blue badges for USED BY distinguish from orange/green DEPENDS ON badges
- **Persistent Collapse State**: Badge visibility state is maintained during live updates

#### Improvements
- Enhanced tooltips for better clarity on reverse dependencies
- Improved click handling to prevent conflicts between arrow toggles and badge clicks
- Visual feedback (opacity change) for collapsed arrow states
- Updated translations with new strings for USED BY features

### Version 0.9 - 2025-11-26

#### Initial Release
- Display field IDs inline with field labels
- Show conditional logic dependencies as clickable badges
- Live updates when modifying conditional logic
- Tooltips with natural language descriptions
- Multilingual support (English and French)
- ALL/ANY logic type indicators
- Click badges to open conditional logic settings

## Support

For issues, feature requests, or contributions, please visit the [GitHub repository](https://github.com/guilamu/Gravity-Forms-Field-ID-and-Conditional-Logic-Display).

