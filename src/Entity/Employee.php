<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmployeeRepository;
use App\Traits\SearchTrait;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    use SearchTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(length: 255)]
    private ?string $social_security_number = null;

    #[ORM\Column(length: 255)]
    private ?string $citizen_number = null;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: Leave::class)]
    private Collection $leaves;

    public function __construct()
    {
        $this->leaves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getSocialSecurityNumber(): ?string
    {
        return $this->social_security_number;
    }

    public function setSocialSecurityNumber(string $social_security_number): self
    {
        $this->social_security_number = $social_security_number;

        return $this;
    }

    public function getCitizenNumber(): ?string
    {
        return $this->citizen_number;
    }

    public function setCitizenNumber(string $citizen_number): self
    {
        $this->citizen_number = $citizen_number;

        return $this;
    }

    /**
     * @return Collection<int, Leave>
     */
    public function getLeaves(): Collection
    {
        return $this->leaves;
    }

    public function addLeaf(Leave $leaf): self
    {
        if (!$this->leaves->contains($leaf)) {
            $this->leaves->add($leaf);
            $leaf->setEmployee($this);
        }

        return $this;
    }

    public function removeLeaf(Leave $leaf): self
    {
        if ($this->leaves->removeElement($leaf)) {
            // set the owning side to null (unless already changed)
            if ($leaf->getEmployee() === $this) {
                $leaf->setEmployee(null);
            }
        }

        return $this;
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'social_security_number' => $this->social_security_number,
            'citizen_number' => $this->citizen_number,
        ];
    }

    public function serialized($datas)
    {
        $serialize = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);

        $response = new Response($serialize->serialize($datas, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
            'ignored_attributes' => ['employee'],
            'datetime_format' => 'Y-m-d H:i:s',
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function search($query, $search)
    {
        $terms = $this->splitName($search);

        foreach ($terms as $term) {
            $query->where(function ($query) use ($term) {
                $query->where('first_name', 'like', '%' . $term[0] . '%')
                    ->orWhere('last_name', 'like', '%' . $term[1] . '%');
            });
        }
    }
}
