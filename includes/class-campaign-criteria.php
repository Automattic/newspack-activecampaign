<?php
/**
 * Campaign Criteria for ActiveCampaign's data.
 *
 * @package Newspack
 */

namespace Newspack\ActiveCampaign;

/**
 * Main Class.
 */
final class Campaign_Criteria {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', [ __CLASS__, 'register_criteria' ] );
		add_action( 'newspack_data_event_reader_logged_in', [ __CLASS__, 'pull_reader_score_data' ], 10, 2 );
		add_action( 'admin_init', [ __CLASS__, 'admin_init' ] );
	}

	/**
	 * Register criteria.
	 */
	public static function register_criteria() {
		if ( ! method_exists( 'Newspack_Popups_Criteria', 'register_criteria' ) ) {
			return;
		}
		$scores = get_option( 'newspack_campaigns_ac_scores' );
		if ( ! $scores ) {
			return;
		}
		foreach ( $scores as $score ) {
			// Bail if score is not active.
			if ( '1' !== $score['status'] ) {
				return;
			}
			$name = sprintf(
				/* translators: %s: score name */
				__( 'ActiveCampaign %s', 'newspack-bluelena-campaigns' ),
				$score['name']
			);
			\Newspack_Popups_Criteria::register_criteria(
				'ac_score_' . $score['id'],
				[
					'name'               => $name,
					'category'           => 'reader_engagement',
					'matching_function'  => 'range',
					'matching_attribute' => 'ac_score_' . $score['id'],
				]
			);
		}
	}

	/**
	 * Fetch the latest scores setup from ActiveCampaign and store in options.
	 */
	public static function update_available_scores() {
		// Get AC credentials from Newspack Newsletters.
		$url = get_option( 'newspack_newsletters_active_campaign_url' );
		$key = get_option( 'newspack_newsletters_active_campaign_key' );

		if ( ! $url || ! $key ) {
			return;
		}

		// Get the latest scores setup.
		$scores_res  = wp_safe_remote_get(
			$url . '/api/3/scores',
			array(
				'headers' => array(
					'Api-Token' => $key,
				),
			)
		);
		$scores_data = json_decode( wp_remote_retrieve_body( $scores_res ), true );
		if ( empty( $scores_data ) || ! isset( $scores_data['scores'] ) ) {
			return;
		}
		update_option( 'newspack_campaigns_ac_scores', $scores_data['scores'] );
	}

	/**
	 * Update available scores on wizard page load.
	 */
	public static function admin_init() {
		if ( ! isset( $_GET['page'] ) || 'newspack-popups-wizard' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		self::update_available_scores();
	}

	/**
	 * Pull ActiveCampaign's score values for a reader and set as reader data.
	 *
	 * @param int   $timestamp Timestamp.
	 * @param array $data      Data.
	 */
	public static function pull_reader_score_data( $timestamp, $data ) {
		// Bail if reader data is not available.
		if ( ! method_exists( 'Newspack\Reader_Data', 'update_item' ) ) {
			return;
		}

		// Get AC credentials from Newspack Newsletters.
		$url = get_option( 'newspack_newsletters_active_campaign_url' );
		$key = get_option( 'newspack_newsletters_active_campaign_key' );

		// Fetch the latest scores setup.
		self::update_available_scores();

		// Get contact ID.
		$contact_id_res  = wp_safe_remote_get(
			$url . '/api/3/contacts?email=' . urlencode( $data['email'] ),
			array(
				'headers' => array(
					'Api-Token' => $key,
				),
			)
		);
		$contact_id_data = json_decode( wp_remote_retrieve_body( $contact_id_res ), true );
		if ( empty( $contact_id_data ) || ! isset( $contact_id_data['contacts'] ) ) {
			return;
		}
		$contact_id = $contact_id_data['contacts'][0]['id'];

		// Get the contact's score values.
		$score_values_res  = \wp_safe_remote_get(
			$url . '/api/3/contacts/' . $contact_id . '/scoreValues',
			array(
				'headers' => array(
					'Api-Token' => $key,
				),
			)
		);
		$score_values_data = json_decode( wp_remote_retrieve_body( $score_values_res ), true );
		if ( empty( $score_values_data ) || ! isset( $score_values_data['scoreValues'] ) ) {
			return;
		}
		$score_values = $score_values_data['scoreValues'];

		foreach ( $score_values as $score ) {
			\Newspack\Reader_Data::update_item( $data['user_id'], 'ac_score_' . $score['score'], wp_json_encode( (int) $score['scoreValue'] ) );
		}
	}


}
new Campaign_Criteria();
