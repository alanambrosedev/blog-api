import { createRoot } from "react-dom/client";
import "./bootstrap";
import Login from "./components/Login";

const root = createRoot(document.getElementById("root"));
root.render(<Login />);
