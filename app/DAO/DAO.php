<?php

namespace DAO;

use Illuminate\Database\Eloquent\Model;

    // Parent class
abstract class DAO {

    public Model $entity;
  
    //CRUD
    abstract public function create(array $fields) : bool;
  }
