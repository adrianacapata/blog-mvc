<?php

namespace Blog\Model\Repository;

use Blog\Model\BaseRepository;

class CategoryRepository extends BaseRepository
{

    public function getCategories()
    {
        $conn = $this->getConnection();
        $stmt = $conn->query("Select * FROM `category`");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}