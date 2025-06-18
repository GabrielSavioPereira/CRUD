export class request{
    id: number
    employeeId:number
    date: Date
    status: 'PENDING'| 'APPROVED'| 'REJECTED'
    note: string
}