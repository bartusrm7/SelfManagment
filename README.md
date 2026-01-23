# SelfManagment

### SelfManagment is a web application designed to help users manage daily tasks, meetings, notes, and expenses. It allows users to create, edit, complete, and delete tasks while tracking their progress over time.

The project includes user registration and login, along with essential features for organizing personal activities and monitoring productivity.

## Features:
*Task Management  
Create, edit, mark as completed, and delete tasks.

*Progress Tracking  
Visualize task activity and completion trends.

*User Authentication  
Secure registration and login system.

*Responsive Design  
Fully optimized for desktop and mobile devices.

## Screens:
### Sign In/Up
#### 1. Welcome page
Intro screen shown before login.
<img src="assets/images/Zrzut ekranu 2026-01-23 141548.png" width="100%" /> 

#### 2. Register
Sign-up form for new users.
<img src="assets/images/Zrzut ekranu 2026-01-23 141607.png" width="100%" /> 

#### 3. Login
Secure login interface.
<img src="assets/images/Zrzut ekranu 2026-01-23 134557.png" width="100%" /> 


### Dashboard
#### 4. Dashboard main page
Main overview with charts, task list, and expense summary.
<img src="assets/images/Zrzut ekranu 2026-01-23 134912.png" width="100%" /> 

### Personal
#### 5. Daily Tasks
Manage personal tasks with full CRUD functionality.
<img src="assets/images/Zrzut ekranu 2026-01-23 134710.png" width="100%" /> 

#### 6. Meetings
View and manage your scheduled meetings, including upcoming and past events.
<img src="assets/images/Zrzut ekranu 2026-01-23 134805.png" width="100%" /> 

#### 7. Notes
Store and review personal notes. Users can toggle visibility of their latest notes and reminders.
<img src="assets/images/Zrzut ekranu 2026-01-23 134817.png" width="100%" /> 

### Finances
#### 8. Expenses
Track and manage your expenses with monthly summaries, detailed entries, and customizable budget settings.
<img src="assets/images/Zrzut ekranu 2026-01-23 134852.png" width="100%" /> 


## Technologies:
* Symfony
* Twig
* JavaScript
* MySQL
* SCSS
* Bootstrap
* CoreUI
  
## Installation:
To run the project locally follow for these steps:


1
```bash
git clone git@github.com:bartusrm7/SelfManagment.git
```

2
```bash
cd SelfManagment
```

3
```bash
composer install
```

4
```bash
npm install
npm run build
```

5
Then in .env set your own database.

6
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

7
```bash
symfony server:start
```
