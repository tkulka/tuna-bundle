<?php

namespace TunaCMS\PageComponent\Model;

use TunaCMS\CommonComponent\Model\AttachmentInterface;
use TunaCMS\CommonComponent\Model\BodyInterface;
use TunaCMS\CommonComponent\Model\CategoryInterface;
use TunaCMS\CommonComponent\Model\GalleryInterface;
use TunaCMS\CommonComponent\Model\IdInterface;
use TunaCMS\CommonComponent\Model\ImageInterface;
use TunaCMS\CommonComponent\Model\LocaleInterface;
use TunaCMS\CommonComponent\Model\PublishInterface;
use TunaCMS\CommonComponent\Model\SlugInterface;
use TunaCMS\CommonComponent\Model\TeaserInterface;
use TunaCMS\CommonComponent\Model\TimestampInterface;
use TunaCMS\CommonComponent\Model\TitleInterface;
use TunaCMS\CommonComponent\Model\TranslateInterface;

interface NewsInterface extends
    AttachmentInterface,
    BodyInterface,
    CategoryInterface,
    GalleryInterface,
    IdInterface,
    ImageInterface,
    LocaleInterface,
    PublishInterface,
    SlugInterface,
    TeaserInterface,
    TimestampInterface,
    TitleInterface,
    TranslateInterface
{
}