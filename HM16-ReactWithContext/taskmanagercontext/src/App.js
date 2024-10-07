import React from "react";
import {TaskProvider} from "./TaskContext";
import TaskManager from "./TaskManager";
import './App.css';

function App() {
    return (
        <TaskProvider>
            <TaskManager/>
        </TaskProvider>
    );
}

export default App;
