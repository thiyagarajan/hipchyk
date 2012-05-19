<?php // (C) Copyright Bobbing Wide 2012

/**
 * Logic to create metaboxes for each of the custom fields defined for a particular post type
 */

  
add_action( 'add_meta_boxes', 'bw_effort_meta_boxes' );

/* Do something with the data entered */
add_action( 'save_post', 'bw_effort_save_postdata', 10, 2 );


/**
 * Create  "Fields" meta box for each field associated with each object type in the mapping
 */
function bw_effort_meta_boxes() {
  oik_require( 'bobbforms.inc' );
  global $bw_mapping;
  bw_trace2( $bw_mapping );
  
  foreach ( $bw_mapping['field'] as $object_type => $fields ) {
    bw_trace( "object_type", __FUNCTION__, __LINE__, __FILE__, $object_type );
    bw_trace2( $fields );
    add_meta_box( 'bw_effort', 'Fields', 'bw_effort_box', $object_type, 'normal' , 'high' , $fields );
  }  
}


/*
 * Return a default value from $args if $value is not set
 *
 * @param string $value the current value
 * @param array $args an array containing the default value
 * @returns string $value
 */
function bw_default_value( $value, $args ) {
  if ( !$value ) {
    $value = bw_array_get( $args, '#default', NULL );
  }
  return( $value );
}


/**
 * bw_form_field_ - default formatting for a field - as a textfield
 *
 * e.g. textfield( '_bw_header_image', 80, "Custom header image", $value );
 */
function bw_form_field_( $name, $type, $title, $value, $args ) {
  $length = bw_array_get( $args, '#length', 40 );
  $value = bw_default_value( $value, $args );
  $class = bw_array_get( $args, "#class", null );
  $extras = bw_array_get( $args, "#extras", null );
  bw_textfield( $name, $length, $title, $value, $class, $extras );
} 

/**
 * bw_form_field_select - formatting for a field as a select list
 * The options come from $args[#options] 
 */
function bw_form_field_select( $name, $type, $title, $value, $args ) {
  bw_select( $name, $title, $value, $args );
}

/** 
 * Build a simple ID, title array from an array of $post objects
 * @param array $post - array of post objects
 * @return array - associative array of post ID to post_title
 */
function bw_post_array( $posts ) {
  $options = array();
  foreach ($posts as $post ) {
    $options[$post->ID] = $post->post_title; 
  }
  return bw_trace2( $options );
}

/** 
 * Build a simple ID, title array from an array of $term objects
 * @param array $term - array of term objects
 * @return array - associative array of term_id to name
 */
function bw_term_array( $terms ) {
  $options = array();
  if ( count( $terms ) ) {
    foreach ($terms as $term ) {
      $options[$term->term_id] = $term->name; 
    }
  }
  return bw_trace2( $options );
}

/**
 * Load an array of node references
 *
 * @param string/array $args - array of args containing the #type of the node to load
 * @returns array $options - array of nodes keyed by ID with value of title
 *
 * Note: You can simply pass this as a string if you so wish 
 * 
 * **?** This is probably an inefficient use of get_posts, especially for very large lists
 * **?** Could we not just pass $args to bw_get_posts to allow the returned list to be fine tuned
 * 
 * 
 */
function bw_load_noderef( $args ) {
  $args['post_type'] = bw_array_get( $args, '#type', $args );
  $posts = bw_get_posts( $args );
  $options = bw_post_array( $posts );
  return( $options );
}

/**
 * bw_form_field_noderef - formatting for a field as a select list of a list of nodes of a particular type
 * The options come from $args[#type] 
 */
function bw_form_field_noderef( $name, $type, $title, $value, $args ) {
  $options = bw_load_noderef( $args );
  bw_select( $name, $title, $value, array( '#options' => $options ));
}
   
/**
 * Enqueue the debug script if needed otherwise enqueue the minified (packed) one
*/
function bw_datepicker_enqueue_script( ) {
  if ( defined('SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true) {
    wp_enqueue_script( 'jquery-ui-datepicker' );
  } else {
    wp_enqueue_script( 'jquery-ui-datepicker' );
  } 
} 

/** 
 * bw_form_field_date - format a date field
 *
 */
function bw_form_field_date( $name, $type, $title, $value, $args ) {
  $args['#length'] = bw_array_get( $args, '#length', 10 );
  wp_enqueue_style( "jquery-ui-datepicker-css", plugin_dir_url( __FILE__). "css/jquery.ui.datepicker.css" ); 
  wp_enqueue_style( "jquery-ui-theme-css", plugin_dir_url( __FILE__). "css/jquery.ui.theme.css" );
   
  bw_datepicker_enqueue_script();
  bw_jquery( "#${name}", "datepicker", bw_jkv( "dateFormat : 'yy-mm-dd', changeMonth: true, changeYear: true" ) );
  bw_form_field_( $name, $type, $title, bw_format_date( $value ), $args ); 
}

/** 
 * bw_form_field_numeric - format a numeric field
 *
 */
function bw_form_field_numeric( $name, $type, $title, $value, $args ) {
  $args['#length'] = bw_array_get( $args, '#length', 10 );
  bw_form_field_( $name, $type, $title, $value, $args ); 
}
 
function bw_form_field_checkbox( $name, $type, $title, $value, $args ) {
  bw_checkbox( $name, $title, $value );
}  

/** 
 * Return the function name to be used to 'form' the field
 * 
 * 
 * We append to the $prefix function name to find the most precise name that exists:
 * 
 * $prefix            - default function if the field type is not known
 * $prefix$field_type - e.g. bw_form_field_text or bw_form_field_url
 * $prefix$field_name - e.g. bw_form_field__task_ref
 *
 * @param string $field_type - type of the field to form
 * @param string $field_name - name of the field to form
 
 * @param string $suffix - optional suffix for multiple theming functions 
 * @returns string $funcname - the name of the function to invoke
 * 
 */
function bw_form_function( $prefix="bw_form_field_", $field_type= 'text', $field_name = NULL ) {
  $funcname = $prefix;
  
  $testname = $funcname . $field_type;
  if ( function_exists( $testname ) ) 
    $funcname = $testname;
    
  $testname = $prefix . $field_name; 
  if ( function_exists( $testname ))
    $funcname = $testname;
    
  return bw_trace2( $funcname );
}   

/** 
 *
 */
function bw_form_field( $field_name, $field_type, $field_title, $field_value, $args=NULL ) {
  bw_trace2();
  $funcname = bw_form_function( "bw_form_field_", $field_type, $field_name );
  $funcname( $field_name, $field_type, $field_title, $field_value, $args );
}

/**
 * Create fields in the meta box to accept data for the fields
 */
function bw_effort_box( $post, $args ) {
  //bw_bang();
  global $bw_fields; 
  $fields = $args['args'];
  //bw_trace2( $fields );
  stag( 'table class="form-table"' );
  foreach ( $fields as $field ) {
    $data = $bw_fields[$field];
    //bw_trace2( $field );
    //bw_trace2( $data );
    $value = get_post_meta( $post->ID, $field, TRUE );
    bw_form_field( $field, $data['#field_type'], $data['#title'], $value, $data['#args'] );
  }
  etag( "table" );
  echo( bw_ret());
}

if ( !function_exists( "bw_authorized" ) ) {
  function bw_authorized() {
    $authorized = TRUE;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
        $authorized = FALSE;
        
    // if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename( __FILE__ ) ) )
    return $authorized;
  }
}

/**
 * Save the custom fields for this post when called for the 'save_post' action
 * 
 * @param integer $post_id The ID of this post
 * @param object $post the post object
 *
 * In order to receive the $post object we need to indicate to add_action that for 'save_post' 
 * we allow two parameters to be passed.
 * From the $post we can determine the $post_type of the object being saved
 * and therefore determine the custom fields from the $bw_mapping global
 * for each field defined for the post type we obtain the value from the $_POST and
 * update the post meta data. 
 */
function bw_effort_save_postdata( $post_id, $post ) {
  if ( bw_authorized() ) {
    $post_type = $post->post_type; 
    
    //bw_trace2( $_POST, "_POST" );
    //bw_trace( $post_type, __FUNCTION__, __LINE__, __FILE__, "post->post_type" );
    do_action( "save_post_${post_type}", $post_id, $post );
  
    global $bw_mapping; 
    bw_trace( $bw_mapping, __FUNCTION__, __LINE__, __FILE__, "bw_mapping" );
    if ( isset(  $bw_mapping['field'][$post_type] )) {
      foreach ( $bw_mapping['field'][$post_type] as $field ) {
      
        bw_trace( $field, __FUNCTION__, __LINE__, __FILE__, "field" );
        $mydata = bw_array_get( $_POST, $field, NULL ) ;
        
        bw_trace( $mydata, __FUNCTION__, __LINE__, __FILE__, "mydata" );
        update_post_meta( $post_id, $field, $mydata );
      }
    }
  } else {
  }   
}




