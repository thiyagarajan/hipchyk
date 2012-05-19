<?php

class slickr_flickr_oauth {
  
  public static function append_signature($method, $url, $args) {
    
    $oauth = array('oauth_version' => '1.0',
                      'oauth_nonce' =>  md5(microtime().mt_rand()),
                      'oauth_timestamp' => time(),
                      'oauth_consumer_key' => $args['api_key'],
                      'oauth_token' => $args['token'],
                      'oauth_signature_method' => 'HMAC-SHA1'
                      );    
    $params = array_merge($args,$oauth);
    $token = $params['token']; unset($params['token']);
    $token_secret = $params['token_secret']; unset($params['token_secret']);
    $consumer_secret = $params['consumer_secret']; unset($params['consumer_secret']);
    $params['oauth_signature'] = self::build_signature($method, $url, $params, $consumer_secret,$token_secret);
    return $params;
  }

  public static function build_signature($method, $url, $params, $consumer_secret, $token_secret) {
    
   // Urlencode both keys and values
    $keys = self::urlencode_rfc3986(array_keys($params));
    $values = self::urlencode_rfc3986(array_values($params));
    $params = array_combine($keys, $values);

    uksort($params, 'strcmp');

    // Generate key=value pairs
    $pairs = array();
    foreach ($params as $key=>$value ) {
      if (is_array($value)) {
        // If the value is an array, it's because there are multiple
        // with the same key, sort them, then add all the pairs
        natsort($value);
        foreach ($value as $v2) {
          $pairs[] = $key . '=' . $v2;
        }
      } else {
        $pairs[] = $key . '=' . $value;
      }
    }

	$encoded_params =  implode('&', $pairs);
    $parts = array( strtoupper($method), self::get_normalized_http_url($url), $encoded_params);
    $parts = self::urlencode_rfc3986($parts);
    $base_string = implode('&', $parts);

    $key_parts = self::urlencode_rfc3986( array( $consumer_secret, $token_secret ));
    $key = implode('&', $key_parts);
    
    return base64_encode( hash_hmac('sha1', $base_string, $key, true));
    
  }

  public static function get_normalized_http_url($url) {
    $parts = parse_url($url);
    $port = @$parts['port'];
    $scheme = $parts['scheme'];
    $host = $parts['host'];
    $path = @$parts['path'];

    $port or $port = ($scheme == 'https') ? '443' : '80';

    if (($scheme == 'https' && $port != '443')
        || ($scheme == 'http' && $port != '80')) {
      $host = "$host:$port";
    }
    return "$scheme://$host$path";
  }
  
  public static function urlencode_rfc3986($input) {
  	if (is_array($input)) {
  	  return array_map(array('slickr_flickr_oauth','urlencode_rfc3986'), $input);
  	} else if (is_scalar($input)) {
  	  return str_replace('+', ' ', str_replace('%7E', '~', rawurlencode($input)));
  	} else {
  	  return '';
  	}
  }
  
}
?>