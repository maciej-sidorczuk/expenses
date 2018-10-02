<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenseRepository")
 */
class Expense
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $purchase_date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $total_price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place")
     */
    private $place_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentMethod")
     * @ORM\JoinColumn(nullable=false)
     */
    private $payment_method_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TypeOfExpense")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type_of_expense_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CategoryOfExpense")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category_of_expense_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(\DateTimeInterface $purchase_date): self
    {
        $this->purchase_date = $purchase_date;

        return $this;
    }

    public function getProductId(): ?Product
    {
        return $this->product_id;
    }

    public function setProductId(?Product $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->total_price;
    }

    public function setTotalPrice(float $total_price): self
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getPlaceId(): ?Place
    {
        return $this->place_id;
    }

    public function setPlaceId(?Place $place_id): self
    {
        $this->place_id = $place_id;

        return $this;
    }

    public function getPaymentMethodId(): ?PaymentMethod
    {
        return $this->payment_method_id;
    }

    public function setPaymentMethodId(?PaymentMethod $payment_method_id): self
    {
        $this->payment_method_id = $payment_method_id;

        return $this;
    }

    public function getTypeOfExpenseId(): ?TypeOfExpense
    {
        return $this->type_of_expense_id;
    }

    public function setTypeOfExpenseId(?TypeOfExpense $type_of_expense_id): self
    {
        $this->type_of_expense_id = $type_of_expense_id;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getCategoryOfExpenseId(): ?CategoryOfExpense
    {
        return $this->category_of_expense_id;
    }

    public function setCategoryOfExpenseId(?CategoryOfExpense $category_of_expense_id): self
    {
        $this->category_of_expense_id = $category_of_expense_id;

        return $this;
    }
}
