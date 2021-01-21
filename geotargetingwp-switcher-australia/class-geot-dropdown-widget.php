<?php

/**
 * Adds GeotargetingWP Widget
 * @since  1.0.0
 */
class GeotS_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'geot_switcher', // Base ID
			__( 'Geotarget Custom Switcher', 'geot' ), // Name
			[ 'description' => __( 'Display a dropdown to let users change Australian state/territory', 'geot' ), ] // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 *
	 * @see WP_Widget::widget()
	 *
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];
		$states = array_keys( GeotSwitcher::cities() );
		// check if we have any previously selected item
		$current = isset( $_COOKIE['geot_switcher'] ) ? $_COOKIE['geot_switcher'] : '';
		?>
		<div class="geot_dropdown_container">
			<select class="geot_switcher" name="geot_switcher" id="geot_switcher">
				<option>Choose one</option>
				<?php foreach ( $states as $state ): ?>
					<option value="<?= $state; ?>" <?php selected( $state, $current ); ?>><?= $state; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 *
	 * @return string|void
	 * @see WP_Widget::form()
	 *
	 */
	public function form( $instance ) {
		?>
		<p>No settings available</p>
		<?php
	}

} // class Foo_Widget