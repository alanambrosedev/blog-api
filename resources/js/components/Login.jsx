import React, { useState } from "react";

export default function Login() {
    // 1. Create variables in "State" to hold the input values
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    const handleSubmit = (e) => {
        e.preventDefault(); // Stop the page from refreshing!
        console.log("What we are sending to Laravel eventually:", {
            email,
            password,
        });
    };

    return (
        <div
            style={{
                padding: "20px",
                border: "1px solid #ccc",
                width: "300px",
            }}
        >
            <h2>Login</h2>
            <form onSubmit={handleSubmit}>
                <div>
                    <label>Email</label>
                    <br />
                    <input
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                    />
                </div>
                <br />
                <div>
                    <label>Password</label>
                    <br />
                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                    />
                </div>
                <br />
                <button type="submit">Check Console</button>
            </form>
        </div>
    );
}
