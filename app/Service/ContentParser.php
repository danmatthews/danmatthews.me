<?php

namespace App\Service;

use App\Data\BlogPostData;
use App\Data\FrontMatter;
use App\Data\LinkFrontMatter;
use App\Models\BlogLink;
use App\Models\BlogPost;
use App\Models\Tag;
use Carbon\Carbon;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\MarkdownConverter;
use Phiki\CommonMark\PhikiExtension;
use Phiki\Theme\Theme;

// use Spatie\CommonMarkShikiHighlighter\HighlightCodeExtension;

class ContentParser
{

    public MarkdownConverter $converter;

    public function __construct()
    {
        // Configure the Environment with all the CommonMark parsers/renderers
        $environment = new Environment([]);
        $environment->addExtension(new CommonMarkCoreExtension());

        // Add the extension
        $environment->addExtension(new FrontMatterExtension())->addExtension(new PhikiExtension(Theme::NightOwl, withWrapper: true));

        // Instantiate the converter engine and start converting some Markdown!
        $this->converter = new MarkdownConverter($environment);
    }

    /**
     * @throws CommonMarkException
     * @throws \Exception
     */
    public function parse(string $markdown): FrontMatter
    {
        $result = $this->converter->convert($markdown);

        if ($result instanceof RenderedContentWithFrontMatter) {
            $frontMatter = $result->getFrontMatter();
            return FrontMatter::from([
                ...$frontMatter,
                'content' => $result->getContent(),
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', $frontMatter['date']),
            ]);

//            return BlogPost::create([
//                ...$frontMatter,
//                'date' => $frontMatterObject->date,
//                'title' => $frontMatterObject->title,
//                'slug' => $frontMatterObject->slug,
//                'published' => $frontMatterObject->published,
//                'content' => $result->getContent(),
//            ]);

//            collect($frontMatterObject->tags)->each(function (string $tag) use ($post) {
//                ContentTag::create([
//                    'taggable_id' => $post->id,
//                    'taggable_type' => $post->getMorphClass(),
//                    'tag' => $tag,
//                ]);
//            });


        } else {
            throw new \Exception("Post is missing the required front matter");
        }
    }

    public function parseLink(string $markdown): LinkFrontMatter
    {
        $result = $this->converter->convert($markdown);

        if ($result instanceof RenderedContentWithFrontMatter) {
            $frontMatter = $result->getFrontMatter();
            return LinkFrontMatter::from([
                ...$frontMatter,
                'description' => $result->getContent(),
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', $frontMatter['date']),
            ]);

//            $link = BlogLink::create([
//                ...$frontMatter,
//                'date' => $frontMatterObject->date,
//                'url' => $frontMatterObject->url,
//                'title' => $frontMatterObject->title,
//                'slug' => $frontMatterObject->slug,
//                'published' => $frontMatterObject->published,
//                'description' => $result->getContent(),
//            ]);
//
//            collect($frontMatterObject->tags)->each(function (string $tag) use ($link) {
//                ContentTag::create([
//                    'taggable_id' => $link->id,
//                    'taggable_type' => $link->getMorphClass(),
//                    'tag' => $tag,
//                ]);
//            });

        } else {
            throw new \Exception("Post is missing the required front matter");
        }
    }
}
