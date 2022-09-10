import { Route, Routes } from "react-router-dom";
import { Container } from "react-bootstrap";
import { AddProduct } from "./pages/AddProduct";
import { ProductList } from "./pages/ProductList";
import { Footer } from "./components/Footer";
import "slick-carousel/slick/slick.css"
import "slick-carousel/slick/slick-theme.css"
import { Header } from "./components/Header";
import { useState } from "react";
import axios from "axios";


function App() {

  const [listItems, setListItems] = useState([])
  const [countch,setCountch] = useState(0)
  const [checks,setChecks] = useState([])

  function getProducts(){
    axios.get('https://juniortestnadir.000webhostapp.com/api/').then(function(response){
        if(response.data.constructor === Array)
            setListItems(response.data);
        else
            alert(response.data);
    })
  }

  return (
    <>
      <Header getProducts={getProducts} countch={countch} checks={checks} setChecks={setChecks} setCountch={setCountch}/>
        <Routes>
          <Route path="/" element={<ProductList listItems={listItems} getProducts={getProducts} checks={checks} setChecks={setChecks} setCountch={setCountch}/>}/>
          <Route path="/addproduct/" element={<AddProduct />}/>
        </Routes>
      <Footer/>
    </>
  )
}

export default App
