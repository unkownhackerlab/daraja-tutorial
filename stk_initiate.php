<?php
if(isset($_POST['submit'])){
    date_default_timezone_set('Africa/Nairobi');
    
    $consumerKey = 'jFELg05eVRkC3GRvVLRKCIUzuVgcR9fNtW4VsdqG9HL7UpVClBDZIQHGbhPi7PH6';
    $consumerSecret = 'leLyX6WqZUxJIgH4A4MlzjlEzlqRIQpjCl1qATclshu22nd4';
    $BusinessShortCode = '5719503';
    $Passkey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    
    $PartyA = $_POST['phone'];
    $AccountReference = '2255';
    $TransactionDesc = 'Test Payment';
    $Amount = $_POST['amount'];
    $Timestamp = date('YmdHis');    
    $Password = base64_encode($BusinessShortCode.$Passkey.$Timestamp);
    
    $headers = ['Content-Type:application/json; charset=utf8'];
    $access_token_url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $initiate_url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
    $CallBackURL = 'https://nuchdaraj-e63c9b8483af.herokuapp.com';
    
    // Get Access Token
    $curl = curl_init($access_token_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_USERPWD, $consumerKey.':'.$consumerSecret);
    $result = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $result = json_decode($result);
    
    // ADD THIS ERROR CHECK
    if(isset($result->access_token)) {
        $access_token = $result->access_token;
    } else {
        echo "Error getting access token: ";
        print_r($result);
        exit();
    }
    curl_close($curl);
    
    // STK Push Request
    $stkheader = ['Content-Type:application/json','Authorization:Bearer '.$access_token];
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $initiate_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $stkheader);
    
    $curl_post_data = array(
        'BusinessShortCode' => $BusinessShortCode,
        'Password' => $Password,
        'Timestamp' => $Timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $Amount,
        'PartyA' => $PartyA,
        'PartyB' => $BusinessShortCode,
        'PhoneNumber' => $PartyA,
        'CallBackURL' => $CallBackURL,
        'AccountReference' => $AccountReference,
        'TransactionDesc' => $TransactionDesc
    );
    
    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);
    
    // Return response to AJAX/Form
    echo $curl_response;
}
?>