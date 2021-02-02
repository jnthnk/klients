<?php


// 

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => 'http://127.0.0.1/klients/api/clients/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HTTPHEADER => ['Content-type: application/json']
]);

$data = curl_exec($curl);

$data = json_decode($data, true);


// 

$page = [
  'clients' => $data['clients'],
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
      <h1 class="title is-large">Ver todos os clientes:</h1>
      <p class="text is-large">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
    </header>
    <section class="content">
      <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus, rerum.</p>
      <table class="table">
        <thead class="table-head">
          <tr class="grid">
            <th class="grid-item">ID</th>
            <th class="grid-item">Nome:</th>
            <th class="grid-item">CPF:</th>
            <th class="grid-item">Data de nasc.:</th>
          </tr>
        </thead>
        <?php if ($page['clients']) { ?>
          <tbody class="table-body">
            <?php foreach ($page['clients'] as $client) { ?>
              <tr class="grid">
                <td class="grid-item"><?php echo $client['ID'] ?></td>
                <td class="grid-item"><a class="link" href="client-2.php?ID=<?php echo $client['ID'] ?>"><?php echo $client['name'] ?></a></td>
                <td class="grid-item"><?php echo $client['CPF'] ?></td>
                <td class="grid-item"><?php echo date('d/m/Y', $client['date']) ?></td>
              </tr>
            <?php } ?>
          </tbody>
        <?php } ?>
      </table>
      <nav class="content-options">
        <ul class="grid">
          <li class="grid-item">
            <a class="link" href="client-2.php">Cadastrar Novo Cliente</a>
          </li>
        </ul>
      </nav>
    </section>
  </body>
</html>