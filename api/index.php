<?php


// Tentar conetar a una base de dados em localhost
// Essa base já deve ter sido criada por phpmyadmin
// Retornar mensagens de erro em JSON em caso de falhar a conexao

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


// Analizar o routing atual sobre a API, junto com o tipo de REQUEST
// e determinar objetivamente o tipo de request em um nome curto
// Tb salvar o ID do cliente no caso

$data_request = 'NONE';

$_path = $_GET['path'] ?? '';

if ($_path) {
  
  $_paths = explode('/', $_path);
  
  if ($_paths[0] === 'clients') {
    
    $data_request = 'LIST';
    
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
        
        $data_message = "Client failed to read: ID value is empty or invalid";
        
        break;
        
      }
      
      $data_query = $data->prepare("SELECT * FROM clients WHERE ID = ? LIMIT 1");
      $data_query->execute([$data_ID]);
      $data_client = $data_query->fetch(PDO::FETCH_ASSOC);
      
      if (!$data_client) {
        
        $data_message = "Client with ID $data_ID failed to read: ID doesn't match any record";
        
        break;
        
      }
      
    }
    
    // 
    
    if ($data_request === 'READ') {
      
      $data_message = "Client with ID $data_ID read successfully";
      
      break;
      
    }
    
    // 
    
    if ($data_request === 'DELETE') {
      
      $data_query = $data->prepare('DELETE * FROM clients WHERE ID = ?');
      $data_result = $data_query->execute([$data_ID]);
      
      $data_message = $data_result ? "Client with ID $data_ID deleted successfully" : "Client with ID $data_ID failed to delete: unexpected database error";
      
      break;
      
    }
    
    // Se 
    
    $post_name = $_POST['name'] ?? '';
    $post_CPF = $_POST['cpf'] ?? '';
    $post_date = $_POST['date'] ?? '';
    
    if (!$post_name || !$post_CPF || !$post_date) {
      
      $data_message = $data_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $data_message .= ': one or more required fields are empty';
      
      break;
      
    }
    
    // 
    
    if (!preg_match('/^[a-zA-Z \p{L}]+$/ui', $post_name)) {
      
      $data_message = $data_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $data_message .= ': name value is invalid';
      
      break;
      
    }
    
    // 
    
    if (!preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $post_CPF)) {
      
      $data_message = $data_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $data_message .= ': CPF value is invalid';
      
      break;
      
    }
    
    // 
    
    if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $post_date)) {
      
      $data_message = $data_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $data_message .= ': birth date value is invalid';
      
      break;
      
    } else {
      
      $post_unix = strtotime($post_date);
      
    }
    
    // 
    
    if ($data_request === 'CREATE') {
      
      $data_query = $data->prepare('INSERT INTO clients (name, CPF, date) VALUES (?, ?, ?)');
      $data_result = $data_query->execute([$post_name, $post_CPF, $post_unix]);
      
      $data_message = $data_result ? 'Client created successfully' : 'Client failed to create: unexpected database error';
      
    } else {
      
      $data_query = $data->prepare('UPDATE clients SET name = ?, CPF = ?, date = ? WHERE ID = ?');
      $data_result = $data_query->execute([$post_name, $post_CPF, $post_unix, $data_ID]);
      
      $data_message = $data_result ? "Client with ID $data_ID updated successfully" : "Client with ID $data_ID failed to update: unexpected database error";
      
    }
    
    break;
    
  // 
  
  case 'LIST':
    
    $_query = $data->query("SELECT * FROM clients");
    
    $data_clients = $_query->fetchAll(PDO::FETCH_ASSOC);
    
    $data_message = $data_clients ? 'List of clients read successfully' : 'List of clients not found';
    
    break;
    
  // 
  
  case 'NONE':
    
    $data_message = 'No request found!';
    
}


// Agrupar os dados desejados para enviar ao usuário em um único Array
// Converter o Array em JSON, configurar os corretos HEADERS e enviar

$json = [
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

echo json_encode($json, JSON_UNESCAPED_UNICODE);

