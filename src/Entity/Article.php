<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $qteStock = null;

    #[ORM\Column]
    private ?int $prix = null;

    /**
     * @var Collection<int, DetailArticleDette>
     */
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: DetailArticleDette::class)]
    private Collection $detailArticleDettes;

    /**
     * @var Collection<int, DetailDetteRequest>
     */
    #[ORM\OneToMany(targetEntity: DetailDetteRequest::class, mappedBy: 'article')]
    private Collection $detailDetteRequests;

    public function __construct()
    {
        $this->detailArticleDettes = new ArrayCollection();
        $this->detailDetteRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getQteStock(): ?int
    {
        return $this->qteStock;
    }

    public function setQteStock(int $qteStock): static
    {
        $this->qteStock = $qteStock;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, DetailArticleDette>
     */
    public function getDetailArticleDettes(): Collection
    {
        return $this->detailArticleDettes;
    }

    public function addDetailArticleDette(DetailArticleDette $detailArticleDette): static
    {
        if (!$this->detailArticleDettes->contains($detailArticleDette)) {
            $this->detailArticleDettes->add($detailArticleDette);
            $detailArticleDette->setArticle($this);
        }

        return $this;
    }

    public function removeDetailArticleDette(DetailArticleDette $detailArticleDette): static
    {
        if ($this->detailArticleDettes->removeElement($detailArticleDette)) {
            // set the owning side to null (unless already changed)
            if ($detailArticleDette->getArticle() === $this) {
                $detailArticleDette->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetailDetteRequest>
     */
    public function getDetailDetteRequests(): Collection
    {
        return $this->detailDetteRequests;
    }

    public function addDetailDetteRequest(DetailDetteRequest $detailDetteRequest): static
    {
        if (!$this->detailDetteRequests->contains($detailDetteRequest)) {
            $this->detailDetteRequests->add($detailDetteRequest);
            $detailDetteRequest->setArticle($this);
        }

        return $this;
    }

    public function removeDetailDetteRequest(DetailDetteRequest $detailDetteRequest): static
    {
        if ($this->detailDetteRequests->removeElement($detailDetteRequest)) {
            // set the owning side to null (unless already changed)
            if ($detailDetteRequest->getArticle() === $this) {
                $detailDetteRequest->setArticle(null);
            }
        }

        return $this;
    }
}
