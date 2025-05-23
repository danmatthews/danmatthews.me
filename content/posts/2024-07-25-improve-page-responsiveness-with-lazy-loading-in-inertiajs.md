---
id: 56ce2
title: 'Improve page responsiveness with lazy loading in InertiaJS'
date: '2023-08-16 09:56:56'
tags: ['laravel', 'inertiajs']
slug: improve-page-responsiveness-with-lazy-loading-in-inertiajs
excerpt: 'Lazy loading is one of the biggest and best features of InertiaJS.'
---

# Improve page responsiveness with lazy loading in InertiaJS

Part of the attractiveness of A javascript single page app vs. the more traditional server-rendered ones is a perception
of speed for the users - page loads and navigation can feel instantaneous while presenting loading states to the user to
show them that something's happening, rather than just waiting for the entire page to load.

We're currently using InertiaJS, which allows us to marry all the best parts of a single page app with the server-side
routing and data management benefits of a more traditional javascript app.

Now normally you'd pass your data to Inertia by passing data in a similar way you'd pass it to blade views:

```php
return Inertia::render('MyComponent.svelte', [
    'name' => auth()->user()->name,
    'posts' => $posts,
]);
```

And in our frontend component:

```js
<script>
    // Grab the data as props.
    export let user = undefined;
    export let posts = [];
</script>

{#each posts as post}
    <div>
        <h2>{post.title}</h2>
    </div>
{:else}
    <p>No Posts Yet</p>
{/each}
```

Simple right? But what if `$posts` is a complicated query takes 3-4 seconds to pull from the database? Laravel won't
even send anything to the browser until it's completed that query. Your users will see a blank screen, or even worse,
it'll just look like they've stayed on the same page and nothing has happened.

Now normally, we'd fix this by returning the empty page skeleton, and then making an AJAX request - usually with
something like Axios, to retrieve our data.

This approach has a few drawbacks:

1. You usually have to register another, separate route to make the request to which means you have to write a separate
   controller method or handler.
2. The new controller or handler doesn't automatically have the context from the current page load, so you have to push
   that context (any query arguments or route parameters), in the ajax request.
3. All of that is extra code to maintain and test.

Lazy loading solves this by allowing you run a [partial reload](https://inertiajs.com/partial-reloads#top) on data that
wasn't included at all during the initial page load. The Inertia documentation has
a [great guide](https://inertiajs.com/partial-reloads#lazy-data-evaluation) on how to set up data for lazy loading:

```php MyController.php
return Inertia::render('Users/Index', [

    // ALWAYS included on first visit
    // OPTIONALLY included on partial reloads
    // ALWAYS evaluated
    'users' => User::get(),

    // ALWAYS included on first visit
    // OPTIONALLY included on partial reloads
    // ONLY evaluated when needed
    'users' => fn () => User::get(),

    // NEVER included on first visit
    // OPTIONALLY included on partial reloads
    // ONLY evaluated when needed
    'users' => Inertia::lazy(fn () => User::get()),
]);
```

You can see the big difference with the third approach is that it's "NEVER included on the first visit". This makes your
initial page load much faster, and allows you to set up a
spinner, [skeleton](https://www.google.com/search?q=ui+skeleton), or loading component of your own to show that data is
being loaded in.

To do this, first, wrap any large datasets or queries in the `Inertia::lazy()` function:

```php MyController.php
return Inertia::render('MyComponent.svelte', [
  'name' => auth()->user()->name,
  'posts' => Inertia::lazy(function () {
    return Post::query()
      ->with('author')
      ->where('published','=', 1)
      ->limit(50)
      ->get();
  }
]);
```

Now, because this is lazy-loaded, in our javascript component, the prop will have whatever initial value we set. Here,
i'm setting it as `undefined` by default.

```svelte
<script>
    export let posts = undefined;

    // mounted() in VueJS / useEffect() in React
    onMount(() => {

        // This will show the variable as empty.
        console.log(posts);

        // We fire a partial reload to load the data in:
        Inertia.reload({
            only: ['posts']
        });
})
</script>

<!-- In our template, we determine the state  -->
<!-- of the posts variable to show a loader -->
{#if posts === undefined}
<!-- Show our loader component -->
<p>Loading</p>
{:else}
    {#each posts as post}
<!-- Render our blog post components -->
    {/each}
{/if}
```

We're doing a few things here:

- Defining our prop as `undefined` by default, but you could also make this an empty array if you like.
- When the component is mounted, we're making a partial reload call using `Inertia.reload`.
- Checking for the state of the `posts` variable, and if it's `undefined`, we're showing a loader.

And that's it! You can use this approach to load in data when it's needed, not just on the mount hook.

For example, you could pair it with
the [Intersection API](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API) to detect when an
element enters the brower viewport and fire the partial reload function.
