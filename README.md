# API starter kit

This is a simple API starterkit to kickstart your next project.

# Features
- [barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) to help your IDE's code completion.
- [knuckleswtf/scribe](https://scribe.knuckles.wtf/laravel) to document your API.
- [laravel/pint](https://laravel.com/docs/9.x/pint) for code styling.
- [laravel/sail](https://laravel.com/docs/9.x/sail) to easily create a development environment.
- [nunomaduro/larastan](https://github.com/nunomaduro/larastan) for statical analysis of your code.
- [pestphp/pest](https://pestphp.com/) for simplicity in writing tests.
- [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction) to implement role/permission-based access.
- [laravel/sanctum](https://laravel.com/docs/9.x/sanctum) to easily manage your api tokens.
- Token based authentication endpoints.
- CRUD endpoints for users, roles and permissions.
- Pest test coverage for supplied endpoints.
- API docs for supplied endpoints.


## Composer commands
A number of composer commands are included to easily run the included dependencies.
---
### Pint
```bash
composer pint
```
---
### Pest
```bash
composer pint
```
To also generate a coverage file in Tests/Coverage:
```bash
composer pint --coverage
```
---
### Larastan
```bash
composer larastan
```
---
### Code completion
```bash
composer ide-helper
```

# Notes
I do not own any of these wonderful packages myself, I just implemented them.

Be sure to support their developers!
