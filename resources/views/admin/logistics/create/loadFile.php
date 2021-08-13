<?php
$resp = $_GET['response'];    
// $data1 = json_decode($resp, true);
// echo json_encode($data1); 
file_put_contents(base_path('/storage/json/data.json'), stripslashes($resp));
?>
