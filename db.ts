import { Pool } from 'pg';

const pool = new Pool({
    user: 'root',
    host: 'localhost',
    database: 'storageBG',
    password: '123456',
    port: 5432,
});

export default {
    query: (text: string, params?: any[]) => pool.query(text, params),
};
