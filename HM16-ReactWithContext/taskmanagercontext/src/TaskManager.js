import React, {useState, useEffect, useContext} from "react";
import {TaskContext} from "./TaskContext";

const TaskManager = () => {
    const {tasks, addTask, deleteTask, editTaskFunc, setEditTask, editTask} = useContext(TaskContext);
    const [taskText, setTaskText] = useState("");
    const [currentPage, setCurrentPage] = useState(1);
    const [tasksPerPage] = useState(5);
    const [searchQuery, setSearchQuery] = useState("");

    useEffect(() => {
        if (editTask) {
            setTaskText(editTask.text);
        } else {
            setTaskText("");
        }
    }, [editTask]);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (editTask) {
            editTaskFunc({...editTask, text: taskText});
        } else {
            addTask(taskText);
        }
        setTaskText("");
    };

    const indexOfLastTask = currentPage * tasksPerPage;
    const indexOfFirstTask = indexOfLastTask - tasksPerPage;
    const filteredTasks = tasks.filter((task) =>
        task.text.toLowerCase().includes(searchQuery.toLowerCase())
    );
    const currentTasks = filteredTasks.slice(indexOfFirstTask, indexOfLastTask);
    const totalPages = Math.ceil(filteredTasks.length / tasksPerPage);

    const nextPage = () => {
        if (currentPage < totalPages) {
            setCurrentPage(currentPage + 1);
        }
    };

    const previousPage = () => {
        if (currentPage > 1) {
            setCurrentPage(currentPage - 1);
        }
    };

    return (
        <div className="task-manager">
            <h1>Task Manager</h1>

            <form onSubmit={handleSubmit}>
                <input
                    type="text"
                    value={taskText}
                    onChange={(e) => setTaskText(e.target.value)}
                    placeholder="Add a new task"
                    required
                />
                <button type="submit">{editTask ? "Update Task" : "Add Task"}</button>
            </form>

            <input
                type="text"
                placeholder="Search tasks..."
                value={searchQuery}
                onChange={(e) => {
                    setSearchQuery(e.target.value);
                    setCurrentPage(1);
                }}
            />

            <p className="task-counter">Total tasks: {tasks.length}</p>

            <ul>
                {currentTasks.map((task) => (
                    <li key={task.id}>
                        {task.text}
                        <div>
                            <button onClick={() => setEditTask(task)}>Edit</button>
                            <button onClick={() => deleteTask(task.id)}>Delete</button>
                        </div>
                    </li>
                ))}
            </ul>

            <div className="pagination">
                <button onClick={previousPage} disabled={currentPage === 1}>
                    Previous
                </button>
                <span>
                    Page {currentPage} of {totalPages}
                </span>
                <button onClick={nextPage} disabled={currentPage === totalPages}>
                    Next
                </button>
            </div>
        </div>
    );
};

export default TaskManager;
