<?php
require 'db.php';
$sqlEntries = "
    SELECT e.id, i.name AS produto, e.quantity, e.date, emp.name AS responsavel, e.supplier_id
    FROM entries e
    JOIN items i ON i.id = e.item_id
    JOIN employees emp ON emp.id = e.employee_id
    ORDER BY e.date DESC
";
$entries = $pdo->query($sqlEntries)->fetchAll(PDO::FETCH_ASSOC);

$sqlExits = "
    SELECT x.id, i.name AS produto, x.quantity, x.date, emp.name AS responsavel, x.destination
    FROM exits x
    JOIN items i ON i.id = x.item_id
    JOIN employees emp ON emp.id = x.employee_id
    ORDER BY x.date DESC
";
$exits = $pdo->query($sqlExits)->fetchAll(PDO::FETCH_ASSOC);

$employees = $pdo->query("SELECT id, name FROM employees ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$suppliers = $pdo->query("SELECT id, name FROM suppliers ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
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
          <th>Ações</th>
        </tr>
        <?php foreach ($entries as $entrada): ?>
        <tr>
          <td><?= htmlspecialchars($entrada['produto']) ?></td>
          <td style="color:green">+<?= $entrada['quantity'] ?></td>
          <td><?= $entrada['date'] ?></td>
          <td><?= htmlspecialchars($entrada['responsavel']) ?></td>
          <td>
            <button class="action-btn edit-btn" onclick='openEditModal("entry", <?= $entrada["id"] ?>, "<?= htmlspecialchars($entrada["produto"]) ?>", <?= $entrada["quantity"] ?>, "<?= $entrada["date"] ?>", <?= $entrada["supplier_id"] ?>, "<?= htmlspecialchars($entrada["responsavel"]) ?>", <?= json_encode($suppliers) ?>, <?= json_encode($employees) ?>)'>Editar</button>
            <button class="action-btn delete-btn" onclick="confirmDelete('entry', <?= $entrada['id'] ?>)">Excluir</button>
          </td>
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
          <th>Ações</th>
        </tr>
        <?php foreach ($exits as $saida): ?>
        <tr>
          <td><?= htmlspecialchars($saida['produto']) ?></td>
          <td style="color:red">-<?= $saida['quantity'] ?></td>
          <td><?= $saida['date'] ?></td>
          <td><?= htmlspecialchars($saida['responsavel']) ?></td>
          <td>
            <button class="action-btn edit-btn" onclick='openEditModal("exit", <?= $saida["id"] ?>, "<?= htmlspecialchars($saida["produto"]) ?>", <?= $saida["quantity"] ?>, "<?= $saida["date"] ?>", "<?= htmlspecialchars($saida["destination"]) ?>", "<?= htmlspecialchars($saida["responsavel"]) ?>", null, <?= json_encode($employees) ?>)'>Editar</button>
            <button class="action-btn delete-btn" onclick="confirmDelete('exit', <?= $saida['id'] ?>)">Excluir</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>

  </div>

 
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeEditModal()">×</span>
      <h2>Editar Registro</h2>
      <form id="editForm">
        <input type="hidden" id="editType" name="type">
        <input type="hidden" id="editId" name="id">
        <label>Produto: <input type="text" id="editProduto" name="produto" readonly></label>
        <label>Quantidade: <input type="number" id="editQuantity" name="quantity" min="1" required></label>
        <label>Data: <input type="date" id="editDate" name="date" required></label>
        <label>Responsável: 
          <select id="editEmployeeId" name="employee_id" required>
            
          </select>
        </label>
        <label id="supplierLabel" style="display: none;">Fornecedor: 
          <select id="editSupplierId" name="supplier_id">
            
          </select>
        </label>
        <label id="destinationLabel" style="display: none;">Destino: 
          <input type="text" id="editDestination" name="destination">
        </label>
        <button type="submit">Salvar</button>
      </form>
    </div>
  </div>
        <div id="linkcontroller">
    <a href="../view/EntriesAndExits.html"><input type="button" value="Ir à Inserção" ></a>
   
  </div>
    <div id="linkindex">
      <a href="../index.html"><input type="button" value="Voltar ao Inicio" ></a>
    </div>


  <script>
    function openEditModal(type, id, produto, quantity, date, extra, responsavel, suppliers, employees) {
      const modal = document.getElementById('editModal');
      const form = document.getElementById('editForm');
      const supplierLabel = document.getElementById('supplierLabel');
      const destinationLabel = document.getElementById('destinationLabel');
      const supplierSelect = document.getElementById('editSupplierId');
      const employeeSelect = document.getElementById('editEmployeeId');

     
      document.getElementById('editType').value = type;
      document.getElementById('editId').value = id;
      document.getElementById('editProduto').value = produto;
      document.getElementById('editQuantity').value = quantity;
      document.getElementById('editDate').value = date.split(' ')[0];

     
      employeeSelect.innerHTML = '';
      employees.forEach(emp => {
        const option = document.createElement('option');
        option.value = emp.id;
        option.text = emp.name;
        if (emp.name === responsavel) option.selected = true;
        employeeSelect.appendChild(option);
      });

     
      if (type === 'entry') {
        supplierLabel.style.display = 'block';
        destinationLabel.style.display = 'none';
        supplierSelect.innerHTML = '';
        suppliers.forEach(sup => {
          const option = document.createElement('option');
          option.value = sup.id;
          option.text = sup.name;
          if (sup.id == extra) option.selected = true;
          supplierSelect.appendChild(option);
        });
      } else {
        supplierLabel.style.display = 'none';
        destinationLabel.style.display = 'block';
        document.getElementById('editDestination').value = extra || '';
      }

      modal.style.display = 'block';
    }

    function closeEditModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    function confirmDelete(type, id) {
      if (confirm('Tem certeza que deseja excluir este registro?')) {
        fetch('delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `type=${type}&id=${id}`
        })
        .then(response => {
          console.log('Delete Response Status:', response.status, response.statusText);
          return response.text();
        })
        .then(text => {
          console.log('Delete Raw Response:', text);
          try {
            const data = JSON.parse(text);
            if (data.success) {
              alert('Registro excluído com sucesso!');
              location.reload();
            } else {
              alert('Erro ao excluir registro: ' + (data.error || 'Resposta inválida'));
            }
          } catch (e) {
            console.error('Delete JSON Parse Error:', e, 'Raw Response:', text);
            alert('Erro: Resposta inválida do servidor');
          }
        })
        .catch(error => {
          console.error('Delete Fetch Error:', error);
          alert('Erro: ' + error);
        });
      }
    }

    document.getElementById('editForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const formData = new FormData(this);
      fetch('update.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        console.log('Update Response Status:', response.status, response.statusText);
        return response.text();
      })
      .then(text => {
        console.log('Update Raw Response:', text);
        try {
          const data = JSON.parse(text);
          if (data.success) {
            alert('Registro atualizado com sucesso!');
            location.reload();
          } else {
            alert('Erro ao atualizar registro: ' + (data.error || 'Resposta inválida'));
          }
        } catch (e) {
          console.error('Update JSON Parse Error:', e, 'Raw Response:', text);
          alert('Erro: Resposta inválida do servidor');
        }
      })
      .catch(error => {
        console.error('Update Fetch Error:', error);
        alert('Erro: ' + error);
      });
    });
  </script>
</body>
</html>