    import express from "express"
import { RequestInterface } from "../../interface/requests.interface"
import { requestDTO } from "../Dtos/requestDTO"
    const app = express()

    const requestInterface = new RequestInterface()
export class RequestsController {

    public async CreateNewRequest(data:requestDTO) {
        await requestInterface.post(data);
    }
}