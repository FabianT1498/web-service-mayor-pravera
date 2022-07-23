<?php

namespace App\Repositories;

interface ProductsRepositoryInterface {

	public function getProducts($descrip, $is_active, $instance, $exists, $conn);

	public function getInstances($conn);

	public function getSuggestions($cod_product);

	public function getProductByID($cod_product);

	public function getTotalCostProducts($conn);

	public function getProductsBySuggestionStatus($status);
}
