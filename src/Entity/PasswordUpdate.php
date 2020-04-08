<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordUpdate
{

    private $OldPassword;

    /**
     * @Assert\Length(min="8" , minMessage="votre mot de passe doit contenir 8 caractères !")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword" ,message="vous n'avez pas correctement confimer votre mot de passe !")
     */
    private $confirmPassword;



    public function getOldPassword(): ?string
    {
        return $this->OldPassword;
    }

    public function setOldPassword(string $OldPassword): self
    {
        $this->OldPassword = $OldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
