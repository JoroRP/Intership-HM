import React, {useState, useEffect} from "react";
import "./App.css";

const TaskManager = () => {
    const [tasks, setTasks] = useState([]);
    const [editTask, setEditTask] = useState(null);
    const [currentPage, setCurrentPage] = useState(1);
    const [tasksPerPage] = useState(5);
    const [searchQuery, setSearchQuery] = useState("");

    useEffect(() => {
        const storedTasks = JSON.parse(localStorage.getItem("tasks"));
        if (storedTasks) {
            setTasks(storedTasks);
        }
    }, []);

    useEffect(() => {
        localStorage.setItem("tasks", JSON.stringify(tasks));
    }, [tasks]);

    const addTask = (task) => {
        setTasks((prevTasks) => [...prevTasks, {id: Date.now(), text: task}]);
    };

    const editExistingTask = (task) => {
        setTasks((prevTasks) =>
            prevTasks.map((t) => (t.id === task.id ? task : t))
        );
        setEditTask(null);
    };

    const deleteTask = (id) => {
        setTasks((prevTasks) => prevTasks.filter((task) => task.id !== id));
    };

    const indexOfLastTask = currentPage * tasksPerPage;
    const indexOfFirstTask = indexOfLastTask - tasksPerPage;
    const currentTasks = tasks.slice(indexOfFirstTask, indexOfLastTask);

    const filteredTasks = tasks.filter((task) =>
        task.text.toLowerCase().includes(searchQuery.toLowerCase())
    );

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
        <div>
            <h1>Task Manager</h1>
            <TaskForm
                addTask={addTask}
                editTask={editTask}
                editExistingTask={editExistingTask}
                setEditTask={setEditTask}
            />

            <input
                type="text"
                placeholder="Search tasks..."
                value={searchQuery}
                onChange={(e) => {
                    setSearchQuery(e.target.value);
                    setCurrentPage(1);
                }}
            />

            <TaskCounter count={tasks.length}/>

            <div>
                <TaskList
                    tasks={filteredTasks.slice(indexOfFirstTask, indexOfLastTask)}
                    deleteTask={deleteTask}
                    setEditTask={setEditTask}
                />
            </div>

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

const TaskList = ({tasks, deleteTask, setEditTask}) => {
    return (
        <ul>
            {tasks.map((task) => (
                <li key={task.id}>
                    {task.text}
                    <div>
                        <button onClick={() => setEditTask(task)}>Edit</button>
                        <button onClick={() => deleteTask(task.id)}>Delete</button>
                    </div>
                </li>
            ))}
        </ul>
    );
};

const TaskForm = ({addTask, editTask, editExistingTask, setEditTask}) => {
    const [taskText, setTaskText] = useState("");

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
            editExistingTask({...editTask, text: taskText});
        } else {
            addTask(taskText);
        }
        setTaskText("");
    };

    return (
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
    );
};

const TaskCounter = ({count}) => {
    return <p className="task-counter">Total tasks: {count}</p>;
};

export default TaskManager;
