# Search Configuration
This application is designed to be integrated with search providers to provide powerful search capabilities through the API.

At this time, the application supports configuration with Elasticsearch.

## Contents
* [General Configuration](#general-configuration)
* [Targeting a Provider](#targeting-a-provider)
* [Routing](#routing)
  * [Route Controllers](#route-controllers)

## General Configuration
Search integration requires that a database (as defined by the `.env` variable, `DB_CONNECTION`) is available to the application. Laravel Scout ([documentation](https://laravel.com/docs/8.x/scout)) stores data in the database before pushing it to the search provider's index. Make sure that your database is up and running before attempting to configure your search integration.

## Targeting a Provider
There are a few configuration values that need to be updated to make a given search provider the active provider. Bear in mind that only one provider can be active at any time.

The `SCOUT_DRIVER` environment variable is used by Laravel Scout to decide which search driver to use. So setting this as `SCOUT_DRIVER=elastic` will make Laravel Scout utilise the Elasticsearch driver.

For most major search providers, a Laravel package will already be available online, which can then usually be installed using Composer:
```
$ composer require babenkoivan/elastic-scout-driver
```

After that, you will then need to follow that provider's documentation on how to configure the package for Laravel. The package will most likely add a file to the project's `config` directory where such configuration will take place.

## Routing
API routes are declared in the [api.php](routes/api.php) routes file. Each resource (e.g. Articles, Locations) is separated out into its own group. This allows us to provide different rules to each resource without too much difficulty.

It's assumed that each resource will _at least_ accommodate CRUD actions: Create, Read, Update, Delete. Further, each resource will need to be duplicated for each available search provider.

In practice, it will usually be enough to copy an existing search provider's set of routes and update the controller references.

### Route Controllers
Controllers are organised by search provider. This means that there will be multiple `ArticleController` files within the project. For example:
```
Http
  - Controllers
    - Elastic
      - ArticleController
      - LocationController
    - Algolia
      - ArticleController
      - LocationController
```

While all search providers have to adhere to Scout's [search engine interface](https://laravel.com/docs/8.x/scout#custom-engines), the interface is limited in scope. Some providers have elaborate search capabilities, so some drivers will have additional methods that may not match up with others. This thus requires us to create separate controllers for each provider to keep things modular and neat.
