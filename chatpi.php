<!DOCTYPE html>
<!--
Author: Diego Terrazas
Contributors: Jacob Yoder
Date Last Modified: 10/2/2023
Description: Shows user previously entered preferences for active devices
Includes: Item_handler.php
Included In: ---
Links To: ---
Links From: chat.php
-->
<?php 
    $prompt = $_POST['prompt'];
    $apiKey = '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'; // Replace with your actual API key 
    $apiEndpoint = 'https://api.openai.com/v1/engines/text-davinci-002/completions'; // The API endpoint for GPT-3.5 
    $data = array('prompt' => $prompt, 'max_tokens' => 50); 
    
    $headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $apiKey, ); 
    $ch = curl_init($apiEndpoint); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
    $response = curl_exec($ch); curl_close($ch); 
    
    if ($response === false) {
        die('Error: ' . curl_error($ch)); 
    } 
    
    $result = json_decode($response, true); // Handle the result as needed 
    echo $result['choices'][0]['text']; 
    ?>
