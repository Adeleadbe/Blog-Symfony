<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: "email",
    message: "Cette adresse email existe déjà",
)]
#[UniqueEntity(
    fields: "username",
    message: "Ce pseudo est déjà utilisé",
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotNull(
        message: "Vous devez indiquer un pseudo valide"
    )]
    #[Assert\Length(
        min: 3,
        max: 15,
        minMessage: "Votre peuso est trop court (minimum : 3)",
        maxMessage: "Votre peuso est trop long (maximum : 15)",
    )]
    #[Assert\Regex(
        pattern: "/@/",
        match: false,
        message: "Le pseudonyme indiqué est invalide",
    )]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotNull(
        message: "Vous devez indiquer un email valide"
    )]
    #[Assert\Email(
        message: "L'email indiqué n'est pas valide"
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(
        message: "Veuillez indiquer un mot de passe"
    )]
    private ?string $password = null;

    #[Assert\NotNull(
        message: "Vous devez indiquer une confirmation de mot de passe"
    )]
    #[Assert\EqualTo(
        propertyPath: 'password',
        message: "Vos mots de passe ne sont pas identiques"
    )]
    private ?string $passwordConfirm = null;

    #[ORM\Column]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
    //Function pour identifier par l'email
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    
    public function eraseCredentials()
    {
        
    }

    public function setPasswordConfirm(?string $passwordConfirm): void
    {
        $this->passwordConfirm = $passwordConfirm;
    }

    public function getPasswordConfirm(): ?string 
    {
        return $this->passwordConfirm;
    }
}
