# OG Image Generation with Browsershot

This project uses Spatie's Browsershot package to generate Open Graph (OG) images for blog posts. The OG images are generated using a Blade template and TailwindCSS, allowing for more customization than the previous implementation.

## How It Works

1. A Blade template (`resources/views/og-image.blade.php`) is used to define the layout and styling of the OG images.
2. The `app:og` command (`app/Console/Commands/GenerateOGImages.php`) generates OG images for all blog posts by:
   - Rendering the Blade template with the blog post's title and excerpt
   - Using Browsershot to capture a screenshot of the rendered HTML
   - Saving the screenshot as a PNG file in the `storage/app/public/opengraph` directory

## Requirements

- PHP 8.2+
- Laravel 12+
- Node.js and npm (required by Browsershot)
- Puppeteer (installed automatically by Browsershot)

## Installation

1. Make sure you have Node.js and npm installed on your system.
2. Install the required PHP packages:
   ```bash
   composer install
   ```
3. Browsershot will automatically install Puppeteer when needed.

## Usage

### Generating OG Images

To generate OG images for all blog posts, run:

```bash
php artisan app:og
```

This will create PNG files in the `storage/app/public/opengraph` directory, named after each blog post's ID.

### Customizing the OG Image Template

You can customize the OG image template by editing `resources/views/og-image.blade.php`. The template uses TailwindCSS for styling, so you can use any TailwindCSS classes to customize the appearance.

The template receives the following variables:
- `$title`: The blog post's title
- `$excerpt`: The blog post's excerpt (optional)

### Adding More Variables

If you want to pass more variables to the template, modify the `GenerateOGImages` command:

```php
$html = View::make('og-image', [
    'title' => $post->title,
    'excerpt' => $post->excerpt,
    // Add more variables here
])->render();
```

## Troubleshooting

If you encounter issues with Browsershot:

1. Make sure Node.js and npm are installed and accessible from the command line.
2. Check that Puppeteer is installed correctly.
3. If you're on a server, you might need to install additional dependencies for Puppeteer to work in headless mode.

For more information, see the [Browsershot documentation](https://github.com/spatie/browsershot).
