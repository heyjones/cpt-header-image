<?php

/*
Plugin Name:  CPT Header Image
Plugin URI:   https://github.com/heyjones/cpt-header-image/
Description:  Header Image support for Custom Post Types in WordPress
Version:      0.1
Author:       Chris Jones
Author URI:   http://heyjones.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

namespace cpt_header_image;

add_action( 'customize_register', __NAMESPACE__ . '\\customize_register' );

function customize_register( $wp_customize ){
  $post_types = get_post_types( array(
    'public' => true,
    '_builtin' => false
  ), 'objects' );
  usort( $post_types, function( $a, $b ){
    return( $a->menu_position > $b->menu_position );
  } );
  foreach( $post_types as $post_type ){
    if( $post_type->has_archive ){
      $wp_customize->add_setting( 'header_image_' . $post_type->name );
      $wp_customize->add_control( new \WP_Customize_Cropped_Image_Control( $wp_customize, 'header_image_' . $post_type->name, array(
        'label' => $post_type->labels->menu_name,
        'section' => 'header_image',
        'settings' => 'header_image_' . $post_type->name,
        'width' => 1400,
        'height' => 700
        ) )
      );
    }
  }
}

function theme_mod_header_image( $url ){
  if( is_post_type_archive( $post_types ) ){
    $post_type = get_post_type();
    $post_id = get_theme_mod( 'header_image_' . $post_type );
    if( $post_id ){
      $url = wp_get_attachment_url( $post_id );
    }
  }
  return $url;
}
