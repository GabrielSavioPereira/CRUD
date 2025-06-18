  export class requestDTO{
    employeeId:number
    date: Date
    status: 'PENDING'| 'APPROVED'| 'REJECTED'
    note: string  
    requestType: 'entry' | 'exit'
  }
