# Headless Menu for TYPO3

This TYPO3 extension provides a JSON endpoint for generating menus. It is particularly useful for Webcomponents or headless frontend integrations where you need a slim, JSON-based representation of your page navigation.

## Features

- Generates a menu as JSON.
- Custom `WhitelistMenuFieldsProcessor` to filter exactly which fields are exposed in the JSON output.
- Configurable menu depth and field selection via TypoScript.
- Lightweight and cacheable via TYPO3's native page caching.

## Installation

Install via composer:

```bash
composer require 4viewture/headless-menu
```

Then, include the TypoScript template in your TYPO3 site configuration.

## Usage

### JSON Endpoint

The extension provides a new page type. You can access the menu JSON by appending `type=1770960021` to your URL:

```
https://your-domain.com/?type=1770960021
```

By default, the menu is only generated for the site's root page (`site("rootPageId")`) to prevent unnecessary cache entries for every single page.

This extension is compatible with TYPO3 v12, v13, and v14 as it uses modern TypoScript conditions.

### TypoScript Configuration

The following TypoScript constants are available to customize the menu output:

| Constant | Default Value | Description |
|----------|---------------|-------------|
| `lib.tx_headlessmenu.menu.level` | `7` | How many levels deep the menu should be generated. |
| `lib.tx_headlessmenu.menu.fields` | `title,link,target,children` | Main fields to include for each menu item. |
| `lib.tx_headlessmenu.menu.dataFields` | `uid,slug,nav_title` | Specific fields from the raw record to include in the `data` section. |
| `lib.tx_headlessmenu.menu.childrenKey` | `children` | The key used for nesting child menu items. |
| `lib.tx_headlessmenu.menu.header.allowOrigin` | (empty) | Define `Access-Control-Allow-Origin` header. |
| `lib.tx_headlessmenu.menu.page.typeNum` | `1770960021` | The `typeNum` used for the JSON endpoint. |

## CORS Support

You can enable CORS by setting the `lib.tx_headlessmenu.menu.header.allowOrigin` constant. If set, it will also add `Access-Control-Allow-Methods: GET, OPTIONS`.

```typoscript
# Example
lib.tx_headlessmenu.menu.header.allowOrigin = *
```

## Data Processor

The extension includes a custom DataProcessor: `FourViewture\HeadlessMenu\DataProcessing\WhitelistMenuFieldsProcessor`.

It can be used in your own Fluid templates or TypoScript to filter menu arrays:

```typoscript
20 = FourViewture\HeadlessMenu\DataProcessing\WhitelistMenuFieldsProcessor
20 {
  as = menu
  fields = title,link,active
  dataFields = uid,nav_title
  childrenKey = children
}
```

## Authors

- **Kay Strobach** - [4viewture](https://www.4viewture.de)

## License

This project is licensed under the GPL-2.0+ License.
