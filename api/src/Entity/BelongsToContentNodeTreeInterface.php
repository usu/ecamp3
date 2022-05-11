<?php

namespace App\Entity;

interface BelongsToContentNodeTreeInterface {
    public function getRoot(): ?ContentNode;
}
