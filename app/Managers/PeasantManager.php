<?php

namespace App\Managers;

use App\Peasant;

class PeasantManager extends UserManager
{
    /** @var Peasant */
    private $peasant;

    /** @var StorageManager */
    private $storageManager;

    /**
     * StorageManager constructor.
     * @param Peasant $peasant
     * @param UploadManager $uploadManager
     */
    public function __construct(Peasant $peasant, StorageManager $storageManager)
    {
        $this->peasant = $peasant;
        parent::__construct($this->peasant, $storageManager);
    }
}
