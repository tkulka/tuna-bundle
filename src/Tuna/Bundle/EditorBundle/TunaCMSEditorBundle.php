<?php

namespace TunaCMS\EditorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TunaCMSEditorBundle extends Bundle
{
    public function getParent()
    {
        return 'IvoryCKEditorBundle';
    }
}