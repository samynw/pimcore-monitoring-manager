# Pimcore Monitoring Manager

The goal of this bundle is to provide an easy tool for monitoring your application. 
It allows you to add custom jobs which can be monitored by your preferred monitoring tool. 
The criteria could be any business logic, depending on the needs of your application.

The bundle will create endpoints for your monitoring jobs. 
So your monitoring tool can fetch the status code of the HTTP request and alert you if needed.
Alternatively the exit code of a console command can be monitored.

This way the monitoring tool can monitor _any_ application criteria you've defined.
Some examples:
- has the sitemap.xml been regenerated in the last 24 hours?
- is the "failed" queue for Symfony Messenger increasing at an alarming speed?
- check if the number of critical errors in the application log during the last X minutes is higher than Y entries
- is a long-running script taking an unusually long time?
- ...

## Installation
```shell script
# Add package to composer dependencies
composer require samynw/pimcore-monitoring-manager

# Enable and install bundle
php bin/console pimcore:bundle:enable MonitoringManagerBundle
php bin/console pimcore:bundle:install MonitoringManagerBundle
```

Note: the installer will
- enable the webservice API
- create a "monitoring" user with a random API key

You can review and modify or remove the user at your convenience if you prefer to use another user or authentication method.

## Usage

The concept is to create jobs, based on business logic for critical application processes.

Your monitoring software can be configured to schedule either:
- HTTP requests to the bundle endpoints (monitor the HTTP Status code of the response)
- console command to fetch job status (monitor the console exit code)

Detailed information: 
- [Console commands](docs/010_Usage_ConsoleCommands.md)
    - list jobs
    - enable/disable job
    - show job details & status
- [HTTP Requests](docs/020_Usage_StatusResponses.md)
- [Defining custom jobs](docs/050_Jobs_Custom.md)
- [List of available jobs](docs/060_Jobs_Default.md)

## Authorization

The URL patters is added to a firewall, requiring authentication for the HTTP requests. 

For further details, see [Usage: HTTP requests](docs/020_Usage_StatusResponses.md).

