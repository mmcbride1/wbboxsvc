<?php

include('status.php');

class PingSite {

   var $http;
   var $curl;
   var $conf;

   /**
    * Constructor 
    *
    * Allow for 
    * multiple object
    * initializations 
    **/

   public function __construct() {

      $arg = func_get_args();

      $num = func_num_args();

      if(method_exists($this, $f='__construct'.$num)) {

         $all = array($this, $f);

         call_user_func_array($all, $arg);

      }

   }

   /**
    * Constructor1
    *
    * Initalize the
    * user site address
    * on the frontend
    **/

   public function __construct1($addr) {

      $this->http = $addr;
      $this->conf = $this->getcf();
      $this->curl = $this->getheader();
      $this->window();

   }

   /**
    * Constructor2
    *
    * Initalize the
    * user site address
    * on the backend
    **/

   public function __construct2($addr, $flag) {

      $this->http = $addr;
      $this->conf = $this->getcf();
      $this->curl = $this->getheader();

   }

   /**
    * Load the 
    * configuration
    * file 
    **/

   private function getcf() {

      $ini = parse_ini_file('params.ini');

      return $ini;

   }

   /**
    * Performs the 
    * primary ping 
    * operation that
    * gets the site 
    * response info
    **/

   public function getheader() {

      $beat = $this->http;

      $ch = curl_init();

      curl_setopt_array($ch, array(

      CURLOPT_URL => $beat,
      CURLOPT_RETURNTRANSFER => true

      ));

      curl_exec($ch);

      $stat = curl_getinfo($ch);

      curl_close($ch);

      return $stat;

   }

   /**
    * Simply reiterates
    * the address 
    * associated with 
    * the request 
    **/

   public function geturli() {

      $u = $this->curl['url'];

      $u = explode("/", $u);

      return $u[2];

   }

   /**
    * Gets the http
    * total response 
    * time from the 
    * request 
    **/

   public function gettime() {

      $t = $this->curl['total_time'];

      return $t;

   }

   /**
    * Gets the http
    * code from the 
    * request response
    **/

   public function getcode() {

      $c = $this->curl['http_code'];

      return $c;

   }

   /**
    * Delivers the 
    * message to 
    * accompany the 
    * response time along
    * with a indication
    * image 
    **/

   public function indicator($v) {

      // * config * //

      $idlt = $this->conf['idlt'];
      $img1 = $this->conf['img1'];
      $img2 = $this->conf['img2'];
      $img3 = $this->conf['img3'];
      $img4 = $this->conf['img4'];

      // * check response * //

      if ($v >= 0 && $v <= $idlt) {

         $s = $this->conf['good'].($idlt - $v)." ".$img1;

      }

      else if ($v > $idlt && $v <= 7) {

         $s = $this->conf['bad'].($v - $idlt)." ".$img3;
    
      }

      else if ($v > 7 && $v <= 15) {

         $s = $this->conf['bad'].($v - $idlt)." ".$img2;

      }

      else {

         $s = $this->conf['crit']." ".$img4;

      }

      return $s;

   }

   /**
    * Retrieves the 
    * information to 
    * accompany the 
    * request http
    * code 
    **/

   public function codeinfo() {
 
      $c = new StatusCodes();

      $l = $c->getcodes();

      $s = $this->getcode();

      if(array_key_exists($s, $l)) {

         return "$s $l[$s]";

      }

      else {

         return $this->conf['code'];

      }

   }

   /**
    * Determines
    * whether given
    * address is an
    * actual website
    **/

   private function isurl() {

      $u = $this->getcode();

      return $u != '0';

   }

   /**
    * Displays the 
    * collection of 
    * statistics 
    **/

   public function display() {

      if ($this->isurl()) {

         $m1 = $this->conf['t'];
         $m2 = $this->conf['c'];

         $u = $this->geturli();
         $t = $this->gettime();
         $c = self::codeinfo();

         echo '<h1>'.$u.'</h1>';
         echo '<h1>'.$m1.$t.'</h1>';
         echo '<h1>'.$m2.$c.'</h1>';

      }

      else {

         echo $this->conf['404'];

      }

      return;

   }

   /**
    * Delivers the 
    * response time 
    * and whether it is
    * within the ideal 
    * range
    **/

   public function infotime() {
     
      $t = $this->gettime();

      if($this->isurl()) {
      
         $n = self::indicator($t);

         echo '<h3>'.$n.'</h3>';

      }

      return;

   }

   /**
    * Delivers the stats
    * window that will 
    * display the response 
    * statistics 
    **/

   public function window() {

      /* create window */

      echo '<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>';
      echo '<script src="script/window.js"></script>';
      echo "<script>loadStyle('window.css');</script>";
      echo '<script>';
      echo '$(document).ready(function() {';
      echo '$("#takeoff").load("window.php");';
      echo '$.ajaxSetup({ cache: false });';
      echo '});';
      echo '</script>';
 
   }

}

?>
