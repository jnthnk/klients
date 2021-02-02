<?php


// 

$post_ID = $_POST['ID'] ?? '';

if ($post_ID) {
  $post_ID = intval($post_ID);
}

// 

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'http://127.0.0.1/klients/api/client'.($post_ID ? '?ID='.$post_ID : ''),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => ['Content-type: application/json']
]);

$data = curl_exec($curl);

$data = json_decode($data, true);


// 

$page_title = 'Cadastrar Novo Cliente';


// 

$page = [
  'title' => $page_title,
  
];


?><!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <title>Klients v1.0</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles-2.css">
  </head>
  <body>
    <header class="header">
      <h1 class="title is-large"><?php echo $page['title'] ?>:</h1>
      <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
    </header>
    <nav class="options">
      <ul class="grid">
        <li class="grid-item">
          <a class="link" href="index-2.php">Voltar a lista</a>
        </li>
        <li class="grid-item">
          <a class="link" href="client-2.php">Guardar mudancas</a>
        </li>
      </ul>
    </nav>
    <section class="content">
      <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus, rerum.</p>
    </section>
    <section class="section">
      <p class="text">Debug:</p>
      <pre><?php echo var_dump($data); ?></pre>
    </section>
  </body>
</html>