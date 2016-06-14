<?php

namespace TheCodeine\VideoBundle\Form\DataTransformer;

use TheCodeine\VideoBundle\Model\VideoManagerInterface;
use TheCodeine\VideoBundle\Entity\Video;

use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\DataTransformerInterface;

class UrlToTypeAndIdTransformer implements DataTransformerInterface
{
    /**
     * Tag manager instance
     *
     * @var TagManagerInterface
     */
    private $videoManager;

    private $videoType;
    private $videoId;

    public function __construct(VideoManagerInterface $videoManagerInterface)
    {
        $this->videoManager = $videoManagerInterface;
    }

    public function transform($value)
    {
        if (!$value || !$value instanceof Video) {
            return;
        }

        return $value->getUrl();
    }

    public function reverseTransform($value)
    {

        if (!is_string($value)) {
            throw new TransformationFailedException('Expected a string.');
        }

        if (!$value || !$this->parseUrl($value)) {
            throw new TransformationFailedException('Empty or invalid string.');
        }

        $video = $this->videoManager->findByVideoId($this->videoId);

        if (!$video) {
            $video = $this->videoManager->createVideo();

            $video->setVideoId($this->videoId);
            $video->setType($this->videoType);
            $video->setUrl($value);
        }

        return $video;
    }


    private function parseUrl($url)
    {
        if (preg_match('/(?:(vimeo)(?:.com\/(?:video\/)?)(\d+))|(?:(youtu\.?be)(?:\.com\/|\/)?(?:watch|embed)?(?:\?v=|\/)?([a-z0-9-_.]+))/i', $url, $data)) {
            $data = array_values(array_filter($data));
            array_shift($data);

            $this->videoType = str_replace('.', '', $data[0]);
            $this->videoId = $data[1];
            return true;
        }

        return false;
    }
}
