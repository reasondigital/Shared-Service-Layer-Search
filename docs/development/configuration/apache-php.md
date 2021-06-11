# Apache/PHP Configuration
The configuration files for the Apache/PHP set-up can be found in the [docker/webserver](/docker/webserver) directory.

## Contents
* [Restart](#restart)
* [Apache](#apache)
  * [Domains and 'VirtualHost'](#domains-and-virtualhost)
  * [.htaccess](#htaccess)
  * [Environment-specific Configuration](#environment-specific-configuration)
* [PHP](#php)
  * [Additional Config](#additional-config)
  * [XDebug](#xdebug)

## Restart
After making changes to any configuration files, be sure to restart Apache by running the following from the root of the project:
```
$ docker-compose restart webserver
```

## Apache
Commonly used config files within Apache — such as apache2.conf — have been made available to the project. Any updates you make to these files will be reflected when the Docker containers are made active.

Configuration files are mapped into Docker individually, rather than by directory. This is to avoid pulling less used configuration files into the project unnecessarily. If you want to expose a certain file to your local system, update the relevant `docker-compose` configuration accordingly.

### Domains and `VirtualHost`
The [000-default.conf](/docker/webserver/apache/sites-enabled/000-default.conf) can be found in the `docker/webserver/apache/sites-enabled` directory of the project.

The configuration currently has no `ServerName` set, but you can add this directive to the file when required.

You can also add more files to the `sites-enabled` (or any within the webserver config) directory should you need to, but bear in mind they are not loaded into the container by Docker automatically. You'll need to add them to the relevant `docker-compose` configuration file.

### .htaccess
`.htaccess` has been disabled on this project. The use of `.htaccess` can bring a performance hit, and is unnecessary when you have control of Apache's core configuration. Any configuration destined for the `.htaccess` file can instead be added within the `<Directory /var/www/html/public>` directive in the [apache2.conf](/docker/webserver/apache/apache2.conf) file.

If you do need to enable `.htaccess` in the application, you can do so by changing `AllowOverride None` to `AllowOverride All` in [apache2.conf](/docker/webserver/apache/apache2.conf), within the `<Directory /var/www/html/public>` directive.

### Environment-specific Configuration
The main [apache2.conf](/docker/webserver/apache/apache2.conf) file auto-includes any conf file found under the `docker/webserver/apache/env` directory (you may need to create this if it doesn't exist). This directory is included in the project's `.gitignore` file and is great for including environment-specific configuration in different environments.


## PHP
The [php.ini](/docker/webserver/php/php.ini) file is available in the project at `docker/webserver/php/php.ini`. It has been set up for a production environment. However, in [docker-compose.yml](/docker-compose.yml), the [zz-php-development.ini](/docker/webserver/php/conf.d/zz-php-development.ini) file is loaded in addition to that, which sets up the environment for development. (Remember, if deploying using Docker Compose, you should deploy to production environments with the [docker-compose.production.yml](/docker-compose.production.yml) configuration.)

### Additional Config
You can add additional config files to the project if needed by adding them to the `docker/webserver/php/conf.d` directory and mapping them in the relevant `docker-compose` configuration file.

`zz-php-local.ini` has already been added to the project's `.gitignore` file, and is a good option for adding config values that are specific to your environment, such as setting up XDebug.

### XDebug
XDebug (v2.9.8) is installed onto the webserver container by Docker and is great for debugging and profiling your code during development. You can turn on XDebug on the server in a few steps.

First, create a `zz-php-local.ini` file in the `docker/webserver/php/conf.d` directory if it doesn't already exist. Include something like the following within it:
```ini
[xdebug]
zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20190902/xdebug.so
xdebug.idekey = docker
xdebug.remote_enable = 1
xdebug.remote_host = host.docker.internal
```
(The [XDebug website](https://xdebug.org/docs/) has more info on available settings.)

Then, create a `docker-compose.override.yml` file at the root of the project if one doesn't already exist. Within it, include the following, changing the `.ini` file name if appropriate:
```yaml
version: "3.1"
services:

  webserver:
    volumes:
      - ./docker/webserver/php/conf.d/zz-php-local.ini:/usr/local/etc/php/conf.d/zz-php-local.ini
```
This bit of code "overrides" the main `docker-compose` file. In this case, it adds your new `.ini` file to the Docker file mount established in the main file.

You'll then need to stop the containers if they're currently running, and start them back up again with the `--build` flag:
```
$ docker-compose up --build
```

Once the containers are up and running again, check the output of `phpinfo()` to see if the XDebug module settings have been updated.
