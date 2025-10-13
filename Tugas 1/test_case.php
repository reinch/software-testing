<?php
// file test_age.php
require 'validator.php';

// test case 1 - valid age
try{
    $result = validateAge(20);
    echo "PASS : Umur 20 Masuk akal.\n";
}catch (Exception $e){
    echo "FAIL : Umur 20 Tidak masuk akal. Error : ". $e->getMessage() ."\n";
    
}

try{
    $result = validateName("Raihan");
    echo "PASS : Nama 'Raihan' Masuk akal.\n";
}catch (Exception $e){
    echo "FAIL : Nama 'Raihan' Tidak masuk akal. Error : ". $e->getMessage() ."\n";
}

try{
    $result = validateName("    ");
    echo "PASS : Nama '    ' Masuk akal.\n";
}catch (Exception $e){
    echo "FAIL : Nama '    ' Tidak masuk akal. Error : ". $e->getMessage() ."\n";
}