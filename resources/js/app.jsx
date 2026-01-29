import { createRoot } from "react-dom/client";
import "./bootstrap";

function HelloWorld() {
    return <h1>React is alive!</h1>;
}

const root = createRoot(document.getElementById("root"));
root.render(<HelloWorld />);
