# PHP_Testability

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

Analyse the current directory and generate an HTML report into report/

`vendor/bin/testability . -o report` 


Exclude some directories

`vendor/bin/testability . -x vendor,tmp,upload,config -o report` 


Check all the available options.

`vendor/bin/testability --help` 


# Results

Open report/index.html on your browser. You shoule see something like this:

![Screenshot](http://edsonmedina.github.io/php_testability_website/images/dir_report.png)


Have fun.
