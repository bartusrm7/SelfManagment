<?php

namespace App\Entity;

use App\Repository\DailyTasksRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;

#[ORM\Entity(repositoryClass: DailyTasksRepository::class)]
class DailyTasks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $taskName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $taskDescription = null;

    #[ORM\Column(nullable: true)]
    private ?bool $taskDone = null;

    #[ORM\Column(type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'], nullable: true)]
    private ?DateTime $taskDate = null;

    #[ORM\ManyToOne(User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskName(): ?string
    {
        return $this->taskName;
    }

    public function setTaskName(string $taskName): static
    {
        $this->taskName = $taskName;

        return $this;
    }

    public function getTaskDescription(): ?string
    {
        return $this->taskDescription;
    }

    public function setTaskDescription(?string $taskDescription): static
    {
        $this->taskDescription = $taskDescription;

        return $this;
    }

    public function getTaskDone(): ?bool
    {
        return $this->taskDone;
    }

    public function setTaskDone(?bool $taskDone): static
    {
        $this->taskDone = $taskDone;

        return $this;
    }

    public function getTaskDate(): ?DateTime
    {
        return $this->taskDate;
    }

    public function setTaskDate(?DateTime $taskDate): static
    {
        $this->taskDate = $taskDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
