# Demo for Broadway - EventSourcing library for PHP

This repository contains a demo application to show how [Broadway] can be used within a Symfony application.
The example is taken from the [Practical Event Sourcing][practical-eventsourcing] talk from [Mathias Verraes].

[Broadway]: https://github.com/qandidate-labs/broadway
[practical-eventsourcing]: http://verraes.net/2014/03/practical-event-sourcing.markdown/
[Mathias Verraes]: https://twitter.com/mathiasverraes

## Installation

### Vagrant

```sh
vagrant up
```

Vagrant will provision the machine (using ansible).

The box is then available at `192.168.42.100`, the vhost is configured with domain `broadway.local`.

If you don't have ansible installed, you will have to run `composer install` yourself. And run `app/console broadway:event-store:schema:init` to initialize the database.

### Docker Compose

```sh
docker-compose up -d
```

Docker-compose will set up the containers needed to run this demo.

The app will be available at http://localhost:8000 as configured in `docker-compose.yml`.

## Running the demo

This demo doesn't have a GUI, only an API with the following endpoints:

| Method | Path | Description |
|--------|------|-------------|
| POST | `/basket` | Pickup a new basket, returns the basketId |
| POST | `/basket/{basketId}/addProduct` | Add a product to a basket (productId and productName should be given as form fields) |
| POST | `/basket/{basketId}/removeProduct` | Remove a product from a basket (productId as form field) |
| POST | `/basket/{basketId}/checkout` | Checkout a basket |
| GET | `/advice/{productId}` | Retrieve _Other people also bought this_ list |

## Running the tests

By default we exclude functional tests, by providing `--exclude-group=none` you can run the functional tests as well.

### Vagrant

```sh
cd /vagrant
phpunit --exclude-group=none
```

### Docker Compose

```sh
docker-compose run app vendor/bin/phpunit --exclude-group=none
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

Note that there is a functional test (using ElasticSearch) in `test/BroadwayDemoBundle/Functional`

For more information, read our blog post about this demo: http://labs.qandidate.com/blog/2014/12/30/a-broadway-demo-application/
