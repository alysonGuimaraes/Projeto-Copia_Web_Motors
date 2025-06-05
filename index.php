<?php
include('configs/dbconnection.php');
session_start();

$usuario_logado = isset($_SESSION['id_usuario']);
$nivel_usuario = $usuario_logado ? $_SESSION['nivelUsuario'] : null;
$id_usuario = $usuario_logado ? $_SESSION['id_usuario'] : null;

// Filtro "meus anúncios"
$filtrar_meus = isset($_GET['meus']) && $nivel_usuario === 'USR';

// Monta a query conforme o tipo de usuário
if (!$usuario_logado) {
    $query = "SELECT * FROM anuncios WHERE flg_situacao = TRUE";
} elseif ($nivel_usuario === 'USR') {
    if ($filtrar_meus) {
        $query = "SELECT * FROM anuncios WHERE user_id = $id_usuario";
    } else {
        $query = "SELECT * FROM anuncios WHERE flg_situacao = TRUE OR user_id = $id_usuario";
    }
} elseif ($nivel_usuario === 'ADM') {
    $query = "SELECT * FROM anuncios";
}

$result = mysqli_query($con, $query);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carros à venda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Ícones Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Estilo personalizado -->
    <link rel="stylesheet" href="styles/paginaInicial.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4 py-3 border-bottom">
        <a class="navbar-brand fw-bold text-danger" href="#">WebmotorsClone</a>
        <div class="collapse navbar-collapse justify-content-between">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link bi me-1" href="pages/cadastro_anuncio.php">
                        <i class="bi me-1"></i> Anunciar
                    </a>
                </li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Busque por marca ou modelo">
                <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <div class="ms-3 d-flex align-items-center gap-2">
                <?php if (!isset($_SESSION["id_usuario"])): ?>
                    <a href="pages/login.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </a>
                    <a href="pages/cadastro_user.php" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-person-plus me-1"></i> Cadastre-se
                    </a>
                <?php else: ?>
                    <span class="me-2">Olá, <strong><?= htmlspecialchars($_SESSION["nome_usuario"]) ?></strong></span>
                    <a href="scripts/logout.php" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Sair
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Filtros -->
            <aside class="col-3 px-4">
                <h6 class="fw-bold mb-3">Filtros aplicados <i class="bi bi-exclamation-circle-fill text-danger"></i></h6>
                <a href="#" class="d-block text-decoration-none mb-2">Limpar todos</a>

                <div class="btn-group mb-3 w-100" role="group">
                    <button class="btn btn-dark">Carros</button>
                    <button class="btn btn-outline-dark">Motos</button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Localização</label>
                    <input type="text" class="form-control" placeholder="Digite sua cidade ou estado">
                </div>

                <div class="mb-3">
                    <label class="form-label">Novo/Usado</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="novo">
                        <label class="form-check-label" for="novo">Novos (0)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="usado">
                        <label class="form-check-label" for="usado">Usados (0)</label>
                    </div>
                </div>

                <div>
                    <label class="form-label">Marca</label>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="brand-box">Chevrolet</span>
                        <span class="brand-box">Fiat</span>
                        <span class="brand-box">Ford</span>
                        <span class="brand-box">Honda</span>
                        <span class="brand-box">Hyundai</span>
                        <span class="brand-box">Mitsubishi</span>
                    </div>
                </div>
            </aside>


            <main class="col-9">
                <?php if ($usuario_logado && $nivel_usuario === 'USR'): ?>
                    <div class="mb-4 text-end">
                        <a href="?meus=1" class="btn btn-outline-primary btn-sm">Ver Meus Anúncios</a>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">Ver Todos</a>
                    </div>
                <?php endif; ?>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php while ($anuncio = mysqli_fetch_assoc($result)): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= htmlspecialchars($anuncio['imagem']) ?>" class="card-img-top" alt="<?= htmlspecialchars($anuncio['modelo']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($anuncio['modelo']) ?></h5>
                                    <p class="card-text">
                                        Marca: <?= htmlspecialchars($anuncio['marca']) ?><br>
                                        Ano: <?= $anuncio['ano'] ?><br>
                                        Km: <?= $anuncio['quilometragem'] ?> km
                                    </p>

                                    <?php if ($usuario_logado && $nivel_usuario === 'USR' && $anuncio['user_id'] == $id_usuario): ?>
                                        <div class="mb-2">
                                            <strong>Status:</strong>
                                            <?php
                                                if (is_null($anuncio['flg_aprovado'])) echo "<span class='text-secondary'>Em análise</span>";
                                                elseif ($anuncio['flg_aprovado'] == 1) echo "<span class='text-success'>Aprovado</span>";
                                                else echo "<span class='text-danger'>Reprovado</span>";
                                            ?>
                                        </div>
                                        <form method="POST" action="pages/excluir_anuncio.php">
                                            <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Excluir</button>
                                        </form>

                                    <?php elseif ($usuario_logado && $nivel_usuario === 'ADM'): ?>
                                        <?php if (is_null($anuncio['flg_aprovado'])): ?>
                                            <form method="POST" action="pages/aprovar_anuncio.php" class="d-flex gap-2">
                                                <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
                                                <button type="submit" name="acao" value="aprovar" class="btn btn-success btn-sm w-50">Aprovar</button>
                                                <button type="submit" name="acao" value="reprovar" class="btn btn-warning btn-sm w-50">Reprovar</button>
                                            </form>
                                        <?php else: ?>
                                            <div>
                                                <strong>Status:</strong>
                                                <?php
                                                    if ($anuncio['flg_aprovado'] == 1) echo "<span class='text-success'>Aprovado</span>";
                                                    else echo "<span class='text-danger'>Reprovado</span>";
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </main>
        </div>
    </div>

</body>
</html>
