---
id: 2ce23
title: 'The invisible draft pattern in Laravel'
tags: ['laravel']
date: '2025-05-08 11:58:35'
slug: the-invisible-draft-pattern-in-laravel
excerpt: "Simplify your UI and UX by leveraging a pattern of skipping having a gigantic 'creation' form and instead jump straight to the 'edit' form for models."
---

The "invisible draft" pattern is something i've used in a few production apps in my time, sometimes for different reasons, but mostly to _control complexity_ and reduce the need for long complex creation forms that can lose data if the user refreshes or doesn't finish completely filling it out before hitting "Create" or "Save".

The concept here is simple - we create a database row with minimal data first, allowing us to "anchor" any further creation work to a persisted row in the database, while still taking into account why someone might "abandon" the creation of a model.

We leverage Laravel's global scopes and model pruning capabilities to clean up incomplete drafts, while also not cluttering the UI with entities that the user either abandoned or didn't fill out the required fields for.

## The Problem

When you’re creating complex creation and editing UIs for your models, you’ll usually need:

- A creation form, where you request all the required values to CREATE your models.
- An edit form where you have to load + display all the data you have already, then use a separate set of logic and validation rules to UPDATE your models.
- For your **creation** form, there is no database row or model ID to associate data with, so doing asyncronous work like adding relations, or uploading images, has to be done in a temporary way - either using another model, database table, or storing things in browser memory - which means it could be lost by someone refreshing the page when filling out a gigantic form.

## An example - creating an event.

Image you’re using an event management system, and you’re about to go in and create a new event.

The event creation form has _dozens_ of fields - name, location, start date, end date, opening times, ticket costs, the ability to add multiple types of tickets etc. And then you can upload images and documents using file upload fields.

You fill out the form, upload your images, add your tickets, and click “submit” - but there are now 4 validation errors.

And on some of the more badly built forms you might even lose your uploads when you submitted, though that's less common now front-end frameworks are used more widely.

## Ask for as little as possible at first

When creating your model, you should **ask for as little information as possible upfront** and provide `nullable` values or sensible derived defaults for the rest.

For an event, this might just be as simple as the title. Then from that, you create your `Event` model.

“What about the dozens of other fields?!” you might ask - simple, either make them `nullable` or fill them with defaults that you can derive from the information you **have** asked for -  the user will more than likely have to change them anyway.

Now you've created a skeleton model, you can allow this person to edit their event any time, at the URL:

```
http://my-event-app.com/events/93983984839819
```

Where `93983984839819` is the database ID. You could also use UUIDs, so it would look like:

```
http://my-event-app.com/events/82a24e54-7bb8-45ef-b03b-1bb950d1bc7f
```

## So why is this better?

The biggest benefit here is simple: now **you only have to build ONE form**: the edit form, including one set of validation rules, and one set of persistence logic. You could even build a multi-step wizard, where each step incrementally saves the provided data to the model as you go.

And now you have a database record and model to anchor related elements:

- If you’re creating multiple ticket prices as separate entities, you can create them and use relations to tie them directly to your already created model.
- You can upload images and associate them with your model directly, either using columns, or something like [Spatie’s media library](https://spatie.be/docs/laravel-medialibrary/v11/introduction) package.
- The entire form is designed to show content if it exists in the fields, or show blank inputs if the information is still required.

## Separating “real” entries from those created accidentally, or on a whim.

Here’s the key to this pattern - when someone creates an event, and **doesn’t** fill out any other fields, or proceeds to NOT edit the entity further, the event is NOT shown in the events listing page.

So if you click “Create” and enter an event name, then immediately hit back, the event won’t be listed there, like it was never created at all, though it does still exist in the database.

How do we do this? we use Global scopes, and a database column to track the state.

## Invisible Drafts

What we’re going to build here is a system that:

- Someone could come in, and create 100+ events, but if they never enter more information than just a title, they won’t be visible, and will be cleaned up by a cron job.

Let’s setup an Enum for our Event “States”:

```php
enum EventState:string
{
	case DraftInvisible = 'draft_invisible';
	case Draft = 'draft';
	case Published = 'published';
	case Hidden = 'hidden';
	case Cancelled = 'cancelled';
}
```

Here you can see two distinct “draft” states - one being `draft_invisible`.

Invisible drafts are excluded from all queries globally, using a [global scope](https://laravel.com/docs/12.x/eloquent#global-scopes):

```php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Enums\EventState;

class ExcludeInvisibleDraftScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->where('state', '!=', EventState::InvisibleDraft);
    }
}

```

Then add this global state to your `Event` model - notice how i’m casting `status` to the enum as well.

```php
namespace App\Models;

use App\Models\Scopes\ExcludeInvisibleDraftScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use App\Enums\EventState;

#[ScopedBy([ExcludeInvisibleDraftScope::class])]
class Event extends Model
{
    public function casts()
    {
         return [
            'status' => EventState::class,
        ];
    }
}
```

Now when you query for your `Event` models, "invisible drafts" won’t be included.

In our UI, we’re now just asking for a title when creating an event:

![](https://i.imgur.com/mPEGUzo.png)

And in our controller, we validate that this title is long enough, and then store it.

I’m just using inline validation here, but you might want to use a form request.

```php
class EventController
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
        ]);
        
        $event = Event::create([
            'title' => $request->input('title'),
			'status' => EventStatus::DraftInvisible,
        ]);
        
        return to_route('events.edit', ['event' => $event]);
    }
}
```

> Note: Yes, you can add `draft_invisible` as the `->default()` value of your database table if you like when creating your migrations, but that ties it to a string that is now out of control of your application and would require a migration to update - but it’s up to you.

Now the `Event` model exists, and you’ve redirected them to the “edit” form.

## Ensuring route binding still works.

Because our global scope is removing all `draft_invisible` results from the query, it won’t be picked up by Laravel’s [route model binding](https://laravel.com/docs/12.x/folio#route-model-binding) without a small change.

In your model file, add the `resolveRouteBinding` function and customise it to exclude our global scope from earlier.

```php
class Event extends Model
{

	// ...


    /**
     * Resolve the route binding for the given value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Use withoutGlobalScopes() to bypass global scopes
        return $this->withoutGlobalScope(ExcludeInvisibleDraftScope::class)
			->where($field ?? $this->getRouteKeyName(), $value)
			->first();
    }
}
```

## State thresholds.

So, you’ve a mostly blank model sitting on an edit page, and a decision to make **when do we make this draft entity visible to the user in the listings** - the “threshold” at which it transitions from invisible to visible **because we’re reasonably sure the user wants to keep this one**.

This can be either as simple as “does the user ever click the save button on the edit form”, which hits the `update` route, regardless of what data they’ve entered:

```php
public function update(Request $request, Event $event)
{
	// ...

    if ($event->status === EventStatus::DraftInvisible) {
        // The user has hit the save button
		// so they probably want to keep this one
		// change it's status to 'draft'
        $event->update(['status' => EventStatus::Draft]);
    }
}

```

Or could be as complex as “has the user filled in this minimum amount of information before proceeding”:

```php
public function update(Request $request, Event $event)
{
    if ($event->status === EventStatus::DraftInvisible) {
        if (
            !empty($event->title) &&
            !empty($event->email) &&
            !empty($event->start_date) &&
            !empty($event->end_date)
        )
        $event->update(['status' => EventStatus::Draft]);
    }
}
```

If the user gets redirected to the edit page, then clicks the back button, and never returns - they’ve effectively abandoned this draft, and we should clean it up after a set period of time.
## Cleaning up drafts.

For those drafts that don’t get taken any further, or were created by mistake, we need to clean those up, thankfully, Laravel’s [model pruning](https://laravel.com/docs/12.x/eloquent#pruning-models) feature comes in here.

In your model, add the `Prunable` trait, and provide a query to the `pruning` function that will clean up ONLY our invisible drafts.

```php
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use App\Enums\EventStatus;

class Event extends Model
{
    use Prunable;

    // ...

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::query()
            ->where('status', EventStatus::DraftInvisible)
            ->where('created_at', '<=', now()->subDay());
    }
}
```

This will delete any invisible drafts that are more than a day old, keeping your database lovely and clean.

You can also adjust this period of time to suit you.

## Are there any drawbacks to this approach?

As with all software development and “patterns” - they have benefits and drawbacks.

The main drawback of this is that you add some complexity with the global scopes, and you may have database rows sitting around unused for a day.

It also might frustrate users if they “create” an entity, and it’s not in the listings page when they go back. But much of this can be mitigated with UI and UX decision making. For example, telling people that it’s not yet been “saved”:


![](https://i.imgur.com/XEhj7dA.png)

## Thanks for reading

If you've enjoyed this article, have any questions, or would like to chat about it - please let me know over on [Bluesky](https://bsky.app/profile/danmatthews.me).
