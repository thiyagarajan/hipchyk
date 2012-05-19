<?php 
/** 
 * return the raw content fully escaped but with unexpanded shortcodes of the current post 
 *
 * @param mixed $atts - parameters to the shortcode 
 * @return string the "raw" content - that could be put through WP-syntax
 */
function bw_wtf( $atts=null ) { 
  global $post;
  bw_trace2( $post, "post" ); 
  $escaped_content = esc_html( $post->post_content );
  stag( 'p', null, null, 'lang=HTML" escaped="true"' );
  e( $escaped_content );
  etag( "p" );
  return( bw_ret() );

}
