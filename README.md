# Solar Panel Search Application

This is a Laravel-based application for searching and managing solar panels, batteries, and connectors.

## Getting Started

1.  **Clone the repository:**

    ```bash
    git clone <repository-url>
    ```

2.  **Install dependencies:**

    ```bash
    composer install
    ```

3.  **Create a `.env` file:**

    ```bash
    cp .env.example .env
    ```

4.  **Generate an application key:**

    ```bash
    php artisan key:generate
    ```

5.  **Run database migrations:**

    ```bash
    php artisan migrate
    ```

6.  **Seed the database:**

    ```bash
    php artisan db:seed
    ```

7.  **Start the development server:**

    ```bash
    sail up
    ```

## Available Commands

-   `sail up`: Starts the development server.
-   `php artisan db:seed`: Seeds the database with initial data.
-   `php artisan migrate`: Runs database migrations.
-   `php artisan key:generate`: Generates a new application key.
-   `php artisan app:algolia:setup-products`: **(Mandatory)** This command sets up the Algolia search index for products.
-   `php artisan app:search-command`: Used only for quick internal search tests.

## Models

-   **Battery**: Represents a battery.
-   **Connector**: Represents a connector.
-   **ConnectorType**: Represents a type of connector.
-   **Manufacturer**: Represents a manufacturer.
-   **SolarPanel**: Represents a solar panel.
-   **User**: Represents a user.
