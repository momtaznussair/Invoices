# Invoices Management system
This is a system for managing and collecting invoices

## Demo 
 - You can test the Invoices system at  https://invoices-system.herokuapp.com/home
 - Use admin@momtaz.com as email
 - And momtaznussair as password

![alt text](https://github.com/momtaznussair/Invoices/blob/main/shots/login.png?raw=true)

## Table of contents
* [Key features](#Key-features)
* [Technologies](#technologies)
* [Setup](#setup)

## Key features
- Add and manage customers and products.

- Add invoices and automatically calculate VAT VALUE and total commission.
![add](https://github.com/momtaznussair/Invoices/blob/main/shots/add.png?raw=true "add invoice")

- Manage , Archive or print invoices.
![operations](https://github.com/momtaznussair/Invoices/blob/main/shots/operations.png?raw=true "operations")

- Search invoices and generate reports.
![reports](https://github.com/momtaznussair/Invoices/blob/main/shots/reports.png?raw=true "reports")

- Create users , asign custom roles and permissions to them , and suspend or activate users.
- 
![users](https://github.com/momtaznussair/Invoices/blob/main/shots/users.png?raw=true "users")

![permissions](https://github.com/momtaznussair/Invoices/blob/main/shots/permissions.png?raw=true "permissions")

- Statistics about invoices and customers.
![main](https://github.com/momtaznussair/Invoices/blob/main/shots/main.png?raw=true "main")

- Mail and database notifications.
![mail](https://github.com/momtaznussair/Invoices/blob/main/shots/mail.png?raw=true "mail")

![notification](https://github.com/momtaznussair/Invoices/blob/main/shots/notification.png?raw=true "notification")

	
## Technologies
Project is created with:
* Laravel: 8
* Bootstrap: 5
* Javascript
	
## Setup
- Clone project

- Go to the folder application using cd command on your cmd or terminal
- Run ``` $ composer install ``` on your cmd or terminal
- Copy .env.example file to .env on the root folder. You can type ``` $ copy .env.example .env ``` if using command prompt Windows or``` $ cp .env.example .env ``` if using terminal, Ubuntu
- Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration.
- By default, the username is root and you can leave the password field empty. (This is for Xampp)
- By default, the username is root and password is also root. (This is for Lamp)
```
Run $ php artisan key:generate
Run $ php artisan migrate
Run $ php artisan serve

```
Go to localhost:8000
  


