Php Composer Project
====================

This project is a PHP-based web application using the Slim framework. It includes various endpoints for importing CSV data, filtering properties, calculating average prices, and generating reports.

## Getting Started

Follow these instructions to set up and run the project on your local machine.

### Prerequisites

- PHP 7.4 or higher
- Composer
- MySQL or MariaDB

### Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/yourrepository.git
   cd yourrepository `

1.  **Install dependencies:**

    `composer install`

2.  **Create a `.env` file in the project root with the following content:**

    Copy code

    `DB_HOST=localhost
    DB_DATABASE=db_property
    DB_USERNAME=root
    DB_PASSWORD=Strawberry4ever`

3.  **Set up your database:**

    -   Run the SQL script provided in the project root to set up the database schema and any initial data:


        `mysql -u root -p db_property < setup_database.sql`

4.  **Run migrations (if any):**

    Copy code

    `php bin/console migrations:migrate`

### Running the Application

Start the development server:


Copy code

`php -S localhost:8080 -t public`

### Testing Endpoints

You can test the endpoints using a tool like Postman or cURL.

1.  **Import CSV:**

    -   **URL:** `http://localhost:8080/api/import-csv`
    -   **Method:** POST
    -   **Body:** JSON

        json

        Copy code

        `{
          "csv_file": "C:\\path\\to\\your\\file.csv"
        }`

    -   **Note:** Ensure that the `csv_file` path is structured with double backslashes (`\\`) to make the file path readable.

    Example cURL:

    bash

    Copy code

    `curl -X POST -H "Content-Type: application/json" -d '{"csv_file":"C:\\path\\to\\your\\file.csv"}' http://localhost:8080/api/import-csv`

2.  **Get Properties:**

    -   **URL:** `http://localhost:8080/api/properties`
    -   **Method:** GET
    -   **Query Parameters:**
        -   `min_price` (optional): Minimum price filter
        -   `max_price` (optional): Maximum price filter
        -   `bedrooms` (optional): Number of bedrooms filter

    Example cURL:

    bash

    Copy code

    `curl "http://localhost:8080/api/properties?min_price=100000&max_price=500000&bedrooms=3"`

3.  **Get Average Price:**

    -   **URL:** `http://localhost:8080/api/average-price`
    -   **Method:** GET
    -   **Query Parameters:**
        -   `latitude` (optional): Latitude of the location
        -   `longitude` (optional): Longitude of the location
        -   `distance` (optional): Distance in kilometers (default is 10)

    Example cURL:

    bash

    Copy code

    `curl "http://localhost:8080/api/average-price?latitude=40.7128&longitude=-74.0060&distance=5"`

4.  **Generate Report:**

    -   **URL:** `http://localhost:8080/api/generate-report`
    -   **Method:** GET
    -   **Query Parameters:**
        -   `latitude` (optional): Latitude of the location
        -   `longitude` (optional): Longitude of the location
        -   `report_type` (optional): Type of report (e.g., 'summary', 'detailed')

    Example cURL:

    bash

    Copy code

    `curl "http://localhost:8080/api/generate-report?latitude=40.7128&longitude=-74.0060&report_type=pdf`