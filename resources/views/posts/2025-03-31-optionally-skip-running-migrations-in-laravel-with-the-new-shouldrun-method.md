---
id: 6c634
title: 'Optionally skip running migrations in Laravel with the new ‘shouldRun’ method'
date: '2025-03-31 12:08:55'
slug: optionally-skip-running-migrations-in-laravel
excerpt: 'A week or so ago, I contributed something Laravel i’ve wanted for a while - a way to skip running migrations unless a certain criteria is met.'
---

A week or so ago, I [contributed something](https://github.com/laravel/framework/pull/55011) to Laravel i’ve wanted for a while - a way to skip running migrations unless a certain criteria is met.

For [us](https://socialsync.app), this means that we can ship new feature code to production, but not neccessarily run potentially breaking migrations in production unless a feature flag is enabled before deployment, or keep certain migrations that only run on development/local/staging environments for holding debugging information etc.

## The new `shouldRun` method

When you generate a migration, you can now optionally include this method:

```php
public function shouldRun(): bool
{
	return true;
}
```

If the value is `false`, it will SKIP the migration when you run `php artisan migrate` or any similar command, INCLUDING `php artisan migrate:rollback`.

Some sample use cases for this might include:

### Only run a migration when a feature flag is enabled.

You could leverage [Laravel Pennant](https://laravel.com/docs/12.x/pennant#main-content) to determine if a migration should run - depending on the status of a feature flag.

```php
public function shouldRun(): bool
{
	return Feature::active(MyFeature::class);
}
```

### Only run certain migrations in local, staging, or production.

You can choose to only run certain migrations in certain environments:

```php
public function shouldRun(): bool
{
	// Only run this in a local environment
	return app()->environment('local');
}
```

Or only in non-production environments:

```php
public function shouldRun(): bool
{
	// Only run this in any non-production environment
	return ! app()->environment('production');
}
```

...And many more. If you end up using this - let me know, it always makes me happy when I can see something i've contributed being useful to others.

