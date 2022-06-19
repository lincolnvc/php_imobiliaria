<?php

function editor( $val = '', $n='editor', $h = '200px', $w = '200px', $dest = 'file_upload.php' )
{
    $body = "";
    //$body .= "<meta charset=\"iso-8859-1\">\n";
    $body .= "<link rel=\"stylesheet\" href=\"helpers/redactor/api/css/redactor.css\" />\n";
    //$body .= "\t<script src=\"helpers/redactor/api/jquery-1.7.min.js\"></script>\n";
    $body .= "\t<script src=\"helpers/redactor/api/redactor.js\"></script>\n";
    $body .= "\t<textarea id=\"$n\" class=\"redac\" name=\"$n\" style=\"height: $h; width:$w !important\">$val</textarea>\n";
    @header( 'Content-Type: text/html; charset=iso-8859-1' );
    return trim( $body );
}

/*
  <script type="text/javascript">
  $(document).ready(function(){
  $('#editor').redactor({
  imageUpload: '../demo/scripts/image_upload.php',
  fileUpload: '../demo/scripts/file_upload.php',
  imageGetJson: '../demo/json/data.json'
  });
  });
  </script>
 */

/*
  public function editorJsonList()
  {
  $this->helper( 'dir' );
  $lista = list_dir( 'uploads' );
  $json = "[";
  foreach ( $lista as $img )
  {
  $img = HTTPURL.UPLOADDIR."$img";
  $json .= "{ \"thumb\": \"$img\", \"image\": \"$img\" },";
  }
  $json .= "]";
  $json = preg_replace('/,]/',']',$json);
  echo $json;
  }

  public function editorUploadIm()
  {
  $dir = UPLOADDIR;
  $_FILES['file']['type'] = strtolower( $_FILES['file']['type'] );
  if ( $_FILES['file']['type'] == 'image/png'
  || $_FILES['file']['type'] == 'image/jpg'
  || $_FILES['file']['type'] == 'image/gif'
  || $_FILES['file']['type'] == 'image/jpeg'
  || $_FILES['file']['type'] == 'image/pjpeg' )
  {
  // setting file's mysterious name
  //$file = $dir.md5(date('YmdHis')).'.jpg';
  $file = $dir . $_FILES['file']['name'];
  // copying
  copy( $_FILES['file']['tmp_name'], $file );

  // displaying file
  $array = array(
  'filelink' => HTTPURL . $file
  );
  echo stripslashes( json_encode( $array ) );
  }
  }

 * 
 * 
 */
?>
