# Project Setup Guide

Follow the steps below to set up and run the project.

## Prerequisites

Ensure you have the following installed before proceeding (or use the provided Docker image to simplify setup):

- **PHP** > 8
- **Composer** 
- **NPM** 
- **MySQL** (or your preferred database)

---

## Installation Steps

1. **Clone the Repository**  
   Run the following command in your terminal to clone the project:

   ```bash
   git clone https://github.com/totop275/OrderGate.git
   cd OrderGate
   ```

2. **Set Up Environment Variables**  
   Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

   - Update `.env` with your environment-specific configurations (e.g., database credentials, app URL).
   - If you're using Docker, run `docker compose build` followed by `docker compose up -d`. The app should start as expected. If not, proceed to the next step.

3. **Install Composer Dependencies**  
   Run the following command to install all backend dependencies:

   ```bash
   composer install
   ```

4. **Database Setup**  
   - Run migrations to create the database schema:

     ```bash
     php artisan migrate
     ```

   - Seed the database with initial data:

     ```bash
     php artisan db:seed
     ```

   - Seed the demo data (optional):

     ```bash
     php artisan db:seed --class=DemoDataSeeder
     ```

## Accessing the Application

- **Default Admin Credentials:**  
  - **Email:** admin@demo.com  
  - **Password:** demo123

- **Default Staff Credentials:**  
  - **Email:** staff@demo.com  
  - **Password:** demo123

---

## Additional Commands

- **Clear Cache:**  
  Run the following to clear caches (config, routes, views, etc.):

  ```bash
  php artisan optimize:clear
  ```

- **Serve the Application Locally (if not using a web server):**  
  ```bash
  php artisan serve
  ```
## Frontend API
  The frontend API documentation can be viewed at: [https://documenter.getpostman.com/view/9714415/2sB2qgeJFq](https://documenter.getpostman.com/view/9714415/2sB2qgeJFq)

## Demo
  You can access a live demo of the app without installation at: [https://ordergate.zsite.web.id](https://ordergate.zsite.web.id)

## Other Information
  - Due to time constraints, the project may not be fully optimized for mobile or small screen devices.
  - Make sure to set the `APP_URL` environment variable to match your domain name