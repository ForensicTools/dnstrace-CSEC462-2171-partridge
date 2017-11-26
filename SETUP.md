# dnstrace Setup

## Database Setup

### Database

Running setup/dnstrace.sql as an administrative user on your SQL host (preferably Percona Server >5.6 or MySQL >5.6) should create the dnstrace database without tweaks. By default, it comes with no data - domains or otherwise.

### Users

Ideally, create two users on your preferred SQL server, one with full privileges to the dnstrace database and one with nearly-read-only privileges. The administrative user will be used for dnstrace's administrative tools and workers, while the nearly-read-only user will be used for the web frontend.

The nearly-read-only user should have SELECT access to the entire dnstrace database, as well as SELECT, UPDATE, INSERT, DELETE to the Jobs and Processors tables.

It behooves you to create two seperate users that *only* have access in varying levels to the dnstrace database, as this software is under heavy development has not been audited by any third party. Vulnerabilities may appear and disappear at will. As 3OH!3 would say: DON'T TRUST ME.

## Backend Setup

### Composer (Tools)

This project requires Composer for PHP-related package management. To prepare the environment, in this directory you should run the following command(s):

* composer require layershifter/tld-extract

And that will install a couple composer-related files as well as the necessary packages in vendor/*

### Other Dependencies

This project currently depends on the following:

* python-whois by joepie91

You can have them downloaded and installed automatically by running setup/deps.sh which will clone those softwares to deps/. Installation of all required softwares to run those dependencies is not guaranteed, please open issues for any common problems until I get around to cleaning up that section.

### tools/config.example.php

Edit this file as needed to connect to the data backend as an administrative user as well as tune any preferences, before copying it to config.php. The sections that you need to edit should be easy to identify as they are flagged as such.

## Frontend Setup

### Preparing Webserver

Move all files in web/ to the webserver root of your choice. Because I'm old-school, I put my files in /var/www/html and am running an Apache2 frontend. This should require little to no configuration. Ideally, your webserver should be seperate from the dnstrace administrative server as a security measure (as the two have different levels of access to the database). Your mileage may vary.

### web/api/config.example.php

Edit this file as needed to connect to the data backend as a nearly-read-only user as well as specify the FQDN of the webserver, before copying it to config.php. The sections that you need to edit should be easy to identify as they are flagged as such.

### web/base.php

Set your FQDN and forget.

### Composer (Web)

The web component of this project also requires Composer for PHP-related package management. To prepare the environment, in this directory you should run the following command in the api folder of your web root:

* composer require layershifter/tld-extract

### 9. 