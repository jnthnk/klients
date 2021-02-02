<head> 
    <meta charset="utf-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>

<section id="formulario">
        <form action="http://localhost/klients/api/client" method="post" target="_blank">
            <h2>Cadastro</h2>
            <label for="nome">Nome</label>
            <input type="text" name="name" id="name">
            <label for='cpf'>CPF</label>
            <input type="text" name="cpf" id="cpf">
            <label for='date'>Data Nasc</label>
            <input type="text" name="date" id="date">
            <input type="submit" value="OK">
        </form>
</section>