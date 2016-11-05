<?php

namespace App\Managers;

use App\Peasant;

class PeasantManager extends UserManager
{
    /** @var Peasant */
    private $peasant;

    /** @var UploadManager */
    private $uploadManager;

    /**
     * PeasantManager constructor.
     * @param Peasant $peasant
     * @param UploadManager $uploadManager
     */
    public function __construct(Peasant $peasant, UploadManager $uploadManager)
    {
        $this->peasant = $peasant;
        parent::__construct($this->peasant, $uploadManager);
    }
}
