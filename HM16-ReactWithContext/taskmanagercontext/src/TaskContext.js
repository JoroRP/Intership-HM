import React, {createContext, useState, useEffect} from "react";

export const TaskContext = createContext();

export const TaskProvider = ({children}) => {
    const [tasks, setTasks] = useState([]);
    const [editTask, setEditTask] = useState(null);

    useEffect(() => {
        const storedTasks = JSON.parse(localStorage.getItem("tasks"));
        if (storedTasks) {
            setTasks(storedTasks);
        }
    }, []);

    useEffect(() => {
        if (tasks.length > 0) {
            localStorage.setItem("tasks", JSON.stringify(tasks));
        }
    }, [tasks]);

    const addTask = (taskText) => {
        setTasks((prevTasks) => [...prevTasks, {id: Date.now(), text: taskText}]);
    };

    const editTaskFunc = (task) => {
        setTasks((prevTasks) =>
            prevTasks.map((t) => (t.id === task.id ? task : t))
        );
        setEditTask(null);
    };

    const deleteTask = (id) => {
        setTasks((prevTasks) => prevTasks.filter((task) => task.id !== id));
    };

    return (
        <TaskContext.Provider value={{tasks, addTask, editTaskFunc, deleteTask, setEditTask, editTask}}>
            {children}
        </TaskContext.Provider>
    );
};
