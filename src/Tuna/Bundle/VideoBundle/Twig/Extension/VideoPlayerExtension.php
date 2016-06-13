<?php

namespace TheCodeine\VideoBundle\Twig\Extension;

class VideoPlayerExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'videoPlayer' => new \Twig_Function_Method($this, 'videoPlayer', array('is_safe' => array('html'))),
        );
    }

    public function videoPlayer($videoType, $videoId)
    {
        switch ($videoType) {
            case 'youtube':
                return 'https://www.youtube.com/embed/' . $videoId;
                break;
            case 'vimeo':
                return 'https://player.vimeo.com/video/' . $videoId;
                break;
            default:
                return;
                break;
        }
    }

    public function getName()
    {
        return 'thecodeine_video_extension';
    }
}
