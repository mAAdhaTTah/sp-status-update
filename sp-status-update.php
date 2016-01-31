<?php
/**
 * Plugin Name: SP Status Update
 * Plugin URI:  https://github.com/mAAdhaTTah/sitepoint-status-update
 * Description: Post your own Facebook-like status updates
 * Version:     1.0.0
 */

class SP_Status_Update {

	/**
	 * Plugin instance.
	 *
	 * @var static
	 */
	protected static $instance;

	/**
	 * Retrieve the plugin instance.
	 *
	 * @return static
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Plugin constructor.
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'embed_head', array( $this, 'embed_styles' ) );
		add_filter( 'the_title', array( $this, 'remove_embed_title' ), 10, 2 );
		add_filter( 'the_excerpt_embed', array( $this, 'get_excerpt_embed' ), 10, 2 );
		add_action( 'embed_content', array( $this, 'embed_author' ) );
	}

	/**
	 * Register the Status Update custom post type.
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Status Updates', 'Post Type General Name', 'sp-status-update' ),
			'singular_name'         => _x( 'Status Update', 'Post Type Singular Name', 'sp-status-update' ),
			'menu_name'             => __( 'Status Update', 'sp-status-update' ),
			'name_admin_bar'        => __( 'Status Update', 'sp-status-update' ),
			'archives'              => __( 'Satus Update Archives', 'sp-status-update' ),
			'parent_item_colon'     => __( 'Parent Update:', 'sp-status-update' ),
			'all_items'             => __( 'All Updates', 'sp-status-update' ),
			'add_new_item'          => __( 'Add New Status Update', 'sp-status-update' ),
			'add_new'               => __( 'Add New', 'sp-status-update' ),
			'new_item'              => __( 'New Status Update', 'sp-status-update' ),
			'edit_item'             => __( 'Edit Status Update', 'sp-status-update' ),
			'update_item'           => __( 'Update Status Update', 'sp-status-update' ),
			'view_item'             => __( 'View Status Update', 'sp-status-update' ),
			'search_items'          => __( 'Search Status Updates', 'sp-status-update' ),
			'not_found'             => __( 'Not found', 'sp-status-update' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'sp-status-update' ),
			'featured_image'        => __( 'Featured Image', 'sp-status-update' ),
			'set_featured_image'    => __( 'Set featured image', 'sp-status-update' ),
			'remove_featured_image' => __( 'Remove featured image', 'sp-status-update' ),
			'use_featured_image'    => __( 'Use as featured image', 'sp-status-update' ),
			'insert_into_item'      => __( 'Insert into Status Update', 'sp-status-update' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Status Update', 'sp-status-update' ),
			'items_list'            => __( 'Status Updates list', 'sp-status-update' ),
			'items_list_navigation' => __( 'Status Updates list navigation', 'sp-status-update' ),
			'filter_items_list'     => __( 'Filter Status Updates list', 'sp-status-update' ),
		);
		$args = array(
			'label'                 => __( 'Status Update', 'sp-status-update' ),
			'description'           => __( 'Simple Status Update', 'sp-status-update' ),
			'labels'                => $labels,
			'supports'              => array( 'editor', 'author' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'sp_status_update', $args );
	}

	/**
	 * Embed the plugin's custom styles
	 */
	public function embed_styles() {
		if ( 'sp_status_update' !== get_post_type() ) {
			return;
		}

		echo <<<CSS
<style>
	.wp-embed-excerpt, .wp-embed-author {
		font-size: 24px;
		line-height: 24px;
		margin-bottom: 5px;
	}

	.wp-embed-author {
		float: right;
	}
</style>
CSS;
	}

	/**
	 * Remove the title from the Status Update oembed.
	 *
	 * @param string $title Post title.
	 * @param int    $id Post ID.
	 *
	 * @return string
	 */
	public function remove_embed_title( $title, $id ) {
		$post = get_post( $id );

		if ( is_embed() && 'sp_status_update' === $post->post_type ) {
			return '';
		}

		return $title;
	}

	/**
	 * Returns the custom excerpt for the custom post type.
	 *
	 * @param  string $output Default embed output.
	 * @return string         Customize embed output.
	 */
	public function get_excerpt_embed( $output ) {
		if ( 'sp_status_update' !== get_post_type() ) {
			return $output;
		}

		return get_the_content();
	}

	/**
	 * Add the author div to the embed iframe.
	 */
	public function embed_author() {
		if ( 'sp_status_update' !== get_post_type() ) {
			return;
		}

		$output = '<div class="wp-embed-author">';
		$output .= '&mdash; ';
		$output .= get_the_author();
		$output .= get_avatar( get_the_author_meta( 'ID' ), 20 );
		$output .= '</div>';

		echo $output;
	}
}

SP_Status_Update::instance();
