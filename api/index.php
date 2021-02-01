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


// Para ler, atualizar e apagar um cliente, é preciso confirmar a existencia da fileira na database primeiro
// Entao, pre-carregar o cliente desejado ou a lista de todos os clientes em cada caso

if ($json_request === 'READ' || $json_request === 'UPDATE' || $json_request === 'DELETE') {
  
  $_query = $data->query("SELECT * FROM clients WHERE ID = ".$data_ID." LIMIT 1");
  
  $data_client = $_query->fetch(PDO::FETCH_ASSOC);
  
} elseif ($json_request === 'LIST') {
  
  $_query = $data->query("SELECT * FROM clients ORDER_BY name DESC");
  
  $data_clients = $_query->fetchAll(PDO::FETCH_ASSOC);
  
}


// Mais chequagens importantes em cada tipo de REQUEST, como a validacao de variáveis

switch ($json_request) {
  
  // 
  
  case 'CREATE':
    
    
    
  // 
  
  break; case 'READ':
    
    $json_message = $data_client ? 'Client with ID '.$data_ID.' read successfully' : 'Client with ID '.$data_ID.' not found';
    
  // 
  
  break; case 'UPDATE':
    
    if (!$data_client) {
      
      $json_message = 'Client with ID '.$data_ID.' not found';
      
      break;
      
    }
    
    
    
  // 
  
  break; case 'DELETE':
    
    if (!$data_client) {
      
      $json_message = 'Client with ID '.$data_ID.' not found';
      
      break;
      
    }
    
    
    
  // 
  
  break; case 'LIST':
    
    $json_message = $data_client ? 'List of clients read successfully' : 'List of clients not found';
    
  // 
  
  break; case 'NONE':
    
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

