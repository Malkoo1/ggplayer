# Tutor Ai Reseller Dashboard

A Laravel application for managing resellers and app licenses.

## Features

-   Admin dashboard for managing resellers
-   Reseller dashboard for managing app licenses
-   Credit-based subscription system
-   App assignment functionality

## Installation

1. Clone the repository
2. Install dependencies:
    ```
    composer install
    ```
3. Copy the `.env.example` file to `.env` and configure your database
4. Generate application key:
    ```
    php artisan key:generate
    ```
5. Run migrations:
    ```
    php artisan migrate
    ```
6. Seed the database with admin and reseller users:
    ```
    php artisan db:seed-custom
    ```

## Default Users

### Admin Users

-   Email: admin@tutorai.com
-   Password: password123
-   Role: admin
-   Credit: 100

-   Email: superadmin@tutorai.com
-   Password: password123
-   Role: admin
-   Credit: 100

### Reseller Users

-   Email: reseller1@tutorai.com
-   Password: password123
-   Role: reseller
-   Credit: 50

-   Email: reseller2@tutorai.com
-   Password: password123
-   Role: reseller
-   Credit: 75

-   Email: reseller3@tutorai.com
-   Password: password123
-   Role: reseller
-   Credit: 100

## App Records

The system uses app records to manage app licenses. Each app record contains:

-   App ID
-   Operating System (Android or iOS)
-   Status (Enable or Disable)
-   Assign URL
-   Reseller ID (the user who owns the license)
-   Expiry Date

## Subscription Plans

-   1 Month: 1 Credit
-   3 Months: 2 Credits
-   6 Months: 3 Credits
-   12 Months: 5 Credits

## License

This project is licensed under the MIT License.

 <!-- <ul class="chat-msg-more">
                                        <li class="d-none d-sm-block">
                                            <a href="#" class="btn btn-icon btn-sm btn-trigger">
                                                <em class="icon ni ni-reply-fill"></em>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-icon btn-sm btn-trigger dropdown-toggle" data-bs-toggle="dropdown">
                                                    <em class="icon ni ni-more-h"></em>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li class="d-sm-none">
                                                            <a href="#">
                                                                <em class="icon ni ni-reply-fill"></em>
                                                                Reply
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" onclick="editMessage(${message.id})">
                                                                <em class="icon ni ni-pen-alt-fill"></em>
                                                                Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" onclick="deleteMessage(${message.id})">
                                                                <em class="icon ni ni-trash-fill"></em>
                                                                Remove
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul> -->
