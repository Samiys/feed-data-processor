
# Feed Data Processor

## Overview

This project is a data processing application designed to parse XML data from a file (`feed.xml`), validate each item, and insert valid items into a MySQL database table (`items`). It focuses on processing data related to coffee products.

## Features

- **XML Parsing**: Parses XML data from `feed.xml` using the `XMLParser` class.
- **Validation**: Validates each item from the XML against specified criteria using the `ItemValidator` class.
- **Database Interaction**: Inserts valid items into a MySQL database using the `ItemRepository` and `DataInserter` classes.
- **Error Logging**: Logs errors encountered during parsing, validation, and database operations using the `ErrorLogger` class.
- **Dockerized Application**: Configured with Docker and Docker Compose for easy deployment and environment setup.

## Requirements

- PHP >= 7.3
- PDO PHP Extension
- Composer (for installing dependencies)
- Docker (optional, for local development)

## Installation

1. **Clone the repository:**

   ```bash
   git clone <repository-url>
   cd feed-data-processor
   ```

   [Download the project as a ZIP file](https://github.com/yourusername/feed-data-processor/archive/refs/heads/main.zip)

2. **Install dependencies:**

   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Set up environment variables:**

   Copy `.env.example` to `.env` and configure database credentials:

   ```bash
   cp .env.example .env
   ```

4. **Run with Docker (optional):**

   Build and start the Docker containers:

   ```bash
   docker-compose up -d --build
   ```

5. **Run the data processing script:**

   ```bash
   docker-compose run --rm app php src/main.php feed.xml
   ```

6. **Run PHPUnit tests:**

   ```bash
   docker-compose run --rm app vendor/bin/phpunit tests
   ```

### Manual setup (without Docker):

Ensure PHP and required extensions are installed.

Set up a MySQL database and configure `.env` accordingly.

Run the data processing script:

```bash
php src/main.php feed.xml
```

Run PHPUnit tests:

```bash
vendor/bin/phpunit tests
```

## Configuration

Database configuration (`config/config.php`):

Modify database host, name, user, and password as per your environment.

## Logging

Errors encountered during XML parsing, validation, or database operations are logged to `logs/app.log`.

## Contributing

Contributions are welcome! Fork the repository, make your changes, and submit a pull request.

## License

This project is licensed under the MIT License.
