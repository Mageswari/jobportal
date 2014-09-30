<?php

class epsSliderImageClass {

	public $slide = 0;
	public $slider = 0;
	public $settings = array(); // slideshow settings

	/**
	 * Set the slideeps_get_slider_css
	 */
	public function eps_set_slide($id) {
		$this->slide = get_post($id);
	}

	/**
	 * Set the slide (that this slide belongs to)
	 */
	public function eps_set_slider($id) {
		$this->slider = get_post($id);
		$this->settings = get_post_meta($id, 'eps-slider_settings', true);
	}

	/**
	 * Return the HTML for the slide
	 * @return array complete array of slides
	 */
	public function eps_get_slide($slide_id, $slider_id) {

		$this->eps_set_slider($slider_id);

		$this->eps_set_slide($slide_id);

		return $this->eps_get_slide_html();

	}

	/**
	 * Save the slide
	 */
	public function eps_save_slide($slide_id, $slider_id, $fields) {
		$this->eps_set_slider($slider_id);
		$this->eps_set_slide($slide_id);
		$this->eps_save($fields);
	}

	/**
	 * Return the correct slide HTML based on whether we're viewing the slides in the
	 * admin panel or on the front end.
	 *
	 * @return string slide html
	 */
	public function eps_get_slide_html() {
		if (is_admin() && isset($_GET['page']) && $_GET['page'] == 'eps-theme-editor') {
			return $this->eps_get_public_slide();
		}

		if (is_admin() && !isset($_GET['slider_id'])) {
			return $this->eps_get_admin_slide();
		}


		return $this->eps_get_public_slide();

	}

	/**
	 * Tag the slide attachment to the slider tax category
	 */
	public function eps_tag_slide_to_slider() {
		if (!term_exists($this->slider->ID, 'eps-slider')) {
			// create the taxonomy term, the term is the ID of the slider itself
			wp_insert_term($this->slider->ID, 'eps-slider');
		}

		// get the term thats name is the same as the ID of the slider
		$term = get_term_by('name', $this->slider->ID, 'eps-slider');
		// tag this slide to the taxonomy term
		wp_set_post_terms($this->slide->ID, $term->term_id, 'eps-slider', true);
	}

	/**
	 * If the meta doesn't exist, add it
	 * If the meta exists, but the value is empty, delete it
	 * If the meta exists, update it
	 */
	public function eps_add_or_update_or_delete_meta($post_id, $name, $value) {
		$key = "eps-slider_" . $name;

		if ($value == 'false' || $value == "" || !$value) {
			if (get_post_meta($post_id, $key)) {
				delete_post_meta($post_id, $key);
			}
		} else {
			if (get_post_meta($post_id, $key)) {
				update_post_meta($post_id, $key, $value);
			} else {
				add_post_meta($post_id, $key, $value, true);
			}
		}
	} /**
 * If the meta doesn't exist, add it
 * If the meta exists, but the value is empty, delete it
 * If the meta exists, update it
 */
	public function eps_add_or_update_or_delete_bg_meta($post_id, $name, $value) {
		$key = "eps-slider_" . $name;

		if ($value == 'false' || $value == "" || !$value) {
			if (get_post_meta($post_id, $key)) {
				delete_post_meta($post_id, $key);
			}
		} else {
			if (get_post_meta($post_id, $key)) {
				update_post_meta($post_id, $key, $value);
			} else {
				add_post_meta($post_id, $key, $value, true);
			}
		}
	}

	/**
	 * Get the thumbnail for the slide
	 */
	public function eps_get_thumb() {
		$imageHelper = new epsImageHelperClass($this->slide->ID, 150,150,'false');
		return $imageHelper->eps_get_image_url();
	}
}
?>