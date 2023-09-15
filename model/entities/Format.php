<?php
    namespace Model\Entities;

    use App\Entity;

    final class Format extends Entity
    {
        private $id;
        private $label;
        private $rules;

        public function __construct($data)
        {         
            $this->hydrate($data);        
        }

        /**
         * Get the value of id_format
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of id_format
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of label
         */ 
        public function getLabel()
        {
                return $this->label;
        }

        /**
         * Set the value of label
         *
         * @return  self
         */ 
        public function setLabel($label)
        {
                $this->label = $label;

                return $this;
        }

        /**
         * Get the value of rules
         */ 
        public function getRules()
        {
                return $this->rules;
        }

        /**
         * Set the value of rules
         *
         * @return  self
         */ 
        public function setRules($rules)
        {
                $this->rules = $rules;

                return $this;
        }

    }

?>