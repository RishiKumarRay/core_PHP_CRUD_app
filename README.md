# core PHP CRUD app'

a ready-to-use PHP CRUD app' built without a framework that contains various services, who knows ? maybe it'll become a framework itself one day...

## run the app'

- add `php_crud_app.local` (see `nginx.conf` if you want to change this hostname) to your host machine `hosts` file
- `docker compose up`

## test the app'

- `docker exec -it {containerId} bash -c "/php_crud_app/vendor/bin/phpunit"`

## roadmap

- v1 milestone
- modern logging and fullstack observability milestone
- MongoDB adapter + ORM milestone
- CLI milestone
- voice command features milestone
- backlog
