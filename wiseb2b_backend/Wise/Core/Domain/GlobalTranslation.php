<?php

namespace Wise\Core\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * Obiekt przechowujący tłumaczenia globalne
 */
class GlobalTranslation
{
    private ?int $id = null;

    #[ORM\Column(length: 60)]
    private ?string $subjectReferenceName = null;

    #[ORM\Column(length: 60)]
    private ?string $subjectReferenceField = null;

    #[ORM\Column]
    private ?int $subjectReferenceRecordId = null;

    #[ORM\Column(length: 3)]
    private ?string $language = null;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $translation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectReferenceName(): ?string
    {
        return $this->subjectReferenceName;
    }

    public function setSubjectReferenceName(string $subjectReferenceName): self
    {
        $this->subjectReferenceName = $subjectReferenceName;

        return $this;
    }

    public function getSubjectReferenceField(): ?string
    {
        return $this->subjectReferenceField;
    }

    public function setSubjectReferenceField(string $subjectReferenceField): self
    {
        $this->subjectReferenceField = $subjectReferenceField;

        return $this;
    }

    public function getSubjectReferenceRecordId(): ?int
    {
        return $this->subjectReferenceRecordId;
    }

    public function setSubjectReferenceRecordId(int $subjectReferenceRecordId): self
    {
        $this->subjectReferenceRecordId = $subjectReferenceRecordId;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTranslation(): ?string
    {
        return $this->translation;
    }

    public function setTranslation(?string $translation): self
    {
        $this->translation = $translation;

        return $this;
    }
}
