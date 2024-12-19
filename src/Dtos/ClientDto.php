<?php

namespace App\Dtos;
use Symfony\Component\Validator\Constraints as Assert;

class ClientDto
{
    public ?int $id;
    public ?string $surname;
    public ?string $telephone;
    public ?string $adresse;

   
    
    
    // #[Assert\Regex('/^(7[07-8][0-9]{7})$/', 'Format : 77XXXXXX ou 78XXXXXX ou 76XXXXXX')]

   
}
