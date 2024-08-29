---
id: 7c9f6
title: 'Get the new Laravel defer() helper today.'
date: '2024-08-28 09:58:22'
slug: get-the-new-laravel-defer-helper-today
excerpt: 'The new defer() helper is coming to Laravel soon. But you can get it today by writing this little helper function yourself.'
---

**Update 29th Aug. 24:**

[Newton Job](https://x.com/_newtonjob) on Twitter pointed out that the `dispatchAfterResponse` function ALWAYS
uses the `sync` queue driver - so you don't need to add the `->onConnection('sync')` method.

From the docs:

>This should typically only be used for jobs that take about a second, such as sending an email. Since they are processed within the current HTTP request, jobs dispatched in this fashion do not require a queue worker to be running in order for them to be processed

**Original Post:**

Laracon US has had its first day, and mixed in with the BIG announcements were some awesome little tidbits, including the new `defer()` helper, that allows you to push work into the background while sending a swift response to your users.

BUT, you can do this TODAY, but using the existing queue functionality (even if you don't have queues configured!).

## Dispatch After Response.

The core of this is the `dispatchAfterResponse` method on queues, this example from the docs shows it off.

```php
use App\Jobs\SendNotification;

SendNotification::dispatchAfterResponse();
```

The job won't be dispatched until AFTER the response has been sent to the user by NGINX.

But, you can also queue a closure:

```
dispatch(function () {
    Mail::to('taylor@example.com')->send(new WelcomeMessage);
})->afterResponse();
```

So we can do ANYTHING and dispatch it after the response has been sent, without having to create a job class for it.

## But I don't have queues set up, and i don't want the fuss!

That's fine! If you've not got queues set up, you'll likely be using the `QUEUE_CONNECTION=sync` setting in your `.env` file anyway, that means this will JUST WORK straight away.

## But I already have queues set up and I don't want to ACTUALLY push this to the queue.

Luckily, you can set the queue connection you want to dispatch things to at runtime, so do this!

```
dispatch(function () {
    Mail::to('taylor@example.com')->send(new WelcomeMessage);
})
    ->onConnection('sync')
    ->afterResponse();
```

And this will just run the job after the response is sent without adding it to your already existing queues.

## Wrapping it in a helper.

All you need to do is to wrap this in a helper function to clean things up a bit, you can put helpers in a specific `app/helpers.php` file, and add it to `composer.json` in the `autoload` section:

```json
"autoload": {
    "files": [
        "app/helpers.php"
    ],
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
},
```

Then run `composer dumpautoload` to load the file.

Now you can define the helper:

```php
<?php # helpers.php

if (!function_exists('defer')) {
    function defer (Closure $toDefer) {
        dispatch($toDefer)
        ->onConnection('sync')
        ->afterResponse();
    }
}
```

And now you can use it in your code:

```php
Route::get('/test', function() {
    defer(fn () => Log::info("This should be logged second"));
    Log::info("This should be logged first.");
    return view('welcome');
});
```

And there you go!
