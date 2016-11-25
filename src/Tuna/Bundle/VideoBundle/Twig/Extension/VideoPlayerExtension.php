<?php

namespace TheCodeine\VideoBundle\Twig\Extension;

class VideoPlayerExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('video_url', [$this, 'videoPlayer'], [
                'is_safe' => ['html']
            ]),
        );
    }

    public function videoPlayer($videoType, $videoId)
    {
        switch ($videoType) {
            case 'youtube':
                return 'https://www.youtube.com/embed/' . $videoId;
            case 'vimeo':
                return 'https://player.vimeo.com/video/' . $videoId;
            default:
                return;
        }
    }

    public function getName()
    {
        return 'thecodeine_video_extension';
    }
}
