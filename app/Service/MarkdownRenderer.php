<?php

namespace App\Service;

use App\Data\BlogPostData;
use Carbon\Carbon;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Phiki\CommonMark\PhikiExtension;
use Phiki\Theme\Theme;

// use Spatie\CommonMarkShikiHighlighter\HighlightCodeExtension;

class MarkdownRenderer
{

    public function renderSimple(string $markdown): string
    {
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());

        // Instantiate the converter engine and start converting some Markdown!
        $converter = new MarkdownConverter($environment);
        return $converter->convert($markdown)->getContent();
    }

    public function render(string $markdown): BlogPostData
    {
        $config = [];

        // Configure the Environment with all the CommonMark parsers/renderers
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());

        // Add the extension
        $environment->addExtension(new FrontMatterExtension())->addExtension(new PhikiExtension(Theme::NightOwl, withWrapper: true));

        // Instantiate the converter engine and start converting some Markdown!
        $converter = new MarkdownConverter($environment);
        $result = $converter->convert($markdown);

        if ($result instanceof RenderedContentWithFrontMatter) {
            $frontMatter = $result->getFrontMatter();
        } else {
            throw new \Exception("Post is missing the required front matter");
        }

        $content = $result->getContent();

        return BlogPostData::from([
            'id' => $frontMatter['id'],
            'title' => $frontMatter['title'],
            'date' => Carbon::createFromFormat('Y-m-d H:i:s', $frontMatter['date']),
            'slug' => $frontMatter['slug'],
            'content' => $content,
            'excerpt' => $frontMatter['excerpt'] ?? null,
            'published' => $frontMatter['published'] ?? true,
        ]);
    }
}
