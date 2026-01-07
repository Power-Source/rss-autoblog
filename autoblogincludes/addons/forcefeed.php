<?php
/*
Addon Name: Erlaube Feed zu erzwingen
Description: Ermöglicht das Überschreiben der Feed-Validierung und das Erzwingen der Verarbeitung eines Feeds, auch wenn dieser einen falschen MIME-Typ hat. Dies kann bei der Kompatibilität für ungewöhnliche Feeds hilfreich sein. Mit Vorsicht verwenden.
Author: PSOURCE
Author URI: https://github.com/Power-Source
*/

class A_ForceFeedAddon extends Autoblog_Addon {

	/**
	 * Determines whether or not to force feed.
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 * @var boolean
	 */
	private $_force_feed = false;

	/**
	 * Constructor
	 *
	 * @since 4.0.0
	 *
	 * @access public
	 */
    public function __construct() {
		parent::__construct();

		$this->_add_action( 'autoblog_pre_process_feed', 'check_force_feed', 10, 3 );
		$this->_add_action( 'autoblog_feed_edit_form_end', 'add_feed_option', 12, 2 );
		$this->_add_action( 'wp_feed_options', 'enable_force_feed' );
	}

	/**
	 * Saves whether or not we need to force a feed.
	 *
	 * @since 4.0.0
	 * @action autoblog_pre_process_feed 10 2
	 *
	 * @access public
	 * @param int $feed_id The feed id.
	 * @param array $details The feed details.
	 */
	public function check_force_feed( $feed_id, $details ) {
		$this->_force_feed = !empty( $details['enableforcefeed'] ) && $details['enableforcefeed'] == 1;
	}

	/**
	 * Forces a feed if need be.
	 *
	 * @since 4.0.0
	 * @action wp_feed_options
	 *
	 * @access public
	 * @param SimplePie $feed The feed object.
	 */
	public function enable_force_feed( $feed ) {
		if ( $this->_force_feed ) {
			$feed->force_feed( true );
		}
	}

	/**
	 * Renders feed options.
	 *
	 * @since 4.0.0
	 * @action autoblog_feed_edit_form_end 10 2
	 *
	 * @access public
	 * @param string $key
	 * @param array $details The feed details.
	 */
	public function add_feed_option( $key, $details ) {
        $table = !empty( $details->feed_meta ) ? maybe_unserialize( $details->feed_meta ) : array();

		$this->_render_block_header( esc_html__( 'Erzwinge Feed', 'autoblogtext' ) );

		$this->_render_block_element(
			esc_html__( 'Aktiviere Feed erzwingen', 'autoblogtext' ),
			sprintf( '<input type="checkbox" name="abtble[enableforcefeed]" value="1"%s>', checked( isset( $table['enableforcefeed'] ) && $table['enableforcefeed'] == '1', true, false ) )
		);
    }

}

$a_forcefeedaddon = new A_ForceFeedAddon();