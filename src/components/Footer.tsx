export function Footer() {

    return (
        <footer className="text-lg-start bg-light text-muted"
            style={{
                position: "relative",
                backgroundColor: "#efefef",
                textAlign: "center"}}
            >
            <div className="p-4 mt-4 border-top" style={{backgroundColor: "rgba(0, 0, 0, 0.05)"}}>
                <h6 className="text-uppercase fw-bold mb-4 text-center">
                    <i className="fas fa-gem me-3"></i>Scandiweb Test assignment
                </h6>
            </div>
        </footer>
    );
}