<?php

namespace TunaCMS\Bundle\VideoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity
 * @UniqueEntity(fields="url")
 */
class Video
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Id of the video.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     * @Assert\Regex(pattern="/([a-z0-9-]+)/i", message = "The id '{{ value }}' is not a valid video id.")
     */
    protected $video_id;

    /**
     * Type of the video.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     * @Assert\Regex(pattern="/(vimeo|youtube)/i", message = "The type '{{ value }}' is not a valid video type.")
     */
    protected $type;


    /**
     * @var string
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/(?:(vimeo)(?:.com\/(?:video\/)?)(\d+))|(?:(youtu\.?be)(?:\.com\/|\/)?(?:watch|embed)?(?:\?v=|\/)?([a-z0-9-]+))/i",
     *     message = "The url '{{ value }}' is not a valid Youtube or Vimeo url")
     */
    protected $url;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Video
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set video id
     *
     * @param string $video_id
     *
     * @return Video
     */
    public function setVideoId($video_id)
    {
        $this->video_id = $video_id;

        return $this;
    }

    /**
     * Get video id
     *
     * @return string
     */
    public function getVideoId()
    {
        return $this->video_id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Video
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
