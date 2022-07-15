<?php

namespace App\Repositories;

interface ProductsRepositoryInterface {

	public function getProducts($descrip, $is_active, $instance);

	public function getInstances();
}
