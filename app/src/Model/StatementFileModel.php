<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class StatementFileModel
{
    #[Assert\NotBlank()]
    private string $image;

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }
}
