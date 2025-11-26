# Gravity Forms Field ID and Conditional Logic Display

Display field IDs and conditional logic dependencies in the Gravity Forms editor with live updates and clickable badges.

## Features

- **Field ID Badges**: Display field IDs inline with field labels in the form editor
- **Conditional Logic Badges**: Show which fields are referenced in conditional logic rules
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
- **Arrow symbol (→)** when a field has conditional logic
- **COND badges** showing which fields are referenced in the conditional logic
- **ALL/ANY badges** when multiple conditions are configured

### Interactions

- **Hover** over COND badges to see the full conditional logic rule in plain language
- **Click** on COND badges to open the conditional logic settings for that field
- **Hover** over ALL/ANY badges to see their meaning

## Translations

The plugin is translation-ready and includes:

- English (default)
- French (Français)

### Adding Your Own Translation

1. Use the `.pot` file in `/languages/` as a template
2. Create a `.po` file for your language (e.g., `gf-field-id-cond-display-de_DE.po` for German)
3. Translate all strings
4. Compile to `.mo` using Poedit or msgfmt:
   ```bash
   msgfmt -o gf-field-id-cond-display-de_DE.mo gf-field-id-cond-display-de_DE.po
   ```
5. Place both `.po` and `.mo` files in the `/languages/` folder

## Badge Types

### Field ID Badge
- **Display**: `ID: 1`
- **Color**: Light blue/purple
- **Purpose**: Shows the field's unique identifier

### Conditional Logic Badges
- **Display**: `COND: 4` (where 4 is the referenced field ID)
- **Color**: Orange (for ALL logic) or Green (for ANY logic)
- **Purpose**: Shows which fields control this field's visibility
- **Interactive**: Click to open conditional logic settings
- **Tooltip**: Displays full rule in natural language

### Logic Type Badges
- **Display**: `ALL` or `ANY`
- **Color**: Matches the COND badge color
- **Purpose**: Indicates whether all or any conditions must be met
- **Tooltip**: "All conditions must be met" or "Any condition can be met"
