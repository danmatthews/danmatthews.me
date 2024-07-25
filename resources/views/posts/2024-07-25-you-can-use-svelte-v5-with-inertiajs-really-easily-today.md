---
id: bf9de
title: 'You can use Svelte v5 with InertiaJS really easily TODAY.'
date: '2023-11-26 09:46:49'
slug: you-can-use-svelte-v5-with-inertiajs-really-easily-today
excerpt: 'Svelte V5 is still in alpha, and a lot of things are still likely to change, but you can try it with InertiaJS today with ONE LINE of code changed.'
---

# You can use Svelte v5 with InertiaJS really easily TODAY.

Svelte V5 is still in alpha, and a lot of things are still likely to change, but you can try it with InertiaJS today
with ONE LINE of code changed.

This means you get to play
with [Runes](https://www.youtube.com/watch?v=RVnxF3j3N8U&t=564s), [Snippets](https://youtu.be/gGwnF-lxS_Q?t=578), and
more.

## How, then?

Simply install the alpha preview of Svelte 5:

```
npm install svelte@next
```

Then, in this snippet in your `app.js` where you create the actual Inertia app:

```js
import { createInertiaApp } from '@inertiajs/svelte'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.svelte', { eager: true })
    return pages[`./Pages/${name}.svelte`]
  },
  setup({ el, App, props }) {
    new App({ target: el, props })
  },
})
```

Svelte 5
changes [how components mount, and they're now no longer classes](https://svelte-5-preview.vercel.app/docs/breaking-changes#components-are-no-longer-classes).

So all you have to do is change this line:

```js
new App({ target: el, props })
```

By importing the new `mount()` function:

```js
import { mount } from 'svelte'
```

Then change the app line to:

```js
mount(App, { target: el}, props)
```

And there we go! it should just all work as before, thanks to the fact that V5 is largely backwards-compatible with V4.
