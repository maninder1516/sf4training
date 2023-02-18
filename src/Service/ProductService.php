<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;

class ProductService extends AbstractService {

    public function __construct(EntityManager $em, $entityName) {
        $this->em = $em;
        $this->model = $em->getRepository($entityName);
    }

    public function getModel() {
        return $this->model;
    }

    public function getAllProducts() {
        return $this->findAll();
    }

    public function getProduct($product_id) {
        return $this->find($product_id);
    }

    public function addProduct() {        
        return $this->save();
    }

    /**
     * Delete the product
     */
    public function deleteProduct($id) {
        return $this->delete($this->find($id));
    }

}
