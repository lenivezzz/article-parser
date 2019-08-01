<?php
declare(strict_types=1);

namespace php_part\validators;

use php_part\models\Article;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ArticleValidator implements ValidatorInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * ArticleValidator constructor.
     * @param \Symfony\Component\Validator\Validator\ValidatorInterface $validator
     */
    public function __construct(\Symfony\Component\Validator\Validator\ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritDoc
     */
    public function fails(array $attributes) : bool
    {
        $this->errors = [];
        $this->validateTitle($attributes['title'] ?? '');
        $this->validateAnnounce($attributes['announce'] ?? '');
        $this->validateContent($attributes['content'] ?? '');
        $this->validateImageSrc($attributes['image_src'] ?? null);
        $this->validatePublishedAt($attributes['published_at'] ?? '');
        $this->validateHash($attributes['hash'] ?? '');

        if (!$this->getErrors()) {
            $this->validateArticleUnique($attributes['hash']);
        }

        return (bool) $this->getErrors();
    }

    /**
     * @inheritDoc
     */
    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * @param string $title
     */
    private function validateTitle(string $title) : void
    {
        $violations = $this->validator->validate($title, [
            new NotBlank(),
        ]);

        $this->convertViolationsToMessages($violations);
    }

    /**
     * @param string $announce
     */
    private function validateAnnounce(string $announce) : void
    {
        $violations = $this->validator->validate($announce, [
            new NotBlank(),
            new Length([
                'max' => 200
            ]),
        ]);
        $this->convertViolationsToMessages($violations);
    }

    /**
     * @param string $content
     */
    private function validateContent(string $content) : void
    {
        $violations = $this->validator->validate($content, [
            new NotBlank(),
        ]);
        $this->convertViolationsToMessages($violations);
    }

    /**
     * @param string|null $imageSrc
     */
    private function validateImageSrc(?string $imageSrc) : void
    {
        $violations = $this->validator->validate($imageSrc, [
            new Url(),
        ]);
        $this->convertViolationsToMessages($violations);
    }

    /**
     * @param string $dateTime
     */
    private function validatePublishedAt(string $dateTime) : void
    {
        $dateTimeValidator = new DateTime();
        $dateTimeValidator->format = 'Y-m-d H:i:s';
        $violations = $this->validator->validate($dateTime, [
            new NotBlank(),
            $dateTimeValidator,
        ]);
        $this->convertViolationsToMessages($violations);
    }

    /**
     * @param string $hash
     */
    private function validateHash(string $hash) : void
    {
        $violations = $this->validator->validate($hash, [
            new NotBlank(),
            new Length(40),
        ]);
        $this->convertViolationsToMessages($violations);
    }

    /**
     * @param string $hash
     */
    private function validateArticleUnique(string $hash) : void
    {
        if (Article::query()->where(['hash' => $hash])->exists()) {
            $this->addErrorMessage('Duplicated article');
        }
    }

    private function convertViolationsToMessages(ConstraintViolationListInterface $violationList) : void
    {
        if (!$violationList->count()) {
            return;
        }

        foreach ($violationList as $violation) {
            $this->addErrorMessage($violation->getMessage());
        }
    }

    /**
     * @param string $message
     */
    private function addErrorMessage(string $message) : void
    {
        $this->errors[] = $message;
    }
}
