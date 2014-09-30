<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WP_Job_Manager_Writepanels {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ), 1, 2 );
		add_action( 'job_manager_save_job_listing', array( $this, 'save_job_listing_data' ), 20, 2 );
	}

	/**
	 * job_listing_fields function.
	 *
	 * @access public
	 * @return void
	 */
	public function job_listing_fields() {
		global $post;

		$current_user = wp_get_current_user();

		return apply_filters( 'job_manager_job_listing_data_fields', array(
			'_job_location' => array(
				'label' => __( 'Location', 'wp-job-manager' ),
				'placeholder' => __( 'e.g. "London"', 'wp-job-manager' ),
				'description' => __( 'Leave this blank if the location is not important', 'wp-job-manager' )
			),
			'_application' => array(
				'label'       => __( 'Application email/URL', 'wp-job-manager' ),
				'placeholder' => __( 'URL or email which applicants use to apply', 'wp-job-manager' ),
				'description' => __( 'This field is required for the "application" area to appear beneath the listing.', 'wp-job-manager' ),
				'value'       => ( $value = get_post_meta( $post->ID, '_application', true ) ) ? $value : $current_user->user_email
			),
			'_company_name' => array(
				'label' => __( 'Company name', 'wp-job-manager' ),
				'placeholder' => ''
			),
			'_company_website' => array(
				'label' => __( 'Company website', 'wp-job-manager' ),
				'placeholder' => ''
			),
			'_company_tagline' => array(
				'label' => __( 'Company tagline', 'wp-job-manager' ),
				'placeholder' => __( 'Brief description about the company', 'wp-job-manager' )
			),
			'_company_twitter' => array(
				'label' => __( 'Company Twitter', 'wp-job-manager' ),
				'placeholder' => '@yourcompany'
			),
			'_company_logo' => array(
				'label' => __( 'Company logo', 'wp-job-manager' ),
				'placeholder' => __( 'URL to the company logo', 'wp-job-manager' ),
				'type'  => 'file'
			),
			'_company_video' => array(
				'label' => __( 'Company video', 'wp-job-manager' ),
				'placeholder' => __( 'URL to the company video', 'wp-job-manager' ),
				'type'  => 'file'
			),
			'_filled' => array(
				'label' => __( 'Position filled?', 'wp-job-manager' ),
				'type'  => 'checkbox'
			),
			'_featured' => array(
				'label' => __( 'Feature this listing?', 'wp-job-manager' ),
				'type'  => 'checkbox',
				'description' => __( 'Featured listings will be sticky during searches, and can be styled differently.', 'wp-job-manager' )
			),
			'_job_expires' => array(
				'label'       => __( 'Expires', 'wp-job-manager' ),
				'placeholder' => __( 'yyyy-mm-dd', 'wp-job-manager' )
			),
			'_job_author' => array(
				'label' => __( 'Posted by', 'wp-job-manager' ),
				'type'  => 'author'
			)
		) );
	}

	/**
	 * add_meta_boxes function.
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_boxes() {
		global $wp_post_types;

		add_meta_box( 'job_listing_data', sprintf( __( '%s Data', 'wp-job-manager' ), $wp_post_types['job_listing']->labels->singular_name ), array( $this, 'job_listing_data' ), 'job_listing', 'normal', 'high' );
	}

	/**
	 * input_text function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_file( $key, $field ) {
		global $thepostid;

		if ( empty( $field['value'] ) ) {
			$field['value'] = get_post_meta( $thepostid, $key, true );
		}
		if ( empty( $field['placeholder'] ) ) {
			$field['placeholder'] = 'http://';
		}
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>

			<?php if ( ! empty( $field['multiple'] ) ) : ?>
				<?php foreach ( (array) $field['value'] as $value ) : ?>
					<span class="file_url">
						<input type="text" name="<?php echo esc_attr( $key ); ?>[]" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
						<button class="button wp_job_manager_upload_file_button" data-uploader_button_text="<?php _e( 'Use file', 'wp-job-manager' ); ?>"><?php _e( 'Upload', 'wp-job-manager' ); ?></button>
					</span>
				<?php endforeach; ?>
			<?php else : ?>
				<span class="file_url">
					<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" />
					<button class="button wp_job_manager_upload_file_button" data-uploader_button_text="<?php _e( 'Use file', 'wp-job-manager' ); ?>"><?php _e( 'Upload', 'wp-job-manager' ); ?></button>
				</span>
			<?php endif; ?>

			<?php if ( ! empty( $field['description'] ) ) : ?>
				<span class="description"><?php echo $field['description']; ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $field['multiple'] ) ) : ?>
				<button class="button wp_job_manager_add_another_file_button" data-field_name="<?php echo esc_attr( $key ); ?>" data-field_placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" data-uploader_button_text="<?php _e( 'Use file', 'wp-job-manager' ); ?>" data-uploader_button="<?php _e( 'Upload', 'wp-job-manager' ); ?>"><?php _e( 'Add file', 'wp-job-manager' ); ?></button>
			<?php endif; ?>
		</p>
		<?php
	}

	/**
	 * input_text function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_text( $key, $field ) {
		global $thepostid;

		if ( empty( $field['value'] ) )
			$field['value'] = get_post_meta( $thepostid, $key, true );
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" />
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

	/**
	 * input_text function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_textarea( $key, $field ) {
		global $thepostid;

		if ( empty( $field['value'] ) )
			$field['value'] = get_post_meta( $thepostid, $key, true );
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<textarea name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"><?php echo esc_html( $field['value'] ); ?></textarea>
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

	/**
	 * input_select function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_select( $key, $field ) {
		global $thepostid;

		if ( empty( $field['value'] ) )
			$field['value'] = get_post_meta( $thepostid, $key, true );
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<select name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>">
				<?php foreach ( $field['options'] as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php if ( isset( $field['value'] ) ) selected( $field['value'], $key ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

	/**
	 * input_select function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_multiselect( $key, $field ) {
		global $thepostid;

		if ( empty( $field['value'] ) )
			$field['value'] = get_post_meta( $thepostid, $key, true );
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<select multiple="multiple" name="<?php echo esc_attr( $key ); ?>[]" id="<?php echo esc_attr( $key ); ?>">
				<?php foreach ( $field['options'] as $key => $value ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) selected( in_array( $key, $field['value'] ), true ); ?>><?php echo esc_html( $value ); ?></option>
				<?php endforeach; ?>
			</select>
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

	/**
	 * input_checkbox function.
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_checkbox( $key, $field ) {
		global $thepostid;

		if ( empty( $field['value'] ) )
			$field['value'] = get_post_meta( $thepostid, $key, true );
		?>
		<p class="form-field form-field-checkbox">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?></label>
			<input type="checkbox" class="checkbox" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="1" <?php checked( $field['value'], 1 ); ?> />
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

	/**
	 * Box to choose who posted the job
	 *
	 * @param mixed $key
	 * @param mixed $field
	 */
	public function input_author( $key, $field ) {
		global $thepostid, $post;

		if ( empty( $field['value'] ) )
			$field['value'] = get_post_meta( $thepostid, $key, true );
		?>
		<p class="form-field">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ) ; ?>:</label>
			<?php
				wp_dropdown_users( array(
					'who'              => '',
					'show_option_none' => __( 'Guest user', 'wp-job-manager' ),
					'name'             => $key,
					'selected'         => $post->post_author,
					'include_selected' => true
				) );
			?>
			<?php if ( ! empty( $field['description'] ) ) : ?><span class="description"><?php echo $field['description']; ?></span><?php endif; ?>
		</p>
		<?php
	}

	/**
	 * job_listing_data function.
	 *
	 * @access public
	 * @param mixed $post
	 * @return void
	 */
	public function job_listing_data( $post ) {
		global $post, $thepostid;

		$thepostid = $post->ID;

		echo '<div class="wp_job_manager_meta_data">';

		wp_nonce_field( 'save_meta_data', 'job_manager_nonce' );

		do_action( 'job_manager_job_listing_data_start', $thepostid );

		foreach ( $this->job_listing_fields() as $key => $field ) {
			$type = ! empty( $field['type'] ) ? $field['type'] : 'text';

			if ( method_exists( $this, 'input_' . $type ) )
				call_user_func( array( $this, 'input_' . $type ), $key, $field );
			else
				do_action( 'job_manager_input_' . $type, $key, $field );
		}

		do_action( 'job_manager_job_listing_data_end', $thepostid );

		echo '</div>';
	}

	/**
	 * save_post function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @param mixed $post
	 * @return void
	 */
	public function save_post( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) || empty( $_POST ) ) return;
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
		if ( is_int( wp_is_post_revision( $post ) ) ) return;
		if ( is_int( wp_is_post_autosave( $post ) ) ) return;
		if ( empty($_POST['job_manager_nonce']) || ! wp_verify_nonce( $_POST['job_manager_nonce'], 'save_meta_data' ) ) return;
		if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		if ( $post->post_type != 'job_listing' ) return;

		do_action( 'job_manager_save_job_listing', $post_id, $post );
	}

	/**
	 * save_job_listing_data function.
	 *
	 * @access public
	 * @param mixed $post_id
	 * @param mixed $post
	 * @return void
	 */
	public function save_job_listing_data( $post_id, $post ) {
		global $wpdb;

		// These need to exist
		add_post_meta( $post_id, '_filled', 0, true );
		add_post_meta( $post_id, '_featured', 0, true );

		// Save fields
		foreach ( $this->job_listing_fields() as $key => $field ) {
			// Expirey date
			if ( '_job_expires' === $key ) {
				if ( ! empty( $_POST[ $key ] ) ) {
					update_post_meta( $post_id, $key, date( 'Y-m-d', strtotime( sanitize_text_field( $_POST[ $key ] ) ) ) );
				} else {
					update_post_meta( $post_id, $key, '' );
				}
			}

			// Locations
			elseif ( '_job_location' === $key ) {
				if ( update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) ) ) {
					do_action( 'job_manager_job_location_edited', $post_id, sanitize_text_field( $_POST[ $key ] ) );
				} elseif ( apply_filters( 'job_manager_geolocation_enabled', true ) && ! WP_Job_Manager_Geocode::has_location_data( $post_id ) ) {
					WP_Job_Manager_Geocode::generate_location_data( $post_id, sanitize_text_field( $_POST[ $key ] ) );
				}
			}

			elseif ( '_job_author' === $key ) {
				$wpdb->update( $wpdb->posts, array( 'post_author' => $_POST[ $key ] > 0 ? absint( $_POST[ $key ] ) : 0 ), array( 'ID' => $post_id ) );
			}

			elseif ( '_application' === $key ) {
				update_post_meta( $post_id, $key, sanitize_text_field( urldecode( $_POST[ $key ] ) ) );
			}

			// Everything else
			else {
				$type = ! empty( $field['type'] ) ? $field['type'] : '';

				switch ( $type ) {
					case 'textarea' :
						update_post_meta( $post_id, $key, wp_kses_post( stripslashes( $_POST[ $key ] ) ) );
					break;
					case 'checkbox' :
						if ( isset( $_POST[ $key ] ) ) {
							update_post_meta( $post_id, $key, 1 );
						} else {
							update_post_meta( $post_id, $key, 0 );
						}
					break;
					default :
						if ( is_array( $_POST[ $key ] ) ) {
							update_post_meta( $post_id, $key, array_filter( array_map( 'sanitize_text_field', $_POST[ $key ] ) ) );
						} else {
							update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
						}
					break;
				}
			}
		}
	}
}

new WP_Job_Manager_Writepanels();