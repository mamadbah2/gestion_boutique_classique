<?php

namespace App\Entity;

use App\Repository\DetteRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetteRequestRepository::class)]
class DetteRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\ManyToOne(inversedBy: 'detteRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    /**
     * @var Collection<int, DetailDetteRequest>
     */
    #[ORM\OneToMany(targetEntity: DetailDetteRequest::class, mappedBy: 'detteRequest')]
    private Collection $detailDetteRequests;

    public function __construct()
    {
        $this->detailDetteRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

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
            $detailDetteRequest->setDetteRequest($this);
        }

        return $this;
    }

    public function removeDetailDetteRequest(DetailDetteRequest $detailDetteRequest): static
    {
        if ($this->detailDetteRequests->removeElement($detailDetteRequest)) {
            // set the owning side to null (unless already changed)
            if ($detailDetteRequest->getDetteRequest() === $this) {
                $detailDetteRequest->setDetteRequest(null);
            }
        }

        return $this;
    }
}
