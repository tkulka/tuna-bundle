<?php

namespace TheCodeine\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use TheCodeine\FileBundle\Validator\Constraints as FileAssert;

/**
 * Attachment
 *
 * @ORM\Table(name="attachments")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="TheCodeine\FileBundle\Entity\AttachmentTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class Attachment extends AbstractAttachment
{
    /**
     * @Assert\Valid
     *
     * @ORM\OneToMany(targetEntity="AttachmentTranslation", mappedBy="object", cascade={"persist", "remove"})
     */
    protected $translations;
}
