# Support Desk V1 — base Laravel (copiar sobre un proyecto Laravel 11+)

1. `composer create-project laravel/laravel support-desk` y configurar `.env` con PostgreSQL.
   Para V1 usa `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=sync` (sin Redis ni colas).
2. Copiar `app/` y `database/` sobre el proyecto (aplicar `User.patch.md` a `User.php`).
3. `php artisan migrate`
4. `php artisan db:seed --class=ServiceCategorySeeder`
5. `php artisan support:install`
6. Siguiente: formulario público (controller + Blade), Filament (Settings, Categorías, Usuarios) y vistas de tickets.

Uso rápido:
```php
$ticket = app(TicketService::class)->create([...]);
$link   = app(WhatsAppLinkBuilder::class)->toCompany($ticket);   // botón "Enviar por WhatsApp"
$reply  = app(WhatsAppLinkBuilder::class)->toRequester($ticket, 'closed');
```
