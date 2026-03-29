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

1. Password storage - updated password storage to use bcrypt hashing in the database. Before this, unauthorized access to the database would compromit every account, as an attacker could directly connect to any account. Bcrypt, a slow hashing algorithm with salting incorporated in itself, prevents bruteforcing or rainbow table attacks.

2. Password strength - require a password of minimum 10 characters, without any extra requests. This prevents brute forcing attacks. No extra types of characters are required as this is proven to not improve password security.

3. Password reset mechanism - implemented a reset_token database - for each password reset request, a token is generated and its hash is saved in the database. The token is sent through the reset e-mail, and only using this token, which has a lifetime of 1 hour, the password can be reset. This prevents guessing the password reset link.

4. Unauthorized operations - previously, although links to special authority operations were hidden, they could still be accessed. This includes user edit, user list, ticket edit, ticket view. Also, users could see any ticket, even if they were not theirs.

5. Bruteforcing login - no limit was imposed on attempting to login to an account. A limit was imposed based on the audit log, so an IP can attempt at most 5 failed logins per 10 minutes.

6. Account enumeration with register - Registration would inform the user when registration failed because an e-mail was already in used, which allowed account enumeration. Account verification through e-mail was implemented, so that on registration, the user is told to verify their e-mail for next steps. If the account is new, they receive through e-mail the verification link (or if the account is created but not verified yet). If the account already exist, the e-mail informs this and sends the link to fill the "forgot password" form.

# Update log

_27.03.2026_ - Created database tables. Created user system object and authentication pages (Login, Register, Forgotten Password)

_28.03.2026_ - Created ticket creation and view

_29.03.2026_ - Added Analyst update ticket, user self edit and admin user edit. Created the "fixed" branch and updated the app to be secure.
