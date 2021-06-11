# Shared Service Layer - Search
This is an open source project to create a service layer to enable charities to share a common search interface whilst being agnostic to the backend search provider. You can find out more about the reasoning behind this project on the [Catalyst website](https://www.thecatalyst.org.uk/blog/some-of-the-most-common-digital-problems-faced-by-charities-and-what-were-doing-about-them).

You can get an overview of the project's intent by reading the [repository specification](docs/project/specification.md).

## Navigation
* [Requirements](#requirements)
* [Quick start](#quick-start)
* Development
  * [Configuration](docs/development/configuration/index.md)
  * [API documentation](docs/api/index.md)
* [Contributing](docs/project/contributing.md)
* [License](#license)
* [Further Reading](#further-reading)

## Requirements
Developing with this project requires that __Docker__ is installed on your system. You can download and install the appropriate version of the software for your operating system on [Docker's website](https://www.docker.com/get-started).

It's possible to run this application without using Docker, but that approach is not currently officially supported by this project. However, you can use the [docker-compose.yml](docker-compose.yml), [docker-compose.production.yml](docker-compose.production.yml) and [associated configuration](docker) files as a reference in cases where you wish to build your own environment for the application.

## Quick Start
This will get a version of the application up and running with the least possible effort. A deep dive into adjusting the set-up and configuration can be found further into the documentation.

The following assumes you'll be working on the command line.

### Clone the repository
Clone the project onto your system:
```
$ git clone https://github.com/reasondigital/Shared-Service-Layer-Search.git
```

Unless you intend on [contributing to the project](docs/project/contributing.md), you will want to remove this repository's version control history and start afresh. You can do so by moving into the cloned project's directory and removing the `.git` folder from the root:
```
$ cd Shared-Service-Layer-Search && rm -rf .git
```

You can then run `git init` to start a new repository with the cloned files.

### Set up `.env`
Create your local version of the environment configuration file:
```
$ cp .env.example .env
```

This file informs how the application runs, including what services it utilises. Sensible defaults have been set for working with this project.

For this quick start, the only environment variables you may need to adjust are the port forwarding variables. These tell Docker which of your system's ports (e.g. 80, 443) map to the corresponding ports within the Docker network. If, for example, you already have a database running on your system on port 3306, Docker won't be able to map that port to the database container's port within its network. You can get around this by setting a different forwarding port in your `.env` file:
```dotenv
FORWARD_DB_PORT=3307
```

Here's the list of ports that can be forwarded by default in this project, as well as their default values:

| Env variable                    | Default value        |
| ------------------------------- | -------------------- |
| `FORWARD_DB_PORT`               | 3306                 |
| `FORWARD_WEBSERVER_HTTP_PORT`   | 80                   |
| `FORWARD_WEBSERVER_HTTPS_PORT`* | 443                  |
| `FORWARD_ELASTIC_PORT`          | 9200                 |
| `FORWARD_KIBANA_PORT`           | 5601                 |

*This is currently only applicable in the production Docker configuration. You can, though, add it to the development configuration file if you prefer.

### Boot Docker
You can now start up the Docker environment to get the application running.

Make sure that the Docker service is running on your system and execute this command from the root of the project:
```
$ docker-compose up
```

Docker will begin downloading and building the various services that the application needs to run. This may take a while depending on your internet connection and system. (Future `docker-compose up` calls will take only a fraction of the time after this first build has been completed.)

Once the output of server activity stops, you can move on to the next step.

### Set up the application
Once Docker has finishing building the containers, you can install the PHP packages/dependencies required by the application via Composer.

First, log into the webserver container from the project root (you may need to open a new command line window):
```
$ docker exec -it -u www-data ssls-webserver bash
```
\
Once in, run Composer's `install` command:
```
$ composer install
```
\
Laravel, the framework that the application is based on, will require you to then generate an application key:
```
$ php artisan key:generate
```
\
Generate the IDE helper file for Laravel macros:
```
$ php artisan ide-helper:macros
```

### Confirm your set-up
Visit [http://localhost](http://localhost) in your browser. You should see the OpenAPI JSON schema. (Your browser may not "beautify" the JSON, but everything is fine if the JSON data is displaying on the page at all.)

Visit [http://localhost:5601](http://localhost:5601) in your browser to see if the Elasticsearch service is ready. If you see the "Kibana server is not ready yet" message, you may need to wait a few more minutes before checking again.

### Set up the Search Index
While logged in to the webserver container, run the following to set up the search indices:
```
$ php artisan migrate && php artisan elastic:migrate
```

Visit [http://localhost:5601/app/management/data/index_management/indices](http://localhost:5601/app/management/data/index_management/indices), the index management page in Kibana, in your browser to see if the indices have been created.

You can then seed the database with a small set of faker-generated test data from the command line:
```
$ php artisan db:seed
```

The value of the "Docs count" column on the index management should increase.

NOTE: If you need to re-create the index via migration you can run the following
```
$ php artisan tinker
>>> use ElasticMigrations\Facades\Index;
>>> Index::drop('articles');
```

Then exit out and run
```
$ php artisan elastic:migrate
```

### Connect to the database
You can connect to the database from your local machine with the following details:

| Credential   |  Value                           |
| ------------ | -------------------------------- |
| Host         | host.docker.internal             |
| Port         | The value of `FORWARD_DB_PORT`   |
| Database     | ssl_search                       |
| Username     | root                             |
| Password     | password                         |

### Shut down the services
You'll want to properly shut down the Docker services once you're done working with your application. You can do so by switching to the window where you executed `docker-compose up` and typing `Ctrl + C` (or the equivalent for your operating system). Then execute the following command to fully stop and remove the containers:
```
$ docker-compose down
```

## License
This project is licensed under the MIT License. View the [LICENSE](LICENSE) file for details.

## Further Reading
* [Installing and using Docker](https://www.docker.com/get-started)
* [Open API 3](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.1.0.md)
