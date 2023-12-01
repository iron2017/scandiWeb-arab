import React from 'react';

export function Footer() {
    return (
        <footer
            className="text-lg-start bg-light text-muted"
            style={{
                position: "fixed", // Set to fixed
                bottom: 0, // Stick to the bottom
                width: "100%", // Full width
                backgroundColor: "#f8f9fa",
                textAlign: "center",
            }}
        >
            <div className="p-4 mt-4 border-top" style={{ backgroundColor: "rgba(0, 0, 0, 0.05)" }}>
                <h6 className="text-uppercase fw-bold mb-4 text-center" style={{ fontFamily: "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif" }}>
                    <i className="fas fa-gem me-3"></i>Scandiweb Test Assignment
                </h6>
                <p className="text-muted mb-0">
                    Designed and Developed by ARAB Mohammed | © {new Date().getFullYear()}
                </p>
            </div>
        </footer>
    );
}
