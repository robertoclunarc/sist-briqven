<?php
// Default Header
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization,Content-Range, Content-Disposition, Content-Description');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("MIME-Version: 1.0");
header("Content-type:text/html;charset=UTF-8");
// Response type header
//header('Content-Type: application/json');
header('Content-Type: image/bmp');
?>