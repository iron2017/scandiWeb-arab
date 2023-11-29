import axios from "axios";
import React, { Component, ComponentElement, useRef, useState } from "react";
import { Form, Button, Row, Col, Container, Alert } from "react-bootstrap";
import { Link, Navigate, useNavigate } from "react-router-dom";
import { Footer } from "../components/Footer";
import { TypeDetailsBook } from "../components/TypeDetailsBook";
import { TypeDetailsDVD } from "../components/TypeDetailsDVD";
import { TypeDetailsFurniture } from "../components/TypeDetailsFurniture";

export function AddProduct() {
    
    const list = {
        "DVD" : <TypeDetailsDVD/>,
        "Book" : <TypeDetailsBook/>,
        "Furniture" : <TypeDetailsFurniture/>
    };
    
    const [typeP, changeComp] = useState()
    const [inputs, setInputs] = useState()
    const [skuInvalid, setSkuInvalid] = useState(false)
    const navigate = useNavigate();

    const changeDesc = (event) => {
        let res = list[event.target.value]
        changeComp(res)
        setInputs(values => ({...values, "description":{}}))
    }

    const handleChange = (event) => {
        let id = event.target.id
        let value = event.target.value
        setInputs(values => ({...values, [id]:value}))

    }

    const handleTypeChange = (event) => {
        let id = event.target.id
        let value = event.target.value
        setInputs(values => {
            let des = values.description
            des = {...des,[id]:value}
            return {...values, "description":des}
        })
    }

    const handleSubmit = (event) => {
        event.preventDefault()
        inputs.price = Number(inputs.price);
        axios.post('http://localhost:80/api/addproduct.php',inputs).then(function(response){
            setSkuInvalid(false)
            if (response.data == true) {
                navigate('/');
            }
            else {
                if(response.data["type"] == "unique" || response.data == "sku is empty") {
                    alert("SKU must be unique")
                    setSkuInvalid(true)
                }
                else if(response.data["type"] == "empty") {
                    alert(response.data["field"]+" is required")
                }
                else
                    alert(response.data);
            }
        })
    }

    return (
        <>
            <Container style={{paddingBottom:"60px"}}>
                <Row>
                    <Form id="product_form" onSubmit={handleSubmit}
                        style={{
                            position: "relative",
                            padding: "1rem",
                            paddingLeft: "3rem",
                            paddingRight: "3rem",
                            backgroundColor: "#efefef"}}>
                        <Form.Group className="mb-3" onChange={handleChange}>
                            <Form.Label>SKU</Form.Label>
                            <Form.Control id="sku" type="text" placeholder="Enter Code" isInvalid={skuInvalid} required/>
                            <Form.Control.Feedback type="invalid">
                                Please use a valid SKU.
                            </Form.Control.Feedback>
                        </Form.Group>
                        <Form.Group className="mb-3" onChange={handleChange}>
                            <Form.Label>Name</Form.Label>
                            <Form.Control id="name" type="text" placeholder="Enter Name" required/>
                        </Form.Group>
                        <Form.Group className="mb-3" onChange={handleChange}>
                            <Form.Label>Price ($)</Form.Label>
                            <Form.Control id="price" type="number" placeholder="Enter Price" min={0} required/>
                        </Form.Group>
                        <Form.Group className="mb-3" onChange={handleChange} onLoad={handleChange}>
                            <Form.Label>Type Switcher</Form.Label>
                            <Form.Select id="productType" onChange={changeDesc} required>
                                <option value ="" hidden>Type Switcher</option>
                                <option id="DVD" value="DVD">DVD</option>
                                <option id="Book" value="Book">Book</option>
                                <option id="Furniture" value="Furniture">Furniture</option>
                            </Form.Select>
                        </Form.Group>
                        <Form.Group className="mb-3 pb-3" onChange={handleTypeChange}>
                            {typeP}
                        </Form.Group>
                    </Form>
                </Row>
            </Container>
        </>
    );
}