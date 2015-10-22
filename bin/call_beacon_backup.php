<?php

include_once('config.php');

define( 'API_KEY', $config['api_key'] );

//$shop_name     = $_REQUEST['name'];
$url           = "http://api.kontakt.io/beacon?proximity=f7826da6-4fa2-4e98-8024-bc5b71e0893e&major=5190&minor=60593";
$ch            = curl_init($url);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Api-Key: ' . API_KEY, 'Accept: application/vnd.com.kontakt+json; version=3'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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
