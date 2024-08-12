<?php
// Configurações do banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bebidas_db";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta os valores do formulário
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $descricao = isset($_POST['descricao']) ? $_POST['descricao'] : '';
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

    // Faz o upload da imagem
    $imagem_destino = null;
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem_nome = basename($_FILES['imagem']['name']); // Usar basename para evitar problemas de caminho
        $imagem_temp = $_FILES['imagem']['tmp_name'];
        $imagem_destino = 'uploads/' . $imagem_nome;

        // Verifica o tipo de arquivo e tamanho
        $imagem_tipo = mime_content_type($imagem_temp);
        $imagem_tamanho = $_FILES['imagem']['size'];
        $tamanho_max = 2 * 1024 * 1024; // 2MB

        // Tipos de imagem permitidos
        $tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($imagem_tipo, $tipos_permitidos) || $imagem_tamanho > $tamanho_max) {
            die("Tipo de arquivo inválido ou arquivo muito grande.");
        }

        // Move o arquivo para o diretório de uploads
        if (!move_uploaded_file($imagem_temp, $imagem_destino)) {
            die("Falha ao fazer upload da imagem.");
        }
    }

    // Prepara e executa a consulta SQL
    $stmt = $conn->prepare("INSERT INTO bebidas (nome, descricao, tipo, imagem) VALUES (?, ?, ?, ?)");
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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Bebida</title>
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
        input[type="text"], input[type="file"], input[type="number"], textarea {
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
        <h1>Cadastro de Bebida</h1>
        <form action="/cadastrar_bebida" method="post" enctype="multipart/form-data">

            <label for="imagem">Imagem da Bebida:</label>
            <input type="file" id="imagem" name="imagem" accept="image/*">

            <label for="nome">Nome da Bebida:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="valor">Valor:</label>
            <input type="number" id="valor" name="valor" required>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="4" required></textarea>

            <input type="submit" value="Cadastrar">
        </form>
    </div>
</body>
</html>