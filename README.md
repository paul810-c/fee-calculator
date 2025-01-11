# Loan Fee Calculation Project

This project is designed to calculate loan fees based on predefined terms and amounts.

---

## **Project Requirements**

### **Problem Statement**
Given a monetary **amount** and a **term** (the contractual duration of the loan, expressed as a number of months) - will produce an appropriate fee for a loan, based on a fee structure and a set of rules described below.

### **Requirements/Rules**
- The fee structure does not follow a formula.
- Values in between the breakpoints should be interpolated linearly between the lower bound and upper bound that they fall between.
- The number of breakpoints, their values, or storage might change.
- The term can be either 12 or 24 (number of months), you can also assume values will always be within this set.
- The fee should be rounded up such that fee + loan amount is an exact multiple of 5.
- The minimum amount for a loan is £1,000, and the maximum is £20,000.
- You can assume values will always be within this range but they may be any value up to 2 decimal places.

---

# Project Setup and Usage

---

## Running the Project

1. Copy and configure the `.env` file with your MySQL settings:
   ```bash
   cp .env.example .env
   ```
   
2. Build and Start the Docker Container:
   ```bash
   docker-compose up --build
   ```

3. Connect to the Container Shell:
   ```bash
   docker exec -it <container-name> sh
   ```

4. Install Dependencies:
   ```bash
   composer install
   ```

5. Run Database Migrations:
   ```bash
   vendor/bin/phinx migrate -c phinx.php
   ```

6. Seed the Database:
   ```bash
   vendor/bin/phinx seed:run -c phinx.php
   ```
   
7. Run the Symfony Console Command:
   ```bash
   php /app/app.php app:calculate-fee <term> <amount>
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

## Code Quality Tools

This project uses **PHPStan** for static analysis and **PHP_CodeSniffer** for coding standards enforcement.

### PHPStan (Static Analysis)
PHPStan is used to perform static analysis on the codebase.

- **Run PHPStan**:
  ```bash
  vendor/bin/phpstan analyse -c phpstan.neon

- **Run PHP_CodeSniffer: Check for coding standard violations**:
  ```bash
  vendor/bin/phpcs

- **Run PHP_Code Beautifier: Automatically fix issues**:
  ```bash
  vendor/bin/phpcbf
  
## Project Structure

### **Key Directories:**
- `/src`: Contains the main application code.
    - `/Loan`: Includes domain logic, services, and repositories.
    - `/Common`: Shared interfaces and utilities.
- `/tests`: Contains all test cases.
    - `/Unit`: Unit tests.
    - `/Integration`: Integration tests.
- `/db`: Contains database migrations and seeders.
    - `/migrations`: Database migration files.
    - `/seeds`:  Seeder files for populating the database.
- `/vendor`: Contains installed dependencies (ignored by Git).

---

## Notes
- Ensure that `composer install` is run inside the container or locally to install dependencies.
- Run migrations and seeders after setting up the project to populate the database.
- Ensure that `app.php` is executable and properly configured to execute the Symfony Console commands.

