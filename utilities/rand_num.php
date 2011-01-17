<?php
    function gen_seed() {
  $localtime = localtime();
  $num1 = (float)($localtime[0].$localtime[1].$localtime[2].$localtime[3]);
  $num2 = (float)($localtime[3].$localtime[2].$localtime[1].$localtime[0]);;
  return (float) $num1 + ((float) $num2 * 10101010);
}

function  gen_rand() {
    $length = 24;
    $characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
    $string = ''; 
			
	for ($i = 0; $i < $length; $i++) {
		$string .= $characters[mt_rand(0, (strlen($characters)-1))];
    }

	return $string;
}

$activation_code = gen_rand();

echo "Activation: [".$activation_code."] with length: [".strlen($activation_code)."]<br>";
?>
