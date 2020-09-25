# HOOTS Manager

Application to help in managing Magic: The Gathering tournaments ran on [MTGMelee](https://mtgmelee.com/).

Built to support [HOOTS](https://www.jeffhoogland.com/hooglandia-open-series/) - the Hooglandia Open Official Tournament Series.

## Features

The application is in a very early development stage. The currently supported features are:

-   importing standings as CSV,
-   adding custom tags to players,
-   adding custom formulas (using player data and the tags added) creating a custom scoring system which can be used to e.g. pick the "feature matches" to show on broadcast

## The stack

-   [Laravel](https://laravel.com/)
-   [Pest](https://pestphp.com/)
-   [Vue.js](https://vuejs.org/)
-   [Inertia.js](https://inertiajs.com/)
-   [tailwindcss](https://tailwindcss.com/)
-   [heroicons](https://heroicons.com/)

## Installation

Clone the repo locally:

```sh
git clone https://github.com/ibniss/hoots-manager.git hoots-manager
cd hoots-manager
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
yarn
```

Build assets:

```sh
yarn dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go!
