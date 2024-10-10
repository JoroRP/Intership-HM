import React from "react";
import {render, fireEvent, act} from "@testing-library/react";
import {TaskProvider} from "../TaskContext";
import TaskManager from "../TaskManager";

describe("TaskManager", () => {
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

    test("should add a new task", async () => {
        const {getByPlaceholderText, getByText, getAllByRole} = render(
            <TaskProvider>
                <TaskManager/>
            </TaskProvider>
        );

        const input = getByPlaceholderText("Add a new task");
        const button = getByText("Add Task");

        await act(async () => {
            fireEvent.change(input, {target: {value: "New Task"}});
            fireEvent.click(button);
        });

        const items = getAllByRole("listitem");
        expect(items).toHaveLength(2);
        expect(items[1]).toHaveTextContent("New Task");
    });

    test("should edit an existing task", async () => {
        localStorage.getItem.mockImplementation(() => JSON.stringify([]));

        const {getByText, getAllByText, getByPlaceholderText} = render(
            <TaskProvider>
                <TaskManager/>
            </TaskProvider>
        );

        const input = getByPlaceholderText("Add a new task");
        fireEvent.change(input, {target: {value: "Unique Task 1"}});
        fireEvent.click(getByText("Add Task"));

        fireEvent.change(input, {target: {value: "Unique Task 2"}});
        fireEvent.click(getByText("Add Task"));

        const editButtons = getAllByText("Edit");

        fireEvent.click(editButtons[0]);

        expect(input.value).toBe("Unique Task 1");

        fireEvent.change(input, {target: {value: "Edited Unique Task 1"}});
        fireEvent.click(getByText("Update Task"));

        expect(getByText("Edited Unique Task 1")).toBeInTheDocument();
        expect(() => getByText("Unique Task 1")).toThrow();
    });


    test("should delete a task", () => {
        const {getByText, getAllByRole, queryByText} = render(
            <TaskProvider>
                <TaskManager/>
            </TaskProvider>
        );

        const initialItems = getAllByRole("listitem");
        expect(initialItems).toHaveLength(1);
        expect(initialItems[0]).toHaveTextContent("Existing Task");

        fireEvent.click(getByText("Delete"));

        expect(queryByText("Existing Task")).toBeNull();
        expect(() => getAllByRole("listitem")).toThrow();
    });

    test("should filter tasks based on search query", () => {
        const {getByPlaceholderText, getByText, getAllByRole, queryByText} = render(
            <TaskProvider>
                <TaskManager/>
            </TaskProvider>
        );

        expect(getByText("Existing Task")).toBeInTheDocument();

        const searchInput = getByPlaceholderText("Search tasks...");
        fireEvent.change(searchInput, {target: {value: "Non-Existent Task"}});

        expect(queryByText("Existing Task")).toBeNull();
        expect(() => getAllByRole("listitem")).toThrow();

        fireEvent.change(searchInput, {target: {value: ""}});

        expect(getByText("Existing Task")).toBeInTheDocument();
    });

    test("should handle pagination correctly", () => {
        const {getByText, getByPlaceholderText, getAllByRole} = render(
            <TaskProvider>
                <TaskManager/>
            </TaskProvider>
        );

        for (let i = 0; i < 6; i++) {
            fireEvent.change(getByPlaceholderText("Add a new task"), {target: {value: `Task ${i}`}});
            fireEvent.click(getByText("Add Task"));
        }

        let items = getAllByRole("listitem");
        expect(items).toHaveLength(5);

        fireEvent.click(getByText("Next"));
        items = getAllByRole("listitem");
        expect(items).toHaveLength(2);

        fireEvent.click(getByText("Previous"));
        items = getAllByRole("listitem");
        expect(items).toHaveLength(5);
    });

});
