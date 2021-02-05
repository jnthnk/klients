<head> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<?php

$post_ID = $_GET['ID'] ?? '';

if ($post_ID) {
  $post_ID = intval($post_ID);
}

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
  $id = $data['client']['ID'];
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
    <div class="wrap">
      <header class="header">
        <?php if ($data['client'] ?? null) { ?>
          <h1 class="title is-large">Editar cliente:</h1>
          <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
        <?php } else { ?>
          <h1 class="title is-large">Cadastrar novo cliente:</h1>
          <p class="text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident incidunt magni ipsa possimus dolorum numquam aliquid porro odio velit aliquam!</p>
        <?php } ?>
      </header>
      <form class="content" name="form">
        <p class="text">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Necessitatibus, rerum.</p>
        <ul class="grid">
          <?php if ($data['client'] ?? null) { ?>
            <li class="grid-item no-1">
              <div class="content-control is-active">
                <label class="text has-dots" for="name">Nome completo:</label>
                <input class="input" id="name" name="name" type="text" value="<?php echo $data['client']['name']; ?>" placeholder="Insira o nome aqui..." required>
              </div>
            </li>
            <li class="grid-item no-2">
              <div class="content-control is-active">
                <label class="text has-dots" for="cpf">CPF:</label>
                <input class="input" id="cpf" name="cpf" type="text" oninput="maskcpf(this)" value="<?php echo $data['client']['CPF']; ?>" placeholder="Insira o CPF aqui..." required>
              </div>
            </li>
            <li class="grid-item no-3">
              <div class="content-control is-active">
                <label class="text has-dots" for="date">Data de nascimento:</label>
                <input class="input" id="date" name="date" type="date" value="<?php echo date('Y-m-d', $data['client']['date']); ?>" placeholder="dd/mm/yyyy" required>
              </div>
            </li>
          <?php } else { ?>
            <li class="grid-item no-1">
              <div class="content-control is-active">
                <label class="text has-dots" for="name">Nome completo:</label>
                <input class="input" id="name" name="name" type="text" placeholder="Insira o nome aqui..." required>
              </div>
            </li>
            <li class="grid-item no-2">
              <div class="content-control is-active">
                <label class="text has-dots" for="CPF">CPF:</label>
                <input class="input" id="cpf" name="cpf" type="text" oninput="maskcpf(this)" placeholder="Insira o CPF aqui..." required>
              </div>
            </li>
            <li class="grid-item no-3">
              <div class="content-control is-active">
                <label class="text has-dots" for="name">Data de nascimento:</label>
                <input class="input" type="date" name="date" placeholder="dd/mm/yyyy" required>
              </div>
            </li>
          <?php } ?>
        </ul>
          <nav class="content-options">
          <ul class="grid">
            <?php if ($data['client'] ?? null) { ?>
              <li class="grid-item no-1 is-left">
                <a class="button is-red" href="#" onclick="Remove(<?php echo $id; ?>)">Remover</a>
              </li>
              <li class="grid-item no-2 is-right">
                <button class="button is-green" type="submit" onclick="Edit(<?php echo $id; ?>)">Salvar alterações</button>
              </li>
            <?php } else { ?>
              <li class="grid-item no-1 is-right">
                <button class="button is-green" type="submit" onclick="New()">Adicionar novo</button>
              </li>
            <?php } ?>
          </ul>
        </nav>
      </form>
   </div>
    <footer class="copyright">
      <p class="text is-small">Lorem ipsum dolor sit</p>
      <p class="text is-small">Klients v1.1</p>
    </footer>
  </body>

  <script>
      function Edit(id){
        if(!document.getElementById('name').value==""&&!document.getElementById('cpf').value==""&&!document.getElementById('date').value==""){
          $.ajax({
            url: 'http://localhost/klients/api/client/'+id ,
            type: 'POST',
            data: $('form').serialize(),
            success: function(data){ 
              alert("Atualizado com sucesso");   
              window.location.replace("http://localhost/klients");
            }
          });
        }      
      }
      function Remove(id){
        $.ajax({
            url: 'http://localhost/klients/api/client/'+id ,
            type: 'DELETE',
            success: function(data){ 
            alert("Removido com sucesso");   
            window.location.replace("http://localhost/klients");
          }
        });
      }

      function New(){
        if(!document.getElementById('name').value==""&&!document.getElementById('cpf').value==""&&!document.getElementById('date').value==""){
          $.ajax({
          url: 'http://localhost/klients/api/client',
          type: 'POST',
          data:$('form').serialize(),
          success: function(){
              alert("Cadastro com sucesso");
              window.location.replace("http://localhost/klients");
          },             
        });
   
        }
            

      }

      function maskcpf(i){
        var v = i.value;
        if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
            i.value = v.substring(0, v.length-1);
            return;
        }
        i.setAttribute("maxlength", "14");
        if (v.length == 3 || v.length == 7) i.value += ".";
        if (v.length == 11) i.value += "-";
      }


  </script>
</html>