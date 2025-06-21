import express from 'express';
import cors from 'cors';
import router from './routes';

const app = express();
const PORT = 3000;

// Middlewares
app.use(cors());
app.use(express.json());

// Rotas
app.use('/api', router);

// Rota de status
app.get('/', (_, res) => {
  res.send('🚀 API rodando com sucesso!');
});