<?php
    namespace Model\Managers;
    //on utilise dao pour ce connecter a la base de donnée
    use App\DAO;
    //on utilise App/manager ca c'est lui qui execute toute les requete sql !
    use App\Manager;



    class PostManager extends Manager
    {
        protected $className = "Model\Entities\Post";
        protected $tableName ="post";

        public function __construct()
        {
            parent::connect();
        }



        public function findPostByUserId($id)
        {
            $sql = "SELECT *
            FROM ".$this->tableName."
            WHERE user_id = :id";
            
            return $this->getMultipleResults
            (
                DAO::select($sql, ['id' => $id]), 
                $this->className
            );
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


        
        public function findByTopic($idTopic)
        {
            $sql = "SELECT *
                    FROM ".$this->tableName." p
                    WHERE topic_id = :topicid
                    ORDER BY creationDate DESC";
            return $this->getMultipleResults
            (
                DAO::select($sql, ['topicid' => $idTopic]), 
                $this->className
            );
        }
        

        public function addPost($topicId, $userId, $message)
        {
            $data = [
                "topic_id" => $topicId,
                "user_id" => $userId,
                "message" => $message
            ];
            $this->add($data);
        }

        
        
    }