<?php
require 'db.php';

header('Content-Type: application/json');

try {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $date = $_POST['date'];
    $employee_id = $_POST['employee_id'];

    if ($type === 'entry') {
        $supplier_id = $_POST['supplier_id'];
        $sql = "UPDATE entries SET quantity = ?, date = ?, employee_id = ?, supplier_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quantity, $date, $employee_id, $supplier_id, $id]);
    } else if ($type === 'exit') {
        $destination = $_POST['destination'];
        $sql = "UPDATE exits SET quantity = ?, date = ?, employee_id = ?, destination = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quantity, $date, $employee_id, $destination, $id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Tipo inválido']);
        exit;
    }

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>