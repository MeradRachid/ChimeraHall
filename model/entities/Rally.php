<?php
    namespace Model\Entities;

    use App\Entity;

        /**
         * Dans les entities, les propriétés portent le même nom que les champs de la base de données.
         * Sauf pour la clé primaire et les clés étrangères où on enlève le "_id" ou "id_".
         * Chaque Entity va hériter de la classe Entity (dans le dossier App) et toutes les Entities auront exactement le même constructeur : 
         * Il implémente la méthode "hydrate" (de cette même classe Entity)
        */

    final class Rally extends Entity
    {
        private $id;
        private $user;
        private $spot;
        private $adress;
        private $zipCode;
        private $city;
        private $active;

        public function __construct($data)
        {         
            $this->hydrate($data);        
        }

        /**
         * Get the value of (Rally Point) id
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of (Rally Point) id
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

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
         * Get the value of spot
         */ 
        public function getSpot()
        {
                return $this->spot;
        }

        /**
         * Set the value of spot
         *
         * @return  self
         */ 
        public function setSpot($spot)
        {
                $this->spot = $spot;

                return $this;
        }

        /**
         * Get the value of adress
         */ 
        public function getAdress()
        {
                return $this->adress;
        }

        /**
         * Set the value of adress
         *
         * @return  self
         */ 
        public function setAdress($adress)
        {
                $this->adress = $adress;

                return $this;
        }

        /**
         * Get the value of zipCode
         */ 
        public function getZipCode()
        {
                return $this->zipCode;
        }

        /**
         * Set the value of zipCode
         *
         * @return  self
         */ 
        public function setZipCode($zipCode)
        {
                $this->zipCode = $zipCode;

                return $this;
        }

        /**
         * Get the value of city
         */ 
        public function getCity()
        {
                return $this->city;
        }

        /**
         * Set the value of city
         *
         * @return  self
         */ 
        public function setCity($city)
        {
                $this->city = $city;

                return $this;
        }

        /**
         * Get the value of active
         */ 
        public function getActive()
        {
                return $this->active;
        }

        /**
         * Set the value of active
         *
         * @return  self
         */ 
        public function setActive($active)
        {
                $this->active = $active;

                return $this;
        }
    }