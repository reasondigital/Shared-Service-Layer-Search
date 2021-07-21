# Search Configuration
This application is designed to be integrated with search providers to provide powerful search capabilities through the API.

At this time, the application supports configuration with Elasticsearch.

## Contents
* [General Configuration](#general-configuration)
* [Targeting a Provider](#targeting-a-provider)
* [Search Results](#search-results)
* [Articles Search Fields](#articles-search-fields)
* [Locations Search Radius](#locations-search-radius)
* [Routing](#routing)
  * [Route Controllers](#route-controllers)

## General Configuration
Search integration requires that a database (as defined by the `.env` variable, `DB_CONNECTION`) is available to the application. Laravel Scout ([documentation](https://laravel.com/docs/8.x/scout)) stores data in the database before pushing it to the search provider's index. Make sure that your database is up and running before attempting to configure your search integration.

## Targeting a Provider
The `SCOUT_DRIVER` environment variable is used by Laravel Scout to decide which search driver to use. So setting this as `SCOUT_DRIVER=elastic` will make Laravel Scout utilise the Elasticsearch driver. Bear in mind that only one provider can be active at any time.

For most major search providers, a Laravel package will already be available online, which can then usually be installed using Composer on the command line:
```
$ composer require babenkoivan/elastic-scout-driver
```

After that, you will then need to follow that provider's documentation on how to configure the package for Laravel. The package will most likely add a file to the project's `config` directory where such configuration will take place.

## Search Results
You can adjust the default number of results returned by your application by setting the `RESULTS_COUNT_ARTICLES` and `RESULTS_COUNT_LOCATIONS` variables in your [.env](/.env) file.

## Articles Search Fields
The Articles search endpoint has the `in` parameter, which allows API consumers to narrow their search specific fields. This parameter, however, is optional, which means a default set of fields needs to be provided to the application.

The [search.php](/config/search.php) config file contains the default search fields established by the application. You can update this default by setting the `DEFAULT_QUERY_FIELDS_ARTICLES` variable in your [.env](/.env) file. For example:
```dotenv
DEFAULT_QUERY_FIELDS_ARTICLES=name,articleBody,abstract
```

All fields, except for the "sensitive" field, are eligible.

## Locations Search Radius
Location search can have a constraint, such as a geo-shape or distance. If no constraints are provided with a location request, the application will default to using the distance constraint. Update the value of `ADDRESS_SEARCH_RADIUS` in your [.env](/.env) file to adjust the default distance to constrain a location search by.

## Routing
API routes are declared in the [routes/api.php](/routes/api.php) routes file. Each resource (e.g. Articles, Locations) is separated out into its own group. Within each group, a `switch` construct checks the configured search provider and sets the appropriate controller class based on the value.

Add a new case to the `switch` constructs when a new search provider is implemented in the application.

### Route Controllers
Controllers are organised by search provider. This means that there will be multiple `ArticleController` files within the project, for example:
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

While all search providers have to adhere to Scout's [search engine interface](https://laravel.com/docs/8.x/scout#custom-engines), the interface is limited in scope. Most providers have sophisticated search capabilities, so some drivers will have additional methods that may not match up with others. This thus requires us to create separate controllers for each provider to keep things modular and neat.

There are base controllers for each of the resources, on which any search provider-specific controllers should extend. The base controllers include basic implementations for the more straightforward endpoints, such as "get by ID" and "delete by ID". Other, more sophisticated operations — i.e. "save", "search" and "update" — will require custom implementations for each search provider in their respective controllers.
