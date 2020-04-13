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

Copy .env.example to .env and set missing env variables
```bash
cp .env.example .env
```

Generate jwt secret
```bash
 php artisan jwt:secret
```

Cache config
```bash
 php artisan config:cache
```

Install dependencies
```bash
composer install
```

Run migrations
```bash
php artisan migrate
```

Seed DB
```bash
php artisan db:seed
```

## API URL
http://postalcodes.localhost/api/v1

## Routes

| Domain | Method    | URI                               | Name                        | Action                                                    | Middleware   |
-------------------------------------------------------------------------------------------------------------------------------------------------------------------
|        | GET|HEAD  | api/v1/cities                     | cities.index                | App\Http\Controllers\API\V1\CityController@index          | api          |
|        | POST      | api/v1/cities                     | cities.store                | App\Http\Controllers\API\V1\CityController@store          | api,auth:api |
|        | GET|HEAD  | api/v1/cities/{city}              | cities.show                 | App\Http\Controllers\API\V1\CityController@show           | api          |
|        | PUT|PATCH | api/v1/cities/{city}              | cities.update               | App\Http\Controllers\API\V1\CityController@update         | api,auth:api |
|        | DELETE    | api/v1/cities/{city}              | cities.destroy              | App\Http\Controllers\API\V1\CityController@destroy        | api,auth:api |
|        | GET|HEAD  | api/v1/countries                  | countries.index             | App\Http\Controllers\API\V1\CountryController@index       | api          |
|        | POST      | api/v1/countries                  | countries.store             | App\Http\Controllers\API\V1\CountryController@store       | api,auth:api |
|        | GET|HEAD  | api/v1/countries/{country}        | countries.show              | App\Http\Controllers\API\V1\CountryController@show        | api          |
|        | PUT|PATCH | api/v1/countries/{country}        | countries.update            | App\Http\Controllers\API\V1\CountryController@update      | api,auth:api |
|        | DELETE    | api/v1/countries/{country}        | countries.destroy           | App\Http\Controllers\API\V1\CountryController@destroy     | api,auth:api |
|        | GET|HEAD  | api/v1/countries/{country}/cities | countries.cities.index      | App\Http\Controllers\API\V1\CountryCitiesController@index | api          |
|        | POST      | api/v1/login                      |                             | App\Http\Controllers\API\V1\AuthController@login          | api          |
|        | GET|HEAD  | api/v1/postal-codes               | postal-codes.index          | App\Http\Controllers\API\V1\PostalCodeController@index    | api          |
|        | POST      | api/v1/postal-codes               | postal-codes.store          | App\Http\Controllers\API\V1\PostalCodeController@store    | api,auth:api |
|        | GET|HEAD  | api/v1/postal-codes/{postal_code} | postal-codes.show           | App\Http\Controllers\API\V1\PostalCodeController@show     | api          |
|        | PUT|PATCH | api/v1/postal-codes/{postal_code} | postal-codes.update         | App\Http\Controllers\API\V1\PostalCodeController@update   | api,auth:api |
|        | DELETE    | api/v1/postal-codes/{postal_code} | postal-codes.destroy        | App\Http\Controllers\API\V1\PostalCodeController@destroy  | api,auth:api |
|        | GET|HEAD  | api/v1/provinces                  | provinces.index             | App\Http\Controllers\API\V1\ProvinceController@index      | api          |
|        | POST      | api/v1/provinces                  | provinces.store             | App\Http\Controllers\API\V1\ProvinceController@store      | api,auth:api |
|        | GET|HEAD  | api/v1/provinces/{province}       | provinces.show              | App\Http\Controllers\API\V1\ProvinceController@show       | api          |
|        | PUT|PATCH | api/v1/provinces/{province}       | provinces.update            | App\Http\Controllers\API\V1\ProvinceController@update     | api,auth:api |
|        | DELETE    | api/v1/provinces/{province}       | provinces.destroy           | App\Http\Controllers\API\V1\ProvinceController@destroy    | api,auth:api |
|        | POST      | api/v1/register                   |                             | App\Http\Controllers\API\V1\AuthController@register       | api          |
