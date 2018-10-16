<?php 
function fibo($fn){
    $a = [];
    for ($i=1; $i <= $fn; $i++) { 
        $a[] = 1/sqrt(5)*(pow((1+sqrt(5))/2, $i)-pow((1-sqrt(5))/2, $i));
    }
    return $a;
}

print_r(fibo(5));
 ?>