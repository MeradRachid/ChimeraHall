<?php
    namespace Model\Entities;

    use App\Entity;

    final class User extends Entity
    {
        private $id;
        private $pseudo;
        private $role;
        private $email;
        private $zipCode;
        private $password;
        private $registered;
        private $avatar;
        private $ban; 

        public function __construct($data)
        {         
            $this->hydrate($data);        
        }
 
        /**
         * Get the value of (user) id
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of (user) id
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of pseudo
         */ 
        public function getPseudo()
        {
                return $this->pseudo;
        }

        /**
         * Set the value of pseudo
         *
         * @return  self
         */ 
        public function setPseudo($pseudo)
        {
                $this->pseudo = $pseudo;

                return $this;
        }
    
        /**
         * Get the value of role
         */ 
        public function getRole()
        {
                return $this->role;
        }
     
        public function setRole($role)
        { 
                $this->role = json_decode($role);
                 if(empty($this->role))
                 { 
                        $this->role = "ROLE_USER"; 
                 } 
        } 
        
        public function hasRole($role)
        {
                $result = $this->role == $role; 
                return $result; 
        }

        /**
         * Get the value of email
         */ 
        public function getEmail()
        {
                return $this->email;
        }

        /**
         * Set the value of email
         *
         * @return  self
         */ 
        public function setEmail($email)
        {
                $this->email = $email;

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
         * Get the value of password
         */ 
        public function getPassword()
        {
                return $this->password;
        }

        /**
         * Set the value of password
         *
         * @return  self
         */ 
        public function setPassword($password)
        {
                $this->password = $password;

                return $this;
        }

        public function getRegistered()
        {
            $formattedDate = $this->registered->format("l/d/M/Y - H:i:s");
            return $formattedDate;
        }

        public function setRegistered($date)
        {
            $this->registered = new \DateTime($date);
            return $this;
        }

        /**
         * Get the value of avatar
         */ 
        public function getAvatar()
        {
                return $this->avatar;
        }

        /**
         * Set the value of avatar
         *
         * @return  self
         */ 
        public function setAvatar($avatar)
        {
                $this->avatar = $avatar;

                return $this;
        }


        /**
         * Get the value of ban
         */ 
        public function getBan()
        {
                return $this->ban;
        }

        /**
         * Set the value of ban
         *
         * @return  self
         */ 
        public function setBan($ban)
        {
                $this->ban = $ban;

                return $this;
        }

    }

?>