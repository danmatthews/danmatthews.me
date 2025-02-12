---
id: d20d3
title: 'Svelte 5 - Snippets.'
date: '2024-08-02 10:13:25'
slug: svelte-5-snippets
excerpt: 'Snippets are a new feature of Svelte 5 that allow you to define re-usable chunks of markup that can be passed around as state and props, and rendered when needed.'
---

Svelte’s new `snippets` feature is an attempt to solve an issue consistently raised by devs - especially ones who have came across from React who miss being able to components pieces of their application’s HTML by returning a slice of JSX from a function.

Snippets has caught some flak in the lead up to release, but I really like it - it’s strict in that you can only pass one argument, and because they’re just functions, you can pass them around as props, or return them from component files.

## What problem is it trying to solve?

Rich specifically says in his presentation that [slots](https://svelte.dev/docs/special-elements) are a bit of a bone of contention for him - they're special pseudo elements that are limited in what can be achieved because of how they interact with the compiler.

There's also another interesting thing: until now, you couldn't re-use sections of markup without extracting it into a separate component, which is overkill in some cases, and as I’ve mentioned above: specifically people from the React world missed the ability to essentially have sections of markup assigned to a function or variable.

Svelte, as well, currently, has no real way to build "render less" components, in a sense that you can define behaviour and have a third party provide the markup - see something like Headless UI for an example.

The main problem has been that you can't pass components as props (easily, you can, with a little bit of playing around), and you can't have slots at any level that isn't under the root of the component currently.

### Snippets are just functions (with a single argument) that 'return' markup.

And as such, they are more explicit about how you pass data. Previously to pass data to a _slot_, you had to use the `let:variable` construct.

For example, a header:

```svelte
{#snippet header({isLoading, title})}

	<header>
    {#if isLoading}
    	<Loader />
    {:else}
    	<h1>{title}</h1>
    {/if}
    </header>

{/snippet}
```

Now you can pass this as a variable to your layout component:

```svelte
<script>
// MyPage.svelte

let loading = false;

</script>

<Layout
	header={header({loading, "Page One"})
/>
```

And you can render it like so:

```svelte
<script>
let {header} = $props();
</script>

<header>
 {@render header}
</header>
```

## The ‘children’ snippet.

In Svelte 3/4, if you wanted to pass markdown or other components inside a component tag, you would have to print a `<slot />` tag that would then render that content.

### Svelte 4 example:

In your parent component:

```svelte
<script>

</script>

<MyComponent>
	<p>This is some child content.</p>
</MyComponent>
```

Then in your custom component:

```svelte
<script>
</script>

<div>
	<slot />
</div>
```


### Svelte 5 example:

Your parent component will look the same:

```svelte
<script>

</script>

<MyComponent>
	<p>This is some child content.</p>
</MyComponent>
```

But your child component, all markup inside that is not part of a snippet, __automatically becomes part of the `children` snippet__, and you need to use the `@render` notation for it:

```svelte
<script>
</script>

<div>
  {@render children}
</div>
```


## Advanced Snippets

Svelte also provides advanced tools to create your own snippet objects using the [createRawSnippet](https://svelte-5-preview.vercel.app/docs/imports#svelte-createrawsnippet) API.

This allows you to construct snippets for use with (for example) component libraries.

```javascript
import { createRawSnippet } from 'svelte';

const Button = createRawSnippet(({text, onclick}) => {
	return {
		render: () => `
			<button {onclick}>{text}</button>
		`,
		setup: (node) => {
			// You can run $effect or $derived runes in here.
		}
	};
});
```

You can then import this, and use it directly as a snippet:

```svelte
<script>
import {Button} from './Button.svelte';
</script>

{@render Button({text: "Click Me", onclick: () => alert("hey!")})}
```