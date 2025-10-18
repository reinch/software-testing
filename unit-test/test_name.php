<?php
// file test_age.php
require 'validator.php';

// test case 1 - valid age
try{
    $result = validateName("1111");
    echo "PASS : Nama 'John' Masuk akal.\n";
}catch (Exception $e){
    echo "FAIL : Nama 'John' Tidak masuk akal. Error : ". $e->getMessage() ."\n";
}