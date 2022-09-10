import { Form } from "react-bootstrap";

export function TypeDetailsFurniture(){
    return(
        <>
            <Form.Label>Height (CM)</Form.Label>
            <Form.Control id="height" type="number" placeholder="Enter Height" min={0} required/>
            
            <Form.Label>Width (CM)</Form.Label>
            <Form.Control id="width" type="number" placeholder="Enter Width" min={0} required/>
            
            <Form.Label>Length (CM)</Form.Label>
            <Form.Control id="length" type="number" placeholder="Enter Length" min={0} required/>

            <Form.Text muted>
                Please provide dimensions, the main list of products will show them in HxWxL format
            </Form.Text>
        </>
    )
}