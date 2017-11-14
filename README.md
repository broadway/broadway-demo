# Demo for Broadway - EventSourcing library for PHP

This repository contains a demo application to show how [Broadway] can be used within a Symfony application.
The example is taken from the [Practical Event Sourcing][practical-eventsourcing] talk from [Mathias Verraes].

[Broadway]: https://github.com/broadway/broadway
[practical-eventsourcing]: http://verraes.net/2014/03/practical-event-sourcing.markdown/
[Mathias Verraes]: https://twitter.com/mathiasverraes

For simplicity the demo uses the official [DBAL event store] and a custom DBAL read model implementation.
You will need to have [SQLite] installed to run the demo.

[DBAL event store]: https://github.com/broadway/event-store-dbal
[SQLite]: https://www.sqlite.org/

## Running the demo

```
composer install
app/console broadway:event-store:schema:init
app/console broadway:read-model:create
app/console server:run
```

This demo doesn't have a GUI, only an API with the following endpoints:

| Method | Path | Description |
|--------|------|-------------|
| POST | `/basket` | Pick up a new basket, returns the basketId |
| POST | `/basket/{basketId}/addProduct` | Add a product to a basket (productId and productName should be given as form fields) |
| POST | `/basket/{basketId}/removeProduct` | Remove a product from a basket (productId as form field) |
| POST | `/basket/{basketId}/checkout` | Check out a basket |
| GET | `/advice/{productId}` | Retrieve _Other people also bought this_ list |

```bash
# pick up a new basket
$ curl -X POST http://localhost:8000/basket
{
  "id":"1bd683ac-f75d-403f-babc-82ddcdb33de7"
}

# add products to the basket
$ curl -d "productId=2009&productName=Incredibad" -X POST http://localhost:8000/basket/1bd683ac-f75d-403f-babc-82ddcdb33de7/addProduct
$ curl -d "productId=2011&productName=Turtleneck+%26+Chain" -X POST http://localhost:8000/basket/1bd683ac-f75d-403f-babc-82ddcdb33de7/addProduct
$ curl -d "productId=2013&productName=The+Wack+Album" -X POST http://localhost:8000/basket/1bd683ac-f75d-403f-babc-82ddcdb33de7/addProduct

# remove a product from the basket
curl -d "productId=2009" -X POST http://localhost:8000/basket/1bd683ac-f75d-403f-babc-82ddcdb33de7/removeProduct

# check out the basket
$ curl -X POST http://localhost:8000/basket/1bd683ac-f75d-403f-babc-82ddcdb33de7/checkout

# get _Other people also bought this_ list
$ curl http://localhost:8000/advice/2011
{
  "purchasedProductId": 2011,
  "otherProducts": {
    "2009": 1,
    "2013": 1
  }
}
```

## Running the tests

To run all the tests:

```
./vendor/bin/phpunit
```

## Code structure

- Domain code can be found in `src/BroadwayDemo/Basket`
- ReadModel code can be found in `src/BroadwayDemo/ReadModel`
- Controller / services can be found in `src/BroadwayDemoBundle`

Note that there are two files for the services: `services.xml` and `domain.xml`.
`domain.xml` contains all the domain-specific services (CommandHandler,
ReadModels, Repositories), while `services.xml` contains domain-unspecific
services (controllers etc).

The domain specific tests can be found in `test/BroadwayDemo/Basket` and `test/BroadwayDemo/ReadModel`

Note that there is a functional test in `test/BroadwayDemoBundle/Functional`

For more information, read our blog post about this demo: http://labs.qandidate.com/blog/2014/12/30/a-broadway-demo-application/
