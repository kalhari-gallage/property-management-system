<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Property Management System API ##
### Description ###
This project is a RESTful API built using Laravel for a Property Management System. The system allows users to manage properties and tenants, with features like user authentication, CRUD operations, and rent calculation logic. The API ensures that only authenticated users can access the resources.

### Features ###
- User registration, login, and logout (using Laravel Sanctum).
- Property and tenant management (CRUD operations).
- Rent distribution logic (split rent among tenants).
- Optional features: Soft deletes, monthly rent for tenants, and search filters.
- Requirements
- PHP >= 8.2
- Composer
- Laravel 11.x
- MySQL or any database supported by Laravel
### Installation ###
Clone the repository to your local machine:
```bash
git clone https://github.com/kalhari-gallage/property-management-system.git
```

Navigate to the project directory:

```bash
cd property-management-system
```

Install the required dependencies:

```bash
composer install
```

Set up the environment file:

```bash
copy .env.example .env
```

Modify the .env file to configure your database and other settings.

Generate the application key:

```bash
php artisan key:generate
```

Run the migrations and seed the database:

```bash
php artisan migrate --seed
```

This will set up the database and populate it with sample data using Laravel Seeders.
Start the local development server:

```bash
php artisan serve
```
The API will be accessible at [http://localhost:8000].

#### API Endpoints ####

###### Authentication #####

For endpoints that require authentication, you must include a `Bearer` Token in the request header.

Example:

In your Headers section, add:
```bash
Authorization: Bearer {your_access_token}
```
How to Get the Bearer Token:    


First, use the `POST /api/login` endpoint to log in with your user credentials and receive the token.
Use this token in the Authorization header for all subsequent requests to endpoints that require authentication, such as `GET /api/properties`.
Example of a request with Bearer Token:
```bash
GET /api/properties
```
`Authorization: Bearer 5|lgPMzvajbU0KduZlUsXlCwa3SRnFy3attIoi...`


- `POST /api/register:` Register a user.
- `POST /api/login:` Login and receive an access token.

Request

```bash
{
    "email": "test@gmail.com",
    "password": "password"
}
```

Response

```bash
{
    "status": "Success",
    "message": "Successfully logged in.",
    "data": {
        "user": {
            "id": 29,
            "name": "Tests",
            "email": "test@gmail.com",
            "email_verified_at": null,
            "created_at": "2025-02-25T09:52:36.000000Z",
            "updated_at": "2025-02-25T09:52:36.000000Z"
        },
        "access_token": "3|jh4dfg6BDp5tZyxte1VXpfbCf9PCFbfHOyAZAqjge9dc3999"
    }
}
```


- `POST /api/logout:` Log out and revoke the token.

###### Property Management ######

- `GET /api/properties:` Retrieve all properties (only for authenticated users).

The response should be like this.

```bash
{
    {
    "status": "Success",
    "message": "Success",
    "data": [
        {
            "id": 1,
            "name": "Bins-Russel",
            "address": "8084 Alberto Road Suite 392\nStrosinport, AK 57665",
            "rent_amount": 4453.16,
            "owner_id": 1
        },
        {
            "id": 2,
            "name": "Green-Kris",
            "address": "5698 Kreiger Harbors Suite 777\nEast Kaylee, IA 80956-2754",
            "rent_amount": 2808.13,
            "owner_id": 2
        },
    ]
}

```

- `POST /api/properties:` Create a new property.
  
Request

```bash
{
    "name": "Green Park",
    "address": "43 Main St, New York, NY",
    "rent_amount": 2000,
    "owner_id": 2
}
```

Response

```bash
{
    "status": "Success",
    "message": "Property created successfully.",
    "data": [
        {
            "id": 17,
            "name": "Green Park",
            "address": "43 Main St, New York, NY",
            "rent_amount": 2000,
            "owner_id": 2
        }
    ]
}
```


- `GET /api/properties/{id}:` Retrieve a single property with its tenants.
- `PUT /api/properties/{id}:` Update property details.
- `DELETE /api/properties/{id}:` Delete a property.

###### Tenant Management ######

- `POST /api/tenants:` Assign a tenant to a property.
- `GET /api/tenants:` Retrieve all tenants with their property details.
- `DELETE /api/tenants/{id}:` Remove a tenant from a property.

##### Sample Data ##### 
Sample data is seeded using Laravel Seeders. You can modify the seeders located in database/seeders to adjust the sample data.

##### Testing ##### 
You can test the API endpoints using tools like Postman. Be sure to use a valid authentication token for any endpoint that requires it.

##### Response codes ##### 
- Success (201 Created):
- Success (200 Ok):
- Error (422 Unprocessable content):
- Error (401 Unauthorized):
- Error (404 Not Found):

Example

```bash
{
    "status": "Error",
    "message": "No tenants found for this property",
    "errors": null,
    "error_code": 404
}
```
