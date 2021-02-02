<!DOCTYPE html>
<html lang="pt-br">
<head> 
    <meta charset="utf-8">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <section id="formulario">
        <form action="http://localhost/klients/client.php">
            <h2>Controle de Clientes</h2>
            <input  type="button" value="Cadastrar">
        </form>
    </section>

    <section id="tabela">
        <table>
            <tr id="Titulo">
                <td>ID</td>
                <td>NOME</td>
                <td>CPF</td>
                <td colspan="2">DATA NASC</td>
            </tr>
            
            <?php
                $request = array(
                    CURLOPT_URL => "http://localhost/klients/api/clients",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array("cache-control: no-cache")
                );

                $curl = curl_init();
                
                curl_setopt_array($curl, $request);
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);

                if(!$err){
                    $lista = json_decode($response,true);
                    
                    foreach ($lista['clients'] as $key => $linha) {
                        echo
                            '<tr id="Titulo">
                                <td>'.$linha['ID'].'</td>
                                <td>'.$linha['name'].'</td>
                                <td>'.$linha['CPF'].'</td>
                                <td>'.$linha['date'].'</td>
                                <td><input  type="submit" value="Editar"><input  type="submit" value="Excluir"></td>
                            </tr>';
                    }
                }
            ?>
        </table>
    </section>

</body>
</html>