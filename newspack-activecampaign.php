<?php
/**
 * Plugin Name:     Newspack ActiveCampaign
 * Plugin URI:      https://newspack.com
 * Description:     ActiveCampaign integration for Newspack.
 * Author:          Automattic
 * License:         GPL2
 * Text Domain:     newspack-activecampaign
 * Version:         0.1.0
 *
 * @package         Newspack
 */

namespace Newspack\ActiveCampaign;

defined( 'ABSPATH' ) || exit;

// Define NEWSPACK_ACTIVECAMPAIGN_PLUGIN_FILE.
if ( ! defined( 'NEWSPACK_ACTIVECAMPAIGN_PLUGIN_FILE' ) ) {
	define( 'NEWSPACK_ACTIVECAMPAIGN_PLUGIN_FILE', __FILE__ );
}

require_once dirname( __FILE__ ) . '/includes/class-updater.php';
require_once dirname( __FILE__ ) . '/includes/class-events.php';
require_once dirname( __FILE__ ) . '/includes/class-campaign-criteria.php';

new Updater(
	'newspack-activecampaign/newspack-activecampaign.php',
	NEWSPACK_ACTIVECAMPAIGN_PLUGIN_FILE,
	'Automattic/newspack-activecampaign'
);
