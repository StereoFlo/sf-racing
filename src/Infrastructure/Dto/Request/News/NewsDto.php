<?php

declare(strict_types = 1);

namespace App\Infrastructure\Dto\Request\News;

use App\Infrastructure\Dto\RequestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class NewsDto implements RequestDtoInterface
{
    /**
     * @var int|null
     *
     * @Assert\Type(type="integer")
     */
    private $id;

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
        if ($request->get('id')) {
            $this->id = (int) $request->get('id');
        }

        $this->title            = $request->get('title');
        $this->content          = $request->get('content');
        $this->isShowAuthorized = $request->get('is_show_authorized', false);
    }

    public function getId(): ?int
    {
        return $this->id;
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
