<?php

    /** Catch search terms via POST **/
    if(isset($_POST['s']) AND $_POST['s'] !== '' AND !isset($_POST['export'])){
        $feed = searchApi($_POST['s']);
        echo json_encode($feed);
    }
    /** Catch export action POST **/
    if(isset($_POST['export']) AND $_POST['export'] == TRUE){
        $feed = searchApi($_POST['s']);
        $lil_array = array();
        foreach ($feed->statuses as $myTwit) {
            $lil_array[] = array( 
                                "username"  =>  $myTwit->user->screen_name,
                                "content"   =>  $myTwit->text, 
                                "datetime"  =>  $myTwit->created_at, 
                                "user_profile_url" =>  "https://twitter.com/".$myTwit->user->screen_name  
                            );
        }
        return exportAsCsv($lil_array);
        exit;
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

    function buildAuthorizationHeader($oauth){
        $r = 'Authorization: OAuth '; 
        $values = array(); 
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\""; 
        $r .= implode(', ', $values); 
        return $r; 
    }

    /**
     * Export result_set set as csv
     * @param Array $array
     * @param String $filename
     * @return Array $data_set
     */
    function exportAsCsv($array = array(), $filename = "tweets-search-results.csv"){

        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        $content =  ob_get_clean();
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
        echo $content;
    }