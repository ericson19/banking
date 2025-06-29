<?php
session_start();
$url = "https://restcountries.com/v3.1/all";
$response = file_get_contents($url);
$countries = json_decode($response, true);

//sort countries alphabetically
usort($countries, function ($a, $b) {
    return strcmp($a['name']['common'], $b['name']['common']);
});

         echo "<select name='country'>";
        echo "<option value=''>Select countries</option>";
         foreach ($countries as $country) {
        if (isset($country['currencies'])) {
        $countryName = $country['name']['common'];
     $currencyCode = array_keys($country['currencies'])[0];
      $currencySymbol = $country['currencies'][$currencyCode]['symbol'];
                    
                   
      echo "<option value='$countryName' data-currency='$currencyCode' check-symbol='$currencySymbol'>$countryName: $currencyCode ($currencySymbol)</option>";
      };
                      
  };
 echo "</select>";
                   
                       
 echo "<button>CHECK Country</button>";
 echo "<input type='hidden' name='currencyCode' id='currencyCode'>";
echo "<input type='hidden' name='currencySymbol' id='currencySymbol'>";

 ?>

 <?php
$countries = array('nigeria' => 'NG', 'ghana' => 'GH', 'USA' => 'US', 'U.K' => 'U.K');

echo "<select>";
echo "<option> select countries here </option>";
foreach ($countries as $country => $value) {
    echo "<option value='$value'>{$country}: $value</option>";
}
