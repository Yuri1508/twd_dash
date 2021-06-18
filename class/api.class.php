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
        // Closing
        // curl_close($ch);

        
        $t_result = json_decode($j_data,true);

        // get all time 
        foreach($t_result[$Dstart]["T"] as $stime => $value){

            $t_time['T'][] = $stime;
        }

        foreach($t_result[$Dstart]["M"] as $stime => $value){

            $t_time['M'][] = $stime;
        }

        // build array t_data
        foreach($t_time['T'] as $v){

            $t_data['data']['T'][] = $t_result[$Dstart]["T"][$v];
        }

        foreach($t_time['M'] as $v){

            $t_data['data']['M'][] = $t_result[$Dstart]["M"][$v];
        }


        return json_encode($t_data);
        }
    }


?>