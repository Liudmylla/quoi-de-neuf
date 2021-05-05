<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

//Ici on importe le package Vich, que l’on utilisera sous l’alias “Vich”

use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * //On précise à l’entité que nous utiliserons l’upload du package Vich uploader
 * @Vich\Uploadable
 */
class Annonce

{
    



      //On va créer un nouvel attribut à notre entité, qui ne sera pas lié à une colonne
   
        // Tu peux d’ailleurs voir que l’annotation ORM column n’est pas spécifiée car
   
      //On ne rajoute pas de données de type file en bdd
   
        /**
   
         * @Vich\UploadableField(mapping="poster_file", fileNameProperty="poster")
   
         * @var File
   
         */
   
        private $posterFile;
   
   



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lieux;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_validated;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="annonces")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="annonces")
     */
    private $auteur;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $poster;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getLieux(): ?string
    {
        return $this->lieux;
    }

    public function setLieux(?string $lieux): self
    {
        $this->lieux = $lieux;

        return $this;
    }

    public function getIsValidated(): ?bool
    {
        return $this->is_validated;
    }

    public function setIsValidated(bool $is_validated): self
    {
        $this->is_validated = $is_validated;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

      /**
       * Get the value of poster
       */
      public function getPoster()
      {
            return $this->poster;
      }

      /**
       * Set the value of poster
       *
       * @return  self
       */
      public function setPoster($poster)
      {
            $this->poster = $poster;

            return $this;
      }

      public function setPosterFile(File $image = null)

  {

    $this->imageFile = $image;

    if ($image) {

      $this->updatedAt = new DateTime('now');

    }

  }


    public function getPosterFile(): ?File

    {

        return $this->posterFile;

    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

   
}
