import express from "express"
import { RequestsController } from "./infrastructure/controllers/requests.controller"


const app = express()
const port = 3000

app.get('/', (req, res) => {
  res.send('Hello World!')
})

// const requestController = new RequestsController(); 
// app.post('/request', async (req, res) => {
//   var response = await requestController.CreateNewRequest(req.body)
//   response ? res.append("sucesso") : res.append("erro")
// })

app.listen(port, () => {
  console.log(`Example app listening on port ${port}`)
})

