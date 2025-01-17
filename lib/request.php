<?php 

/**
* Arena::Request
*/

class Request extends Arena
{

  function __construct($path, $options = null) {
    include 'config.php';

    $base_url = 'http://api.are.na/v2/';
    $url = $base_url . $path . $this->set_url_params($options);

    $this->request = curl_init($url);
    curl_setopt($this->request, CURLOPT_RETURNTRANSFER, true);
    
    // set X-AUTH-TOKEN if defined
    if ($config['auth_token'] !== ''){
      curl_setopt($this->request, CURLOPT_HTTPHEADER, array('X-AUTH-TOKEN: '.$config['auth_token']));
    }

    $this->data = curl_exec($this->request);
    curl_close($this->request);

    if($this->data !== null){
      $data = mb_convert_encoding($this->data, 'UTF-8', 'ASCII,UTF-8,ISO-8859-1');
      $this->data = json_decode($data, true);
      return $this;
    }
  }

  function set_url_params ($options) {
    if($options !== null) {
      
      $str = "?";
      $idx = 1;
      $options_length = count($options);

      foreach ($options as $key => $value) {
        $str .= "$key=$value";
        if($idx !== $options_length){
          $str .= "&";
          $idx++;
        }
      }

      return $str;

    }
  }

}

?>