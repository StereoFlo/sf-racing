<?php

declare(strict_types = 1);

namespace App\Infrastructure\Dto\Request\News;

use App\Infrastructure\Dto\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class NewsDto implements RequestDtoInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="5", max="250")
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @var bool
     *
     * @Assert\Type(type="boolean")
     */
    private $isShowAuthorized;

    public function __construct(Request $request)
    {
        $this->title            = $request->get('title');
        $this->content          = $request->get('content');
        $this->isShowAuthorized = $request->get('is_show_authorized', false);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function isShowAuthorized(): bool
    {
        return $this->isShowAuthorized;
    }
}
