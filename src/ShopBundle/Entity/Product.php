<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use SplFileInfo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductRepository")
 */
class Product
{
    const SERVER_PATH_TO_IMAGE_FOLDER = '/images';
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     */
    protected $description;

    /**
     * @ORM\Column(type="float")
     */
    protected $price;

    /**
     * @ORM\Column(type="string")
     */
    protected $photo;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="product")
     */
    protected $photos;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     */
    protected $category;


    /**
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="products")
     */
    protected $subcategory;


    /**
     * @ORM\OneToMany(targetEntity="ProductSpecification", mappedBy="product")
     */
    protected $productSpecifications;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return Product
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        return $this;
    }


    /**
     * @param UploadedFile|null $photo
     * @return $this
     */
    public function setFile(UploadedFile $photo = null)
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set subcategory
     *
     * @param \ShopBundle\Entity\Subcategory $subcategory
     *
     * @return Product
     */
    public function setSubcategory(Subcategory $subcategory = null)
    {
        if ($subcategory != null) {
            $this->setCategory($subcategory->getCategory());
        }
        $this->subcategory = $subcategory;
        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \ShopBundle\Entity\Subcategory
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productSpecifications = new ArrayCollection();
    }

    /**
     * Add productSpecification
     *
     * @param \ShopBundle\Entity\ProductSpecification $productSpecification
     *
     * @return Product
     */
    public function addProductSpecification(ProductSpecification $productSpecification)
    {
        if (!$this->productSpecifications->contains($productSpecification)) {
            $this->productSpecifications[] = $productSpecification;
            $productSpecification->setProduct($this);
        }
        return $this;
    }

    /**
     * Remove productSpecification
     *
     * @param \ShopBundle\Entity\ProductSpecification $productSpecification
     */
    public function removeProductSpecification(ProductSpecification $productSpecification)
    {
        if ($this->productSpecifications->contains($productSpecification)) {
            $this->productSpecifications->removeElement($productSpecification);
            if ($productSpecification->getProduct() === $this) {
                $productSpecification->setProduct(null);
            }
        }
    }

    /**
     * Get productSpecifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductSpecifications()
    {
        return $this->productSpecifications;
    }

    /**
     * Set category
     *
     * @param \ShopBundle\Entity\Category $category
     *
     * @return Product
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return \ShopBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function setProductSpecifications(ArrayCollection $productSpecifications = null)
    {
        $this->productSpecifications = $productSpecifications;
    }

    /**
     * Add photo
     *
     * @param \ShopBundle\Entity\Photo $photo
     *
     * @return Product
     */
    public function addPhoto(Photo $photo)
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setProduct($this);
        }
        return $this;
    }

    /**
     * Remove photo
     *
     * @param \ShopBundle\Entity\Photo $photo
     */
    public function removePhoto(Photo $photo)
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            if ($photo->getProduct() === $this) {
                $photo->setProduct(null);
            }
        }
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }


    public function setPhotos(ArrayCollection $photos = null)
    {
        $this->photos = $photos;
        return $this;
    }

    /**
     *
     */
    public function upload()
    {
        if (null === $this->getPhoto() || !($this->getPhoto() instanceof File)) {
            return;
        }
        $info = new SplFileInfo($this->getPhoto()->getClientOriginalName());
        $fileName = date('U') . '.' . $info->getExtension();
        $this->getPhoto()->move(WEB_DIRECTORY . Photo::SERVER_PATH_TO_IMAGE_FOLDER, $fileName);
        $this->photo = Product::SERVER_PATH_TO_IMAGE_FOLDER . '/' . $fileName;
    }


    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     *
     */
    public function refreshUpdated() { }

    function __toString()
    {
        return ($this->getName() != null) ? $this->getName() : '';
    }
}
