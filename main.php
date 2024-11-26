<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$database = "loja_eletronicos";

$connect = mysqli_connect($host, $user, $password, $database);

if (!$connect) {
    die("Falha na conexão: " . mysqli_connect_error());
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$query = mysqli_query($connect, "SELECT * FROM produtos");

// Adicionar item ao carrinho
if (isset($_GET['add'])) {
    $produtoId = $_GET['add'];

    if (isset($_SESSION['carrinho'][$produtoId])) {
        $_SESSION['carrinho'][$produtoId]++;
    } else {
        $_SESSION['carrinho'][$produtoId] = 1;
    }

    header("Location: main.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja de Eletrônicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Loja de Eletrônicos</h1>
            <div>
                <span class="me-3">Olá, <b><?php echo $_SESSION['usuario']; ?></b></span>
                <a href="carrinho.php" class="btn btn-success me-2">Carrinho</a>
                <a href="logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>

        <div class="row">
            <?php while ($result = mysqli_fetch_array($query)): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <img src="imgs/<?php echo htmlspecialchars($result['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($result['nome']); ?>" style="object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($result['nome']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($result['descricao']); ?></p>
                            <p class="card-text"><b>R$ <?php echo number_format($result['preco'], 2, ',', '.'); ?></b></p>
                            <a href="main.php?add=<?php echo $result['id']; ?>" class="btn btn-primary">Adicionar ao Carrinho</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
