<?php
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

$host = 'localhost';
$dbname = 'event_calender5'; 
$username = 'root'; 
$password = '';

// Create connection
$connection = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
echo "<br><br>";

// SQL query to fetch data from users table
$sql = "SELECT * FROM users";

// Execute query and get result
$result = mysqli_query($connection, $sql);

// Check if query execution was successful
if (!$result) {
    echo "Error: " . mysqli_error($connection);
    exit;
}

// Fetch associative array of fetched rows
// while ($data = mysqli_fetch_assoc($result)) {
//     echo "Email: " . $data['email'] . "<br>";
//     echo "Password: " . $data['password'] . "<br>";

    echo "<hr>";

    // Decrypt password
    // $ciphering = "AES-128-CTR";
    // // $encrypt_password = $data['password'];
    // $decryption_iv = '1234567891011121';
    // $options = 0;
    // $decryption_key = "Arcis-Golf";

    //    $decryption_password = openssl_decrypt('613433c6d47cbbe75116e9c8971c7f0b7f73af07517b18379ddef0a5d2c0c905', $ciphering, $decryption_key, $options, $decryption_iv);


    //    echo "Decrypted Password: " . $decryption_password . "<br>";

       echo "<hr>";

    $password = "sandeep@mindwebtree";
    $encrpytpassword = Crypt::encryptString($password);
    $decrpyt = Crypt::decryptString($encrpytpassword);
          
    echo "Original Password ".$password."<br>";
    echo "Hash Password ".$encrpytpassword."<br>";
    echo "Decrpyt Password ".$decrpyt;

    echo "<hr>";

//    }
// echo "<hr>";

    // Decrypt password
    // $ciphering = "AES-128-CTR";
    // // $encrypt_password = $data['password'];
    // $decryption_iv = '1234567891011121';
    // $options = 0;
    // $decryption_key = "Arcis-Golf";

    //    $decryption_password = openssl_decrypt($hashpassword, $ciphering, $decryption_key, $options, $decryption_iv);


    //    echo "Decrypted Password: " . $decryption_password . "<br>";

       echo "<hr>";

       
//     if (Hash::check($password, $hashpassword)) {
//     echo "Password is correct!";
// } else {
//     echo "Password is incorrect.";
// }


   // Close connection
   mysqli_close($connection);
   ?>