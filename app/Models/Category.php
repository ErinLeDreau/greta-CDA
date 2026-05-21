<?php

namespace App\Models;

use App\Models\Traits\GeneralTrait;
use App\Models\Traits\IdentifiableTrait;
use App\Models\Traits\TimestampableTrait;
use DateTime;

class Category
{
    use TimestampableTrait;
    use IdentifiableTrait;
    use GeneralTrait;

    public function __construct(int $id, string $name = '', string $description = '', ?DateTime $createdAt = null, ?DateTime $updatedAt = null)
    {
        $this->initGeneralTrait($name, $description);
        $this->initIdentifiableTrait($id);
        $this->initTimestampableTrait($createdAt, $updatedAt);

    }

    
    /**
     * @param array $data
     * @return Category
     */
    public static function fromArray(array $data): Category
    {
        return new Category(
            $data['id'],
            $data['name'],
            $data['description'],
            $data['created_at'] ? new DateTime($data['created_at']) : null,
            $data['updated_at'] ? new DateTime($data['updated_at']) : null
        );
    }
}
