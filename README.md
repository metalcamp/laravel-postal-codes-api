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

## Routes

| Method    | URI                               | Name                   | Action                                                    | Middleware   |
|-----------|-----------------------------------|------------------------|-----------------------------------------------------------|--------------|
| POST      | api/v1/cities                     | cities.store           | App\Http\Controllers\API\V1\CityController@store          | api          |
| GET/HEAD  | api/v1/cities                     | cities.index           | App\Http\Controllers\API\V1\CityController@index          | api          |
| GET/HEAD  | api/v1/cities/{city}              | cities.show            | App\Http\Controllers\API\V1\CityController@show           | api          |
| PUT/PATCH | api/v1/cities/{city}              | cities.update          | App\Http\Controllers\API\V1\CityController@update         | api          |
| DELETE    | api/v1/cities/{city}              | cities.destroy         | App\Http\Controllers\API\V1\CityController@destroy        | api          |
| GET/HEAD  | api/v1/countries                  | countries.index        | App\Http\Controllers\API\V1\CountryController@index       | api          |
| POST      | api/v1/countries                  | countries.store        | App\Http\Controllers\API\V1\CountryController@store       | api          |
| GET/HEAD  | api/v1/countries/{country}        | countries.show         | App\Http\Controllers\API\V1\CountryController@show        | api          |
| DELETE    | api/v1/countries/{country}        | countries.destroy      | App\Http\Controllers\API\V1\CountryController@destroy     | api          |
| PUT/PATCH | api/v1/countries/{country}        | countries.update       | App\Http\Controllers\API\V1\CountryController@update      | api          |
| GET/HEAD  | api/v1/countries/{country}/cities | countries.cities.index | App\Http\Controllers\API\V1\CountryCitiesController@index | api          |
| GET/HEAD  | api/v1/provinces                  | provinces.index        | App\Http\Controllers\API\V1\ProvinceController@index      | api          |
| POST      | api/v1/provinces                  | provinces.store        | App\Http\Controllers\API\V1\ProvinceController@store      | api          |
| GET/HEAD  | api/v1/provinces/{province}       | provinces.show         | App\Http\Controllers\API\V1\ProvinceController@show       | api          |
| PUT/PATCH | api/v1/provinces/{province}       | provinces.update       | App\Http\Controllers\API\V1\ProvinceController@update     | api          |
| DELETE    | api/v1/provinces/{province}       | provinces.destroy      | App\Http\Controllers\API\V1\ProvinceController@destroy    | api          |
