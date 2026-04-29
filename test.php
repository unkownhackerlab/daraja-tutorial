<?php
$consumerKey = 'jFELg05eVRkC3GRvVLRKCIUzuVgcR9fNtW4VsdqG9HL7UpVClBDZIQHGbhPi7PH6';
$consumerSecret = 'leLyX6WqZUxJIgH4A4MlzjlEzlqRIQpjCl1qATclshu22nd4';
$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_USERPWD, $consumerKey . ':' . $consumerSecret);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if($httpCode == 200) {
    $data = json_decode($response);
    if(isset($data->access_token)) {
        echo "\n✓ SUCCESS! Access token received.\n";
    } else {
        echo "\n✗ No access token in response.\n";
    }
} else {
    echo "\n✗ Authentication failed. Your Consumer Key/Secret may not be properly linked to a Short Code.\n";
}
?>