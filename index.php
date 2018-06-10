<html>
<head>
  <link rel="stylesheet" href="style.css">
</head>

<body>

<?php
 function GetZipDistance($zipcode,$zipcode2,$units = 'mile',$format = 'json'){
  $format = strtolower($format);
  $units = strtolower($units);

  $zipcode_base_url = "http://www.zipcodeapi.com/rest/9m0N6wkC65Ao6GCFHOhFxCdDDGkWB7GNnmMlvy9ShgUVa1HogsNHdHgzhIhHLeA1/";

  $send_url = $zipcode_base_url.'distance.'.$format.'/'.$zipcode.'/'.$zipcode2.'/'.$units;
  return curl($send_url);
}

 function curl($send_url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $send_url);
  curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_VERBOSE, 0);
  $result = curl_exec($ch);
  if (curl_errno($ch)) {
    $error = curl_error($ch);
    curl_close($ch);
    throw new Exception("Failed retrieving  '" . $send_url . "' . Error : '" . $error . "'.");
  }
  return $result;
}
?>

<div class="container">
<h1> Enter Zip codes and get distance between them: </h1>
 <form action="#" method="post">
<label>  Zip Code 1:<br />
  <input type="text"  name="zip1" title="Five digit zip code" maxlength="5" onkeypress="return isNumberKey(event)" /></label>

  <label>
    <br />
  Zip Code 2<br />
  <input type="text" name="zip2" title="Five digit zip code" maxlength="5" onkeypress="return isNumberKey(event)" /></label>
  <br />
  <input type="submit" name="Submit" value="Submit">
</form>

  <?php
      if(isset($_POST['Submit'])){
     $zip1 = $_POST["zip1"];
     $zip2 = $_POST["zip2"];

    $data = GetZipDistance($zip1,$zip2);
    $result = json_decode($data);

  if (isset($result->distance)){
     echo "Distance between ".$zip1." And ".$zip2." is: <h2>".$result->distance."</h2> miles.";
   }
  else if (isset($result->error_code)) {
    if (($result->error_code) == '404')
      echo "Zip Code Is not Found";
  else if (($result->error_code) == '401')
      echo "The API key was not found, was not activated, or has been disabled";
  else if (($result->error_code) == '429')
      echo "The usage limit for your application has been exceeded for the hour time period";
  else
      echo "The request format was not correct";
  }

    }
  ?>

</div>

<!-- Input Protection -->
<script>
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
</script>

</body>

</html>
