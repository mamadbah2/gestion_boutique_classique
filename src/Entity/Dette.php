<?php

namespace App\Entity;

use App\enum\StatusDette;
use App\Repository\DetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetteRepository::class)]
class Dette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column]
    private ?int $montantVerser = null;

    #[ORM\ManyToOne(inversedBy: 'dettes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    private StatusDette $status = StatusDette::Impaye;

    /**
     * @var Collection<int, DetailArticleDette>
     */
    #[ORM\OneToMany(targetEntity: DetailArticleDette::class, mappedBy: 'dette', cascade: ['persist'])]
    private Collection $detailArticleDettes;

    /**
     * @var Collection<int, Payement>
     */
    #[ORM\OneToMany(targetEntity: Payement::class, mappedBy: 'dette', cascade: ['persist'])]
    private Collection $payements;
    

    public function __construct()
    {
        $this->detailArticleDettes = new ArrayCollection();
        $this->payements = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setStatus(StatusDette $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?StatusDette
    {
        if ( $this->montantVerser != 0 && $this->montant === $this->montantVerser) { 
            $this->status = StatusDette::Paye;
        }
        return $this->status;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getMontantVerser(): ?int
    {
        return $this->montantVerser;
    }

    public function setMontantVerser(int $montantVerser): static
    {
        $this->montantVerser = $montantVerser;

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
            $detailArticleDette->setDette($this);
        }

        return $this;
    }

    public function removeDetailArticleDette(DetailArticleDette $detailArticleDette): static
    {
        if ($this->detailArticleDettes->removeElement($detailArticleDette)) {
            // set the owning side to null (unless already changed)
            if ($detailArticleDette->getDette() === $this) {
                $detailArticleDette->setDette(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payement>
     */
    public function getPayements(): Collection
    {
        return $this->payements;
    }

    public function addPayement(Payement $payement): static
    {
        if (!$this->payements->contains($payement)) {
            $this->payements->add($payement);
            $payement->setDette($this);
        }

        return $this;
    }

    public function removePayement(Payement $payement): static
    {
        if ($this->payements->removeElement($payement)) {
            // set the owning side to null (unless already changed)
            if ($payement->getDette() === $this) {
                $payement->setDette(null);
            }
        }

        return $this;
    }
}
