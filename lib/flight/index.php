<?php
/**
 * Created by PhpStorm.
 * User: desarrollador3
 * Date: 11-05-2017
 * Time: 9:21
 */


require 'flight/flight.php';

Flight::route('/', function(){
    echo 'hello world!';
});