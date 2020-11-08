# Todo API

- [Todo API](#todo-api)
  - [Requirements](#requirements)
  - [Setup](#setup)
    - [API](#api)
    - [.env file](#env-file)
    - [Playground](#playground)
  - [Endpoints](#endpoints)
    - [Get all tasks - `GET /task`](#get-all-tasks---get-task)
    - [Add task - `POST /task`](#add-task---post-task)
    - [Get task by id - `GET /task/:id`](#get-task-by-id---get-taskid)
    - [Edit task by id - `PATCH /task/:id`](#edit-task-by-id---patch-taskid)
    - [Delete task by id - `DELETE /task/:id`](#delete-task-by-id---delete-taskid)
  - [Model](#model)
    - [Task](#task)

## Requirements
* PHP 7
* MariaDB/MySQL
* docker and docker-compose

## Setup

### API
Run docker container
```sh
docker-compose up --build
```

Access container and install dependencies
```sh
# get container id
docker ps

# access container
docker exec -it <container-id> /bin/bash

# in container
composer install
```

### .env file
Create a .env file in the root of the project with your database details. Use `.env.example` as template.

The application will be accessible at http://localhost:4001

### Playground

You can test endpoints with the api playground at: http://localhost:

## Endpoints

### Get all tasks `GET /task`
**Response**
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```

### Add task - `POST /task`

**Request**
```json
{
  "description": "task description",
  "starred": "highlighted task",
  "due": "due date"
}
```

**Response**
```json
{
  "success": "Boolean of response success",
  "message": "Response message"
}
```

### Get task by id - `GET /task/:id`

**Response**
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```

### Edit task by id - `PATCH /task/:id`

**Response**
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```

### Delete task by id - `DELETE /task/:id`

**Response**
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```

---

## Model

### Task
**Schema**
```json
{
  "id": "unique identifier",
  "description": "task description",
  "completed": "date completed. **NULL** if not completed",
  "starred": "Int. highlighted tasks",
  "created": "date created",
  "modified": "date edited",
  "due": "due date"
}
  ```
