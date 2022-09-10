import React, { useEffect, useState } from "react";
import { Button, Col, Container, Row } from "react-bootstrap";
import { ListItem } from "../components/ListItem";
import Slider from "react-slick";
import axios, { AxiosResponse } from "axios";
import { Link, Navigate, useNavigate } from "react-router-dom";
import "../../style.css"

function SampleNextArrow(props) {
    const { className, style, onClick } = props;
    return (
      <div
        className={"slide-arrow next-arrow"}
        style={{ ...style, display: "block"}}
        onClick={onClick}
      />
    );
  }
  
function SamplePrevArrow(props) {
    const { className, style, onClick } = props;
    return (
        <div
        className={"slide-arrow prev-arrow"}
        style={{ ...style, display: "block" }}
        onClick={onClick}
        />
    );
}

export function ProductList(){

    const [listItems, setListItems] = useState([])
    const [checks,setChecks] = useState([])
    const [countch,setCountch] = useState(0)
    const navigate = useNavigate()

    useEffect(() => {
        getProducts();
    }, []);

    function getProducts(){
        axios.get('https://juniortestnadir.000webhostapp.com/api/').then(function(response){
            if(response.data.constructor === Array)
                setListItems(response.data);
            else
                alert(response.data);
        })
    }

    const add = (temp, val) => {
        temp.push(val)
        setChecks(temp)
        setCountch(temp.length)
    }
    const remove = (temp, val) => {
        temp = temp.filter(function(id){
            return id !== val
        })
        setChecks(temp)
        setCountch(temp.length)
    }

    const changeCheck = (event) => {
        let val = event.target.id
        let temp = checks
        if(event.target.checked){
            add(temp, val)
        }
        else{
            remove(temp, val)
        }
        console.log(checks)
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

    const buttonStyle = {
        marginTop: "0.6%",
        marginRight: "0.6%",
        height: "2.5rem",
        position:"relative",
        boxShadow: "0 1px 14px rgb(0,0,0,0.8)"
    }

    const settings = {
        dots: true,
        infinite: false,
        speed: 500,
        slidesToScroll: 2,
        rows: 4,
        slidesPerRow: 4,
        nextArrow: <SampleNextArrow />,
        prevArrow: <SamplePrevArrow />
    };

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
                    <h1>Product List</h1>
                </Col>
                <Button style={{...buttonStyle, width: "5rem"}}
                        onClick={() => {navigate("/addproduct");}}
                >ADD</Button>
                <Button
                        style={{...buttonStyle, width: "8rem"}}
                        id="delete_product_btn"
                        onClick={massDelete}
                >MASS DELETE<div className="rounded-circle bg-danger d-flex justify-content-center align-items-center"
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
            </Row>
            <Container style={{minHeight:"700px"}}>
                <Slider {...settings}>
                    {listItems.map(item =>(
                        <Col className="p-1" key={item.id} onChange={changeCheck}>
                            <ListItem {...item}/>
                        </Col>
                    ))}
                </Slider>
            </Container>
        </>
    )
}