---
id: 0b93f
title: 'Why we won’t be using Laravel Cloud (just yet)'
date: '2025-03-19 01:54:06'
tags: ['laravel']
slug: why-we-wont-be-using-laravel-cloud-just-yet
excerpt: "I work through a few shortcomings of Laravel Cloud that don't make it a great fit for us just yet."
---

> Note: i'm very aware of the slight irony that this blog is hosted on Cloud, while writing a post on why it's not suitable for us at work yet.

I was so excited to move to [Laravel Cloud](https://cloud.laravel.com) at [Social Sync](https://socialsync.app).

It was announced at the perfect time for us - we were needing to move away from our Forge based setup that was hard to scale to match Spiky workloads, but it brough the promise of not needing to adapt your application to the unique constraints of serverless like we would have with [Laravel Vapor](https://vapor.laravel.com).

Pre-launch, the Laravel team was _more_ than accomodating with us when we reached out to see if we could use Cloud with our third-party database - Singlestore (thank you Chris + Joe!) - and it will work, which is the good news.

But during my testing i’ve found that it’s not quite there with what we need just yet for production, and while i’m excited to move there, here are some of the shortfalls we’ve found while evaluating the service.

## Horizon support

We use horizon + redis for our queue workloads, processing upwards of 10k jobs during a normal 60 minute period, and that can go up to the 100k+ jobs per hour during our email and SMS scheduled sending periods.

I created a worker cluster and added a background process running `php artisan horizon`, and ran a deploy.

It didn’t *seem* to start the daemon on deploy, and the horizon dashboard didn’t seem to register as running, not entirely sure why - all the connection settings were correct with the built in KV/Redis compatible store, and no errors were reported in the logs.

I’d also love it if this was a one-click setup that didn’t require putting Horizon in as a background process + restarted it for me when i run a deploy automatically (although, that’s a nice to have).

## Pricing visibility.

First of all, the usage and pricing visibility is still being developed and a few parts are marked as “coming soon” as of the writing of this article, i think i’ll be more comfortable when those have launched.

Some billing alerts and spend controls would be great too, i’d love to be able to turn infinite autoscaling on and be able to receive billing alerts when the usage hits concerning levels.

## Queue autoscaling controls

This one is a massive **nice to have** as I think the strategy they’re using will suffice for most workloads, but I for one would love for the ability to scale up using queue time as a metric - jobs taking longer than usual? apply more power / workers!

## No support for a dedicated queue “cache” instance yet.

We run a LOT of stuff in our queues via redis, our current setup is set to snapshot to disk so that if there’s a disaster, it will recover our workload from disk, whereas our separate cache redis instance is set to NOT do that to allow faster workloads - and cache data is ephemeral.

I’d be worried that the combined KV store offering from Cloud isn’t persisting or snapshotting our queue jobs, and using the database for this is out of the question from a throughput perspective for us.

We *could* move to SQS, or set up a dedicated postgres database for this, but we love the power Horizon gives us over worker counts, visibility and metric recording.

## Slow(er) Deploys

Alright, this heading is a little click-bait-y, but i’ll explain:

We’ve found deploys to be slow for our application - while I **know** that this isn’t something for all projects, I do find that there’s a bit of a black box around parts of the deploy process.

I thought it was just our `npm` process that was slowing it down, but even upon removing our JS build process entirely, some deploys took upwards of 5 minutes, with large parts of that spent on parts of the process that didn’t seem to have anything to do with our app.

For reference, we are using `pnpm` and, running `npx pnpm run build` - so that could have something to do with it.

Some deployment times ~including~ `npx pnpm run build`:

- 7m 59s
- 6m 18s
- 9m 21s

Now, to be fair, running `npx pnpm run build` locally DOES take nearly two minutes:

```
npx pnpm run build  101.92s user 17.42s system 100% cpu 1:59.02 total
```

But even deployment times ~without~  `npx pnpm run build`:
- 3m 48s
- 3m 45s
- 3m 40s

Compare this with our Envoyer deploy times to 6 separate worker and web servers (without the node build process):

- 96 Seconds (1m 36s)
- 106 Seconds (1m 46s)
- 98 Seconds (1m 38s)
- 97 Seconds (1m 37s)

I ~know~ that this will improve with time, even for our non-npm deploys, but for now, it’s terrifying waiting that long.

This leads nicely into my next issue…

## Unknowns around the build + deploy process.

Our issue with this is probably quite unique and _might_ just have been a bug, but I can see it messing with other people’s workflows too.

We use [Lasso](https://github.com/Sammyjo20/lasso) to build our JS artifacts locally, push them to S3, and then deploy them to our production server.

Currently, we have `php artisan lasso:pull` in our deployment script on Cloud - this should run _just_ before the new traffic is routed to our new release as it’s last on the list.

But here’s the issue, the command runs fine - which on Envoyer would mean that the build files have been pulled, unzipped, and moved to their appropriate locations for release.

But when Cloud pushes our release live, we’re hit with the “Manifest not found” 500 error, and our users are greeted with a server error page.

If we run the command manually _after_ release, everything is fine.

So that leads me to wonder if Cloud is doing something like only copying certain files - or those committed to the git repo, across when moving the project live.

It’s a very specific to us (in this case), but very annoying, problem.

## Build asset caching.

One thing I think Cloud needs deperately in this day and age of JS-heavy applications is build caching or “resource change detection” - I love the fact that in our CI pipeline we can detect if files have changed in a certain path or directory in our app, and only trigger certain workflows if those files have changed at all.

The ability to store a cached build of our JS/CSS assets and re-use them on future releases where no changes have been detected would be huge and would speed up deployments dramatically, the same could potentially be said for our composer assets and vendor folder, though i’m sure there’s something being utilised there already.

## Slack + Email Notifications

We love Envoyer’s deployment notifications - we get Slack messages when our Staging and Production environments both succeed and fail deployments, and this is something i’m sure the team are working on.

## VPN/Tailscale Integration

This isn’t a deal breaker at all, but would sure be nice.

I know this one has been mentioned by a few others over on Bluesky but we’d love to be able to integrate Cloud with our VPN so we can limit access to staging environments to our internal team only.

## Overall

I know this is essentially a bunch of “missing” features that i’ve listed, but it is a gorgeously designed platform that I think will only improve with time and feedback.

It’s also worth saying that - we’re not a ‘typical’ Laravel user, we’re using a third party database and a bunch of processes that fit our small distributed team that might not seem to fit into the ‘usual’ 90% of use cases.