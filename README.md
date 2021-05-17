# Food Ordering System API

## Requirements
- php version >= 7.3
- composer version >= 2.0.0
- mysql version 5.7 or 8

## Installation
1. `composer install`
1. copy .env.example to .env and set it based on your system
1. `php artisan key:generate`
1. `php artisan migrate:fresh --seed`
1. `php artisan serve`

## Default User
- email: admin@local | password: admin123 | role: admin
- email: cashier@local | password: cashier123 | role: cashier
- email: waiter@local | password: waiter123 | role: waiter

## API doc
Todo
