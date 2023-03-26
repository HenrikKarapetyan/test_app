<?php

namespace App\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class StatementModel
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private string $name;

    #[Assert\NotNull]
    #[Assert\Type('integer')]
    #[Assert\Range(
        min: 1,
        max: 1000000
    )]
    private int $number;

    private ?User $author = null;
    private string $authorId;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): void
    {
        $this->author = $author;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function setAuthorId(string $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @return array<string,int|string>
     */
    public function asArray(): array
    {
        return [
            'name' => $this->getName(),
            'number' => $this->getNumber(),
        ];
    }
}
