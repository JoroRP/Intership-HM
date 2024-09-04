# Teachers API

This is a simple API test that provides endpoints to manage teacher data.
It supports reaally basic authentication, and CRUD operations (Create, Read, Update, Delete) on teachers.

## Features

- **Authentication**
    - **Login:** Simple login to check if endpoint was access. If not - error 403.
    - **Logout:** Ends the user session and revokes access.

- **CRUD Operations**
    - **Create Teacher:** Add a new teacher record with ID, name, subject, and email.
    - **Read All Teachers:** Retrieve a list of all teacher records.
    - **Read Teacher by ID:** Retrieve a specific teacher record by ID.
    - **Update Teacher:** Update the details of an existing teacher record.
    - **Patch Teacher:** Modify specific fields of an existing teacher record.
    - **Delete Teacher:** Remove a teacher record by ID.

- **Data Storage**
    - Teacher records are stored in a `teachers.json` file, which is used to load and save data.
