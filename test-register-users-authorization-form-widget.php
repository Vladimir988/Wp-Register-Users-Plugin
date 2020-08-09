<?php

class Authorization_Form_Widget extends WP_Widget {

  function __construct(){
    $widget = array('description' => __( 'Your site authorization form', 'register-users' ));
    $control = array('height' => 500);
    parent::__construct(false, $name = 'Authorization Form Widget', $widget, $control);
  }

  public function widget( $args, $instance ) {
    if ( ! isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }
    $output = '';
    $title  = ( !empty( $instance['title'] ) ) ? $instance['title'] : __( 'Authorization Form', 'register-users' );
    $title  = apply_filters( 'authorization_form_widget_title', $title, $instance, $this->id_base );

    $output .= $args['before_widget'];

    if ( $title ) {
      $output .= $args['before_title'] . $title . $args['after_title'];
    }

    $form_args = array(
      'echo'           => false,
      'id_submit'      => 'wp-submit-front',
      'remember'       => true,
      'label_username' => '',
      'label_password' => '',
      'label_remember' => __( 'Remember', 'register-users' ),
    );

    $form = wp_login_form( $form_args );
    $form = str_replace( 'name="log"', 'name="log" placeholder="Username or Email"', $form );
    $form = str_replace( 'name="pwd"', 'name="pwd" placeholder="Password"', $form );

    $output .= '<div class="authorization-user-login-front"><div id="login">';
    $output .= $form;
    $output .= '</div></div>';
    $output .= $args['after_widget'];

    echo $output;
  }

  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = sanitize_text_field( $new_instance['title'] );
    return $instance;
  }

  public function form( $instance ) {
    $title = isset( $instance['title'] ) ? $instance['title'] : 'Authorization Form';
    $login = isset( $instance['login'] ) ? absint( $instance['login'] ) : 'Login';
    $pass  = isset( $instance['password'] ) ? absint( $instance['password'] ) : 'Password';
    ?>
      <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'register-users' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"></p>
    <?php
  }
}

add_action('widgets_init', 'authorization_form_register_widget');

function authorization_form_register_widget(){
  return register_widget( 'Authorization_Form_Widget' );
}