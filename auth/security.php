<?php

//IP Address, Country and city
try {
    $ip = "8.8.8.8";
    $location = file_get_contents("http://ip-api.com/json/{$ip}");
    $data = json_decode($location, true);
    $state = $data['[regionName]'];
    $country = $data['country'];
    $city = $data['city'];
    //device and OS
    $device = $_SERVER['HTTP_USER_AGENT'];
    echo $device;
    echo "<pre>";
    print_r($data);
    echo "</pre>";
} catch (\Throwable $th) {
    echo "error because" . $th;
}
