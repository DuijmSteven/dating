<?php

namespace App\Managers;

use App\Article;
use App\Helpers\ApplicationConstants\UserConstants;
use App\User;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
                'meta_description' => $articleData['meta_description'],
                'slug' => Str::slug($articleData['title'])
            ]);
        } catch (\Exception $exception) {
            throw $exception;
        }

        if (isset($articleData['article_image']) && ($articleData['article_image'] instanceof UploadedFile)) {
            try {
                $uploadedArticleImageFilename = $this->storageManager->saveArticleImage($articleData['article_image'], $createdArticle->getId());
                $createdArticle->setImageFilename($uploadedArticleImageFilename);
            } catch (\Exception $exception) {
                if ($this->storageManager->fileExists($uploadedArticleImageFilename, \StorageHelper::articleImagesPath($createdArticle->getId()))) {
                    $this->storageManager->deleteArticleImage($createdArticle->getId(), $uploadedArticleImageFilename);
                }

                throw $exception;
            }
        }

        $createdArticle->setImageFilename($uploadedArticleImageFilename);
        $createdArticle->save();


    }

    /**
     * @param int $articleId
     * @param array $articleData
     * @throws \Exception
     */
    public function updateArticle(int $articleId, array $articleData)
    {
        try {
            /** @var Article $article */
            $article = Article::find($articleId);
            $article->setTitle($articleData['title']);
            $article->setBody($articleData['body']);
            $article->setMetaDescription($articleData['meta_description']);
            $article->setStatus($articleData['status']);
            $article->setSlug(Str::slug($articleData['title']));

        } catch (\Exception $exception) {
            throw $exception;
        }

        if (isset($articleData['article_image']) && ($articleData['article_image'] instanceof UploadedFile)) {
            try {
                if (
                    $article->getImageFilename() &&
                    $this->storageManager->fileExists(
                        $article->getImageFilename(),
                        \StorageHelper::articleImagesPath($article->getId())
                    )
                ) {
                    $this->storageManager->deleteArticleImage($article->getId(), $article->getImageFilename());
                }

                $uploadedArticleImageFilename = $this->storageManager->saveArticleImage($articleData['article_image'], $article->getId());

                $article->setImageFilename($uploadedArticleImageFilename);
            } catch (\Exception $exception) {
                throw $exception;
            }
        }

        $article->save();
    }
}
