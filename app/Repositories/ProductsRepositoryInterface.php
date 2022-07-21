<?php

namespace App\Repositories;

interface ProductsRepositoryInterface {

	public function getProducts($descrip, $is_active, $instance, $exists);

	public function getInstances();

	public function getSuggestions($cod_product);

	public function getProductByID($cod_product);

	public function getTotalCostProducts();

	public function getProductsBySuggestionStatus($status);
}
