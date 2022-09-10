import { useEffect, useState } from "react"
import { Card, Row, Col, Form, FormCheck } from "react-bootstrap"
import { formatCurrency } from "../utilities/formatCurrency"

type ListItemProps = {
    id: number
    sku: number
    name: string
    price: number
    type: string
    description: string
}

export function ListItem({id, sku, name, price, type, description} : ListItemProps){
    
    const [check, setCheck] = useState(false)
    const ch = () => {
        setCheck(!check)
    }
    
    useEffect(() => {
        setCheck(false)
    }, [id])

    return (
        <Card style={{width:"100%", boxShadow: "0 1px 6px rgb(0,0,0,0.35)"}}>
            <FormCheck id={''+id} className="delete-checkbox m-1" checked={check} onChange={ch}/>
            <Card.Img variant="top" style={{width:"50%",height:"40%", alignSelf: 'center'}} src={type+".png"} hidden/>
            <Card.Body className="d-flex flex-column justify-content-center align-items-center">
                <Card.Title className="d-flex mb-3 text-center">
                    <span className="fs-2">{sku}</span>
                </Card.Title>
                <Row className="d-flex text-center">
                    <Col className="col-12">{name}</Col>
                    <Col className="col-12">{formatCurrency(price)}</Col>
                    <Col className="col-12">{description}</Col>
                </Row>
            </Card.Body>
        </Card>
    )
}