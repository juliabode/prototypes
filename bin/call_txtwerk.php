<?php

define( 'API_KEY', 'gYNOcjJFa7wQVZDD4r98FIv9B' );

$url           = "https://api.neofonie.de/rest/txt/analyzer";
$ch            = curl_init($url);
$cache_file    = dirname(__FILE__) . '\twitter-cache';
$twitter       = json_decode(file_get_contents( $cache_file ));

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: ' . API_KEY));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

foreach ( $listings as $listing ) {
    if (!empty($listing->entities->urls[0]->expanded_url)) {
      $external_url = $listing->entities->urls[0]->expanded_url;
      $text         = $listing->text;
      $user         = $listing->user->name;
    }
}

$response_body = curl_exec($ch);
$status        = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (intval($status) != 200) throw new Exception("HTTP $status\n$response_body");

$response = json_decode($response_body);
$listings = $response;

?>

<div class="product-image-list result-list">
  <header class="clearfix">
  </header>
  <?php
    foreach ( $listings as $listing ) {
      /*$title        = $listing->title;
      $img_url      = $listing->MainImage->url_170x135;
      $listing_id   = $listing->listing_id;*/

      var_dump($listing);
      //echo "<img data-id='$listing_id' class='product-image' src='$img_url' alt='$title' />";
    }
  ?>
</div>
