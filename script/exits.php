<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $employee_id = $_POST['employee_id'];
    $destination = $_POST['destination'];

    try {
        $stmt = $pdo->prepare("CALL registrar_saida(:item_id, :quantity, :employee_id, :destination, :date)");
        $stmt->execute([
            ':item_id' => $item_id,
            ':quantity' => $quantity,
            ':employee_id' => $employee_id,
            ':destination' => $destination,
            ':date' => $date
        ]);
        echo "<script>alert('Saída registrada com sucesso!'); window.history.back();</script>";
    } catch (PDOException $e) {
        echo "Erro ao registrar saída: " . $e->getMessage();
    }
}
?>