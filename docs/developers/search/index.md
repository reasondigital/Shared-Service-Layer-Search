# Search Integrations
This application is designed to be integrated with search providers such as Elasticsearch and Algolia.

Check the project's [.env](.env) file to find out what which is currently in play.

## Contents
* [Integration](#integration)
  * [Elasticsearch](#elasticsearch)

## Integration
Search is integrated into Laravel via Laravel Scout. In most cases, the actual integration will actually already exist as a Laravel Package somewhere online. It is possible, though, to build your own integration implementation.

It's recommended that you have a read of (or at least have on hand) the [Scout documentation](https://laravel.com/docs/8.x/scout) to understand how it pulls together Laravel and any given search provider. Most driver packages will provide documentation on how to configure their integration.

Once you have a driver, the next step in integration is to utilise it within the application. Typically, this is done within controllers. See the [search documentation](https://laravel.com/docs/8.x/scout) for details on basic search implementation using your application's models.

### Elasticsearch
The packages for the integration are available on GitHub and have documentation.

* __[Scout Driver package](https://github.com/babenkoivan/elastic-scout-driver#contents) on GitHub.__ This package simply meets the basic requirements for integration with Laravel Scout and allows for basic search queries.


* __[Scout Driver Plus package](https://github.com/babenkoivan/elastic-scout-driver-plus#contents) on GitHub.__ This package allows us to execute advanced search facilities, such as setting the "fuzziness", searching by range and more.


* __[Migration Package](https://github.com/babenkoivan/elastic-migrations#elastic-migrations) on GitHub.__ This package provides the facility to migrate indexes into Elasticsearch using Laravel's migration feature, making it easy to maintain and update indexes using Laravel's facilities.
