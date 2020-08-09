<?php

class Registration_Form_Widget extends WP_Widget {

  function __construct(){
    $widget = array('description' => __( 'Your site registration form', 'register-users' ));
    $control = array('height' => 500);
    parent::__construct(false, $name = 'Registration Form Widget', $widget, $control);
  }

  public function widget( $args, $instance ) {
    if ( ! isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }
    $output      = '';
    $title       = ( !empty( $instance['title'] ) ) ? $instance['title'] : __( 'Registration Form', 'register-users' );
    $title       = apply_filters( 'registration_form_widget_title', $title, $instance, $this->id_base );
    $placeholder = ( !empty( $instance['placeholder'] ) ) ? $instance['placeholder'] : __( 'Email', 'register-users' );
    $placeholder = apply_filters( 'registration_form_widget_placeholder', $placeholder, $instance, $this->id_base );

    $output .= $args['before_widget'];

    if ( $title ) {
      $output .= $args['before_title'] . $title . $args['after_title'];
    }

    $output .= '<form method="post" class="test-registration-form">';
    $output .= sprintf( '<input id="registration-form-email" name="email" type="email" placeholder="%1$s" value="" required="required">', $placeholder );
    $output .= sprintf( '<input id="registration-form-btn" class="registration-form-btn" type="submit" value="%1$s">', __( 'Send', 'register-users' ) );
    $output .= '</form>';

    $output .= $args['after_widget'];
    echo $output;
  }

  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field( $new_instance['title'] );
    $instance['placeholder'] = sanitize_text_field( $new_instance['placeholder'] );
    return $instance;
  }

  public function form( $instance ) {
    $title       = isset( $instance['title'] ) ? $instance['title'] : 'Registration Form';
    $placeholder = isset( $instance['placeholder'] ) ? $instance['placeholder'] : 'Email';
    ?>
    <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'register-users' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>

    <p><label for="<?php echo $this->get_field_id( 'placeholder' ); ?>"><?php _e( 'Placeholder:', 'register-users' ); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'placeholder' ); ?>" name="<?php echo $this->get_field_name( 'placeholder' ); ?>" type="text" value="<?php echo $placeholder; ?>"></p>
    <?php
  }
}

add_action('widgets_init', 'registration_form_register_widget');

function registration_form_register_widget(){
  return register_widget( 'Registration_Form_Widget' );
}