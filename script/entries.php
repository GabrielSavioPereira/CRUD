<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $employee_id = $_POST['employee_id'];
    $supplier_id = $_POST['supplier_id'];

try {
        $stmt = $pdo->prepare("INSERT INTO entries (item_id, quantity, date, employee_id, supplier_id) VALUES (:item_id, :quantity, :date, :employee_id, :supplier_id)");
        $stmt->execute([
            ':item_id' => $item_id,
            ':quantity' => $quantity,
            ':date' => $date,
            ':employee_id' => $employee_id,
            ':supplier_id' => $supplier_id
        ]);
        echo "<script>alert('Entrada registrada com sucesso!'); window.history.back();</script>";
    } catch (PDOException $e) {
        echo "Erro ao registrar entrada: " . $e->getMessage();
    }
}


