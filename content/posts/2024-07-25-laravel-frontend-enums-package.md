---
id: 114d5
title: 'Laravel Frontend Enums Package'
date: '2023-10-24 09:48:43'
slug: laravel-frontend-enums-package
tags: ['laravel']
excerpt: "I've just published the first release of a little package to help you publish your PHP Enums to the frontend for use in your InertiaJS applications or with other libraries like Alpine.js."
---

# Laravel Frontend Enums Package

I've just published the first release of [Laravel Frontend Enums](https://github.com/intrfce/laravel-frontend-enums) - a
little package to help you publish your PHP Enums to the frontend for use in your InertiaJS applications or with other
libraries like Alpine.js.

## tl;dr

Install the package:

```
composer require intrfce/laravel-frontend-enums
```

Add the PHP enums you want to publish to the boot() method in your `AppServiceProvider.php`:

```php
PublishEnums::publish([
    EmailTypes::class,
  ])->toDirectory(resource_path('js/Enums'));
```

Run the publish command:

```
php artisan publish:enums-to-javascript
```

Import and use the enums in your JS code:

```js
import {EmailTypes} from './Enums/EmailTypes.enum.js';

if (emailType === EmailTypes.WORK) {
    console.log("This is a work email");
}
```

## Why?

We use PHP Enums a lot at SocialSync - they're fantastic for
removing [magic strings](https://en.wikipedia.org/wiki/Magic_string) and similarly - Magic Numbers, from your
application code, and making it so that any change to a string or integer value like this is reflected in code and not
config, or database data.

Take this bit of code:

```php
// Data
$email = [
	"email" => "me@myname.com",
  	"type"  => "work_email",
];

// Laravel/PHP Application Code:
if ($email['type'] === "work_email") {
	// Maybe don't send the email?
}
```

See the problem here? `"work_email"` is an arbitrary value that you might want to check in a few places, including on
the front end:

```js
let data = await fetch('http://my-api.com/get').json();

if (data[0].type == 'work_email') {
    alert("This is a work email!");
}
```

But if someone changes `work_email` to `work`, it can be a small change that can mess up a bunch of code on the front
and back end, your tests might catch it if you have them.

Using an enum gives you a bit more resiliency:

```php
// Enum Definition
enum EmailType:string {
  case WORK = 'work_email';
  case PERSONAL = 'personal_email';
}

// Data
$email = [
	"email" => "me@myname.com",
  	"type"  => EmailType::WORK->value,
];

// In your application code:
if ($email['type'] === EmailType::WORK->value) {
	// Maybe don't send the email?
}
```

Now, if you change the backing string behind your type:

```php
case WORK = 'work';
```

Your comparison still stands! And a little bonus: you'll get IDE completion and hints, along with some type safety on
the PHP side.

## Getting these values into Javascript

This package allows you to publish selected PHP enum files to Javascript without having to manually maintain them.

This is especially awesome if you're working with an InertiaJS application. But could also be handy if you're working on
a NextJS application alongside your Laravel app as an SPA - it works either way.

The bonus of this is that you can do direct comparisons to the set enum value in JS rather than maintaining your magic
strings or integers directly.

To borrow from both the tl;dr section above and the email example we've been working on:

```js
import {EmailTypes} from './Enums/EmailTypes.enum.js';

if (emailType === EmailTypes.WORK) {
    console.log("This is a work email");
}
```

## Roadmap:

- Typescript support soon.
- The ability to use a `@enums` blade directive to attach the enums to the window variable if you're using Alpine.js or
  similar.
- A bunch of options for keeping your JS enum files up to date with your PHP ones without having to manually run the
  command.

Dont' forget to let me know what you think on [twitter](http://twitter.com/danmatthews)!
