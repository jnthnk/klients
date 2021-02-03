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

if ($data['client'] ?? '') {
  $page_button = 'Salvar alteracoes';
} else {
  $page_button = 'Adicionar novo';
}


// 

$page = [
  'title' => $page_title,
  'button' => $page_button,
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
    <form class="content">
      <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus, rerum.</p>
      <ul class="grid">
        <li class="grid-item">
          <label class="text" for="name">Nome completo:</label>
          <input class="input" id="CPF" name="CPF" type="text" placeholder="Insira o nome aqui..." required>
        </li>
        <li class="grid-item">
          <label class="text" for="CPF">CPF:</label>
          <input class="input" id="CPF" name="CPF" type="text" placeholder="Insira o CPF aqui..." required>
        </li>
        <li class="grid-item">
          <label class="text" for="name">Data de nascimento:</label>
          <input class="input" type="date" placeholder="Selecione a data aqui..." required>
        </li>
      </ul>
      <nav class="content-options">
        <ul class="grid">
          <li class="grid-item">
            <a class="link" href="client-2.php">Cadastrar Novo Cliente</a>
          </li>
          <li class="grid-item">
            <button class="link" type="submit"><?php echo $page['button'] ?></button>
          </li>
        </ul>
      </nav>
    </form>
  </body>
</html>