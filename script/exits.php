<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $employee_id = $_POST['employee_id'];
    $destination = $_POST['destination']; 
    try {
        $stmt = $pdo->prepare("INSERT INTO exits (item_id, quantity, date, employee_id, destination)VALUES (:item_id, :quantity, :date, :employee_id, :destination )");
        $stmt->execute([
            ':item_id' => $item_id,
            ':quantity' => $quantity,
            ':date' => $date,
            ':employee_id' => $employee_id,
            ':destination' => $destination
        ]);
        echo "<script>alert('Saída registrada com sucesso!'); window.history.back();</script>";
    } catch (PDOException $e) {
        echo "Erro ao registrar saída: " . $e->getMessage();
    }
}
