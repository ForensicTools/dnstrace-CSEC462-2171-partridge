# dnstrace Setup

### 1. Database

Running setup/dnstrace.sql as an administrative user on your SQL host (preferably Percona Server >5.6 or MySQL >5.6) should create the dnstrace database without tweaks. By default, it comes with no data - domains or otherwise.

### 2. Users

Ideally, create two users on your preferred SQL server, one with full privileges to the dnstrace database and one with read-only privileges. The administrative user will be used for dnstrace's administrative tools and workers, while the read-only user will be used for the web frontend.

It behooves you to create two seperate users that *only* have access in varying levels to the dnstrace database, as this software is under heavy development has not been audited by any third party. Vulnerabilities may appear and disappear at will. As 3OH!3 would say: DON'T TRUST ME.

A full list of required privileges will be posted shortly.