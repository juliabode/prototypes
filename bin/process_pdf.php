<?php

$inner_html = $_REQUEST['html'];

$html = 
  '<html>'.
  '<title><link href="../../css/style.css" rel="stylesheet">'.
  '<style type="text/css">.button{display:none;}.product-detail .desc{width: auto;}</style>'.
  '</title>'.
  '<body style="padding-left:40px; padding-right: 40px;">'.
  $inner_html.
  '</body></html>';

$tmpfile = tempnam("../dompdf/tmp", "dompdf_");
file_put_contents($tmpfile, $html);
chmod($tmpfile, 511);

$url = "dompdf/dompdf.php?input_file=" . rawurlencode($tmpfile) . 
       "&paper=letter&output_file=" . rawurlencode("My Fancy PDF.pdf");

echo "http://" . $_SERVER["HTTP_HOST"] . "/$url";
?>
