<?php
namespace Model\Managers;

use App\Manager;
use App\DAO;

class LikeManager extends Manager
{
    protected $className = "Model\Entities\Like";
    protected $tableName = "like";
    public function __construct()
    {
        parent::connect();
    }



    // find a topic 
    public function findOneByTopic($data)
    {
        $sql = "SELECT * FROM `" . $this->tableName . "` u WHERE u.topic_id = :id ";
        return $this->getOneOrNullResult(DAO::select($sql, ['id' => $data], false), $this->className);
    }


    // find a pseudo 
    public function findOneByPseudo($user, $event)
    {
        $sql = "SELECT * FROM `" . $this->tableName . "` u WHERE u.user_id = ? AND u.event_id = ? ";
        return $this->getOneOrNullResult(DAO::select($sql, [$user, $event], false), $this->className);
    }

    public function findOneByEvent($eventId)
    {
        $sql = "SELECT *
                FROM ".$this->tableName." p
                WHERE event_id = :eventId
                ORDER BY creationDate DESC";
        return $this->getMultipleResults
        (
            DAO::select($sql, ['eventId' => $eventId]), 
            $this->className
        );
    }

    public function countLikes($eventID)
    {
        $sql = "SELECT COUNT(*) FROM `" . $this->tableName . "` l WHERE l.event_id = ?";
        return $this->getSingleScalarResult(DAO::select($sql, [$eventID], false));
    }

    // delete a like on a specific id of topic and user id 
    public function deleteLike($event, $user)
    {
        $sql = "DELETE FROM `" . $this->tableName . "` WHERE event_id = ? and user_id = ? ";
        return DAO::delete($sql, [$event, $user]);
    }

}