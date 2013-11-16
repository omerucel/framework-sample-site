<?php

namespace Application\Site;

class InternalError extends BaseSiteController
{
    public function get()
    {
        return $this->toPlainText('Site:Internal server error', 500);
    }
}
