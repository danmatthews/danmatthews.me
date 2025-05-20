---
id: 13b26
title: 'Building a trunk based development workflow in a small team'
date: '2025-05-20 10:40:40'
slug: trunk-based-development
excerpt: 'One thing became evident as our team grew: the existing git-flow-like workflow of putting all our work in progress on our dev branch, then merging to main when it came time to do a release, just wasn’t working.'
---
We’ve spent about four years working on Social Sync now - in that time, the development team has grown from just me, to 5 full time developers, and we’re constantly collaborating with trusted contractors.

One thing became evident as we grew: the [existing](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow) `git-flow` like workflow of putting all our work in progress on our `dev` branch, then merging to `main` when it came time to do a release, just wasn’t working. Why? 

First, let’s talk about `git flow` and the system we used, roughly:

- `main` is your production branch and is always deployed/deployable.
- `dev` is where all your work in progress for the next release goes, and is deployed to the staging site so new features are available there.
- `feature/{x}` branches are where you developer larger features, then PR them into `dev` after review/discussion.
- Fixes to production bugs would be done on `main` directly, or larger ones would pull a `hotfix/{x}` branch off `main` then be PRd and reviewed / discussed. You would then either cherry pick the commit from `main` to `dev`, or merge the branch into `dev` as well.

A release would usually involve merging `dev` into `main`, and then deploying, sounds simple right? But…

### Huge Merges are a pain.

This is an understatement, if you’re trying to merge 3 months worth of work into an effectively stale branch (`main`), that has been receiving bugfixes, then you’re going to hit some huge conflicts.

This became even more painful and problematic as we became a larger team - it was no longer possible for me to manually review the conflicts and recognise what was supposed to make it into `main` and what was supposed to get left behind, meaning that during the release, we’d have to draft each team member in, and it became an incredibly slow process.

This includes conflicts in updated tests, too.

### Our staging site is often diverged too far from the currently deployed `main` branch.

Our staging site contained the code from the `dev` branch, so testing anything on staging before it went live meant that the codebase might look very different once that feature went live. We weren’t testing as close to production as possible.

### Sometimes hotfixes and fixes are not merged to the development branch, or missed.

When we wanted to fix a bug in production, we’d usually check out the `main` branch, fix the bug, and push that to production.

The fix would then be cherry-picked to `dev` , but the branch was sometimes different by a number of weeks, so the fix on `main` might not look identical to the one that needs to go onto `dev`  to solve the same problem.

## Along came trunk based development.

There’s an entire [site](https://trunkbaseddevelopment.com/) dedicated to trunk based development, but i’ll try and give you the overview of _our_ take on it, as well as why we chose the path we did.

> By the way, trunk based development ALONE wasn’t enough to solve all of our problems - Feature Flags were a huge shift in thinking for us, and i’ve got [Tom from Few and Far](https://www.few-far.co/) to thank for that - he did some work for us and taught us the ways of the feature flag.


## All work is done directly on the main branch.

This is a HUGE shift from before - all our work is done directly (with a few caveats i’ll discuss shortly) on the `main` branch, this means that `main` is what is deployed to our production AND staging sites, so they’re as close as possible.

## The production branch should always be deployable.

The goal is to keep `main` deployable - meaning anyone can do a release at any time without messing up production. This is mainly done by hiding new functionality behind [feature flags](https://martinfowler.com/articles/feature-toggles.html) and ensuring that migrations that DO run don’t mess with production data.

> I also contributed a `shouldRun` method [to the migrations class in Laravel](https://laravel.com/docs/12.x/migrations#skipping-migrations) that means you can skip migrations depending on a boolean condition - like a feature flag!

This doesn’t always go to plan - sometimes something has to go to `main` to allow other work to continue alongside it or on top of it, but for the most part, this is the goal.

## Feature work happens in short-lived branches.

If you need to work on a feature and really get your teeth into something you can still pull a feature branch from `main` - but with a few conditions:

- A branch should never live for more than a few days, if it does, can it be split into multiple short lived branches.
- Before merging your branch into `main`, ensure that it isn’t going to break anything, and will not break production if it’s released.

## Releases are branches pulled from main.

When we release, we pull a release branch from `main` and call it `release/v{major}.{minor}.{patch}` - this is then set as the release branch in [Envoyer](https://envoyer.dev) and deployed to live.

This allows us to keep working on `main` without fear of _accidentally_ wrecking the deployed production branch.

## Bug fixes are performed on main, and cherry-picked to the current release branch.

Because the difference between production and `main` is now minimal, hotfixes can be performed directly on `main` and cherry-picked to production. _Sometimes_ a difference between the WIP on `main` neccessitates a slightly different fix directly on the release branch, but it’s much less common than it was on our other workflow.


## Outcomes:

Anyone can now initiate a release of new features and functionality with a quick go/no-go from the team on if their latest work is deployable.

Most new features are hidden by feature flags, and their migrations will run in the background with no impact on production data.

If we _do_ need to run a deploy that alters a lot of data, or changes the structure of something that might have a detrimental effect - or something hard to test in staging, then we can plan downtime and/or skip that migration until we’re ready.
