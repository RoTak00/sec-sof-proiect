# Authentication Security

This repository represents a demonstration of authentication security in a PHP website. The project is part of the Developing Secure Software Applications course of the Faculty of Mathematics and Informatics, University of Bucharest, by prof. Marius-Iulian Mihăilescu.

## Installation

```bash
# create config
cp config.php.example config.php

# run sass parsing
sass -w ./

# start containers
docker compose up --build
```

Access the website at `localhost:8080`, and the mailpit app at `localhost:8025`

# Structure

The application is built in a custom PHP MVC, developed by [the author](https://roberttakacs.ro) based on the OpenCart 2 structure. Several parts of the MVC have been removed to fit the theme of the project.

# Fixes on the "fixed" branch

1. Password storage - updated password storage to use bcrypt hashing in the database.

# Update log

_27.03.2026_ - Created database tables. Created user system object and authentication pages (Login, Register, Forgotten Password)

_28.03.2026_ - Created ticket creation and view

_29.03.2026_ - Added Analyst update ticket, user self edit and admin user edit
