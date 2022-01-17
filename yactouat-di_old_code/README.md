# yactouat-di

my implementation of the psr/container interface (<https://packagist.org/packages/yactouat/di>)

## prerequisites

- PHP ^8.0

## includes

- the logic of the package lies in the `./src` folder
- PHPUnit installed out of the box with
  - a `./tests` folder containing example tests

### tests

- `reset && ./vendor/bin/phpunit --color --testdox ./tests` will run your tests
- you can output your test results to an HTML file running `./vendor/bin/phpunit --color --testdox my_tests_output.html` (*txt* and *xml* files are also supported)
