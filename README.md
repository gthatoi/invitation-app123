# Prerequisites
* Docker
* Docker compose version 3

# Steps
* Inside the invitation-app
* run `docker-compose up -d`
* You can see the list of containers using `docker ps -a`
* Execute the following commands
    * `docker-compose exec app php artisan key:generate`
    * `docker-compose exec app php artisan config:cache`
* Go to `http://localhost:8082/`
    * You will see a text `Hello World`
* The application is up and running

## MySQL config
* Go inside the db container 
    * `docker-compose exec db bash`
* Run the following commands
    * `mysql -u root -p` password will be from `docker-compose.yaml:48`
    * `show databases;`
    * `create database invitation;`
    * `GRANT ALL ON invitation.* TO 'db_user'@'%' IDENTIFIED BY 'db_password';`
    * `FLUSH PRIVILEGES;`
    * `Exit`
    
### Migrations and mock data
* `docker-compose exec app php artisan migrate:fresh --seed`

### Tests
* API tests will be found in `\Tests\Controllers\InvitationsControllerTest`
* Command: `docker-compose exec app php artisan test`

