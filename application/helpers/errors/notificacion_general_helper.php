<?php 
    function slack($message)
    {
    $payload = array('payload' => json_encode(array('text' => $message)));
    $c = curl_init('https://hooks.slack.com/services/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');
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_POST, true);
    curl_setopt($c, CURLOPT_POSTFIELDS, $payload);
    curl_exec($c);
    curl_close($c);
    }
?>