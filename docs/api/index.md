# API Documentation

## Navigation
* [Authorisation](#authorisation)
    * [Token Abilities](#token-abilities)
    * [Manage Tokens by Config File](#manage-tokens-by-config-file)
    * [Manage Tokens by Command Line](#manage-tokens-by-command-line)
* [Consuming the API](#consuming-the-api)
* [Data Schema](#data-schema)

## Authorisation
Authorisation is run on every exposed route on the API. To authorise any request against the API, you must include the `Authorization` header in your request with a `Bearer` token as its value:
```
Authorization: Bearer <token>
```

There are two ways to establish API tokens that will be understood by the application: via config file and via the command line. Both approaches can be used simultaneously.

### Token Abilities
Endpoints are protected not only by authorisation, but by specific ability restrictions too. Abilities (or "permissions") allow different authorisation tokens to have different executive powers.

The abilities supported by the application are:
* `read_public`\
  Allows the token bearer to retrieve objects that from the API have _not_ been marked as `sensitive`.


* `read_sensitive`\
  Allows the token bearer to retrieve objects that from the API have been marked as `sensitive`. Having this ability is equivalent to having both the `read_public` and `read_sensitive` abilities.


* `write`\
  Allows the token bearer to save and update data in the API. Combine this with the `read_sensitive` and `read_public` abilities to grant the bearer full read and write access.

### Manage Tokens by Config File
If it doesn't exist already, create a config file at [config/api-tokens.php](/config/api-tokens.php). Use the following as the contents of the file:
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Tokens
    |--------------------------------------------------------------------------
    |
    | Manually create "anonymous" access tokens. Generate your own token string
    | and add it to the 'tokens' config. You'll then need to provide that
    | token with its 'abilities' so that it has established permissions.
    |
    */
    /**
     * @see App\Constants\ApiAbilities For individual API abilities.
     * @see App\Constants\AccessLevels For roles comprised of API abilities.
     *
     * @link https://codepen.io/corenominal/full/rxOmMJ Generate keys online.
     */

    'replace-this-with-a-unique-token-string-1' => [
        'name' => 'Website',
        'abilities' => App\Constants\AccessLevels::READ_PUBLIC,
    ],
    'replace-this-with-a-unique-token-string-2' => [
        'name' => 'iOS App',
        'abilities' => App\Constants\AccessLevels::READ_ALL,
    ],
    'replace-this-with-a-unique-token-string-3' => [
        'name' => 'CMS',
        'abilities' => [
            App\Constants\ApiAbilities::READ_PUBLIC,
            App\Constants\ApiAbilities::READ_SENSITIVE,
            App\Constants\ApiAbilities::WRITE,
        ],
    ],

];
```

This file returns an array of API tokens and their details, with the array's keys being the actual tokens. (You can generate API tokens [online](https://codepen.io/corenominal/full/rxOmMJ.).)

The `name` key of the token data is for your reference and not used anywhere in the application.

The `abilities` key expects a simple array of abilities. You can either create the array manually using abilities from [app/Constants/ApiAbilities.php](/app/Constants/ApiAbilities.php), or you can set its value to an existing role from [app/Constants/AccessLevels.php](/app/Constants/AccessLevels.php).

API tokens added to the file are ultimately saved in the application's database against an "anonymous" user. Tokens that are removed from the file are subsequently deleted from the database. It isn't possible to update a token's details via the config file, you'll have to remove it and create a new one.

### Manage Tokens by Command Line
You can generate, list and delete tokens for the API using the command line. This will require you to have SSH access to the server that the application is on.

#### Create a token
Use `php artisan token:create <name> <accessLevel> <emailAddress>` to generate a token. for example:
```
$ php artisan token:create "iOS App" read_all developer@charity.org
```
\
This will generate a token and display it in the console. Be sure to copy the token and store it securely, because there is no way for the application to display that token again.

Access levels currently available are:

| Access Level  | Abilities                                |
| ------------  | ---------------------------------------- |
| `read_public` | `read_public`                            |
| `read_all`    | `read_public`, `read_sensitive`          |
| `write`       | `read_public`, `read_sensitive`, `write` |

#### List existing tokens
Use `php artisan token:list <emailAddress>` to list existing tokens in the console. For example:
```
$ php artisan token:list developer@charity.org
```

(NOTE: The application stores tokens added via config file against an anonymous user with the email address _anonymous@api.user_. It's technically possible to use this email to list the config file tokens.)

#### Delete an API token
Use `php artisan token:destroy <tokenId> <emailAddress>` to delete a token from the database. For example:
```
$ php artisan token:destroy 5 developer@charity.org
```

Bear in mind that deleting config file tokens this way will have no effect if the token is still listed in the config file itself.

## Consuming the API
This project includes an OpenAPI JSON schema file ([openapi.json](/docs/openapi.json)) that contains the details,  requirements, routes and parameters for consuming the API. This file should be used as a reference point when developing against the API. Use API client software or any another schema reader to get the best of the schema file; you can copy the contents of the [openapi.json](/docs/openapi.json) file into the [Swagger Editor](https://editor.swagger.io/) to generate human-readable documentation, for example.

## Data Schema
Data returned from the API's routes will return in a format that's compatible with [Schema.org](https://schema.org) data formats. There may be additional data points in any given returned object but, as a minimum, the data points necessary to build a valid Schema.org object will usually be present (depending on if enough data was provided to the API in the first place).

### Utilised Schemas
Here are the schemas utilised by the data resources, which you can expect to see in data sent from the API.

* Location object:
    * [Place](https://schema.org/Place)
        * [PostalAddress](https://schema.org/PostalAddress)
        * [GeoCoordinates](https://schema.org/GeoCoordinates)
        * [ImageObject](https://schema.org/ImageObject)
* Article object:
    * [Article](https://schema.org/Article)
        * [AggregateRating](https://schema.org/AggregateRating)
* Shape object:
    * [GeoShape](https://schema.org/GeoShape)
