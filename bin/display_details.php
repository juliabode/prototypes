<?php

define( 'API_KEY', 'js72ltwo7txbx0x1m2yzmpkj' );

$listing_id    = $_REQUEST['product_ids'];
$url           = "http://api.etsy.com/v2/listings/" . $listing_id . "?fields=title,description&includes=MainImage(url_170x135)&api_key=" . API_KEY;
$ch            = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response_body = curl_exec($ch);
$status        = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if (intval($status) != 200) throw new Exception("HTTP $status\n$response_body");

$response        = json_decode($response_body);
$chosen_listings = $response->results;

?>

<div class="products result-list">
  <header class="clearfix">
    <div class="button awesome large alignright submit-get_pdf">continue &raquo;</div>
  </header>
  <?php
    foreach ( $chosen_listings as $listing ) {
      $title        = $listing->title;
      $description  = $listing->description;
      $img_url      = $listing->MainImage->url_170x135; ?>

      <div class="product-detail clearfix">
        <div class="alignleft">
          <img class='product-image' src='<?php echo $img_url; ?>' alt='<?php echo $title; ?>' />
        </div>
        <div class="desc alignleft">
          <h3><?php echo $title; ?></h3>
          <?php echo $description; ?>
        </div>
      </div><?php
    }
  ?>
</div>
