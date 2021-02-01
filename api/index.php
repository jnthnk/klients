<?php


// Try to connect to a database called 'klients' on localhost
// This database must be created before in phpmyadmin
// Send a JSON error message if authentication fails

try {
  
  $data = new PDO('mysql:host=localhost;dbname=klients', 'root', '');
  
} catch (PDOException $_error) {
  
  header('Content-Type:application/json');
  header('HTTP/1.0 500 Internal Server Error');
  
  echo json_encode([
    'message' => 'Database Connection Error: '.$_error->getMessage()
  ]);
  
  exit;
  
}


// 

$_path = $_GET['path'] ?? '';

$json_request = 'NONE';

if ($_path) {
  
  $_paths = explode('/', $_path);
  
  if ($_paths[0] === 'clients') {
    
    $json_request = 'GET ALL';
    
  } elseif ($_paths[0] === 'client') {
    
    $_subpath = $_paths[1] ?? '';
    
    $_method = $_SERVER['REQUEST_METHOD'];
    
    if ($_subpath) {
      
      
      
    } else {
      
      if ($_method === 'POST') {
        
        $json_request = 'ADD ONE';
        
      } else {
        
        $json_request = 'GET ONE';
        $json_message = 'To get certain client data, provide the ID';
        
      }
      
    }
    
  }
  
}


// 




// 

$json = [
  'request' => $json_request,
  'message' => $json_message,
];

// 

header('Content-Type:application/json');

echo json_encode($json, JSON_UNESCAPED_UNICODE);

