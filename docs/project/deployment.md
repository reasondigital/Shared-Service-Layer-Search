# Deployment

* [Heroku](#heroku)
* [Other Environments](#other-environments)


## Heroku
Deployment via Heroku takes advantage of the service's support for using Docker containers. This means that (assuming you develop the project using Docker) the deployed environment is faithful to the development environment, thus reducing the likelihood of unexpected discrepancies in your staging and production environments.

### Steps
1. [Set up Heroku](#set-up-heroku)
1. [Push the Docker Container to the Container Registry](#push-the-docker-container-to-the-container-registry)
1. [Deploy the code to Heroku](#deploy-the-code-to-heroku)
1. [Set up Initial Environment Variables](#set-up-initial-environment-variables)
1. [Install the Database](#install-the-database)
1. [Set up the Database](#set-up-the-database)
1. [Generate API Tokens](#generate-api-tokens)
1. [Set up Elastisearch](#set-up-elastisearch)
1. [Begin using the API](#begin-using-the-api)

### Set up Heroku
* [Register with Heroku](https://id.heroku.com/signup/login) if you haven't already.
* Download and install the [Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli) on your system.
* [Create a new Heroku app](https://dashboard.heroku.com/new-app).

NOTE: Heroku will ask you to login via Heroku CLI. The command they give you, `heroku login`, will try and open a browser window. If you don't want this, run `heroku login -i` and it will prompt for login details in the console instead.

### Push the Docker Container to the Container Registry
Head to the dashboard for your Heroku app. This can usually be found at https://dashboard.heroku.com/apps/{your-app-name}. Click on the "Deploy" tab and, by the "Deployment method" section, select "Container Registry". (You can also get there by visiting https://dashboard.heroku.com/apps/{your-app-name}/deploy/heroku-container directly.)

For Heroku CLI to be able to push the container, your command line's working directory must contain a valid `Dockerfile` file. Be sure to navigate to the [docker/webserver](/docker/webserver) directory of the project in your command line interface.

Follow the instructions on the Heroku _Container Registry_ page to push the Docker container to the registry.

### Deploy the code to Heroku
From your app's dashboard, head to "Deploy" and select "Heroku Git" by the "Deployment method" section.

Follow the instructions in this section to push your code up to Heroku.

### Set up Initial Environment Variables
Heroku doesn't support sending up non-committed files to your app environment, so it isn't possible to upload a `.env` file. Instead, you can add the environment variables via the "Config Vars" section under your app's "Settings" tab.

The actual variables you'll need to add will vary based on your project set-up, but this list should cover typical cases:
```
APP_NAME
APP_ENV
APP_KEY
APP_DEBUG
APP_URL
RESULTS_COUNT_LOCATIONS
ADDRESS_SEARCH_RADIUS
LOG_LEVEL
SCOUT_PREFIX
SCOUT_DRIVER
GEOCODING_PROVIDER
GEOCODING_API_URL
GEOCODING_API_KEY
```

For `APP_KEY`, you may need to generate one using your local set-up.

At this point, you should be able to access the root domain (https://{your-app-name}.herokuapp.com) and see the contents of the [openapi.json](/docs/openapi.json) file, though this does not apply if `APP_ENV` is set to "production".

### Install the Database
From your app's dashboard, click the "Resources" tab. Under the "Add-ons" section, search for "JawsDB Maria" and select the result. Choose the appropriate plan for your circumstances and click the submit button.

Soon after you've installed the add-on, it should be available in your app's dashboard under the same "Add-ons" section where we installed it from. You'll now need to retrieve your database credentials and configure your app with them.

Click on the "JawsDB Maria" add-on link, it will log you into the database's dashboard where you can retrieve the connection credentials. Keep this tab open or make a note of the connection info properties and values.

Head back to the "Settings" tab of your Heroku app, scroll down to the "Config Vars" section and reveal the config variables. Add the following configuration variables to the config vars, replacing bracketed values with their actual values from the JawsDB dashboard. This will connect your deployed application to the database.

| Variable        | Value           |
| --------------- | --------------- |
| `DB_CONNECTION` | 'mysql'         |
| `DB_HOST`       | {Host}          |
| `DB_PORT`       | {Port}          |
| `DB_DATABASE`   | {Database}      |
| `DB_USERNAME`   | {Username}      |
| `DB_PASSWORD`   | {Password}      |

### Set up the Database
From your app's dashboard, open the "More" option in the top right of the page and select "Run Console". Enter the following command in the bash field and hit the "Run" button:
```
$ php artisan migrate
```

This command creates the database tables configured in the application.

### Generate API Tokens
You'll need to generate API tokens before you can consume the API. The "ephemeral" nature of Heroku's cloud services means that we're not able to upload files like you would on a static webserver. This means we can't upload a `config/api-tokens.php` config file. Instead, we'll use the command line to generate an API token.

From your app's dashboard, open the "More" option in the top right of the page and select "Run Console". Run the `php artisan token:create` command in the bash field, making sure to provide the appropriate arguments. Details on how this command works can be found in the project's [API docs](/docs/api/index.md#manage-tokens-by-command-line).

### Set up Elastisearch
* [Create an account](https://cloud.elastic.co) on Elastic if you haven't already.
* Create a deployment and save the credentials somewhere safe.

Head back to the app dashboard in Heroku and go to the config vars. You'll want to add  `ELASTIC_HOST` as a variable.

`ELASTIC_HOST` is a connection string in the following format:
```
https://{username}:{password}@{host}:{port}
```
The `{username}` and `{password}` you will have received whe you first set up the Elastic deployment. The details for the other facets of the connection string will be available in the deployment's dashboard. You can capture those remaining details from the "Elasticsearch" endpoint found on that dashboard.

Return to the Heroku dashboard, open the "More" option in the top right and select "Run console". Enter the following command and hit "Run":
```
php artisan elastic:migrate
```
This command will create search indexes in the Elasticsearch deployment you just created.

### Begin using the API
Your application should now be online and ready to use. Visit this project's [API Documentation](/docs/api/index.md) for information on how to consume the API.

## Other Environments
More general information on deploying this project may be added in the future. For now, you may be able to use the Heroku deployment process along with the Docker configuration as guidance when deploying to different web hosting services.
