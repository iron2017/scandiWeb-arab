import axios from "axios";
import React, { useEffect, useState } from "react";
import {Button, Col, Row} from 'react-bootstrap'
import { useNavigate } from 'react-router';
import { Route, Routes } from "react-router-dom";


export function Header({countch, checks, setChecks, setCountch, getProducts}) {

    const navigate = useNavigate();
    
    const buttonStyle = {
        width : "fit-content",
        marginTop: "0.6%",
        marginRight: "0.6%",
        height: "2.5rem",
        position:"relative",
        boxShadow: "0 1px 14px rgb(0,0,0,0.8)"
    }
    
    const massDelete = (event) => {
        if(countch !== 0)
            axios.post('https://juniortestnadir.000webhostapp.com/api/deleteproducts.php',checks).then(function(response){
                if (response.data == 1) {
                    getProducts()
                    setChecks([])
                    setCountch(0)
                }
                else if (response.data) {
                    alert(response.data)
                }
                else {
                    alert("unrecognized error, please contact the support")
                }
            })
        else
            alert("no products were checked\nplease check some products then try again")
    }

    return (
        <>
            <Row className="d-flex border-bottom"
                style={{
                    position: "relative",
                    padding: "1rem",
                    paddingLeft: "3rem",
                    paddingRight: "3rem",
                    backgroundColor: "#efefef",
                    marginBottom:"10px"}}>
                <Routes>
                    <Route path="/" element={
                    <>
                        <Col className="me-auto">
                            <h1>Product List</h1>
                        </Col>
                        <Button style={{...buttonStyle}} onClick={() => {navigate("/addproduct");}}>ADD</Button>
                        <Button style={{...buttonStyle}} id="delete_product_btn" onClick={massDelete}
                        >MASS DELETE
                            <div className="rounded-circle bg-danger d-flex justify-content-center align-items-center"
                                style={{
                                    color:"white", 
                                    width:"1.5rem", 
                                    height:"1.5rem", 
                                    position:"absolute", 
                                    bottom:0, right:0,
                                    transform:"translate(25%,25%)"}}>
                                {countch}
                            </div>
                        </Button>
                        </>}
                    />
                </Routes>
                <Routes>
                    <Route path="/addproduct" element={<>
                            <Col className='me-auto'>
                                <h1>Product Add</h1>
                            </Col>
                            <Button style={{...buttonStyle}} form="product_form" type="submit">Save</Button>
                            <Button style={{...buttonStyle}} onClick={() => {navigate("/");}}>Cancel</Button>
                        </>}/>
                </Routes>
            </Row>
        </>
    );
}