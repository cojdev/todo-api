# Todo API

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
Create a .env file in the root of the project with your database details. These can be found in `dockercompose.yml`
```sh
# Database credentials
DB_HOST=your_database_host
DB_NAME=your_database_name
DB_USER=your_database_username
DB_PASS=your_database_password
```

The application will be accessible at http://localhost:4001

### Playground

You can test endpoints with the api playground at: http://localhost:

## Endpoints

### Get all tasks `GET /task`
<details>
<summary>Response</summary>
<p>
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```
</p>
</details>

### Add task - `POST /task`
<details>
<summary>Request</summary>
<p>
```json
{
  "description": "task description",
  "starred": "highlighted task",
  "due": "due date"
}
```
</p>
</details>

<details>
<summary>Response</summary>
<p>
```json
{
  "success": "Boolean of response success",
  "message": "Response message"
}
```
</p>
</details>


### Get task by id - `GET /task/:id`
<details>
<summary>Response</summary>
<p>
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```
</p>
</details>

### Edit task by id - `PATCH /task/:id`
<details>
<summary>Response</summary>
<p>
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```
</p>
</details>

### Delete task by id - `DELETE /task/:id`
<details>
<summary>Response</summary>
<p>
```json
{
  "success": "Boolean of response success",
  "data": "Returned data",
  "code": "HTTP response code",
  "message": "Response message"
}
```
</p>
</details>

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
