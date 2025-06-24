<?php
require 'db.php';

header('Content-Type: application/json');

try {
    $type = $_POST['type'];
    $id = $_POST['id'];

    if ($type === 'entry') {
        $sql = "DELETE FROM entries WHERE id = ?";
    } else if ($type === 'exit') {
        $sql = "DELETE FROM exits WHERE id = ?";
    } else {
        echo json_encode(['success' => false, 'error' => 'Tipo inválido']);
        exit;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>