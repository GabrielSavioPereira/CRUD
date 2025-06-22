<?php
require 'db.php';

$sqlEntries = "
    SELECT i.name AS produto, e.quantity, e.date, emp.name AS responsavel
    FROM entries e
    JOIN items i ON i.id = e.item_id
    JOIN employees emp ON emp.id = e.employee_id
    ORDER BY e.date DESC
";
$entries = $pdo->query($sqlEntries)->fetchAll(PDO::FETCH_ASSOC);

$sqlExits = "
    SELECT i.name AS produto, x.quantity, x.date, emp.name AS responsavel
    FROM exits x
    JOIN items i ON i.id = x.item_id
    JOIN employees emp ON emp.id = x.employee_id
    ORDER BY x.date DESC
";
$exits = $pdo->query($sqlExits)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Controle de Estoque - Almoxarifado</title>
  <link rel="stylesheet" href="../style/controller.css">
</head>
<body>
  <h1>Controle de Estoque - Almoxarifado</h1>

  <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">

    <div>
      <h2>Entrada de Produtos</h2>
      <table border="1">
        <tr>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Data</th>
          <th>Responsável</th>
        </tr>
        <?php foreach ($entries as $entrada): ?>
        <tr>
          <td><?= htmlspecialchars($entrada['produto']) ?></td>
          <td style="color:green">+<?= $entrada['quantity'] ?></td>
          <td><?= $entrada['date'] ?></td>
          <td><?= htmlspecialchars($entrada['responsavel']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

    <div>
      <h2>Saída de Produtos</h2>
      <table border="1">
        <tr>
          <th>Produto</th>
          <th>Quantidade</th>
          <th>Data</th>
          <th>Responsável</th>
        </tr>
        <?php foreach ($exits as $saida): ?>
        <tr>
          <td><?= htmlspecialchars($saida['produto']) ?></td>
          <td style="color:red">-<?= $saida['quantity'] ?></td>
          <td><?= $saida['date'] ?></td>
          <td><?= htmlspecialchars($saida['responsavel']) ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </div>
</body>
</html>
