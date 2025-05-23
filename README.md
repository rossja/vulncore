# Vulncore

This project provides simple examples of common application security vulnerabilities.
The content is intended to be used as standalone demonstrations of security problems that engineers will often encounter.

## Project Layout

The project has the following layout: 

.
├── demos
│   ├── genai
│   │   ├── agentic
│   │   └── promptinjection
│   └── web
│       ├── authn
│       ├── authz
│       ├── sqli
│       └── xss
└── README.md

* The demos directory holds all the vulnerable code
* demos/genai showcases generative AI application security vulnerabilities
* demos/web showcases web application security vulnerabilities

## Tech Stack

1. Each of the demos is a discrete standalone component. 
2. Docker is used to containerize the applications and ensure isolation from the host system.
3. Docker compose is used to simplify running the containers
4. Environment variables are used from .env files, passed in through docker compose environment declarations
5. Data is not persisted across container runtime, every time the container is run it starts up "clean"
6. The code may be written in any of PHP, Python, Javascript, Typescript, Ruby, or Golang. 
7. Databases may include SQLite, MySQL/Mariadb, Postgresql, MongoDB, or Redis.