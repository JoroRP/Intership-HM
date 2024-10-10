import React, {useContext} from "react";
import {render, fireEvent, act} from "@testing-library/react";
import {TaskProvider, TaskContext} from "../TaskContext";

const TestComponent = () => {
    const {tasks, addTask, editTaskFunc, deleteTask, setEditTask, editTask} = useContext(TaskContext);

    return (
        <div>
            <button onClick={() => addTask("New Task")}>Add Task</button>
            <button onClick={() => setEditTask(tasks[0])}>Set Edit Task</button>
            <button onClick={() => editTaskFunc({...tasks[0], text: "Edited Task"})}>
                Edit Task
            </button>
            <button onClick={() => deleteTask(tasks[0]?.id)}>Delete Task</button>
            <ul>
                {tasks.map((task) => (
                    <li key={task.id}>{task.text}</li>
                ))}
            </ul>
            {editTask && <div>Edit Mode: {editTask.text}</div>}
        </div>
    );
};

describe("TaskContext", () => {
    beforeEach(() => {
        jest.spyOn(Storage.prototype, "setItem");
        jest.spyOn(Storage.prototype, "getItem");
        localStorage.setItem.mockClear();
        localStorage.getItem.mockClear();
        localStorage.getItem.mockImplementation(() => JSON.stringify([{id: 1, text: "Existing Task"}]));
    });

    afterEach(() => {
        localStorage.getItem.mockRestore();
        localStorage.setItem.mockRestore();
    });

    test("should add a task and update localStorage", async () => {
        const {getByText, getAllByRole} = render(
            <TaskProvider>
                <TestComponent/>
            </TaskProvider>
        );

        await act(async () => {
            fireEvent.click(getByText("Add Task"));
        });

        const items = getAllByRole("listitem");
        expect(items).toHaveLength(2);
        expect(items[1]).toHaveTextContent("New Task");

        expect(localStorage.setItem).toHaveBeenCalledTimes(2);
    });

    test("should edit a task", () => {
        const {getByText, getAllByRole} = render(
            <TaskProvider>
                <TestComponent/>
            </TaskProvider>
        );

        fireEvent.click(getByText("Set Edit Task"));
        expect(getByText("Edit Mode: Existing Task")).toBeInTheDocument();

        fireEvent.click(getByText("Edit Task"));
        const items = getAllByRole("listitem");
        expect(items).toHaveLength(1);
        expect(items[0]).toHaveTextContent("Edited Task");
    });

    test("should delete a task", () => {
        const {getByText, getAllByRole, queryByText} = render(
            <TaskProvider>
                <TestComponent/>
            </TaskProvider>
        );

        const initialItems = getAllByRole("listitem");
        expect(initialItems).toHaveLength(1);
        expect(initialItems[0]).toHaveTextContent("Existing Task");

        fireEvent.click(getByText("Delete Task"));

        expect(queryByText("Existing Task")).toBeNull();
        expect(() => getAllByRole("listitem")).toThrow();
    });

    test("should load tasks from localStorage on initialization", () => {
        const {getAllByRole} = render(
            <TaskProvider>
                <TestComponent/>
            </TaskProvider>
        );

        const items = getAllByRole("listitem");
        expect(items).toHaveLength(1);
        expect(items[0]).toHaveTextContent("Existing Task");
    });
});
