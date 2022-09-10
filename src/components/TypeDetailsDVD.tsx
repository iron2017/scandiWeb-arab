import { Form } from "react-bootstrap";

export function TypeDetailsDVD(){
    return(
        <>
            <Form.Label>Size (MB)</Form.Label>
            <Form.Control id="size" type="number" placeholder="Enter Size" min={0} required/>

            <Form.Text muted>
                Please make sure the size provided is in MB
            </Form.Text>
        </>
    )
}