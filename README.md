# Project Setup and Usage

This document provides instructions for installing, running, and testing the project. Follow these steps to ensure proper setup and execution.

---

## Running the Project

You can run the project using one of the following methods:

### **1. Using Docker**

1. Build and Start the Docker Container:
   ```bash
   docker-compose up --build
   ```

2. Connect to the Container Shell:
   ```bash
   docker exec -it <container-name> sh
   ```

3. Install Dependencies:
   ```bash
   composer install
   ```

4. Run the Symfony Console Command:
   ```bash
   php /app/app.php app:calculate-fee <term> <amount>
   ```
   Replace `<term>` and `<amount>` with the desired inputs (e.g., `12 1000`).

### **2. Directly on the Host Machine**

1. Install Dependencies:
   ```bash
   composer install
   ```

2. Run the Symfony Console Command:
   ```bash
   php app.php app:calculate-fee <term> <amount>
   ```

Replace `<term>` and `<amount>` with the desired inputs (e.g., `12 1000`).

---

## Running the Tests

### **1. Unit Tests**
Run the unit tests to verify individual components:
   ```bash
   vendor/bin/phpunit --testsuite "Unit Tests"
   ```

### **2. Integration Tests**
Run the integration tests to verify the interaction between components:
   ```bash
   vendor/bin/phpunit --testsuite "Integration Tests"
   ```

### **3. All Tests**
Run all test suites:
   ```bash
   vendor/bin/phpunit
   ```

---

## Project Structure

### **Key Directories:**
- `/src`: Contains the main application code.
    - `/Loan`: Includes domain logic, services, and repositories.
    - `/Common`: Shared interfaces and utilities.
- `/tests`: Contains all test cases.
    - `/Unit`: Unit tests.
    - `/Integration`: Integration tests.
- `/vendor`: Contains installed dependencies (ignored by Git).

---

## Notes
- Ensure that `composer install` is run inside the container or locally to install dependencies.
- Ensure that `app.php` is executable and properly configured to execute the Symfony Console commands.

