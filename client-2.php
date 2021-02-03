<?php


// 

$post_ID = $_GET['ID'] ?? '';

if ($post_ID) {
  $post_ID = intval($post_ID);
}

// 

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'http://127.0.0.1/klients/api/client'.($post_ID ? '/'.$post_ID : ''),
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
    <link rel="stylesheet" href="assets/reset-2.css">
    <link rel="stylesheet" href="assets/styles-2.css">
  </head>
  <body>
    <header class="header">
      <?php if ($data['client'] ?? null) { ?>
        <h1 class="title is-large">Editar cliente:</h1>
        <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
      <?php } else { ?>
        <h1 class="title is-large">Cadastrar novo cliente:</h1>
        <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
      <?php } ?>
    </header>
    <form class="content">
      <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus, rerum.</p>
      <ul class="grid">
        <li class="grid-item no-1">
          <div class="content-control">
            <label class="text" for="name">Nome completo:</label>
            <input class="input" id="CPF" name="CPF" type="text" placeholder="Insira o nome aqui..." required>
          </div>
        </li>
        <li class="grid-item no-2">
          <div class="content-control">
            <label class="text" for="CPF">CPF:</label>
            <input class="input" id="CPF" name="CPF" type="text" placeholder="Insira o CPF aqui..." required>
          </div>
        </li>
        <li class="grid-item no-3">
          <div class="content-control">
            <label class="text" for="name">Data de nascimento:</label>
            <input class="input" type="date" placeholder="Selecione a data aqui..." required>
          </div>
        </li>
      </ul>
      <nav class="content-options">
        <ul class="grid">
          <li class="grid-item no-1">
            <a class="button" href="index-2.php">Retornar à lista</a>
          </li>
          <?php if ($data['client'] ?? null) { ?>
            <li class="grid-item no-2">
              <a class="button is-red" href="#">Remover</a>
            </li>
            <li class="grid-item no-3">
              <button class="button is-green" type="submit">Salvar alterações</button>
            </li>
          <?php } else { ?>
            <li class="grid-item no-2">
              <button class="button is-green" type="submit">Adicionar novo</button>
            </li>
          <?php } ?>
        </ul>
      </nav>
    </form>
  </body>
</html>