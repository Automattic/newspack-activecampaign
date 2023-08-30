<?php
/**
 * Plugin Name:     Newspack ActiveCampaign
 * Plugin URI:      https://newspack.com
 * Description:     ActiveCampaign integration for Newspack.
 * Author:          Automattic
 * License:         GPL2
 * Version:         1.0.0
 *
 * @package         Newspack
 */

namespace Newspack\ActiveCampaign;

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/includes/class-events.php';
require_once dirname( __FILE__ ) . '/includes/class-campaign-criteria.php';
