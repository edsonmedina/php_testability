# php_testability

Analyses and produces a report with testability issues of a php codebase.

## Installation
### Composer 

Add `edsonmedina/php_testability` as a dependency to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project. 

    {
        "require-dev": {
            "edsonmedina/php_testability": "*"
        }
    }

And run `composer update`.

# Usage

`vendor/bin/testability . -o report` will analyse the current directory and generate an HTML report into report/

`vendor/bin/testability . -x vendor,tmp,upload,config` will exclude those dirs

`vendor/bin/testability --help` will show you an help page with all the available options.



Have fun.
