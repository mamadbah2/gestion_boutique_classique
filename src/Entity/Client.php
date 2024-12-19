<?php

namespace App\Entity;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ClientRepository::class)]
#[UniqueEntity('telephone', message:"Le telephone doit etre unique")]
#[UniqueEntity('surname', message:"Le surname doit etre unique")]

class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message:"Veuillez saisir un surnom valide ")]
    private ?string $surname = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    private ?string $adresse = null;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?User $account = null;

    

    /**
     * @var Collection<int, Dette>
     */
    #[ORM\OneToMany(targetEntity: Dette::class, mappedBy: 'client')]
    private Collection $dettes;

    /**
     * @var Collection<int, DetteRequest>
     */
    #[ORM\OneToMany(targetEntity: DetteRequest::class, mappedBy: 'client')]
    private Collection $detteRequests;

   

    public function __construct()
    {
        $this->dettes = new ArrayCollection();
        $this->detteRequests = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }


   

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(?User $account): static
    {
        // unset the owning side of the relation if necessary
        if ($account === null && $this->account !== null) {
            $this->account->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($account !== null && $account->getClient() !== $this) {
            $account->setClient($this);
        }

        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection<int, Dette>
     */
    public function getDettes(): Collection
    {
        return $this->dettes;
    }

    public function addDette(Dette $dette): static
    {
        if (!$this->dettes->contains($dette)) {
            $this->dettes->add($dette);
            $dette->setClient($this);
        }

        return $this;
    }

    public function removeDette(Dette $dette): static
    {
        if ($this->dettes->removeElement($dette)) {
            // set the owning side to null (unless already changed)
            if ($dette->getClient() === $this) {
                $dette->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DetteRequest>
     */
    public function getDetteRequests(): Collection
    {
        return $this->detteRequests;
    }

    public function addDetteRequest(DetteRequest $detteRequest): static
    {
        if (!$this->detteRequests->contains($detteRequest)) {
            $this->detteRequests->add($detteRequest);
            $detteRequest->setClient($this);
        }

        return $this;
    }

    public function removeDetteRequest(DetteRequest $detteRequest): static
    {
        if ($this->detteRequests->removeElement($detteRequest)) {
            // set the owning side to null (unless already changed)
            if ($detteRequest->getClient() === $this) {
                $detteRequest->setClient(null);
            }
        }

        return $this;
    }

   
}
