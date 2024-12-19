<?php

namespace App\Entity;

use App\Repository\DetailDetteRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailDetteRequestRepository::class)]
class DetailDetteRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $qte = null;

    #[ORM\ManyToOne(inversedBy: 'detailDetteRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\ManyToOne(inversedBy: 'detailDetteRequests')]
    private ?DetteRequest $detteRequest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): static
    {
        $this->qte = $qte;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): static
    {
        $this->article = $article;

        return $this;
    }

    public function getDetteRequest(): ?DetteRequest
    {
        return $this->detteRequest;
    }

    public function setDetteRequest(?DetteRequest $detteRequest): static
    {
        $this->detteRequest = $detteRequest;

        return $this;
    }
}
