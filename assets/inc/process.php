<?php

/** Catch search terms via POST **/
if(isset($_POST['s']) AND $_POST['s'] !== ''){
    $feed = searchApi($_POST['s']);
    echo json_encode($feed);
}

/**
 * Search tweets by hashtag term
 * @param String $search 
 * @return json $results
 * @see Twitter API v1.1
 */
function searchApi($search){
    
    if(!$search OR $search === "" )
        return FALSE;
    
    $base_url = "https://api.twitter.com/1.1/search/tweets.json";
    $oauth_access_token         = "75000668-9H9LUsWx7pgTXWxH0MNsc1viRQgEwgimZEPYXI2bs";
    $oauth_access_token_secret  = "lanxd81VHbYECftvzZjA1jGtp5Z5cCKjgVVsigQimLizS";
    $consumer_key               = "NoTSj2gOL6XVWOOcWke9ATLMK";
    $consumer_secret            = "Xk2WPZ1We1QFLV8EIL51sQKQilOBkTA6YJNXLrnMA4rsIMIhhA";
    $body = array( "count" => 100, "q" => urlencode("#{$search}"), "result_type" => "recent");

    $oauth = array(
                    'oauth_consumer_key'    => $consumer_key,
                    'oauth_nonce'           => time(),
                    'oauth_signature_method'=> 'HMAC-SHA1',
                    'oauth_token'           => $oauth_access_token,
                    'oauth_timestamp'       => time(),
                    'oauth_version'         => '1.0'
                );

    $base_params = empty($body) ? $oauth : array_merge($body,$oauth);
    $base_info = buildBaseString($base_url, 'GET', $base_params);
    $base_url = empty($body) ? $base_url : $base_url . "?" . http_build_query($body);

    $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
    $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
    $oauth['oauth_signature'] = $oauth_signature;

    $header = array(buildAuthorizationHeader($oauth), 'Expect:');
    $options = array( 
                        CURLOPT_HTTPHEADER      => $header,
                        CURLOPT_HEADER          => false,
                        CURLOPT_URL             => $base_url,
                        CURLOPT_RETURNTRANSFER  => true,
                        CURLOPT_SSL_VERIFYPEER  => false
                    );

    $feed = curl_init();
    curl_setopt_array($feed, $options);
    $response = curl_exec($feed);
    curl_close($feed);
    return  json_decode($response);
}

function buildBaseString($baseURI, $method, $params){
    $r = array(); 
    ksort($params);
    foreach($params as $key=>$value)
        $r[] = "$key=" . rawurlencode($value); 

    return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r)); 
}

function buildAuthorizationHeader($oauth)
{
    $r = 'Authorization: OAuth '; 
    $values = array(); 
    foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\""; 
    $r .= implode(', ', $values); 
    return $r; 
}