<?php
// creating folder by cliens ip
$ip = getIpAddress();
if (!file_exists("storage/$ip/")) {
    mkdir("storage/$ip/");
}

// database connection (config your connection in config.json)
$config = json_decode(file_get_contents("config.json"));

/** Helper functions write below */
function query($koneksi, $string)
{
    $array = [];
    $query = mysqli_query($koneksi, $string);
    if (mysqli_affected_rows($koneksi) > 0) {
        while ($data = mysqli_fetch_assoc($query)) {
            $array[] = $data;
        }
        return $array;
    }
    return false;
}

function getIpAddress()
{
    return $ipaddress = $_SERVER['HTTP_CLIENT_IP']
        ?? $_SERVER["HTTP_CF_CONNECTING_IP"] # when behind cloudflare
        ?? $_SERVER['HTTP_X_FORWARDED']
        ?? $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['HTTP_FORWARDED']
        ?? $_SERVER['HTTP_FORWARDED_FOR']
        ?? $_SERVER['REMOTE_ADDR']
        ?? '0.0.0.0';
}

function writeCSV($array, $targetfilename)
{
    $file = fopen($targetfilename, "w");
    foreach ($array as $data) {
        fputcsv($file, $data);
    }

    fclose($file);
}
