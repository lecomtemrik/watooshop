<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $alink;

    #[ORM\Column(type: 'float')]
    private $rating;

    #[ORM\Column(type: 'decimal', precision: 7, scale: 2)]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $asin;

    #[ORM\ManyToOne(targetEntity: SubCategory::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $subcategory;

    #[ORM\ManyToOne(targetEntity: Rank::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $rank;

    #[ORM\Column(type: 'string', length: 255)]
    private $pathProduct;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $ratingTotal;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $reviewTotal;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Attribute::class, orphanRemoval: true)]
    private $attributes;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Review::class)]
    private $reviews;

    #[ORM\Column(type: 'string', length: 255)]
    private $brand;

    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $name): self
    {
        $this->title = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAlink(): ?string
    {
        return $this->alink;
    }

    public function setAlink(string $alink): self
    {
        $this->alink = $alink;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $note): self
    {
        $this->rating = $note;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAsin(): ?string
    {
        return $this->asin;
    }

    public function setAsin(string $asin): self
    {
        $this->asin = $asin;

        return $this;
    }

    public function getSubcategory(): ?SubCategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?SubCategory $subcategory): self
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    public function getRank(): ?Rank
    {
        return $this->rank;
    }

    public function setRank(?Rank $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getPathProduct(): ?string
    {
        return $this->pathProduct;
    }

    public function setPathProduct(string $pathProduct): self
    {
        $this->pathProduct = $pathProduct;

        return $this;
    }

    public function getRatingTotal(): ?int
    {
        return $this->ratingTotal;
    }

    public function setRatingTotal(?int $ratingTotal): self
    {
        $this->ratingTotal = $ratingTotal;

        return $this;
    }

    public function getReviewTotal(): ?int
    {
        return $this->reviewTotal;
    }

    public function setReviewTotal(?int $reviewTotal): self
    {
        $this->reviewTotal = $reviewTotal;

        return $this;
    }

    /**
     * @return Collection<int, Attribute>
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function addAttribute(Attribute $attribute): self
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes[] = $attribute;
            $attribute->setProduct($this);
        }

        return $this;
    }

    public function removeAttribute(Attribute $attribute): self
    {
        if ($this->attributes->removeElement($attribute)) {
            // set the owning side to null (unless already changed)
            if ($attribute->getProduct() === $this) {
                $attribute->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setProduct($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getProduct() === $this) {
                $review->setProduct(null);
            }
        }

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
