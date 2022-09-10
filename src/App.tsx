import { Route, Routes } from "react-router-dom";
import { Container } from "react-bootstrap";
import { AddProduct } from "./pages/AddProduct";
import { ProductList } from "./pages/ProductList";
import { Footer } from "./components/Footer";
import "slick-carousel/slick/slick.css"
import "slick-carousel/slick/slick-theme.css"
import { Header } from "./components/Header";


function App(): JSX.Element {
  return (
    <>
      <Header/>
        <Routes>
          <Route path="/" element={<ProductList />}/>
          <Route path="/addproduct" element={<AddProduct />}/>
        </Routes>
      <Footer/>
    </>
  )
}

export default App
