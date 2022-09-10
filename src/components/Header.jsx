import {Button, Col, Row} from 'react-bootstrap'
import { useNavigate } from 'react-router';

export function Header() {

    const navigate = useNavigate();
    
    const buttonStyle = {
        marginTop: "0.6%",
        marginRight: "0.6%",
        height: "2.5rem",
        position:"relative",
        boxShadow: "0 1px 14px rgb(0,0,0,0.8)"
    }

    return (
        <>
            <Row className="d-flex border-bottom"
                style={{
                    position: "relative",
                    padding: "1rem",
                    paddingLeft: "3rem",
                    paddingRight: "3rem",
                    backgroundColor: "#efefef"}}>
                <Col className="me-auto">
                    <h1>Product Add</h1>
                </Col>
                <Button style={{...buttonStyle, width: "5rem"}} form="product_form" type="submit">Save</Button>
                <Button style={{...buttonStyle, width: "5rem"}} onClick={() => {navigate("/");}}>Cancel</Button>
            </Row>
        </>
    );
}