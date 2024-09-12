---
id: 50a44
title: "I'm a Laravel Contributor, finally!"
date: '2024-09-12 11:26:15'
slug: im-a-laravel-contributor-finally
excerpt: "I completely stole someone else's idea and managed to get something merged to Laravel core."
---
So, at 36 years old, after using Laravel since version 3, I made my first contribution to core.

And… it wasn’t even an idea of mine, really, it was an idea from [@joshhanley on Twitter 🧵](https://x.com/_joshhanley/status/1834012220151529582) that I nerd-sniped so I could try out the contribution process.

I would love to contribute more to open source, as well as build more packages in my little package repository at github.com/intrfce - but I work a lot of hours a day at my job, passionately, to build features, and it doesn’t leave much time for it.

Thanks, Josh & Josh!

So… yeah, the `when()` helper is now in core as a way of printing or returning something when a condition is truthy:

```php
<div {{ when($isTrue, 'wire:poll.5ms="myMethod"') }}>
```