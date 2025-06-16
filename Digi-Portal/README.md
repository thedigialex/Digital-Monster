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
```

### Docker Notes

To create a docker image to run

-   `Update ENV File`: Use your production level .env
-   `Disconnect storage`: rm public/storage
-   `Build NPM`: npm run build
-   `Build Image`: docker build -t digi-portal .
-   `Save Image`: docker save -o digi-portal.tar digi-portal:latest

Update to production location

-   `Load Image`: docker load -i digi-portal.tar
-   `Stop Current`: docker stop staging-digi-portal
-   `Remove Current`: docker rm staging-digi-portal
-   `Run Image`: docker run -d --name staging-digi-portal -p 8004:80 --network bridge --restart unless-stopped -v digi-uploads:/var/www/html/public/uploads digi-portal:latest


Setup 
//Docker
docker network create mariadb-network
docker run -d --name mariadb --network mariadb-network -e MARIADB_ROOT_PASSWORD=password -p 3306:3306 mariadb
docker run -d --name phpmyadmin --network mariadb-network -e PMA_HOST=mariadb -p 8080:80 phpmyadmin

//Project
php artisan mirgrate
php artisan db:seed

//Login
test@example.com	
ChangeMe1

//Local
rm public/storage
npm run build
docker build -t digi-portal .
docker save -o digi-portal.tar digi-portal:latest

//Server
docker load -i digi-portal.tar
docker stop staging-digi-portal
docker rm staging-digi-portal
docker run -d --name staging-digi-portal -p 8004:80 --network bridge --restart unless-stopped -v digi-uploads:/var/www/html/storage/app/public digi-portal:latest
