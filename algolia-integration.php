<?php

require_once __DIR__ . '/vendor/autoload.php';

use AlgoliaIntegration\wp\InitPlugin;

/**
* Plugin Name:     Algolia Integration
* Description:     Sync WP data into Algolia database.
* Text Domain:     algolia-integration
* Version:         1.0.0
* Requires PHP:    7.2
* Author:          Nolte
* Author URI:      https://www.wearenolte.com
* License:         GPL v2 or later
* License URI:     https://www.gnu.org/licenses/gpl-2.0.html
*
* @package         Algolia_Integration
*/

new InitPlugin();
