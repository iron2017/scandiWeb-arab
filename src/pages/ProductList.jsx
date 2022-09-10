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

export function ProductList({checks, setCountch, setChecks, getProducts, listItems}){

    useEffect(() => {
        getProducts();
    }, []);

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
    }

    const settings = {
        dots: true,
        infinite: false,
        speed: 500,
        slidesToScroll: 2,
        rows: 3,
        slidesPerRow: 4,
        nextArrow: <SampleNextArrow />,
        prevArrow: <SamplePrevArrow />
    };

    return (
        <>
            <Container style={{minHeight:"69vh"}}>
                <Slider {...settings}>
                    {listItems.map(item =>(
                        <div className="p-3" key={item.id} onChange={changeCheck}>
                            <ListItem {...item}/>
                        </div>
                    ))}
                </Slider>
            </Container>
        </>
    )
}