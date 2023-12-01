import axios from "axios";
import React, { useEffect } from "react";
import { Button, Col, Row } from "react-bootstrap";
import { useNavigate } from "react-router";
import { Route, Routes } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus, faTrash, faSave, faTimes } from "@fortawesome/free-solid-svg-icons";

export function Header({ countch, checks, setChecks, setCountch, getProducts }) {
    const navigate = useNavigate();

    const buttonStyle = {
        width: "fit-content",
        marginTop: "0.6%",
        marginRight: "0.6%",
        height: "2.5rem",
        position: "relative",
        boxShadow: "0 2px 8px rgba(0, 0, 0, 0.1)",
        borderRadius: "4px",
        transition: "background-color 0.3s ease",
        fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif", // Modern font
    };

    const iconStyle = {
        marginRight: "0.5rem",
    };

    const massDelete = (event) => {
        if (countch !== 0)
            axios.post("http://localhost:80/api/deleteproducts.php", checks).then(function (response) {
                if (response.data == 1) {
                    getProducts();
                    setChecks([]);
                    setCountch(0);
                } else if (response.data) {
                    alert(response.data);
                } else {
                    alert("Unrecognized error, please contact support");
                }
            });
        else alert("No products were checked. Please check some products and try again.");
    };

    return (
        <>
            <Row
                className="d-flex border-bottom"
                style={{
                    position: "relative",
                    padding: "1rem",
                    paddingLeft: "3rem",
                    paddingRight: "3rem",
                    backgroundColor: "#2c3e50",
                    color: "#ecf0f1",
                    marginBottom: "10px",
                    borderBottom: "2px solid #34495e",
                }}
            >
                <Routes>
                    <Route
                        path="/"
                        element={
                            <>
                                <Col className="me-auto">
                                    <h1 style={{ margin: 0, fontFamily: "'Nova Square', sans-serif"}}>Product List</h1>
                                </Col>
                                <Button
                                    style={{
                                        ...buttonStyle,
                                        backgroundColor: "#3498db",
                                        color: "#ecf0f1",
                                        border: "1px solid #2980b9",
                                    }}
                                    onClick={() => navigate("/addproduct")}
                                >
                                    <FontAwesomeIcon icon={faPlus} style={iconStyle} />
                                    ADD
                                </Button>
                                <Button
                                    style={{
                                        ...buttonStyle,
                                        backgroundColor: "#e74c3c",
                                        color: "#ecf0f1",
                                        border: "1px solid #c0392b",
                                    }}
                                    id="delete_product_btn"
                                    onClick={massDelete}
                                >
                                    <FontAwesomeIcon icon={faTrash} style={iconStyle} />
                                    MASS DELETE
                                    <div
                                        className="rounded-circle bg-danger d-flex justify-content-center align-items-center"
                                        style={{
                                            color: "#ecf0f1",
                                            width: "1.5rem",
                                            height: "1.5rem",
                                            position: "absolute",
                                            bottom: 0,
                                            right: 0,
                                            transform: "translate(25%,25%)",
                                        }}
                                    >
                                        {countch}
                                    </div>
                                </Button>
                            </>
                        }
                    />
                </Routes>
                <Routes>
                    <Route
                        path="/addproduct"
                        element={
                            <>
                                <Col className="me-auto">
                                    <h1 style={{ margin: 0, fontFamily: "'Nova Square', sans-serif" }}>Product Add</h1>
                                </Col>
                                <Button
                                    style={{
                                        ...buttonStyle,
                                        backgroundColor: "#2ecc71",
                                        color: "#ecf0f1",
                                        border: "1px solid #27ae60",
                                    }}
                                    form="product_form"
                                    type="submit"
                                >
                                    <FontAwesomeIcon icon={faSave} style={iconStyle} />
                                    Save
                                </Button>
                                <Button
                                    style={{
                                        ...buttonStyle,
                                        backgroundColor: "#95a5a6",
                                        color: "#ecf0f1",
                                        border: "1px solid #7f8c8d",
                                    }}
                                    onClick={() => navigate("/")}
                                >
                                    <FontAwesomeIcon icon={faTimes} style={iconStyle} />
                                    Cancel
                                </Button>
                            </>
                        }
                    />
                </Routes>
            </Row>
        </>
    );
}
