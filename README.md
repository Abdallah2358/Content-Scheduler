# Content Scheduler
A Laravel-based tool to help you plan, organize, and schedule your content across multiple platforms.

## Features

- **Post Scheduling:** Schedule posts for future dates and times.
- **Status Tracking:** Track content status (draft, scheduled, published).
- **Multi-Platform Support:** Schedule content for platforms like Twitter, Facebook, LinkedIn, and more.
- **User Management:** Support for multiple users and roles (admin, editor, viewer).


## Getting Started
1. **Prerequisites**
   1. PHP +8.4
   2. Composer
   3. Node +18
   4. MySql 

2. **Clone the repository:**
    ```bash
    git clone https://github.com/yourusername/Content-Scheduler.git
    ```

3. **Install dependencies:**
    ```bash
    cd Content-Scheduler
    composer install
    npm install
    ```

4. **Set up environment variables:**
    - Copy `.env.example` to `.env` and update the values as needed (database, mail, API keys, etc.).

5. **Generate application key:**
    ```bash
    php artisan key:generate
    ```

6. **Run migrations and seeders:**
    ```bash
    php artisan migrate --seed
    ```

7. **Start the development server:**
    ```bash
    composer run dev
    ```
    The app will be available at `http://localhost:8000`.

## Usage

- **Add Content:** Use the dashboard to create new posts. Fill in the title, description, platform, and schedule date.
- **Manage Calendar:** View scheduled content in the calendar interface. Filter by platform or status.