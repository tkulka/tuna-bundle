<?php

namespace TunaCMS\Bundle\EditorBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TunaCMSEditorBundle extends Bundle
{
    public function getParent()
    {
        return 'IvoryCKEditorBundle';
    }
}
