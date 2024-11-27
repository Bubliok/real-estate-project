<?php

namespace App\Entity;

class Estate
{
    public function __construct(
        private int $id,
        private string $title,
        private int $price,
        private string $description
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

}