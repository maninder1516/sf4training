<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;

abstract class AbstractService {
   /**
    * @var \Doctrine\ORM\EntityRepository
    */
    protected $model;

    protected $em;

    /**
     * @param EntityManager $em
     * @param $entityName 
     */
    protected function __construct(EntityManager $em, $entityName) {
        $this->em = $em;
        $this->model = $em->getRepository($entityName);
    }

    /**
     * @return array
     */
    protected function findAll() {        
        return $this->model->findBy([], ['id' => 'DESC']);
        //return $this->model->findAll();                
    }   

    /**
     * @param $id  
     * @return null|object
     */
    protected function find($id)
    {   
        return $this->model->find($id);
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @return null|object
     */
    protected function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->model->findOneBy($criteria, $orderBy);
    }

    /**
     *  Save the Model
     */
    protected function save($object) {    
        $this->em->persist($object);
        $this->em->flush();
    }

    /**
     *  Delete the Model
     */
    protected function delete($object)  {   
        $this->em->remove($object);
        $this->em->flush();
    }

    protected function entityManager()
    {
        return $this->em;
    }

    abstract protected function getModel();
}


