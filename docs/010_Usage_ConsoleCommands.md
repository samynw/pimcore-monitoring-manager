# Usage: console commands

## Get a list of all available jobs

Simply run the command:
```shell
$ php bin/console monitoring:list:available 
```
This will give you an overview of all registered services.

The jobs that are active, will also show you the HTTP endpoint and their current status.

Sample output without active jobs:
```
+-----------------------------------------------------+--------+----------+--------+
| Monitoring task                                     | Active | Endpoint | Status |
+-----------------------------------------------------+--------+----------+--------+
| MonitoringManagerBundle\Service\Jobs\PackageChecker | no     |          |        |
| MonitoringManagerBundle\Service\Jobs\CoffeeMaker    | no     |          |        |
+-----------------------------------------------------+--------+----------+--------+
```

Sample output with active jobs:
```
+-----------------------------------------------------+--------+------------------------------------+--------+
| Monitoring task                                     | Active | Endpoint                           | Status |
+-----------------------------------------------------+--------+------------------------------------+--------+
| MonitoringManagerBundle\Service\Jobs\PackageChecker | yes    | /monitoring/status/package-checker | OK     |
| MonitoringManagerBundle\Service\Jobs\CoffeeMaker    | no     |                                    |        |
+-----------------------------------------------------+--------+------------------------------------+--------+
```

## Enable a job

There's a command which will prompt you the needed info:
- the service (select option from the suggested list)
- the key (will be used as identifier and uri segment)

The key must be unique. If not, the user will be asked to enter a new key. 
By default an slugified version of the shortname of the service class is used.

```shell
$ php bin/console monitoring:job:enable

Monitoring service
------------------

Select the monitoring service you want to enable:
  [0] MonitoringManagerBundle\Service\Jobs\PackageChecker
  [1] MonitoringManagerBundle\Service\Jobs\CoffeeMaker
 > 0

Monitoring job key
------------------

The key of the service will be used as
a) the key of the config entry
b) the URI segment in the route

So choose a unique key for the job
The key "package-checker" will translated to url "/monitoring/status/package-checker"

Please enter a key for the service (default package-checker): package-ckecker

 [OK] Enabled monitoring job "package-checker".
```

### Disable a job
Similar to the "enable" command, there's a "disable" command as well.
You can either add the job identifier as optional argument, or select from the suggested list.

```shell
$ php bin/console monitoring:job:disable
Select the monitoring job you want to disable:
  [0] package-checker
  [1] coffee-maker
 > 1


 [OK] Job "coffee-maker" has been disabled
```

### Get job details

Using the job identifier, you can fetch the details of a job.
This will print details about the configured job, run the job and show the status details.

```shell
$ php bin/console monitoring:job:details package-checker

Composer security checker
=========================

Check for known vulnerabilities in the Composer dependencies.

Job details
-----------

 ---------- -----------------------------------------------------
  Endpoint   /monitoring/status/package-checker
  Class      MonitoringManagerBundle\Service\Jobs\PackageChecker
 ---------- -----------------------------------------------------

Status details
--------------

 ------------------- ------------------------------------------------
  Status              success
  HTTP status code    200
  Console exit code   0
  Class               MonitoringManagerBundle\Service\Status\Success
 ------------------- ------------------------------------------------


 [OK] Status check passed

```
