# API Documentation

## Global Schemas

For this project we will be using schemas from [schema.org](https://schema.org):

* [Place](https://schema.org/Place)
* [Article](https://schema.org/Article)

## API Documentation

The main API documentation is made using [OpenAPI 3.0.0](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.1.0.md) ( [Swagger](https://swagger.io/resources/open-api/) ).

## Installation

To view the API you will need:

* Docker

## Configuration

Start by creating a docker container with the name `swagger` using the code below in the root of this project:

```sh
docker pull swaggerapi/swagger-ui
docker run -p 8080:8080 -e SWAGGER_JSON=/api/api.json -v ${PWD}:/api --name swagger --detach swaggerapi/swagger-ui
```

To view the Swagger UI open a web browser and go to `http://localhost:8080`.

If this port is already in use on your machine, modify the first argument to the Docker command:

```
-p 8090:8080 # Port 8090 on the local machine
```

From now on, you can start / stop / restart the container using this code:

```sh
docker start swagger
docker stop swagger
docker restart swagger
```

To get a list of the running Docker containers:

```sh
docker ps
```

To remove the container from your machine:

```sh
docker rm swagger
```