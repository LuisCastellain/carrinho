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

$query = mysqli_query($connect, "SELECT * FROM produtos");

$total = 0;

// Remover item do carrinho
if (isset($_GET['remove'])) {
    $produtoId = $_GET['remove'];

    if (isset($_SESSION['carrinho'][$produtoId])) {
        $_SESSION['carrinho'][$produtoId]--;
        if ($_SESSION['carrinho'][$produtoId] <= 0) {
            unset($_SESSION['carrinho'][$produtoId]);
        }
    }

    header("Location: carrinho.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Seu Carrinho</h1>
        <?php if (empty($_SESSION['carrinho'])): ?>
            <p>Seu carrinho está vazio!</p>
            <a href="main.php" class="btn btn-primary">Voltar à Loja</a>
        <?php else: ?>
            <ul class="list-group mb-4">
                <?php while ($result = mysqli_fetch_array($query)): ?>
                    <?php if (isset($_SESSION['carrinho'][$result['id']])): ?>
                        <?php 
                            $quantidade = $_SESSION['carrinho'][$result['id']];
                            $subtotal = $quantidade * $result['preco'];
                            $total = $subtotal;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <img src="imgs/<?php echo htmlspecialchars($result['imagem']); ?>" alt="Imagem do Produto" style="max-width: 100px; margin-right: 10px;">
                                <b><?php echo htmlspecialchars($result['nome']); ?></b> (<?php echo $quantidade; ?> unidade<?php echo $quantidade > 1 ? 's' : ''; ?>)
                                <p class="mb-0"><?php echo htmlspecialchars($result['descricao']); ?></p>
                            </div>
                            <div>
                                <span class="me-3"><b>Subtotal: R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></b></span>
                                <a href="carrinho.php?remove=<?php echo $result['id']; ?>" class="btn btn-danger btn-sm">Remover</a>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endwhile; ?>
            </ul>
            <p><b>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></b></p>
            <a href="main.php" class="btn btn-primary">Voltar à Loja</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
