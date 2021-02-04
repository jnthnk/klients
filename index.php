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
    <main class="wrap">
      <header class="header">
        <h1 class="title is-large">Ver todos os clientes:</h1>
        <p class="text is-large">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
      </header>
      <section class="content">
        <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus, rerum.</p>
        <table class="content-table">
          <thead class="content-t-head">
            <tr class="grid">
              <th class="grid-item no-1">ID</th>
              <th class="grid-item no-2">Nome:</th>
              <th class="grid-item no-3">CPF:</th>
              <th class="grid-item no-4">Data de nasc.:</th>
            </tr>
          </thead>
          <?php if ($page['clients']) { ?>
            <tbody class="content-t-body">
              <?php foreach ($page['clients'] as $client) { ?>
                <tr class="grid">
                  <td class="grid-item no-1"><?php echo $client['ID'] ?></td>
                  <td class="grid-item no-2"><a class="link" href="client.php?ID=<?php echo $client['ID'] ?>"><?php echo $client['name'] ?></a></td>
                  <td class="grid-item no-3"><?php echo $client['CPF'] ?></td>
                  <td class="grid-item no-4"><?php echo date('d/m/Y', $client['date']) ?></td>
                </tr>
              <?php } ?>
            </tbody>
          <?php } ?>
        </table>
        <nav class="content-options">
          <ul class="grid">
            <li class="grid-item is-right">
              <a class="button is-green" href="client.php">Cadastrar Novo Cliente</a>
            </li>
          </ul>
        </nav>
      </section>
    </main>
    <footer class="copyright">
      <p class="text is-small">Lorem ipsum dolor sit</p>
      <p class="text is-small">Klients v1.1</p>
    </footer>
  </body>
</html>