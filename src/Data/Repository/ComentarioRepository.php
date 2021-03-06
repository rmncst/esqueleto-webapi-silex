<?php

namespace Data\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ComentarioRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComentarioRepository extends EntityRepository
{
    public function getComentariosPorPost(int $postId)
    {
        return $this->findBy(['idPost' => $postId]);        
    }
}
