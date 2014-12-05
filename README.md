[![Build Status](https://travis-ci.org/edsonmedina/php_testability.svg?branch=master)](https://travis-ci.org/edsonmedina/php_testability/)
[![Code Climate](https://codeclimate.com/github/edsonmedina/php_testability/badges/gpa.svg)](https://codeclimate.com/github/edsonmedina/php_testability)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/edsonmedina/php_testability/master.svg?style=flat)](https://scrutinizer-ci.com/g/edsonmedina/php_testability/?branch=master)

# PHP_Testability

Analyses and produces a report with testability issues of a php codebase.

## Installation
### Composer 

Add `edsonmedina/php_testability` as a dependency to your project's `composer.json` file if you use [Composer](http://getcomposer.org/) to manage the dependencies of your project. 

    {
        "require-dev": {
            "edsonmedina/php_testability": "dev-master"
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


If you click on a file with issues, it'll show you a code browser and will highlight the lines with issues.

These are issues that hinder testability, such as:
* references to global variables, super globals, etc
* calls to functions that can't be mocked (like static methods or global functions)
* `new` instances of objects (tight coupling - can't be mocked/injected)
* ...and much more

Many interesting features are being added:
* Integration with jenkins (via [plot plugin](https://wiki.jenkins-ci.org/display/JENKINS/Plot+Plugin)) for tracking the progress
* Integration with clover.xml (from PHPUnit) to get the CRAP index and for displaying which methods are already covered
* A dashboard with a few lists of file (ie": "easy-to-pick", "needs major refactoring")
* Issues highlighted will have tips with suggestions of refactoring patterns to solve it

Kudos to the brilliant [PHP-Parser](https://github.com/nikic/PHP-Parser/) (by nikic) on which PHP_Testability relies heavily.

