# Installation
## How to Run the Application
1. Copy ```.env.example``` and rename it to ```.env```.
2. Configure the database username and password in the ```.env``` file.
3. Run the command ```docker compose up --build -d```.
4. The application can be accessed at ```127.0.0.1:8000```.
5. You don't need to recreate container if you had a changes. It'll update automatically.

- If you don't want to run the seeder because it will replace your existing data in MySQL, simply set ```RUN_SEEDER=false``` in the ```.env``` file.
- If database can't connect to app service, run the command ```docker compose down -v``` first.
- If you encounter an error with message 'ERROR [app internal] load build context, delete vendor folder and run the command ```Get-ChildItem -Recurse | ForEach-Object { icacls $_.FullName /reset }```.

## How to Create a Module
1. Run the command ```docker exec -it sipta-app-dev php artisan make:modul ModulName```.
2. Inside every module, you will find:
    - Routes
    - Controller
    - Views

## Database Table and Seeder Configuration
Database migration and seeding are automatically performed when you run the application with Docker. However, if you want to do it manually, follow these steps:
1. Run the command ```docker exec -it sipta-app-dev php artisan migrate``` (This will run the migration)
2. Run the command ```docker exec -it sipta-app-dev php artisan db:seed``` (This will seed the database)

If you encounter an error, do this:
1. Run the command ```docker exec -it sipta-app-dev php artisan db:wipe --force``` (This will drop the current database.)
2. Run the command ```docker exec -it sipta-app-dev php artisan:migrate --seed --force``` (This will run the migrations and seed the database.)

<br>
<br>

# Deployment Guide
## Deployment for Staging
1. Copy all files from the folder deployment/staging to the root directory.
2. Rename ```.env.example``` to ```.env```, then complete the missing variables with these notes:
    - ```APP_KEY``` will be generated automatically by Laravel.
    - If you don't want to use a prefix, leave ```PREFIX_URL```, ```SESSION_PATH```, and ```ASSET_URL``` empty. And modify docker web server configuration at Docker/nginx/dev-default.conf.
    - If ```DB_HOST``` uses a Docker Compose service name, the ```DB_PORT``` must match the container port, not the host port.
    - To hide impersonate feature, set ```IMPERSONATE=false```.
    - ```MAIL_ENCRYPTION``` should be set to 'tls' or 'ssl'.
3. Check and adjust port configurations in docker-compose.yml if needed (e.g., when a port is already used).
4. Use Watchtower for CI/CD updates with the following command:
  ```
  docker run -d \
    --name watchtower \
    -e WATCHTOWER_CLEANUP=true \
    -e WATCHTOWER_POLL_INTERVAL=30 \
    -v /var/run/docker.sock:/var/run/docker.sock \
    containrrr/watchtower
  ```
5. Add nginx configuration for application container as shown below. Delete ```sipta-dev/``` if you don't want to use prefix, or modify with actual prefix as you set on ```.env```.
   ```
    location /sipta-dev/ {
        proxy_pass http://localhost:8003;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }

    location ~ ^/sipta-dev/(.+\.(?:css|js|png|jpe?g|gif|svg|ico|woff2?|ttf|eot|map))$ {
        proxy_pass http://localhost:8003/$1;
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
   ```

## Deployment for Production
Same steps as staging, but use the folder deployment/production instead:
1. Copy all files from the folder deployment/production to the root directory.
2. Rename ```.env.example``` to ```.env```, and fill in necessary variables with the same rules as staging. Modify docker web server configuration at Docker/nginx/prod-default.conf if don't want to use prefix.
3. Configure port mappings and services in docker-compose.yml as needed.
4. Deploy Watchtower as shown above to enable automatic updates.
5. Add nginx configuration for application container as shown below. Delete ```sipta/``` if you don't want to use prefix, or modify with actual prefix as you set on ```.env```.
   ```
    location /sipta/ {
        proxy_pass http://localhost:8002;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }

    location ~ ^/sipta/(.+\.(?:css|js|png|jpe?g|gif|svg|ico|woff2?|ttf|eot|map))$ {
        proxy_pass http://localhost:8002/$1;
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }
   ```

## CI/CD
To use CI/CD, you need to set Secrets and Variables as below:
| Secret Variable | Description                                                                     |
| --------------- | ------------------------------------------------------------------------------- |
| SSH_PRIVATE_KEY | The private SSH key used for authenticating to the staging server via SSH.      |
| SERVER_IP       | The IP address of the staging server where the deployment will take place.      |
| SERVER_USER     | The username used to log in to the staging server via SSH.                      |
| SUDO_PASSWORD   | The sudo password on the server, used to execute commands with root privileges. |
| DOCKER_USERNAME | The Docker Hub account username used for logging in and pushing Docker images.  |
| DOCKER_PASSWORD | The Docker Hub account password used for logging in and pushing Docker images.  |

<br>
<br>

# Technology are Used
## Documentation of AdminLTE Template Usage
- https://jeroennoten.github.io/Laravel-AdminLTE/sections/overview/usage.html
- config on : config/adminlte.php
  
## Spatie Response Cache
- https://github.com/spatie/laravel-responsecache 
- https://www.tiktok.com/@yogameleniawan/photo/7467150735872937223 

## Logging
- Laravel Observer

## Authentication
- Laravel Fortify
