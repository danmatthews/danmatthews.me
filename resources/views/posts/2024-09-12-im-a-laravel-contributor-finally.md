---
id: 50a44
title: "I'm a Laravel Contributor, finally!"
date: '2024-09-12 11:26:15'
slug: im-a-laravel-contributor-finally
excerpt: "I completely stole someone else's idea and managed to get something merged to Laravel core."
---
So, at 36 years old, after using Laravel since version 3, [I made my first contribution to core.](https://github.com/laravel/framework/pull/52665#issuecomment-2344656676)

And… it wasn’t even an idea of mine, really, it was an idea from [@joshhanley on Twitter 🧵](https://x.com/_joshhanley/status/1834012220151529582) that I nerd-sniped so I could try out the contribution process.

The idea was originally to add a `@when` directive to blade, but it was dialled back to prevent potential confusion, plus - see further down the post for how to add it into your app.

I would love to contribute more to open source, as well as build more packages in my little package repository at github.com/intrfce - but I work a lot of hours a day at my job, passionately, to build features, and it doesn’t leave much time for it.

Thanks, [Josh Hanley](https://x.com/_joshhanley) & [Josh Cirre](https://twitter.com/joshcirre)!

So… yeah, the `when()` helper is now in core as a way of printing or returning something when a condition is truthy:

```php
<div {!! when($isTrue, 'wire:poll.5ms="myMethod"') !!}>
```

## Add the @when directive to blade in your app.

If you want to use this to add a `@when` directive to your own app to use in blade, you can do it by adding this to your app's `ApplicationServiceProvider`, in the `boot()` method.

I've actually added two directives here: `@when` and `@whenRaw` - `@when` will escape the output like `{{ }}` would do in blade, where as `@whenRaw` won't, like blade's `{!! !!}` tag.

```php
<?php

// AppServiceProvider.php

public function boot()
{
    Blade::directive('when', function (string $expression) {
        return "<?php echo e(when($expression)) ?>";
    });

    Blade::directive('whenRaw', function (string $expression) {
        return "<?php echo when($expression) ?>";
    });
}
```
