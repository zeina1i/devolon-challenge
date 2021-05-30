
# Devolon Challenge (Cart Service)

My demo for interview with Devolon. This app contains a dockerized web app which designed to easily install and configured on any machine.

## Installation Requirements
* Shell Access
* Docker
* Docker Compose
* Git
* Make
* CURL

## Docker Services
* NGINX Reverse Proxy
* PHP FPM
* MySQL Database

## Installation Guide:
```
cd ~
git clone git@github.com:zeina1i/devolon-challenge.git
cd devolon-challenge
cp .env.example .env
make setup
```

Now app should be alive on [http://localhost:8090](http://localhost:8090)
<p align="center"><img src="./devolon-cart-service.png"></p>

## DataModel
<p align="center"><img src="./devolon-datamodel.png"></p>

## Routes
<p align="center"><img src="./devolon-routes.png"></p>

## How To Use App:
Issue these sets of command inside your terminal:
<br />
### Cart
Create an empty cart:
```
curl --location --request POST 'http://localhost:8090/api/v1/cart/create/' \
--header 'Content-Type: application/json' \
--data-raw ''
```

Add item to cart:
```
curl --location --request POST 'http://localhost:8090/api/v1/cart/add-item' \
--header 'Content-Type: application/json' \
--data-raw '{
    "product_id": 1,
    "cart_id": 1
}'
```

Change quantity of existing item in cart:
```
curl --location --request POST 'http://localhost:8090/api/v1/cart/change-quantity' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "product_id": 1,
    "cart_id": 1,
    "quantity": 4
}'
```

Remove item from cart:
```
curl --location --request DELETE 'http://localhost:8090/api/v1/cart/remove-item' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "product_id": 1,
    "cart_id": 1
}'
```

### Product
Create product:
```
curl --location --request POST 'http://localhost:8090/api/v1/product' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "title": "Huawei",
    "unit_price": 21
}'
```
Update Product:
```
{
    "product_id": 1,
    "title": "Huawei",
    "unit_price": 21
}
```
Delete Product:
```
curl --location --request DELETE 'http://localhost:8090/api/v1/product' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "product_id": 3
}'
```
### Special Price
Create Special Price
```
{
    "product_id": 1,
    "quantity": 3,
    "price": 100
}
```

Update Special Price
```
{
    "id": 1,
    "product_id": 1,
    "quantity": 4,
    "price": 100
}
```

Delete Special Price
```
curl --location --request DELETE 'http://localhost:8090/api/v1/special-price' \
--header 'Content-Type: text/plain' \
--data-raw '{
    "product_id": 1,
    "quantity": 4
}'
```
## Commands
In order to run commands easily you can use make command. This will help you to easily interact with containers or app.
<p align="center"><img src="./devolon-commands.png"></p>

# Logs path
We store api errors logs in the bellow path:
```
./storage/logs/laravel.log
```

## Run Tests
```
make phpunit
```