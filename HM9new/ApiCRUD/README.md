# Product Management API

This is a Symfony-based API for managing products, customers, and orders.

## Features

- **Product Management**: CRUD operations for products, including assigning categories.
- **Customer Management**: Handle customer creation, updates, and deletion.
- **Order Management**: Create and manage orders with statuses like Pending, Completed, and Cancelled.
- **Category Management**: Add, edit, and delete product categories.

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/product-management-api.git

2. Create and set up the database:

- Update your .env.local file with correct DB credentials
  DATABASE_URL="mysql://root:root@127.0.0.1:3306/product_management?charset=utf8mb4"

- Create the database
  php bin/console doctrine:database:create

- Run migrations
  php bin/console doctrine:migrations:migrate

3. Start the Symfony server
    ```bash
   symfony serve

4. Visit http://localhost:8000 or use Postman to access and test the API.

### Categories

| Method | Endpoint               | Description             |
|--------|------------------------|-------------------------|
| GET    | `/api/categories`      | List all categories     |
| GET    | `/api/categories/{id}` | Get category details    |
| POST   | `/api/categories`      | Create a new category   |
| PUT    | `/api/categories/{id}` | Update category details |
| DELETE | `/api/categories/{id}` | Delete a category       |

### Products

| Method | Endpoint             | Description            |
|--------|----------------------|------------------------|
| GET    | `/api/products`      | List all products      |
| GET    | `/api/products/{id}` | Get product details    |
| POST   | `/api/products`      | Create a new product   |
| PUT    | `/api/products/{id}` | Update product details |
| DELETE | `/api/products/{id}` | Delete a product       |

### Customers

| Method | Endpoint              | Description             |
|--------|-----------------------|-------------------------|
| GET    | `/api/customers`      | List all customers      |
| GET    | `/api/customers/{id}` | Get customer details    |
| POST   | `/api/customers`      | Create a new customer   |
| PUT    | `/api/customers/{id}` | Update customer details |
| DELETE | `/api/customers/{id}` | Delete a customer       |

### Orders

| Method | Endpoint           | Description          |
|--------|--------------------|----------------------|
| GET    | `/api/orders`      | List all orders      |
| GET    | `/api/orders/{id}` | Get order details    |
| POST   | `/api/orders`      | Create a new order   |
| PUT    | `/api/orders/{id}` | Update order details |
| DELETE | `/api/orders/{id}` | Delete an order      |