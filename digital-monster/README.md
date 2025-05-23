# Digital Monster Raising and Battle Application

Welcome to the Digital Monster Raising and Battle application! This project allows users to raise digital monsters, train them, and battle with them. It features user role management and dynamic equipment systems.

## Features

-   **User Roles**: Admin and User
-   **Monster Training**: Users can raise their monsters and train them.
-   **Battle Mechanics**: Monsters can be pitted against each other for battles.
-   **Equipment System**: Equipments are assigned to monsters, and users can upgrade them.

## Getting Started

Follow these steps to get the application running on your local development server:

1.  **Install Dependencies**

    Ensure you have all the necessary dependencies by running the following commands:

    ```bash
    composer install
    npm install
    ```

2.  **Set up the Environment**

    Copy the `.env.example` to a `.env` file in the root directory:

    ```bash
    cp .env.example .env
    ```

3.  **Generate Application Key**

    Run the following command to generate your application key:

    ```bash
    php artisan key:generate
    ```

4.  **Run Migrations**

    Create the required database tables:

    ```bash
    php artisan migrate
    ```

5.  **Seed the Database**

    Seed the database with initial data, including a test user and equipment data:

    ```bash
    php artisan db:seed
    ```

    This will create a new admin user with the email `test@example.com`. You can modify the password in the `DatabaseSeeder` file.

## Available User Roles

-   **Admin**: The admin has full access to the system and can manage users, monsters, and equipment.
-   **User**: Regular users can raise and battle monsters.

## Authentication & Routes

Role-based authentication is implemented for admin and user roles.

The routes and views will be accessible according to the user role.

-   Admins have access to manage equipment, users, and other admin functionalities.
-   Users can manage their own monsters, battle, and upgrade them.

## Usage Example

### Creating Equipment

You can create various types of equipment, such as:

-   `DigiGarden`: A digital garden equipment.
-   `DigiGate`: A gate equipment.
-   `Stat Equipment`: Equipment that affects stats like Strength, Agility, Defense, etc.

### User Registration Event

When a user is created, an event `UserRegistered` is triggered, and associated user equipment is set up automatically.

### Frontend Setup

Once the backend is set up, you can compile frontend assets by running:

```bash
npm run dev