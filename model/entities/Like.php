<?php
    namespace Model\Entities;

    use App\Entity;

    final class Like extends Entity
    {
        private Event $event ;
        private User $user;

        public function __construct($data)
        {         
            $this->hydrate($data);        
        }

        /**
         * Get the value of user
         */ 
        public function getUser()
        {
                return $this->user;
        }

        /**
         * Set the value of user
         *
         * @return  self
         */ 
        public function setUser($user)
        {
                $this->user = $user;

                return $this;
        }

        /**
         * Get the value of event
         */ 
        public function getEvent()
        {
                return $this->event;
        }

        /**
         * Set the value of event
         *
         * @return  self
         */ 
        public function setEvent($event)
        {
                $this->event = $event;

                return $this;
        }
    }

?>