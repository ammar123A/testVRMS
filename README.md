# VRMS.my

This is a modern full-stack web sytem powered by **Laravel**, **Blade**, and **Livewire**. It also integrates UI libraries like **Bootstrap** to enhance the user experience and simplify frontend development.

## Prerequisites

Before setting up the project, ensure you have the following tools installed on your machine:

-   **PHP 8.4+**
-   **Composer**: PHP dependency manager
-   **Node.js 16+**: JavaScript runtime for building assets
-   **NPM**: Package manager for Node.js dependencies
-   **Oracle Database**: Relational database
-   **Postgres**: Temporary database

Additionally, this application uses the following libraries:

-   **Bootstrap**: https://getbootstrap.com/

## Setup / Installation

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/ammar123A/testVRMS
cd VRMS_Upgrade
```

### 2. Install Backend Dependencies (Laravel)

Install the PHP dependencies using Composer:

```bash
composer install
```

### 3. Install Frontend Dependencies (blade.php)

Navigate to the frontend directory and install the JavaScript dependencies:

```bash
npm install
# or
yarn install
```

### 4. Set Up Environment Variables

Copy the `.env.example` file to create a `.env` file:

```bash
cp .env.example .env
```

Configure your `.env` file with the necessary database credentials and other environment settings.

### 5. Generate Application Key

Generate the Laravel application key:

```bash
php artisan key:generate
```

### 6. Run Migrations

Run the database migrations to set up the schema:

```bash
php artisan migrate
```

### 7. Start Development Server

Start the Laravel development server:

```bash
php artisan serve
```

For the blade.php frontend, you can compile assets using:

```bash
npm run dev
```

Now, your app should be running on `http://localhost:8000` (or whichever port you configured).

## Coding Standards

We adhere to a set of coding standards and practices to ensure the consistency and quality of the codebase:

### 1. **JavaScript, HTML / Livewire Coding Standards**

-   **Naming Conventions**: Use **camelCase** for variable and function names.
-   **Component Names**: Use **PascalCase** for blade component names (e.g., `main.blade.php`).
-   **Single File Components**: Always use `.blade.php` extension for components.
-   **Props Naming**: Use **camelCase** for props, but when passing props to custom components, use **kebab-case** (e.g., `<my-component :my-prop="'{{ $title }}'" :another-value="{{ $count }}" />`).

### 2. **PHP / Laravel Coding Standards**

-   **Naming Conventions**: Use **camelCase** for variable names, **PascalCase** for class names.
-   **Controller Methods**: Methods should be **snake_case** and follow the RESTful convention (e.g., `getUser`, `createPost`).
-   **Model Names**: Always use singular, **PascalCase** names (e.g., `User`, `Post`).
-   **Routes**: Use **snake_case** for route names and route variables (e.g., `user_posts`).
-   **Database Column Names**: Use **snake_case** for database column names.
-   **Indentation**: Use **4 spaces** for indentation, not tabs.
-   **Lines Length**: Lines of code should not exceed **120 characters**.

### 3. **Code Formatting**

-   Use **Prettier** for JavaScript and Livewire formatting.
    -   Install Prettier plugin for your code editor.
    -   Run `npx prettier --write .` to format your code automatically.
-   Use **PHPIntelephense** for PHP code formatting.
