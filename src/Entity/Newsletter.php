<?php

namespace App\Entity;

use App\Repository\NewsletterRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsletterRepository::class)]
class Newsletter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $emailAdresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailAdresse(): ?string
    {
        return $this->emailAdresse;
    }

    public function setEmailAdresse(string $emailAdresse): self
    {
        $this->emailAdresse = $emailAdresse;

        return $this;
    }
}
