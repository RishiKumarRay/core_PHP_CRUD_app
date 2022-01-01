# core PHP CRUD app'

a PHP CRUD app' built without a framework, who knows ? maybe it'll become a framework itself one day...

## run the app'

- add `php_crud_app.local` (see `nginx.conf` if you want to change this hostname) to your host machine `hosts` file
- `docker compose up`

## test the app'

- `docker exec -it core_php_crud_app-php_crud_app-1 bash -c "/php_crud_app/vendor/bin/phpunit"`

## TODOS

- TODO's
- license the app'
- automate adding dev app' url to hosts file
  - on Windows
  - on Linux
  - on MacOS
- form builder
- prod. version of the application stack
- deploy the application stack as a demo
