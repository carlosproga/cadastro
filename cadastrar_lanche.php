<?php
// Configurações do banco de dados
$bt_imagem = ""
$bt_nome = "localhost";
$bt_valor = "root";
$dbname = "cadastrar_lanche";
$bt_descricao = ""


// Cria a conexão
$conn = new mysqli($bt_nome, $bt_valor, $bt_descricao, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $tipo = $_POST['tipo'];

    // Faz o upload da imagem
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_temp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = 'uploads/' . $imagem_nome;

        // Move o arquivo para o diretório de uploads
        if (!move_uploaded_file($imagem_temp, $imagem_destino)) {
            die("Falha ao fazer upload da imagem.");
        }
    } else {
        $imagem_destino = null;
    }

    // Prepara e executa a consulta SQL
    $stmt = $conn->prepare("INSERT INTO lanches (nome, descricao, tipo, imagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $descricao, $tipo, $imagem_destino);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }

    $stmt->close();
}

// Fecha a conexão
$conn->close();
?>

<?php
// Processar os dados do formulário quando o formulário for enviado

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Coletar valores do formulário
    
    $bt_nome = htmlspecialchars($_POST['nome']);
    $bt_valor = htmlspecialchars($_POST['valor']);
    $bt_descricao = htmlspecialchars($_POST['descricao']);

// Exibir os dados coletados
    
    echo "<h2>Dados Recebidos:</h2>";
    echo "Nome: " . $bt_nome . "<br>";
    echo "Valor: " . $bt_valor . "<br>";
    echo "Descriçao: " . $bt_descricao . "<br>";

                    
    }

?>


<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro dos lanches</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="index.css">
    </head>

    <body>

        <div class="container">

        <p>Nome do lanche: </p>
        <p>Valor do lanche: </p>
        <p>Descrição do Produto:</p>
       
        </div>

    </body>

</html>