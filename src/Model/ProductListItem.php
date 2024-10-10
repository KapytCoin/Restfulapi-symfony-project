<?php

namespace App\Model;

class ProductListItem
{
    private int $id;

    private string $title;

    private string $slug;

    private string $image;

    private int $publicationDate;

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getPublicationDate(): int
    {
        return $this->publicationDate;
    }

    public function setPublicationDate(int $publicationDate): static
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }
}