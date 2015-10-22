<?php

include_once('config.php');

/**
 *  Usage:
 *  Send the url you want to access url encoded in the url paramater, for example (This is with JS): 
 *  /twitter-proxy.php?url='+encodeURIComponent('statuses/user_timeline.json?screen_name=MikeRogers0&count=2')
*/

// The tokens, keys and secrets from the app you created at https://dev.twitter.com/apps

$config = array(
    'oauth_access_token' => $config['oauth_access_token'],
    'oauth_access_token_secret' => $config['oauth_access_token_secret'],
    'consumer_key' => $config['consumer_key'],
    'consumer_secret' => $config['consumer_secret'],
    'api_key_txtwerk' => $config['api_key_txtwerk'],
    'use_whitelist' => false, // If you want to only allow some requests to use this script.
    'base_url' => 'https://api.twitter.com/1.1/'
);


$cache_file = dirname(__FILE__) . '/twitter-cache';
$modified = filemtime( $cache_file );
$now = time();
$interval = 0; // ten minutes

// check the cache file
if ( !$modified || ( ( $now - $modified ) > $interval ) ) {

// Only allow certain requests to twitter. Stop randoms using your server as a proxy.
$whitelist = array(
    'statuses/user_timeline.json?screen_name=MikeRogers0&count=10&include_rts=false&exclude_replies=true'=>true
);

/*
* Ok, no more config should really be needed. Yay!
*/

// We'll get the URL from $_GET[]. Make sure the url is url encoded, for example encodeURIComponent('statuses/user_timeline.json?screen_name=MikeRogers0&count=10&include_rts=false&exclude_replies=true')
if(!isset($_GET['url'])){
    die('No URL set');
}

$url = $_GET['url'];

if($config['use_whitelist'] && !isset($whitelist[$url])){
    die('URL is not authorised');
}

// Figure out the URL parmaters
$url_parts = parse_url($url);
parse_str($url_parts['query'], $url_arguments);

$full_url = $config['base_url'].$url; // Url with the query on it.
$base_url = $config['base_url'].$url_parts['path']; // Url without the query.

/**
* Code below from http://stackoverflow.com/questions/12916539/simplest-php-example-retrieving-user-timeline-with-twitter-api-version-1-1 by Rivers 
* with a few modfications by Mike Rogers to support variables in the URL nicely
*/

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key=>$value){
    $r[] = "$key=" . rawurlencode($value);
    }
    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value)
    $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $r .= implode(', ', $values);
    return $r;
}

// Set up the oauth Authorization array
$oauth = array(
    'oauth_consumer_key' => $config['consumer_key'],
    'oauth_nonce' => time(),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_token' => $config['oauth_access_token'],
    'oauth_timestamp' => time(),
    'oauth_version' => '1.0'
);
    
$base_info = buildBaseString($base_url, 'GET', array_merge($oauth, $url_arguments));
$composite_key = rawurlencode($config['consumer_secret']) . '&' . rawurlencode($config['oauth_access_token_secret']);
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;

// Make Requests
$header = array(
    buildAuthorizationHeader($oauth), 
    'Expect:'
);
$options = array(
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_HEADER => false,
    CURLOPT_URL => $full_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false
);

$feed = curl_init();
curl_setopt_array($feed, $options);
$result = curl_exec($feed);
$info = curl_getinfo($feed);
curl_close($feed);

  if ( $result ) {
    $cache_static = fopen( $cache_file, 'w' );
    fwrite( $cache_static, $result );
    fclose( $cache_static );
  }

} else {
    $result = file_get_contents( $cache_file );
}

$response = json_decode($result);
$listings = $response;

function get_txtwerk($url, $api) {
    $url_txtwerk   = "http://api.neofonie.de/rest/txt/analyzer?url=" . urlencode($url) . "&services=categories,tags";
    $ch_txtwerk    = curl_init($url_txtwerk);

    curl_setopt($ch_txtwerk, CURLOPT_HTTPHEADER, array('X-Api-Key: ' . $api));
    curl_setopt($ch_txtwerk, CURLOPT_RETURNTRANSFER, true);
    $response_body_txtwerk = curl_exec($ch_txtwerk);
    $status_txtwerk        = curl_getinfo($ch_txtwerk, CURLINFO_HTTP_CODE);
    curl_close ( $ch_txtwerk );

    if (intval($status_txtwerk) != 200) return 'No categories';//throw new Exception("HTTP $status_txtwerk\n$response_body_txtwerk");


    $response_txtwerk = json_decode($response_body_txtwerk);

    return $response_txtwerk;
}

?>

<div class="product-image-list result-list">
  <header class="clearfix">
  </header>
  <?php
    foreach ( $listings as $listing ) {
        if (!empty($listing->entities->urls[0]->expanded_url)) {
            $external_url = $listing->entities->urls[0]->expanded_url;
            $classifier   = get_txtwerk($external_url, $config['api_key_txtwerk']);
            $text         = (empty($classifier->text)) ? 'Kein Text gefunden.' : substr($classifier->text, 0, 1200);
            $categories   = (empty($classifier->categories)) ? 'Keine Kategorie gefunden.' : $classifier->categories[0]->label;
            $categorie_img= (empty($classifier->categories)) ? 'kategorielos' : $classifier->categories[0]->label;
            $tags         = (empty($classifier->tags)) ? 'Keine Tags gefunden.' : $classifier->tags;
            $tweet        = $listing->text;
            $user         = $listing->user->name;

            echo "<article>";
            echo "<div class='img-wrapper'>";
            echo "<img src='img/" . $categorie_img . ".jpg'>";
            echo "<div class='img-border'></div>";
            echo "</div>";
            echo "<p><span style='color: blue;'>" . $user . " sagt:</span> " . $tweet . "</p>";
            echo "<p>" . $external_url . "</p>";
            echo "<p>" . $text . "</p>";
            echo "<p>Kategorie: <span class=" . $categories . ">" . ucfirst($categories) . "</span></p>";
            if (!empty($classifier->tags)) {
                echo "<p>Tags: ";
                foreach ( $tags as $tag ) {
                    echo $tag->term . ', ';
                }
                echo "</p>";
            } else {
                echo "<p>Tags: " . $tags . "</span></p><br>";
            }
            echo "</article>";
        }
    }
  ?>
</div>