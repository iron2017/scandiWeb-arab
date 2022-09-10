import { Form } from "react-bootstrap";

export function TypeDetailsBook(){
    return(
        <>
            <Form.Label>Weight (KG)</Form.Label>
            <Form.Control id="weight" type="number" placeholder="Enter Weight" min={0} required/>

            <Form.Text muted>
                Please provide be precise about the weight to improve the purchasing process
            </Form.Text>
        </>
    )
}