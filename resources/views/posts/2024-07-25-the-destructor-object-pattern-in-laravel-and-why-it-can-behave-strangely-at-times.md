---
id: 1dbfd
title: 'The destructor object pattern in Laravel and why it can behave strangely at times.'
date: '2023-09-16 09:51:08'
slug: destructor-object-pattern-in-laravel
excerpt: 'Have you noticed that some of the more fluent method chains in Laravel never actually call a "run()" function - let me walk you through why.'
---

# The destructor object pattern in Laravel and why it can behave strangely at times.

Have you ever been using certain features in Laravel and had them behave... unpredictably? I.e. something appears to
fire at the wrong time, or not at all?

Chances are you were dealing with an object that leverages the `__destruct` method in PHP to fire, so that you don't
have to **explicitly** fire it using a method.

For example, Laravel's `PendingDispatch` object, which is returned when queueing something, allows you to chain methods
onto it, but you never have to explicitly tell it to "dispatch" your job:

```php

function myFunction() {
    // $pendingJob becomes an instance of PendingDispatch
    $pendingJob = dispatch(new MyJobClass());

    // And you can call methods on it.
    $pendingJob->onQueue('background-jobs');

    // ...Then you do a hundred other things here...

    // But then you can STILL call further methods on it:
    $pendingJob->delay(now()->addMinutes(2));
}

```

And after that, it will just dispatch the queued job ... but why, when, and how?

## First, let's consider the mechanism of dispatching a job:

Dispatching a job, as an example, what is actually going on there?

A job is just a PHP class, and laravel needs to do two things:

1) Serialize it so it can be stored.
2) Store it to our desired queue (database, redis, SQS etc).

These are _actions_ that need to be performed, so naturally we'd need to call functions to do both of these.

But once we've done it, doing things like the above - changing the queue to execute it on, or setting a delay - would
involve updating that object in storage, an expensive operation.

So for convenience, we need to:

1) Allow you to make changes to the object the entire time the object is in scope.
2) Dispatch that job automatically, without having to call a method manually, at the last possible convenient moment.

## Enter the `__destruct` method in PHP

You've written many PHP classes and used the `__construct()` method loads of times, but it has a
counterpart: `__destruct`.

Destruct is called when PHP's memory manager (or "garbage collector") cleans up objects, or if you manually
call `unset($objectInstance);` - it exists to allow you to do things to "clean up" things you may have done during the
lifecycle of your object.

For web-based programs at least, it also means that you know there's a predictable end to most program executions: when
the request ends and the response is served to the user.

But also, PHP will intelligently 'clean up' objects to free up memory when they fall out of scope of a function, so we
can also rely on that in longer running programs such as the CLI.

It's just a regular PHP function, so you can do anything within it, and your object isn't cleaned up until the method is
finished executing, so you can still invoke methods from inside your class in it.

This is directly from the `PendingDispatch` class in Laravel, but i've added some comments to help illustrate what's
going on.

```php
    public function __destruct()
    {
        if (! $this->shouldDispatch()) {
			// This will return without doing anything if for some reason
            // the job does not need to be dispatched any more.
            return;
        } elseif ($this->afterResponse) {
            // This will hold the object once more until we've sent a response
            // then it'll dispatch it.
            app(Dispatcher::class)->dispatchAfterResponse($this->job);
        } else {
            // Dispatch the job, which saves it to the DB/Redis/SQS
            app(Dispatcher::class)->dispatch($this->job);
        }
    }
```

## "That's out of order!"

Where things can get really weird is when expect objects with this behaviour to run their actions in the same order the
code is written.

> In my experience one of the most obvious places this happens is when writing tests and using the `sync` queue driver

Similarly, you can demonstrate this behaviour by setting your `QUEUE_CONNECTION` to `sync` in your `.env` file and
writing some code that fires a queued job:

```php

// App/Jobs/StepThreeJob.php
class StepThreeJob {
  public function handle() {
    echo 'Step Three';
  }
}

// Just a test route
Route::get('/test', function() {
  
  echo 'Step 1';

  echo 'Step 2';

  // our job class that echoes "Step 3"
  dispatch(new StepThreeJob);

  echo 'Step 4;

});

```

Chances are, if you run this, it would output:

```
Step 1
Step 2
Step 4
Step 3
```

Weird right?

**It's because, our job class, even though we dispatched it third, sticks around until PHP cleans up the object, fires
the `__destruct` method, and actually dispatches the job.**

To demonstrate how to 'fix' this (in the wrong way), we can use `unset()` to clean up the object early:

```php
Route::get('/test', function() {
  
  echo 'Step 1';

  echo 'Step 2';

  // our job class that echoes "Step 3"
  $myPendingJob = dispatch(new StepThreeJob);
  unset($myPendingJob);

  echo 'Step 4;

});
```

This should output things in the right order - `Step 1`, `Step 2`, `Step 3`, `Step 4`.

But of course, this both looks horrible and _feels wrong_, so instead, we can use `dispatch_sync` to choose to "fire"
the job immediately, this will work even if your queue connection is set to a regular queue runner
like `database`, `redis` or `sqs` too:

```php
Route::get('/test', function() {
  
  echo 'Step 1';

  echo 'Step 2';

  // our job class that echoes "Step 3"
  dispatch_sync(new StepThreeJob);

  echo 'Step 4;

});
```

And this, too, would echo out in the right order: `Step 1`, `Step 2`, `Step 3`, `Step 4`.

It's a contrived example, **but it demonstrates what's going on and how "unset" can be used to trigger the behaviour
early.**
