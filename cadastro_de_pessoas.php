<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastrar_lanche";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['bt_nome'];
    $valor = $_POST['bt_valor'];
    $descricao = $_POST['bt_descricao'];
    
    // Faz o upload da imagem
    if (isset($_FILES['bt_imagem']) && $_FILES['bt_imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem_nome = $_FILES['bt_imagem']['name'];
        $imagem_temp = $_FILES['bt_imagem']['tmp_name'];
        $imagem_destino = 'uploads/' . $imagem_nome;

        // Move o arquivo para o diretório de uploads
        if (!move_uploaded_file($imagem_temp, $imagem_destino)) {
            die("Falha ao fazer upload da imagem.");
        }
    } else {
        $imagem_destino = null;
    }

    // Prepara e executa a consulta SQL
    $stmt = $conn->prepare("INSERT INTO lanches (nome, descricao, valor, imagem) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $descricao, $valor, $imagem_destino);

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


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Lanche</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="index.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="file"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastro de Lanche</h1>
        <form action="cadastrar_lanche.php" method="post" enctype="multipart/form-data">
            <label for="imagem">Imagem do Lanche:</label>
            <input class="form-control" type="file" id="imagem" name="bt_imagem" accept="image/*">

            <label for="nome">Nome do Lanche:</label>
            <input class="form-control" type="text" id="nome" name="bt_nome" required>

            <label for="valor">Valor:</label>
            <input class="form-control" type="number" id="valor" name="bt_valor" required>

            <label for="descricao">Descrição:</label>
            <textarea class="form-control" id="descricao" name="bt_descricao" required></textarea>

            <input type="submit" value="Cadastrar">
        </form>
    </div>
</body>
</html>