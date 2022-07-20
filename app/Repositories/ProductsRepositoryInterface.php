<?php

namespace App\Repositories;

interface ProductsRepositoryInterface {

	public function getProducts($descrip, $is_active, $instance);

	public function getInstances();

	public function getSuggestions($cod_product);

	public function getTotalCostProducts();

	public function getProductsBySuggestionStatus($status, $cod_prods);
}
