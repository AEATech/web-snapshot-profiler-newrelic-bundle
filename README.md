# AEATech - Web snapshot profiler newrelic bundle

[![Code Coverage](.build/coverage_badge.svg)](.build/clover.xml)

The package contains symfony bundle to profile web applications with newrelic.
It can be used for production profiling.

System requirements:
- PHP >= 8.2
- ext-newrelic (tested on 12.1+)

Installation (Composer):
```bash
composer require aeatech/web-snapshot-profiler-newrelic-bundle
```

## Auto installation

You can install it with custom recipe.

```bash
composer config extra.symfony.allow-contrib true
composer config --json --merge extra.symfony.endpoint '["https://api.github.com/repos/AEATech/recipes/contents/index.json?ref=main", "flex://defaults"]'
composer require aeatech/web-snapshot-profiler-newrelic-bundle
```

## Manual installation

Enable bundle in dev and prod env.

```php
// config/bundles.php

return [
    // ...
    AEATech\WebSnapshotProfilerNewrelicBundle\AEATechWebSnapshotProfilerNewrelicBundle::class => ['dev' => true, 'prod' => true],
    // ...
];
```

## Configuration

Symfony Flex generates a default configuration in config/packages/aea_tech_web_snapshot_profiler_newrelic.yaml

```yaml
aea_tech_web_snapshot_profiler_newrelic:
    # Enable/Disable profiling
    is_profiling_enabled: false

    # newrelic configuration
    newrelic:
        app_name: '%env(string:AEA_TECH_WEB_SNAPSHOT_PROFILER_NEWRELIC_APP_NAME)%'
        license: '%env(string:AEA_TECH_WEB_SNAPSHOT_PROFILER_NEWRELIC_LICENSE)%'

    ###
    # Event matched configuration - START
    ###
    event_matcher:
        # Enable/Disable all routes profiling
        is_profile_all_routes: false

        # Enable profile if header was set (\AEATech\WebSnapshotProfilerEventSubscriber\EventMatcher\HeaderEventMatcher)
        header:
            is_enabled: false
            name: 'x-profiling-header'
            value: '1'

        # Enable profile if request param was set and route matched (\AEATech\WebSnapshotProfilerEventSubscriber\EventMatcher\RequestParamAwareRouteEventMatcher)
        request:
            is_enabled: false
            name: 'x-profile-request-param'
            route_to_probability:
                # 1% probability to profile route
                -
                    route_name: 'app_route_name_1'
                    probability: 1
                # 100% probability to profile route
                -
                    route_name: 'app_route_name_2'
                    probability: 100

        # Enabled profile if route matched and probability happened
        route:
            is_enabled: false
            route_to_probability:
                # 1% probability to profile route
                -
                    route_name: 'app_route_name_1'
                    probability: 1
                # 100% probability to profile route
                -
                    route_name: 'app_route_name_2'
                    probability: 100
    ###
    # Event matched configuration - END
    ###
```

## License

MIT License. See [LICENSE](./LICENSE) for details.