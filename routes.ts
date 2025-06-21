import { Router } from 'express';
import db from './db';

const router = Router();


router.get('/suppliers', async (_, res) => {
    const result = await db.query('SELECT * FROM suppliers');
    res.json(result.rows);
});

router.get('/suppliers/:id', async (req, res) => {
    const result = await db.query('SELECT * FROM suppliers WHERE id = $1', [req.params.id]);
    res.json(result.rows[0]);
});

router.post('/suppliers', async (req, res) => {
    const { name, cnpj, contact, certification } = req.body;
    const result = await db.query(
        'INSERT INTO suppliers (name, cnpj, contact, certification) VALUES ($1, $2, $3, $4) RETURNING *',
        [name, cnpj, contact, certification]
    );
    res.status(201).json(result.rows[0]);
});

router.put('/suppliers/:id', async (req, res) => {
    const { name, cnpj, contact, certification } = req.body;
    const result = await db.query(
        'UPDATE suppliers SET name = $1, cnpj = $2, contact = $3, certification = $4 WHERE id = $5 RETURNING *',
        [name, cnpj, contact, certification, req.params.id]
    );
    res.json(result.rows[0]);
});

router.delete('/suppliers/:id', async (req, res) => {
    await db.query('DELETE FROM suppliers WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.get('/categories', async (_, res) => {
    const result = await db.query('SELECT * FROM categories');
    res.json(result.rows);
});

router.post('/categories', async (req, res) => {
    const { name } = req.body;
    const result = await db.query('INSERT INTO categories (name) VALUES ($1) RETURNING *', [name]);
    res.status(201).json(result.rows[0]);
});

router.put('/categories/:id', async (req, res) => {
    const { name } = req.body;
    const result = await db.query('UPDATE categories SET name = $1 WHERE id = $2 RETURNING *', [name, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/categories/:id', async (req, res) => {
    await db.query('DELETE FROM categories WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.get('/roles', async (_, res) => {
    const result = await db.query('SELECT * FROM roles');
    res.json(result.rows);
});

router.post('/roles', async (req, res) => {
    const { name } = req.body;
    const result = await db.query('INSERT INTO roles (name) VALUES ($1) RETURNING *', [name]);
    res.status(201).json(result.rows[0]);
});

router.put('/roles/:id', async (req, res) => {
    const { name } = req.body;
    const result = await db.query('UPDATE roles SET name = $1 WHERE id = $2 RETURNING *', [name, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/roles/:id', async (req, res) => {
    await db.query('DELETE FROM roles WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.get('/employees', async (_, res) => {
    const result = await db.query('SELECT * FROM employees');
    res.json(result.rows);
});

router.post('/employees', async (req, res) => {
    const { name, email, roleId } = req.body;
    const result = await db.query('INSERT INTO employees (name, email, role_id) VALUES ($1, $2, $3) RETURNING *', [name, email, roleId]);
    res.status(201).json(result.rows[0]);
});

router.put('/employees/:id', async (req, res) => {
    const { name, email, roleId } = req.body;
    const result = await db.query('UPDATE employees SET name = $1, email = $2, role_id = $3 WHERE id = $4 RETURNING *', [name, email, roleId, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/employees/:id', async (req, res) => {
    await db.query('DELETE FROM employees WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.get('/sectors', async (_, res) => {
    const result = await db.query('SELECT * FROM sectors');
    res.json(result.rows);
});

router.post('/sectors', async (req, res) => {
    const { name, employeeId } = req.body;
    const result = await db.query('INSERT INTO sectors (name, employee_id) VALUES ($1, $2) RETURNING *', [name, employeeId]);
    res.status(201).json(result.rows[0]);
});

router.put('/sectors/:id', async (req, res) => {
    const { name, employeeId } = req.body;
    const result = await db.query('UPDATE sectors SET name = $1, employee_id = $2 WHERE id = $3 RETURNING *', [name, employeeId, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/sectors/:id', async (req, res) => {
    await db.query('DELETE FROM sectors WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

// ------------ ITEMS ------------
router.get('/items', async (_, res) => {
    const result = await db.query('SELECT * FROM items');
    res.json(result.rows);
});

router.post('/items', async (req, res) => {
    const { name, description, categoryId, sectorId, quantity, active } = req.body;
    const result = await db.query('INSERT INTO items (name, description, category_id, sector_id, quantity, active) VALUES ($1, $2, $3, $4, $5, $6) RETURNING *', [name, description, categoryId, sectorId, quantity, active]);
    res.status(201).json(result.rows[0]);
});

router.put('/items/:id', async (req, res) => {
    const { name, description, categoryId, sectorId, quantity, active } = req.body;
    const result = await db.query('UPDATE items SET name = $1, description = $2, category_id = $3, sector_id = $4, quantity = $5, active = $6 WHERE id = $7 RETURNING *', [name, description, categoryId, sectorId, quantity, active, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/items/:id', async (req, res) => {
    await db.query('DELETE FROM items WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.post('/entries', async (req, res) => {
    const { itemId, quantity, date, employeeId, supplierId } = req.body;
    await db.query('CALL registrar_entrada($1, $2, $3, $4, $5)', [itemId, quantity, employeeId, supplierId, date]);
    res.status(201).json({ message: 'Entrada registrada com sucesso.' });
});

router.post('/exits', async (req, res) => {
    const { itemId, quantity, date, employeeId, destination } = req.body;
    const result = await db.query(
        'SELECT estoque_suficiente($1, $2) AS permitido',
        [itemId, quantity]
    );

    if (!result.rows[0].permitido)
        return res.status(400).json({ error: 'Estoque insuficiente.' });

    await db.query(
        'CALL registrar_saida($1, $2, $3, $4, $5)',
        [itemId, quantity, employeeId, destination, date]
    );

    return res.status(201).json({ message: 'Saída registrada com sucesso.' });
});

router.get('/requests', async (_, res) => {
    const result = await db.query('SELECT * FROM requests');
    res.json(result.rows);
});

router.post('/requests', async (req, res) => {
    const { employeeId, date, status, note } = req.body;
    const result = await db.query('INSERT INTO requests (employee_id, date, status, note) VALUES ($1, $2, $3, $4) RETURNING *', [employeeId, date, status, note]);
    res.status(201).json(result.rows[0]);
});

router.put('/requests/:id', async (req, res) => {
    const { employeeId, date, status, note } = req.body;
    const result = await db.query('UPDATE requests SET employee_id = $1, date = $2, status = $3, note = $4 WHERE id = $5 RETURNING *', [employeeId, date, status, note, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/requests/:id', async (req, res) => {
    await db.query('DELETE FROM requests WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.get('/request-items', async (_, res) => {
    const result = await db.query('SELECT * FROM request_items');
    res.json(result.rows);
});

router.post('/request-items', async (req, res) => {
    const { requestId, itemId, amount } = req.body;
    const result = await db.query('INSERT INTO request_items (request_id, item_id, amount) VALUES ($1, $2, $3) RETURNING *', [requestId, itemId, amount]);
    res.status(201).json(result.rows[0]);
});

router.put('/request-items/:id', async (req, res) => {
    const { requestId, itemId, amount } = req.body;
    const result = await db.query('UPDATE request_items SET request_id = $1, item_id = $2, amount = $3 WHERE id = $4 RETURNING *', [requestId, itemId, amount, req.params.id]);
    res.json(result.rows[0]);
});

router.delete('/request-items/:id', async (req, res) => {
    await db.query('DELETE FROM request_items WHERE id = $1', [req.params.id]);
    res.sendStatus(204);
});

router.get('/views/itens-baixo-estoque', async (_, res) => {
    const result = await db.query('SELECT * FROM vw_itens_baixo_estoque');
    res.json(result.rows);
});

router.get('/views/pedidos-funcionario', async (_, res) => {
    const result = await db.query('SELECT * FROM vw_pedidos_funcionario');
    res.json(result.rows);
});

router.get('/views/historico-entradas', async (_, res) => {
    const result = await db.query('SELECT * FROM vw_historico_entradas');
    res.json(result.rows);
});

export default router;
