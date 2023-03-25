<?php

namespace App\Entity;

use App\Repository\StatementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatementRepository::class)]
class Statement
{
    public const FILE_SAVE_PATH = '/uploads/statement_images/';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private int $number;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $file_url = null;

    #[ORM\ManyToOne(inversedBy: 'statements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->file_url;
    }

    public function setFileUrl(string $file_url): self
    {
        $this->file_url = Statement::FILE_SAVE_PATH.$file_url;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
