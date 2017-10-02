[![SensioLabsInsight](https://insight.sensiolabs.com/projects/c009dadf-a219-4808-b888-e9abad9a73bf/mini.png)](https://insight.sensiolabs.com/projects/c009dadf-a219-4808-b888-e9abad9a73bf)
[![Build Status](https://travis-ci.org/upwork/phystrix-bundle.svg)](https://travis-ci.org/upwork/phystrix-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/odesk/phystrix-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/odesk/phystrix-bundle/?branch=master)


# Phystrix Bundle

This bundle provides phystrix command factory service: `phystrix.command_factory` with default configuration

## Installation

Install component by using [Composer](https://getcomposer.org).
Update your project's `composer.json` file to include dependency.

```json
"require": {
    "odesk/phystrix-bundle": "~1.1"
}
```

Register bundle in your `AppKernel`

``` php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Odesk\Bundle\PhystrixBundle\OdeskPhystrixBundle()
            // ...
        );
    }
}
```

## Configuration

Default configuration:

### app/config/config.yml

```yaml
odesk_phystrix:
  default:
    fallback: ~
    circuitBreaker:
      errorThresholdPercentage: 50
      forceOpen: false
      forceClosed: false
      requestVolumeThreshold: 20
      sleepWindowInMilliseconds: 5000
    metrics:
      healthSnapshotIntervalInMilliseconds: 1000
      rollingStatisticalWindowInMilliseconds: 10000
      rollingStatisticalWindowBuckets: 10
    requestCache: ~
    requestLog: ~
```

## Web Profiler

Phystrix bundles comes with a web profiler plugin, it is enabled automatically whenever Symfony profiler is enabled.
You only need to make sure requestLog feature is turned on:

```yaml
odesk_phystrix:
  default:
    requestLog:
      enabled: true
```

Only do this in mode/environment where profiler is active.

## Usage

You may use `phystrix.service_locator` to provide additional dependencies in runtime:

```php
$container->get('phystrix.service_locator')->set('somekey', $somevalue);
```

How to create and run a command:

```php
$command = $container->get('phystrix.command_factory')->getCommand('MyCommand', $parameter1, $parameter2);
$command->execute();
```

### License

This file is a part of the Phystrix Bundle

Copyright 2013-2017 Upwork Global Inc. All Rights Reserved.

This file is licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

### Logger

How to add logger to Commands: define `logger.phystrix` service
```yml
# config.yml
monolog:
    channels: ['phystrix']
```
and
```yml
# services.yml
services:
    logger.phystrix:
        alias: monolog.logger.phystrix
```
