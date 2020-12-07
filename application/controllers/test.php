<?php

class Test  extends CI_Controller {

    

    function __construct() {

       parent::__construct(); 

    }  

    public function index(){       
   
       /* curl -X POST "https://api.chartmogul.com/v1/plans" \
        -u YOUR_ACCOUNT_TOKEN:YOUR_SECRET_KEY \
        -H "Content-Type: application/json" \
        -d '{            
            "name": "Basico Trimestral",
            "interval_count": 3,
            "interval_unit": "month"
            }'*/

        $data = array(
            "name" => "Basico Trimestral",
            "interval_count" => 1,
            "interval_unit" => "month"
        );

         $response = post_curl_chartmogul("plans",$data);
         print_r($response);
    }   
}
?>