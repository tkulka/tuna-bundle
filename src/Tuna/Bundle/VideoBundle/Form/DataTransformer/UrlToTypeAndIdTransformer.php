<?php

namespace TunaCMS\Bundle\VideoBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use TunaCMS\Bundle\VideoBundle\Doctrine\VideoManagerInterface;
use TunaCMS\Bundle\VideoBundle\Entity\Video;

class UrlToTypeAndIdTransformer implements DataTransformerInterface
{
    /**
     * Tag manager instance
     *
     * @var VideoManagerInterface
     */
    private $videoManager;

    /**
     * @var string
     */
    private $videoType;

    /**
     * @var string
     */
    private $videoId;

    /**
     * UrlToTypeAndIdTransformer constructor.
     *
     * @param VideoManagerInterface $videoManagerInterface
     */
    public function __construct(VideoManagerInterface $videoManagerInterface)
    {
        $this->videoManager = $videoManagerInterface;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    public function transform($value)
    {
        if (!$value || !$value instanceof Video) {
            return '';
        }

        return $value->getUrl();
    }

    /**
     * @param string $value
     *
     * @return Video|\Traversable
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

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

    /**
     * @param $url
     *
     * @return bool
     */
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
