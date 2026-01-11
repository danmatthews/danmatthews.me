<?php

namespace App\Service;

use App\Data\BlogPostData;
use App\Data\FrontMatter;
use App\Models\BlogPost;
use App\Models\PostTag;
use Carbon\Carbon;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Phiki\Adapters\CommonMark\PhikiExtension;
use Phiki\Theme\Theme;

// use Spatie\CommonMarkShikiHighlighter\HighlightCodeExtension;

class PostContentParser
{

    public MarkdownConverter $converter;

    public function __construct()
    {
        // Configure the Environment with all the CommonMark parsers/renderers
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());

        // Add the extension
        $environment->addExtension(new FrontMatterExtension())->addExtension(new PhikiExtension(Theme::NightOwl));

        // Instantiate the converter engine and start converting some Markdown!
        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * @throws CommonMarkException
     * @throws \Exception
     */
    public function parse(string $markdown): BlogPost
    {
        $result = $this->converter->convert($markdown);

        if ($result instanceof RenderedContentWithFrontMatter) {
            $frontMatter = $result->getFrontMatter();
            $frontMatterObject = FrontMatter::from([
                ...$frontMatter,
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', $frontMatter['date']),
            ]);

            $post = BlogPost::create([
                ...$frontMatter,
                'date' => $frontMatterObject->date,
                'title' => $frontMatterObject->title,
                'slug' => $frontMatterObject->slug,
                'published' => $frontMatterObject->published,
                'content' => $result->getContent(),
            ]);

            collect($frontMatterObject->tags)->each(function (string $tag) use ($post) {
                PostTag::create([
                    'blog_post_id' => $post->id,
                    'tag' => $tag,
                ]);
            });

            return $post;

        } else {
            throw new \Exception("Post is missing the required front matter");
        }
    }
}
