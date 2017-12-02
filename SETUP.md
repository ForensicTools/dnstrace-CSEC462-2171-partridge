# dnstrace Setup

For setup, we define three machines: your Database, an Administrative host, and a Webserver. Keep track of which one we're taking about - if there is the capacity for confusion, a note will be made in the title of a section to determine what machine we're taking about.

## Database Setup (DB)

### Database

Running setup/dnstrace.sql as an administrative user on your SQL host (preferably Percona Server >5.6 or MySQL >5.6) should create the dnstrace database without tweaks. By default, it comes with no data - domains or otherwise.

### Users

Ideally, create two users on your preferred SQL server, one with full privileges to the dnstrace database and one with nearly-read-only privileges. The administrative user will be used for dnstrace's administrative tools and workers, while the nearly-read-only user will be used for the web frontend.

The nearly-read-only user should have SELECT access to the entire dnstrace database, as well as SELECT, UPDATE, INSERT, DELETE to the Jobs and Processors tables.

It behooves you to create two seperate users that *only* have access in varying levels to the dnstrace database, as this software is under heavy development has not been audited by any third party. Vulnerabilities may appear and disappear at will. As 3OH!3 would say: DON'T TRUST ME.

## Backend Setup (Admin)

### Composer

This project requires Composer for PHP-related package management. To prepare the environment, in this directory you should run the following command(s):

* composer require layershifter/tld-extract

And that will install a couple composer-related files as well as the necessary packages in vendor/*

### Other Dependencies

This project currently depends on the following:

* python-whois by joepie91
* czdap-tools by fourkitchens (for zone file downloads)

You can have them downloaded and installed automatically by running setup/deps.sh which will clone those softwares to deps/. Installation of all required softwares to run those dependencies is not guaranteed. If you will be parsing zone files, additionally install python-crypto and python-requests with your package manager (as per czdap readme).

### tools/config.example.php

Edit this file as needed to connect to the data backend as an administrative user as well as tune any preferences, before copying it to config.php. The sections that you need to edit should be easy to identify as they are flagged as such.

## Frontend Setup (Web)

### Preparing Webserver

Move all files in web/ to the webserver root of your choice. Because I'm old-school, I put my files in /var/www/html and am running an Apache2 frontend. This should require little to no configuration. Ideally, your webserver should be seperate from the dnstrace administrative server as a security measure (as the two have different levels of access to the database). Your mileage may vary.

### web/api/config.example.php

Edit this file as needed to connect to the data backend as a nearly-read-only user as well as specify the FQDN of the webserver, before copying it to config.php. The sections that you need to edit should be easy to identify as they are flagged as such.

### web/base.example.php

Edit, set your FQDN, save to base.php, and forget.

### Composer (Again)

The web component of this project also requires Composer for PHP-related package management. To prepare the environment, in this directory you should run the following command in the api folder of your web root:

* composer require layershifter/tld-extract

## Data Manipulation

### Ingestion (Admin)

You must create flags for each domain ingested using tools/rep_add.php before using tools/fqdn_add.php to ingest the necessary data. Running both of these tools without arguments will provide a verbose help dialogue to help you get started.

### Acquisition (Admin)

Run tools/update_all_dns.php with as many threads as you'd like (see its help dialogue for assistance). A good rule of thumb here is the number of cores available on your management machine multiplied by eight. This will acquire data for all domains in the database, and may take some time.

### Processing (Web)

From a commandline (preferably screen) or in crontab (@reboot), run an instance of web/api/process_manager.php with as many concurrent threads as you'd like (see its help dialogue for assistance). More threads means more concurrent users can be served, but each thread will go slower as database load increases. A reasonable rule of thumb is the sum of your webserver and database cores.