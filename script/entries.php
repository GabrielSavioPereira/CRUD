<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'];
    $date = $_POST['date'];
    $employee_id = (int)$_POST['employee_id'];
    $supplier_id = (int)$_POST['supplier_id'];

    if (!DateTime::createFromFormat('Y-m-d', $date)) {
        die("Erro: Formato de data inválido. Use YYYY-MM-DD.");
    }

    try {
        $stmt = $pdo->prepare("CALL registrar_entrada(:item_id, :quantity, :employee_id, :supplier_id, :date)");
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':employee_id', $employee_id, PDO::PARAM_INT);
        $stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        echo "<script>alert('Entrada registrada com sucesso!'); window.history.back();</script>";
    } catch (PDOException $e) {
        echo "Erro ao registrar entrada: " . $e->getMessage();
    }
}
?>