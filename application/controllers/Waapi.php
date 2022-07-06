<?php
/*
-- ---------------------------------------------------------------
-- ---------------------------------------------------------------
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Waapi extends CI_Controller {
	function __construct(){
        parent::__construct();
    }
	function intCodeRandom($length = 6){
        $char="0123456789"; // Define characters which you needs in your random string
        $random=substr(str_shuffle($char), 0, $length); // Put things together.
        return $random;
    }
    function wasid(){
        $hp = $this->input->post('nohp');
        $msg = $this->input->post('message');
        //$msg = "#OTP ".$code." Please enter this 6 digit number into the Application Mobile";
        $curl = curl_init();
        $token = "gIlo07trqUVeFicinlicv4AYOPkHtE7ZCooZsJ6RnbjY1eqfwRNLrQzuHvuEPzPr";
        $data = [
            'phone' => $hp,
            'message' => $msg,
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $err  = curl_error($curl);
        curl_close($curl);
        //var_dump($response);
        if ($err) {
          //echo 'cURL Error #:'. $err;
          $datax["responce"] = true;
          $datax["hp"] = $hp;
          $datax["resp"] = "".$err;
        } else {
          //echo $response;
          $datax["responce"] = true;
          $datax["hp"] = $hp;
          $datax["resp"] = $response;
        }
        echo json_encode($datax);

        //echo "<pre>";
        //print_r($result);
    }
	function sendwamsg($hp,$code){
        //$hp = $this->uri->segment(3);
        //$msg = $this->uri->segment(4);
        $msg = "#OTP ".$code." Please enter this 6 digit number into the Application Mobile";
        $curl = curl_init();
        $token = "gIlo07trqUVeFicinlicv4AYOPkHtE7ZCooZsJ6RnbjY1eqfwRNLrQzuHvuEPzPr";
        $data = [
            'phone' => $hp,
            'message' => $msg,
        ];
        curl_setopt($curl, CURLOPT_HTTPHEADER,
            array(
                "Authorization: $token",
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_URL, "https://solo.wablas.com/api/send-message");
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        $err  = curl_error($curl);
        curl_close($curl);
        //var_dump($response);
        if ($err) {
          //echo 'cURL Error #:'. $err;
          $datax["responce"] = true;
          $datax["hp"] = $hp;
          $datax["resp"] = "".$err;
        } else {
          //echo $response;
          $datax["responce"] = true;
          $datax["hp"] = $hp;
          $datax["resp"] = $response;
        }
        echo json_encode($datax);

        //echo "<pre>";
        //print_r($result);
    }
	
}
