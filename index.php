<?php
include('configs/dbconnection.php');
session_start();

$usuario_logado = isset($_SESSION['id_usuario']);
$nivel_usuario = $usuario_logado ? $_SESSION['nivelUsuario'] : null;
$id_usuario = $usuario_logado ? $_SESSION['id_usuario'] : null;

// Filtro "meus anúncios"
$filtrar_meus = isset($_GET['meus']) && $nivel_usuario === 'USR';

$where = [];

if (!$usuario_logado) {
    $where[] = "flg_situacao = TRUE";
} elseif ($nivel_usuario === 'USR') {
    if ($filtrar_meus) {
        $where[] = "user_id = $id_usuario";
    } else {
        $where[] = "(flg_situacao = TRUE OR user_id = $id_usuario)";
    }
} elseif ($nivel_usuario === 'ADM') {
    if (isset($_GET['pendentes'])) {
        $where[] = "flg_situacao IS NULL";
    }
}

// Filtros adicionais
if (!empty($_GET['marca'])) {
    $marcas = array_map('mysqli_real_escape_string', array_fill(0, count($_GET['marca']), $con), $_GET['marca']);
    $where[] = "marca IN ('" . implode("','", $marcas) . "')";
}

if (!empty($_GET['intervalo_ano'])) {
    switch ($_GET['intervalo_ano']) {
        case '1': $where[] = "ano < 1980"; break;
        case '2': $where[] = "ano BETWEEN 1980 AND 2000"; break;
        case '3': $where[] = "ano BETWEEN 2000 AND 2010"; break;
        case '4': $where[] = "ano BETWEEN 2010 AND 2020"; break;
        case '5': $where[] = "ano > 2020"; break;
    }
}

if (!empty($_GET['km_max'])) {
    $km_max = (int) $_GET['km_max'];
    $where[] = "quilometragem <= $km_max";
}

if (!empty($_GET['busca'])) {
    $busca = mysqli_real_escape_string($con, $_GET['busca']);
    $where[] = "(modelo LIKE '%$busca%' OR marca LIKE '%$busca%')";
}

$query = "SELECT * FROM anuncios";
if (!empty($where)) {
    $query .= " WHERE " . implode(" AND ", $where);
}

$result = mysqli_query($con, $query);
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Webmotors clone</title>
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

                <?php if ($usuario_logado && $nivel_usuario === 'ADM'): ?>
                <li class="nav-item">
                    <a class="nav-link bi me-1" href="pages/cadastro_user.php">
                        <i class="bi me-1"></i> Cadastrar usuário
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <form class="d-flex" role="search" method="GET">
                <input class="form-control me-2" name="busca" type="search" placeholder="Busque por marca ou modelo" value="<?= $_GET['busca'] ?? '' ?>">
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
                <form method="GET" id="filtroForm">
                    <h6 class="fw-bold mb-3">Filtros <i class="bi bi-exclamation-circle-fill text-danger"></i></h6>

                    <div class="mb-3">
                        <label class="form-label">Marca</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php
                            $marcas = ["Chevrolet", "Fiat", "Ford", "Honda", "Hyundai", "Mitsubishi"];
                            foreach ($marcas as $marca): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="marca[]" value="<?= $marca ?>"
                                        <?= isset($_GET['marca']) && in_array($marca, $_GET['marca']) ? 'checked' : '' ?>>
                                    <label class="form-check-label"><?= $marca ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Intervalo de Ano</label>
                        <select name="intervalo_ano" class="form-select">
                            <option value="">Selecione</option>
                            <option value="1" <?= $_GET['intervalo_ano'] ?? '' === '1' ? 'selected' : '' ?>>Menor que 1980</option>
                            <option value="2" <?= $_GET['intervalo_ano'] ?? '' === '2' ? 'selected' : '' ?>>1980 - 2000</option>
                            <option value="3" <?= $_GET['intervalo_ano'] ?? '' === '3' ? 'selected' : '' ?>>2000 - 2010</option>
                            <option value="4" <?= $_GET['intervalo_ano'] ?? '' === '4' ? 'selected' : '' ?>>2010 - 2020</option>
                            <option value="5" <?= $_GET['intervalo_ano'] ?? '' === '5' ? 'selected' : '' ?>>Acima de 2020</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quilometragem máxima</label>
                        <input type="number" name="km_max" class="form-control" value="<?= $_GET['km_max'] ?? '' ?>">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                        <a href="index.php" class="btn btn-outline-secondary">Limpar todos</a>
                    </div>
                </form>
            </aside>


            <main class="col-9">
                <?php if ($usuario_logado && $nivel_usuario === 'USR'): ?>
                    <div class="mb-4 text-end">
                        <a href="?meus=1" class="btn btn-outline-primary btn-sm">Ver Meus Anúncios</a>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">Ver Todos</a>
                    </div>
                <?php endif; ?>

                <?php if ($usuario_logado && $nivel_usuario === 'ADM'): ?>
                    <div class="mb-4 text-end">
                        <a href="?pendentes=1" class="btn btn-outline-warning btn-sm me-2">Ver Somente Pendentes</a>
                        <a href="index.php" class="btn btn-outline-secondary btn-sm">Ver Todos</a>
                    </div>
                <?php endif; ?>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php while ($anuncio = mysqli_fetch_assoc($result)): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm" id="card-<?= $anuncio['id'] ?>">
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
                                                if (is_null($anuncio['flg_situacao'])) echo "<span class='text-secondary'>Em análise</span>";
                                                elseif ($anuncio['flg_situacao'] == 1) echo "<span class='text-success'>Aprovado</span>";
                                                else echo "<span class='text-danger'>Reprovado</span>";
                                            ?>
                                        </div>
                                        <form method="POST" action="scripts/excluir_anuncio.php">
                                            <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm w-100">Excluir</button>
                                        </form>

                                    <?php elseif ($usuario_logado && $nivel_usuario === 'ADM'): ?>
                                        <?php if (is_null($anuncio['flg_situacao'])): ?>
                                            <form method="POST" action="scripts/aprovar_anuncio.php" class="d-flex gap-2">
                                                <input type="hidden" name="id" value="<?= $anuncio['id'] ?>">
                                                <button type="submit" name="acao" value="aprovar" class="btn btn-success btn-sm w-50">Aprovar</button>
                                                <button type="submit" name="acao" value="reprovar" class="btn btn-warning btn-sm w-50">Reprovar</button>
                                            </form>
                                        <?php else: ?>
                                            <div>
                                                <strong>Status:</strong>
                                                <?php
                                                    if ($anuncio['flg_situacao'] == 1) echo "<span class='text-success'>Aprovado</span>";
                                                    else echo "<span class='text-danger'>Reprovado</span>";
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <button onclick="imprimirAnuncio('card-<?= $anuncio['id'] ?>')" class="btn btn-outline-dark btn-sm mt-2 w-100">
                                        <i class="bi bi-printer me-1"></i> Imprimir Anúncio
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        function imprimirAnuncio(cardId) {
            const card = document.getElementById(cardId);
            const conteudo = card.innerHTML;
            const win = window.open('', '_blank', 'width=800,height=600');

            win.document.write(`
                <html>
                    <head>
                        <title>Impressão de Anúncio</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            img { max-width: 100%; height: auto; }
                            button { display: none; }
                        </style>
                    </head>
                    <body>
                        ${conteudo}
                    </body>
                </html>
            `);
            
            win.document.close();
            win.focus(); 
            win.print(); 
            win.close();
        }
    </script>

</body>
</html>
