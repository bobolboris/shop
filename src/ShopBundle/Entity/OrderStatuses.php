<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ordersstatuses")
 */
class OrderStatuses
{
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
     * @ORM\OneToMany(targetEntity="Order", mappedBy="status")
     */
    protected $orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }


    /**
     * Set id
     *
     * @param integer $id
     *
     * @return OrderStatuses
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

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
     * @return OrderStatuses
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
     * Add order
     *
     * @param \ShopBundle\Entity\Order $order
     *
     * @return OrderStatuses
     */
    public function addOrder(Order $order)
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setStatus($this);
        }
        return $this;
    }

    /**
     * Remove order
     *
     * @param \ShopBundle\Entity\Order $order
     */
    public function removeOrder(Order $order)
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            if ($order->getStatus() === $this) {
                $order->setStatus(null);
            }
        }
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }

    function __toString()
    {
        return ($this->getName() != null) ? $this->getName() : '';
    }


}
