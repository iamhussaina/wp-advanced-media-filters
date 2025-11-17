<?php
/**
 * Core class for the Hussainas Media Filters feature.
 *
 * This class implements the Singleton pattern and handles all
 * interactions with WordPress hooks to add and process
 * the custom MIME type filter in the Media Library.
 *
 * @package    HussainasMediaFilters
 * @version     1.0.0
 * @author      Hussain Ahmed Shrabon
 * @license     GPL-2.0-or-later
 * @link        https://github.com/iamhussaina
 * @textdomain  hussainas
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Final Class Hussainas_Media_Filters.
 *
 * Declared as 'final' to prevent extension, ensuring the
 * Singleton pattern's integrity.
 */
final class Hussainas_Media_Filters {

	/**
	 * The single, static instance of this class.
	 *
	 * @var Hussainas_Media_Filters|null
	 */
	private static $instance = null;

	/**
	 * The unique ID for the filter's GET parameter.
	 *
	 * @var string
	 */
	private $filter_id = 'hussainas_mime_type_filter';

	/**
	 * Private constructor to force use of get_instance().
	 *
	 * This method is private to prevent direct instantiation.
	 * It sets up all the necessary action hooks for WordPress.
	 */
	private function __construct() {
		// Hook to add the filter dropdown to the media library page.
		add_action( 'restrict_manage_posts', array( $this, 'render_filter_dropdown' ) );

		// Hook to modify the main query based on the filter selection.
		add_action( 'pre_get_posts', array( $this, 'apply_query_filter' ) );
	}

	/**
	 * Provides a global access point to the single instance of the class.
	 *
	 * @return Hussainas_Media_Filters The one true instance.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Renders the custom MIME type filter dropdown.
	 *
	 * This method is hooked into 'restrict_manage_posts' and only
	 * executes on the 'upload.php' (Media Library) screen.
	 *
	 * @param string $post_type The post type being displayed (e.g., 'post', 'page', 'attachment').
	 * @return void
	 */
	public function render_filter_dropdown( $post_type ) {
		// We only want this filter to appear on the Media Library screen (post_type 'attachment').
		if ( 'attachment' !== $post_type ) {
			return;
		}

		// Fetch all distinct MIME types from the attachments.
		$mime_types = $this->get_available_mime_types();

		// Get the currently selected filter value, if any.
		$selected = ( isset( $_GET[ $this->filter_id ] ) ) ?
			sanitize_text_field( $_GET[ $this->filter_id ] ) :
			'';

		// Start output buffer.
		ob_start();
		?>
		<select name="<?php echo esc_attr( $this->filter_id ); ?>" id="<?php echo esc_attr( $this->filter_id ); ?>" class="postform">
			<option value="">
				<?php esc_html_e( 'All MIME Types', 'hussainas' ); ?>
			</option>
			<?php
			if ( ! empty( $mime_types ) ) {
				foreach ( $mime_types as $mime_type ) {
					// Check for null or empty string, as database might contain it.
					if ( empty( $mime_type ) ) {
						continue;
					}
					printf(
						'<option value="%1$s" %2$s>%3$s</option>',
						esc_attr( $mime_type ),
						selected( $selected, $mime_type, false ),
						esc_html( $mime_type )
					);
				}
			}
			?>
		</select>
		<?php
		// Echo the clean, buffered output.
		echo ob_get_clean();
	}

	/**
	 * Applies the filter to the main WordPress query.
	 *
	 * This method is hooked into 'pre_get_posts' and modifies
	 * the WP_Query object if our custom filter is set.
	 *
	 * @param WP_Query $query The main query object (passed by reference).
	 * @return void
	 */
	public function apply_query_filter( $query ) {
		// Check if we are in the admin, on the main query, and our filter is set.
		if (
			is_admin() &&
			$query->is_main_query() &&
			isset( $_GET[ $this->filter_id ] ) &&
			! empty( $_GET[ $this->filter_id ] )
		) {
			global $pagenow;

			// Ensure we are only modifying the 'upload.php' query.
			if ( 'upload.php' === $pagenow ) {
				$filter_value = sanitize_text_field( $_GET[ $this->filter_id ] );

				// Set the 'post_mime_type' parameter for the query.
				$query->set( 'post_mime_type', $filter_value );
			}
		}
	}

	/**
	 * Fetches all unique MIME types from the media library.
	 *
	 * Caches the result in a transient for performance.
	 *
	 * @global wpdb $wpdb
	 * @return array An array of unique MIME type strings.
	 */
	private function get_available_mime_types() {
		global $wpdb;

		// Try to get from cache first.
		$cache_key = 'hussainas_all_mime_types';
		$cached_types = get_transient( $cache_key );

		if ( false !== $cached_types ) {
			return $cached_types;
		}

		// Not in cache, query the database.
		// We only select distinct, non-empty MIME types.
		$sql = "
			SELECT DISTINCT post_mime_type
			FROM {$wpdb->posts}
			WHERE post_type = 'attachment'
			AND post_mime_type <> ''
			ORDER BY post_mime_type ASC
		";

		// get_col() is perfect for fetching a single column from multiple rows.
		$mime_types = $wpdb->get_col( $sql ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery

		// Save to transient, cache for 1 hour.
		set_transient( $cache_key, $mime_types, HOUR_IN_SECONDS );

		return $mime_types;
	}

	/**
	 * Prevent cloning of the instance.
	 */
	public function __clone() {}

	/**
	 * Prevent unserializing of the instance.
	 */
	public function __wakeup() {}
}
