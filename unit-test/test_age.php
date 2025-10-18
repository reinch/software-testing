<?php
require 'validator.php';

try{
    $result = validateAge(19);
    echo "PASS : Umur 19 Masuk akal.\n";
}catch (Exception $e){
    echo "FAIL : Umur 19 Tidak masuk akal. Error : ". $e->getMessage() ."\n";
    
}

try{
    $result = validateAge(-5);
    echo "  FAIL: Umur -5 seharusnya ditolak\n";
}catch (Exception $e){
    echo "PASS Umur -5 ditolak. Error: ".$e->getMessage()."\n";
}