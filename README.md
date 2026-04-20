# Baris Store Body Class Module

A lightweight, production-ready Magento 2 module that automatically adds store code and normalized store name as CSS classes to the frontend `<body>` tag.

## Features

- ✅ Automatically adds store code as CSS class (e.g., `store-fr`)
- ✅ Automatically adds normalized store name as CSS class (e.g., `store-name-french-store`)
- ✅ Works on every frontend page
- ✅ Does not affect admin area
- ✅ No core file modifications
- ✅ Graceful error handling
- ✅ PHP 8.1+ compatible
- ✅ Magento 2.4.8+ compatible

## Installation

### Option 1: Using Composer (Recommended)

Add the repository to your Magento project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/barist26/magento2_bodyclass"
        }
    ]
}
```

Then install via composer:

```bash
composer require baris/magento2-store-bodyclass:dev-main
```

### Option 2: Manual Installation

1. **Copy the module** to your Magento installation:
   ```bash
   cp -r Baris/StoreBodyClass /path/to/magento2/app/code/Baris/
   ```

2. **Enable the module**:
   ```bash
   php bin/magento module:enable Baris_StoreBodyClass
   ```

### After Installation (Both Methods)

3. **Run setup upgrade**:
   ```bash
   php bin/magento setup:upgrade
   ```

4. **Optional: Recompile** (if in production mode):
   ```bash
   php bin/magento setup:di:compile
   ```

5. **Clear cache**:
   ```bash
   php bin/magento cache:flush
   ```

## How It Works

The module uses an **Observer** that listens to the `page_render_before` event on frontend pages:

1. Detects the current store code and name
2. Adds store code class: `store-<storecode>`
3. Adds normalized store name class: `store-name-<normalized-name>`
4. Classes are added to the page before rendering

## Usage Examples

### Store Code Classes

| Store Code | CSS Class Added |
|-----------|-----------------|
| `fr` | `store-fr` |
| `nl` | `store-nl` |
| `en` | `store-en` |

### Store Name Classes (Normalized)

| Store Name | CSS Class Added |
|-----------|-----------------|
| `French Store` | `store-name-french-store` |
| `Nederland / NL` | `store-name-nederland-nl` |
| `Ma Boutique FR!` | `store-name-ma-boutique-fr` |
| `Café Français` | `store-name-cafe-francais` |

### Example Body Tag

```html
<body class="cms-index-index catalog-product-view store-fr store-name-french-store">
```

## Normalization Rules

Store names are normalized as follows:

1. **Lowercase** all characters
2. **Replace** spaces and special characters with hyphens (`-`)
3. **Collapse** multiple consecutive hyphens into one
4. **Trim** leading and trailing hyphens

## CSS Usage Examples

```css
/* Style for French store */
body.store-fr {
    --primary-color: #0055cc;
}

/* Style for Dutch store */
body.store-nl {
    --primary-color: #ff6700;
}

/* Style by store name */
body.store-name-french-store {
    font-family: 'Helvetica', sans-serif;
}

/* Combine selectors */
body.store-fr.store-name-french-store {
    border: 2px solid #0055cc;
}
```

## Architecture

### Files Structure

```
magento2_bodyclass/ (Repository root)
├── Baris/StoreBodyClass/
│   ├── registration.php          # Module registration
│   ├── composer.json             # Package metadata
│   ├── etc/
│   │   ├── module.xml           # Module configuration
│   │   └── frontend/
│   │       └── events.xml       # Observer event declaration
│   └── Observer/
│       └── AddStoreBodyClass.php # Main observer class
├── README.md                     # Documentation
└── composer.json                 # Root composer config for distribution
```

When installed via composer, the module is placed in:
```
app/code/Baris/StoreBodyClass/
```

### How the Observer Works

The `AddStoreBodyClass` observer:

1. Listens to the `page_render_before` event (frontend only)
2. Skips execution in admin area
3. Gets the current store from `StoreManagerInterface`
4. Generates CSS classes from store code and name
5. Adds classes to the page config using `PageConfig::addBodyClass()`
6. Silently handles errors to prevent page breaks

## PHP Requirements

- PHP 8.1 or higher
- Magento 2.4.0 or higher (tested with 2.4.8)

## Magento Requirements

- `magento/framework` ^103.0.0 or ^104.0.0

## Development & Debugging

### Check if module is enabled

```bash
php bin/magento module:status Baris_StoreBodyClass
```

### Clear cache after changes

```bash
php bin/magento cache:clean
php bin/magento cache:flush
```

### Verify classes are added

Open the browser's developer tools (F12) and inspect the `<body>` tag to confirm the CSS classes are present.

## Troubleshooting

### Module not showing up

1. **Verify composer installation** (if using composer):
   ```bash
   composer show baris/magento2-store-bodyclass
   ```

2. **Verify files** are in `app/code/Baris/StoreBodyClass/`

3. **Clear compilation cache**:
   ```bash
   rm -rf var/generation/*
   rm -rf var/di/*
   ```

4. **Re-enable module**:
   ```bash
   php bin/magento module:enable Baris_StoreBodyClass
   php bin/magento setup:upgrade
   ```

### Classes not appearing on page

1. Check if module is enabled: `php bin/magento module:status`
2. Clear all caches: `php bin/magento cache:flush`
3. Check that you're viewing a **frontend** page (not admin)
4. Check browser console for JavaScript errors

### Special characters not normalized correctly

The module uses Unicode-aware regex patterns to handle:
- Accented characters (é, ñ, ü, etc.)
- Non-Latin scripts (Cyrillic, Arabic, etc.)
- Multiple special characters

## License

MIT License - Feel free to use and modify.

## Support

For issues or questions, please open an issue on GitHub.

---

**Created by:** Baris  
**Version:** 1.0.0  
**Last Updated:** 2026-04-20
