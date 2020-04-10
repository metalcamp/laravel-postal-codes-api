## Postal codes API in Laravel

### Requirements
* Docker
* docker-compose

### Setup

Add following line to your hosts file 
```
127.0.0.1 postalcodes.localhost
```

or run
 
```bash 
echo "127.0.0.1 postalcodes.localhost" | sudo tee -a /etc/hosts
```

Build docker images and start docker containers (this can take a while)
```bash
cd laradock-laravel-postal-codes-api && sudo docker-compose up -d nginx postgres workspace 
```

Enter workspace container
```bash
sudo docker-compose exec --user=laradock workspace bash
```

Install dependencies
```bash
composer install
```

Run migrations
```bash
php artisan migrate
```

Visit http://postalcodes.localhost in your browser




