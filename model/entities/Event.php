<?php
    namespace Model\Entities;

    use App\Entity;

        /**
         * Dans les entities, les propriétés portent le même nom que les champs de la base de données.
         * Sauf pour la clé primaire et les clés étrangères où on enlève le "_id" ou "id_".
         * Chaque Entity va hériter de la classe Entity (dans le dossier App) et toutes les Entities auront exactement le même constructeur : 
         * Il implémente la méthode "hydrate" (de cette même classe Entity)
        */

    final class Event extends Entity
    {
        private $id;
        private $rally;
        private $user;
        private $type;
        private $format;
        private $name;
        private $dateTime;
        private $description;
        private $nbMaxPlayer;
        private $eventLocked;

        public function __construct($data)
        {         
            $this->hydrate($data);        
        }
 
        /**
         * Get the value of (Event) id
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of (Event) id
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of Rally Point
         */ 
        public function getRally()
        {
                return $this->rally;
        }

        /**
         * Set the value of Rally Point
         *
         * @return  self
         */ 
        public function setRally($rally)
        {
                $this->rally = $rally;

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
        
        /**
         * Get the value of type_id
         */ 
        public function getType()
        {
                return $this->type;
        }

        /**
         * Set the value of type_id
         *
         * @return  self
         */ 
        public function setType($type)
        {
                $this->type = $type;

                return $this;
        }

        /**
        * Get the value of format_id
        */ 
        public function getFormat()
        {
                return $this->format;
        }

        /**
         * Set the value of format_id
         *
         * @return  self
         */ 
        public function setFormat($format)
        {
                $this->format = $format;

                return $this;
        }
        
        /**
         * Get the value of name
         */ 
        public function getName()
        {
                return $this->name;
        }

        /**
         * Set the value of name
         *
         * @return  self
         */ 
        public function setName($name)
        {
                $this->name = $name;

                return $this;
        }

        public function getDateTime()
        {

                return $this->dateTime; // ->format('Y-m-d ~H:i'); // ("l d/M/Y ~H\Hi"); // Le symbole "\" permet d'échapper le caractère suivant et le traite comme un caractère littéral. 
        }

        public function setDateTime($date)
        {
            $this->dateTime = new \DateTime($date);
            return $this;
        }

        /**
         * Get the value of description
         */ 
        public function getDescription()
        {
                return $this->description;
        }

        /**
         * Set the value of description
         *
         * @return  self
         */ 
        public function setDescription($description)
        {
                $this->description = $description;

                return $this;
        }

        /**
         * Get the value of nbMaxPlayer
         */ 
        public function getNbMaxPlayer()
        {
                return $this->nbMaxPlayer;
        }

        /**
         * Set the value of nbMaxPlayer
         *
         * @return  self
         */ 
        public function setNbMaxPlayer($nbMaxPlayer)
        {
                $this->nbMaxPlayer = $nbMaxPlayer;

                return $this;
        }

        /**
         * Get the value of eventLocked
         */ 
        public function getEventLocked()
        {
                return $this->eventLocked;
        }

        /**
         * Set the value of eventLocked
         *
         * @return  self
         */ 
        public function setEventLocked($eventLocked)
        {
                $this->eventLocked = $eventLocked;

                return $this;
        }

    }

?>