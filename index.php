<?php

// faz a verificação se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/vendor/tecnickcom/tcpdf/tcpdf.php';

    $quantidade = intval($_POST['quantidade']);
    $campanha = htmlspecialchars($_POST['campanha']);
    $premio = htmlspecialchars($_POST['premio']);
    $valor = htmlspecialchars($_POST['valor']);

    // css inline para o pdf pq (TCPDF não lê arquivo externo)
    $css = '
    <style>
    .bilhete {
        border: 2px dashed #333;
        border-radius: 8px;
        padding: 10px 0;
        margin-bottom: 10px;
        width: 90%;
        text-align: center;
        font-size: 4px;
        background: #f9f9f9;
    }
    .titulo { font-size: 22px; font-weight: bold; color: #007bff; }
    .premio { font-size: 16px; color: #555; }
    .valor { font-size: 16px; color: #28a745; }
    .numero { font-size: 28px; font-weight: bold; color: #dc3545; }
    </style>
    ';

    // monta a estrutura do html dos bilhetes
    $html = $css;
    for ($i = 1; $i <= $quantidade; $i++) {
        $numero = str_pad($i, 3, "0", STR_PAD_LEFT);
        $html .= '
        <div class="bilhete">
            <div class="titulo">' . $campanha . '</div>
            <div class="premio">Prêmio: ' . $premio . '</div>
            <div class="valor">Valor: R$ ' . $valor . '</div>
            <div class="numero">Nº ' . $numero . '</div>
        </div>
        ';
    }

    // gera o PDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('Gerador de Rifas');
    $pdf->SetTitle('Bilhetes de Rifa');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output('rifa.pdf', 'I');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerador de Rifas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Gerador de Rifas</h2>
        <form method="post">
            <label for="campanha">Nome da Campanha:</label>
            <input type="text" id="campanha" name="campanha" required>

            <label for="premio">Prêmio(s):</label>
            <textarea id="premio" name="premio" required></textarea>

            <label for="valor">Valor do Bilhete (R$):</label>
            <input type="number" step="0.01" id="valor" name="valor" required>

            <label for="quantidade">Quantidade de Bilhetes:</label>
            <input type="number" id="quantidade" name="quantidade" min="1" max="999" required>

            <button type="submit">Gerar e Imprimir Bilhetes</button>
        </form>
    </div>
</body>
</html>