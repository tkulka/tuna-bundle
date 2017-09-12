<?php

namespace TunaCMS\Bundle\VideoBundle\Tests\Twig\Extension;

use PHPUnit\Framework\TestCase;
use TunaCMS\Bundle\VideoBundle\Twig\Extension\VideoPlayerExtension;

class VideoPlayerExtensionTest extends TestCase
{
    /**
     * @dataProvider getVideoPlayerData
     *
     * @param string $videoType
     * @param string|integer $videoId
     * @param string $expected
     */
    public function testVideoPlayer($videoType, $videoId, $expected)
    {
        $extension = new VideoPlayerExtension();

        $this->assertEquals($expected, $extension->videoPlayer($videoType, $videoId));
    }

    public function getVideoPlayerData()
    {
        return [
            [
                'other',
                '5',
                '',
            ],
            [
                'youtube',
                '2dsf34',
                'https://www.youtube.com/embed/2dsf34',
            ],
            [
                'vimeo',
                '67df',
                'https://player.vimeo.com/video/67df',
            ],
        ];
    }
}
