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
        
        $data_ID = intval($data_ID);
        
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


// Mais chequagens importantes em cada tipo de REQUEST

switch ($json_request) {
  
  // CREATE, READ, UPDATE ou DELETE (CRUD ;D)
  
  case 'CREATE': case 'READ': case 'UPDATE': case 'DELETE':
    
    // No caso de READ, UPDATE e DELETE
    // Confirmar primeiro a existencia de um registro com o ID pasado na URL
    // Se nao existir criar mensagem de erro
    
    if ($json_request !== 'CREATE') {
      
      $_query = $data->query("SELECT * FROM clients WHERE ID = ".$data_ID." LIMIT 1");
      
      $data_client = $_query->fetch(PDO::FETCH_ASSOC);
      
      if (!$data_client) {
        
        $json_message = 'Client with ID '.$data_ID.' not found';
        
        break;
        
      }
      
    }
    
    // 
    
    if ($json_request === 'READ') {
      
      $json_message = 'Client with ID '.$data_ID.' read successfully';
      
      break;
      
    }
    
    // 
    
    if ($json_request === 'DELETE') {
      
      $_query = $data->query("DELETE * FROM clients WHERE ID = ".$data_ID);
      
      $json_message = 'Client with ID '.$data_ID.' removed successfully';
      
      break;
      
    }
    
    // Se 
    
    $post_name = $_POST['name'] ?? '';
    $post_cpf = $_POST['cpf'] ?? '';
    $post_date = $_POST['date'] ?? '';
    
    if (!$post_name || !$post_cpf || !$post_date) {
      
      $json_message = $json_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $json_message .= ': one or more required fields are empty';
      
      break;
      
    }
    
    // 
    
    if (!preg_match('/^([0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2})$/', $post_name)) {
      
      $json_message = $json_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $json_message .= ': name value is invalid';
      
      break;
      
    }
    
    // 
    
    if (!preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $post_cpf)) {
      
      $json_message = $json_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $json_message .= ': CPF value is invalid';
      
      break;
      
    }
    
    // 
    
    if (!preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $post_date)) {
      
      $json_message = $json_request === 'CREATE' ? 'Client failed to create' : 'Client failed to update';
      $json_message .= ': birth date value is invalid';
      
      break;
      
    } else {
      
      $post_unix = strtotime($post_date);
      
    }
    
    // 
    
    if ($json_request === 'CREATE') {
      
      $data_query = $data->query("");
      
    } else {
      
      $data_query = $data->query("");
      
    }
    
    break;
    
  // 
  
  case 'LIST':
    
    $_query = $data->query("SELECT * FROM clients");
    
    $data_clients = $_query->fetchAll(PDO::FETCH_ASSOC);
    
    $json_message = $data_clients ? 'List of clients read successfully' : 'List of clients not found';
    
    break;
    
  // 
  
  case 'NONE':
    
    $json_message = 'No request found!';
    
}


// Agrupar os dados desejados para enviar ao usuário em um único Array
// Converter o Array em JSON, configurar os corretos HEADERS e enviar

$json = [
  'request' => $json_request,
  'message' => $json_message,
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

