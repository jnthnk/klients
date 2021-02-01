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
    'message' => 'Database Error: '.$_error->getMessage()
  ]);
  
  exit;
  
}


// 

$_path = $_GET['path'] ?? '';

$json_request = 'NONE';

if ($_path) {
  
  $_paths = explode('/', $_path);
  
  if ($_paths[0] === 'clients') {
    
    $json_request = 'LIST';
    
  } elseif ($_paths[0] === 'client') {
    
    $data_ID = $_paths[1] ?? '';
    
    $_method = $_SERVER['REQUEST_METHOD'];
    
    if ($data_ID) {
      
      if (ctype_digit($data_ID)) {
        
        switch ($_method) {
          case 'PUT': case 'POST':
            $json_request = 'UPDATE';
          break; case 'DELETE':
            $json_request = 'DELETE';
          break; default:
            $json_request = 'READ';
        }
        
      } else {
        
        $json_request = 'READ';
        $json_message = 'To read certain client data, provide a valid integer ID';
        
      }
      
    } else {
      
      if ($_method === 'POST') {
        
        $json_request = 'CREATE';
        
      } else {
        
        $json_request = 'READ';
        $json_message = 'To read certain client data, provide an ID';
        
      }
      
    }
    
  }
  
}


// 

if ($json_request === 'READ' || $json_request === 'UPDATE' || $json_request === 'DELETE') {
  
  
  
}


// 

switch ($json_request) {
  
  // 
  
  case 'CREATE':
    
    
    
  // 
  
  break; case 'READ':
  
    
    
  // 
  
  break; case 'UPDATE':
    
    
    
  // 
  
  break; case 'DELETE':
    
    
    
  // 
  
  break; default;
    
    
    
}


// 

$json = [
  'request' => $json_request,
  'message' => $json_message,
];

if (isset($json_clients)) {
  $json['clients'] = $json_clients;
}

if (isset($json_client)) {
  $json['client'] = $json_client;
}

// 

header('Content-Type:application/json');

echo json_encode($json, JSON_UNESCAPED_UNICODE);

