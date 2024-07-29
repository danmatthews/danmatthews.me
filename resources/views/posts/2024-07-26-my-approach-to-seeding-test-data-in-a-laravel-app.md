---
id: '68825'
title: 'My approach to seeding test data in a Laravel app'
date: '2024-07-26 10:19:34'
published: false
slug: seeding-test-data-in-a-laravel-app
excerpt: "I'm by no means an authority on testing, but recent articles talking about approaches to seeding and in particular seeding test data have made me want to share my approach."
---
# My approach to seeding test data in a Laravel app

I was reading Joel Clermont's take on [How he likes to set up application seeders](https://masteringlaravel.io/daily/2024-07-26-how-i-like-to-set-up-application-seeders) in his Laravel apps, and - as ever, with Joel being the king of brevity in his posts, and his two point overview is the king of guides:

>1. We use migrations for system-level data that needs to exist in production
>2. We like tests to start with a clean view of the world, and we create the data needed in each test.

## Meet me in the middle.

Starting from scratch is great as long as you've got all your factories set up with sensible defaults and/or  




