<?php 
//hash password_hash 
     echo "<h2> uso de password_hash</h2>";
     $r=password_hash("hola mundo", PASSWORD_DEFAULT);
     echo "resultado: " . $r . "<br>";
     //verifica si el password es valido
     if (password_verify("hola mundo", $r)){
        echo "Password is valid";
     } else{
        echo "Invalid password";
     }


    echo "<h1> funcion hash </h1>";


    echo "<h1> Texto Original: Hola mundo </h1>";

    echo "<h2> Hola mundo MD5 </h2>";
    echo hash ('md5', "hola mundo")."<br>";



    echo "<h2> Hola mundo SHA1 </h2>";
    echo hash ('sha1', "hola mundo")."<br>";

    //todos los hash de php
     echo "<h2> Todos los hash con Hola Mundo </h2>";
    $data="hola mundo";
    foreach(hash_algos()as $v ){
        $r= hash($v,$data);
        echo $v . " - " . $r . " - " . strlen($r) . "<br>";
    }
?>