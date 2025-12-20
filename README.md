# Tharad Tech E-Commerce API

A **Laravel 12 API** for e-commerce, built with modern practices like the **repository pattern**, **Form Requests**, **API Resources**, **Events & Listeners**, **database transactions**, and **caching** for performance.  
Supports features like **authentication, product management, cart management, and checkout**.

---

## Table of Contents

1. [Project Overview](#tharad-tech-e-commerce-api)  
2. [Notes / Additional Info](#notes--additional-info)  
3. [Features / Pull Requests](#features--pull-requests)    
4. [API Endpoints](#api-endpoints)  
5. [Testing / Postman](#testing--postman)  


---

## Notes / Additional Info

This project uses **best practices and Laravel features** to build a maintainable and scalable API:

- **Versioning**: All API endpoints are versioned under `/api/v1/`. 
- **Caching**: Product listing endpoints cache results for faster response times.  
- **Repository Pattern**: All database interactions are handled in repositories, keeping controllers clean and business logic centralized.  
- **Form Requests**: Input validation is handled via Laravel Form Requests for all endpoints.  
- **API Resources**: Responses are standardized with Resources (`AuthResource`, `UserResource`, `ProductResource`, `CartResource`, `OrderResource`) for consistent JSON output.  
- **Events & Listeners**: Example: `OrderCreated` event triggers `ClearUserCart` listener to empty the cart after checkout.  
- **Database Transactions**: Checkout uses transactions to ensure data consistency; either all operations succeed or none.   
- **Localization**: Products support multi-language titles and descriptions (English and Arabic).  
- **Clean Architecture**: Separation of concerns is maintained via Controllers, Repositories, Resources, Requests, Events, and Listeners.  
- **Error Handling**: Standardized error responses and validation messages across all endpoints.

---

## 3. Features / Pull Requests

### API v1 Auth
- Adds endpoints for **Register, Login, and Logout**.
- Uses **repository pattern**, **Form Requests** for validation, and **AuthResource** for consistent JSON responses.
- Endpoints: POST /auth/register, POST /auth/login, POST /auth/logout.

### API v1 Product Management
- Adds endpoints to **list products** and **view a single product**.
- Supports **filtering**, **sorting**, and uses **caching** for faster responses.
- Uses **repository pattern**, **ProductResource**, and includes **ProductSeeder** to generate sample products in English and Arabic.
- Endpoints: GET /products, GET /products/{id}.

### API v1 Cart Management
- Adds endpoints to **add items to cart, view cart, remove items, and clear cart**.
- Uses **repository pattern**, **Form Requests**, and **CartResource** to format responses.
- Endpoints: GET /cart, POST /cart, DELETE /cart/{id}, DELETE /cart/clear.

### Checkout Feature
- Allows creating an **order from the cart**.
- Uses **repository pattern**, **database transactions**, and **OrderResource**.
- Includes **Event/Listener** to automatically clear the cart after successful checkout.
- Endpoint: POST /checkout.

---
## 4. API Endpoints

### Auth Endpoints
| Method | Endpoint           | Description              |
|--------|------------------|--------------------------|
| POST   | /auth/register    | Register a new user      |
| POST   | /auth/login       | Login an existing user   |
| POST   | /auth/logout      | Logout the authenticated user |

### Product Endpoints
| Method | Endpoint          | Description                        |
|--------|-----------------|------------------------------------|
| GET    | /products        | List all products (supports filtering & sorting) |
| GET    | /products/{id}   | Get details of a specific product  |

### Cart Endpoints
| Method | Endpoint          | Description                          |
|--------|-----------------|--------------------------------------|
| GET    | /cart             | View current user's cart             |
| POST   | /cart             | Add a product to cart                |
| DELETE | /cart/{id}        | Remove a specific item from cart     |
| DELETE | /cart/clear       | Clear all items from cart            |

### Checkout Endpoint
| Method | Endpoint      | Description                        |
|--------|--------------|------------------------------------|
| POST   | /checkout     | Create an order from the user's cart |


---

## 5. Testing / Postman

Follow these steps to test the API endpoints using Postman or any API testing tool:

### 1. Run the Server
- Command: `php artisan serve`
- Base URL: `http://127.0.0.1:8000/api/v1/`

### 2. Auth Endpoints
- **Register**  
  - Method: POST `/auth/register`  
  - Provide: `name`, `email`, `password`
- **Login**  
  - Method: POST `/auth/login`  
  - Receive a **Bearer token** for authentication
- **Logout**  
  - Method: POST `/auth/logout`  
  - Use the **Bearer token** in the request header

### 3. Product Endpoints
- **List Products**  
  - Method: GET `/products`  
  - Optional query parameters: `min_price`, `max_price`, `sort_by` (price_asc, price_desc, created_at_asc, created_at_desc)
- **View Product**  
  - Method: GET `/products/{id}`  
  - Get details of a specific product

### 4. Cart Endpoints
- **View Cart**  
  - Method: GET `/cart`  
  - Requires authentication
- **Add to Cart**  
  - Method: POST `/cart`  
  - Provide: `product_id`, `quantity`
- **Remove Item**  
  - Method: DELETE `/cart/{id}`
- **Clear Cart**  
  - Method: DELETE `/cart/clear`

### 5. Checkout Endpoint
- **Checkout**  
  - Method: POST `/checkout`  
  - Cart must have items, user must be authenticated
- **Verify Cart Cleared**  
  - Method: GET `/cart`  
  - After checkout, the cart should be empty



