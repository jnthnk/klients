<?php


// Tentar conetar a una base de dados em localhost
// Essa base já deve ter sido criada por phpmyadmin
// Retornar mensagens de erro em JSON em caso de falhar a conexao

try {
  
  $data = new PDO('mysql:host=localhost;dbname=klients;charset=utf8', 'root', '');
  
} catch (PDOException $_error) {
  
  header('Content-Type:application/json');
  header('HTTP/1.0 500 Internal Server Error');
  
  echo json_encode([
    'code' => 10,
    'message' => 'DATABASE CONNECTION ERROR ('.$_error->getMessage().')',
  ]);
  
  exit;
  
}


// Analizar o routing atual sobre a API, junto com o tipo de REQUEST
// e determinar objetivamente o tipo de request em um nome curto
// Tb salvar o ID do cliente no caso

// O tipo de requisicao
// NONE --> 
// LIST --> 
// FIND --> 
// CREATE --> 
// READ --> 
// UPDATE --> 
// DELETE --> 

$data_request = 'NONE';

$_path = $_GET['path'] ?? '';

if ($_path) {
  
  $_paths = explode('/', $_path);
  
  if ($_paths[0] === 'clients') {
    
    $data_name = $_paths[1] ?? '';
    $data_request = $data_name ? 'FIND' : 'LIST';
    
  } elseif ($_paths[0] === 'client') {
    
    $data_ID = $_paths[1] ?? '';
    
    $_method = $_SERVER['REQUEST_METHOD'];
    
    if ($data_ID) {
      
      if ($_method === 'PUT' || $_method === 'POST') {
        $data_request = 'UPDATE';
      } elseif ($_method === 'DELETE') {
        $data_request = 'DELETE';
      } else {
        $data_request = 'READ';
      }
      
    } else {
      
      if ($_method === 'POST') {
        $data_request = 'CREATE';
      } else {
        $data_request = 'READ';
      }
      
    }
    
  }
  
}


// Mais chequagens importantes em cada tipo de REQUEST

switch ($data_request) {
  
  // CREATE, READ, UPDATE ou DELETE (CRUD ;D)
  
  case 'CREATE': case 'READ': case 'UPDATE': case 'DELETE':
    
    // No caso de READ, UPDATE e DELETE, primeiro confirmar a existencia de um registro com o ID pasado na URL
    // Se o ID for inválido ou nao existir registro com esse ID, criar mensagem de erro no caso
    
    if ($data_request !== 'CREATE') {
      
      if ($data_ID && ctype_digit($data_ID)) {
        
        $data_ID = intval($data_ID);
        
      } else {
        
        $data_code = 13;
        
        break;
        
      }
      
      $data_query = $data->prepare("SELECT * FROM clients WHERE ID = ? LIMIT 1");
      $data_result = $data_query->execute([$data_ID]);
      
      if ($data_result) {
        
        $data_client = $data_query->fetch(PDO::FETCH_ASSOC);
        
        if (!$data_client) {
          
          $data_code = 14;
          
          break;
          
        }
        
      } else {
        
        $data_code = 11;
        
        break;
        
      }
      
    }
    
    // 
    
    if ($data_request === 'READ') {
      
      $data_code = 4;
      
      break;
      
    }
    
    // 
    
    if ($data_request === 'DELETE') {
      
      $data_query = $data->prepare('DELETE FROM clients WHERE ID = ?');
      $data_result = $data_query->execute([$data_ID]);
      $data_code = $data_result ? 6 : 11;
      
      break;
      
    }
    
    //  
    
    $post_name = $_POST['name'] ?? '';
    $post_CPF = $_POST['cpf'] ?? '';
    $post_date = $_POST['date'] ?? '';
    
    if (!$post_name || !$post_CPF || !$post_date) {
      
      $data_code = 15;
      
      break;
      
    }
    
    if (!preg_match('/^[a-zA-Z \p{L}]+$/ui', $post_name)) {
      
      $data_code = 16;
      
      break;
      
    }
    
    if (!preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $post_CPF)) {
      
      $data_code = 17;
      
      break;
      
    }
    
    if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $post_date)) {
      
      $data_code = 18;
      
      break;
      
    } else {
      
      $post_unix = strtotime($post_date);
      
    }
    
    // 
    
    if ($data_request === 'CREATE') {
      
      $data_query = $data->prepare('INSERT INTO clients (name, CPF, date) VALUES (?, ?, ?)');
      $data_result = $data_query->execute([$post_name, $post_CPF, $post_unix]);
      $data_code = $data_result ? 3 : 11;
      
    } else {
      
      $data_query = $data->prepare('UPDATE clients SET name = ?, CPF = ?, date = ? WHERE ID = ?');
      $data_result = $data_query->execute([$post_name, $post_CPF, $post_unix, $data_ID]);
      $data_code = $data_result ? 5 : 11;
      
    }
    
    break;
    
  // 
  
  case 'LIST':
    
    $data_query = $data->query("SELECT * FROM clients");
    $data_clients = $data_query->fetchAll(PDO::FETCH_ASSOC) ?: [];
    $data_code = 1;
    
    break;
    
  // 
  
  case 'NONE':
    
    $data_code = 12;
    
}


// 

$data_message = [
  1 => 'LIST CLIENTS',
  2 => 'FIND CLIENTS',
  3 => 'CREATE CLIENT',
  4 => 'READ CLIENT',
  5 => 'UPDATE CLIENT',
  6 => 'DELETE CLIENT',
  7 => '',
  8 => '',
  9 => '',
  10 => 'DATABASE CONNECTION ERROR',
  11 => 'DATABASE EXECUTION ERROR',
  12 => 'NO REQUEST',
  13 => 'CLIENT ID INVALID',
  14 => 'CLIENT NOT FOUND',
  15 => 'CLIENT FIELD MISSING',
  16 => 'CLIENT NAME INVALID',
  17 => 'CLIENT CPF INVALID',
  18 => 'CLIENT DATE INVALID',
  19 => '',
  20 => '',
][$data_code] ?? '';

// 

$json = [
  'code' => $data_code,
  'message' => $data_message,
];

if ($data_clients ?? null) {
  $json['clients'] = $data_clients;
}

if ($data_client ?? null) {
  $json['client'] = $data_client;
}

// 

header('Content-Type:application/json');

if ($data_code === 18) {
  header('HTTP/1.0 500 Internal Server Error');
}

echo json_encode($json, JSON_UNESCAPED_UNICODE);

