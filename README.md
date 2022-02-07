# REDCap Minimisation (Prototype)

## About 

REDCap Minimisation (prototype) is a web application that can be used to randomise participant using minimisation method in clinical trial that using REDCap database.

This application is still in development and not ready to be used.

## How to install
1. Clone this project repo locally
2. cd into your project folder
3. Install Composer Dependencies
```markdown
   composer install
```
4. Install NPM Dependencies
```markdown
   npm install
```
5. Create a copy of your .env file
```markdown
   cp .env.example .env
```
6. Generate an app encryption key
```markdown
   php artisan key:generate
```
7. Create an empty database
8. Put the database information to .env file
9. Migrate the database
```markdown
   php artisan migrate
```
