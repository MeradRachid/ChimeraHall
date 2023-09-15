<?php
    namespace Model\Entities;

    use App\Entity;

    

    final class Participation extends Entity
    {
        private $event;

        private $user;

        // private $likes;

        public function __construct($data)
        {         
            $this->hydrate($data);        
        }

        /**
         * Get the value of event_id
         */ 
        public function getEvent()
        {
                return $this->event;
        }

        /**
         * Set the value of event_id
         *
         * @return  self
         */ 
        public function setEvent($event)
        {
                $this->event = $event;

                return $this;
        }

        /**
         * Get the value of user_id
         */ 
        public function getUser()
        {
                return $this->user;
        }

        /**
         * Set the value of user_id
         *
         * @return  self
         */ 
        public function setUser($user)
        {
                $this->user = $user;

                return $this;
        }
    }

?>