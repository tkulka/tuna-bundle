<?php

namespace TunaCMS\Bundle\VideoBundle\Twig\Extension;

class VideoPlayerExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('video_url', [$this, 'videoPlayer'], [
                'is_safe' => ['html']
            ]),
        ];
    }

    /**
     * @param string $videoType
     * @param string $videoId
     *
     * @return string
     */
    public function videoPlayer($videoType, $videoId)
    {
        switch ($videoType) {
            case 'youtube':
                return 'https://www.youtube.com/embed/' . $videoId;
            case 'vimeo':
                return 'https://player.vimeo.com/video/' . $videoId;
            default:
                return '';
        }
    }
}
