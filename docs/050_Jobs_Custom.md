# Custom jobs

You can easily add your own jobs. Just create a new service inside your application.
The service must implement the `\MonitoringManagerBundle\Service\Jobs\JobInterface`

Your `run` method must return an implementation of the StatusInterface 
(some default provided in the bundle, but you could use your own implementations). 

The business logic used inside the job is totally up to you!

For example:
- has the sitemap.xml been regenerated in the last 24 hours?
- is the "failed" queue for Symfony Messenger increasing at an alarming speed?
- check if the number of critical errors in the application log during the last X minutes is higher than Y
- is a long-running script taking an unusually long time?
- ...

## Service config

Your service must be public, and must be tagged as `monitoring_manager.job`.
All tagged services will be injected into the listing class, from then on you can activate the job by using the console commands.

For example:
```yaml
MonitoringManagerBundle\Service\Jobs\CoffeeMaker:
    tags: [ 'monitoring_manager.job' ]
    public: true
```
