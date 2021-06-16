<?php

    class twd {
        public function getData($Dstart,$Dend){

        $url = "http://api.aremiti.net:31415/rest-api/aremiti/horaires?date_debut=". $Dstart ."&date_fin=". $Dend;
        //  Initiate curl
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $j_data=curl_exec($ch);
        // // Closing
        curl_close($ch);

        return $j_data;
        }
    }


?>