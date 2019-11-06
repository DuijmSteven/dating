<?php

namespace App\Managers;

use App\Article;
use App\Helpers\ApplicationConstants\UserConstants;
use App\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ArticleManager
 * @package App\Managers
 */
class ArticleManager
{
    /** @var StorageManager */
    private $storageManager;

    /** @var Article $article */
    private $article;

    /**
     * ArticleManager constructor.
     * @param User $user
     * @param StorageManager $storageManager
     */
    public function __construct(
        Article $article,
        StorageManager $storageManager
    ) {
        $this->article = $article;
        $this->storageManager = $storageManager;
    }

    /**
     * @param array $articleData
     * @throws \Exception
     */
    public function persistArticle(array $articleData)
    {
        try {
            /** @var Article $createdArticle */
            $createdArticle = $this->article->create([
                'title' => $articleData['title'],
                'body' => $articleData['body'],
                'status' => $articleData['status'],
                'meta_description' => $articleData['meta_description']
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }

        if (isset($articleData['article_image']) && ($articleData['article_image'] instanceof UploadedFile)) {
            try {
                $uploadedArticleImageFilename = $this->storageManager->saveArticleImage($articleData['article_image'], $createdArticle->getId());
            } catch (\Exception $exception) {
                throw $exception;
            }
        }

        $createdArticle->setImageFilename($uploadedArticleImageFilename);
        $createdArticle->save();
    }
}
