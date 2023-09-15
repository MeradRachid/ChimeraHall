<?php

namespace Model\Managers;

//on utilise DAO pour ce connecter a la base de données
use App\DAO;

//on utilise App/manager car c'est lui qui execute toute les requete sql !
use App\Manager;

class EventManager extends Manager
{ // On extends Manager pour pouvoir accéder à ses méthodes publiques 

    protected $className = "Model\Entities\Event"; // arborescence des namespaces
    protected $tableName = "event"; // C'est la table à laquelle on veut accéder.  

    /** Pourquoi Protected : 
     * Les propriétés et méthodes définies comme "protected" ne sont accessibles que : 
     * Depuis la classe elle-même et ses sous-classes (héritage)
     * Il est donc courant d'utiliser la portée "protected" : 
     * Pour des propriétés et des méthodes qui doivent être accessibles par les sous-classes du parent,
     * mais qui ne devraient pas être accessibles depuis l'extérieur de la classe ou ses sous-classes. 
     * Cela permet de mieux contrôler l'accès aux données et aux méthodes de la classe.
     * L'utilisation de la portée "protected" peut également faciliter la maintenance du code. 
     * Si une propriété ou une méthode est définie comme "protected" : 
     * Cela signifie que son comportement ne doit pas être modifié à l'extérieur de la classe ou de ses sous-classes.
     * Permettant de garantir que le comportement de la classe reste cohérent et prévisible.
     * Et ce, même si des modifications sont apportées à l'implémentation interne de la classe.
     * En résumé, la portée "protected" est utile pour contrôler l'accès aux données et méthodes d'une classe.
     * Et pour faciliter la maintenance du code tout en garantissant la cohérence du comportement de la classe.
    */

    public function __construct()
    {
        parent::connect(); // On utilise le parent pour se connecter grâce à DAO
    }    



    public function update($id, $data)
    {
        $sql = "UPDATE " . $this->tableName . " 
                SET name = :name, 
                    type_id = :type_id, 
                    format_id = :format_id, 
                    dateTime = :dateTime, 
                    adress = :adress, 
                    zipCode = :zipCode, 
                    city = :city, 
                    nbMaxPlayer = :nbMaxPlayer
                    WHERE id_event = :id";
        
        return DAO::update(
                                $sql,
                                [
                                    'id' => $id,
                                    'name' => $data['name'],
                                    'type_id' => $data['type_id'],
                                    'format_id' => $data['format_id'],
                                    'dateTime' => $data['dateTime'],
                                    'adress' => $data['adress'],
                                    'zipCode' => $data['zipCode'],
                                    'city' => $data['city'],
                                    'nbMaxPlayer' => $data['nbMaxPlayer']
                                ]
                            );
    }



    // Trouve tous les events créés par user_id
        public function findEventsByUser($id)
        {
            // $orderQuery = ($order) ? "ORDER BY ".$order[0]." ".$order[1] : "";
    
            $sql = "SELECT * 
                    FROM `".$this->tableName.
                    "`WHERE user_id = :id";
                    // .$orderQuery;
    
            return $this->getMultipleResults
            (
                DAO::select($sql, ['id' => $id]),
                $this->className
            );
        }

        public function findEventsBySearch($string)
        {
            // if(isset($_GET['search']))
            // {
            //     $events = (String) trim($_GET['search']);
             
                $sql = "SELECT *
                FROM ".$this->tableName."
                WHERE name LIKE :search ";
    
                $params = ["search" => '%'.$string.'%'];
    
                return $this->getMultipleResults
                (
                    DAO::select($sql, $params),
                    $this->className
                );
            // }
        }

        public function findEventsByName($name)
        {
            // var_dump($name);
            // die();

            $sql= "SELECT * 
                   FROM `".$this->tableName.
                   "`WHERE name LIKE % :val %";        
           // LIKE ‘% val %’ : recherche tous les enregistrements qui contiennent la variable “$name” 
           
            return $this->getMultipleResults
            (
                var_dump($name),
                DAO::select($sql, [$name])
                // $this->className
                // var_dump($val)
            );
            // die();
        }

        public function findEventsByFilter($typeId, $formatLabel)
        {
            $sql = "SELECT *
                    FROM `".$this->tableName."`
                    WHERE type_id = 1 OR 2 OR 3
                    AND format_id = 1 OR 2 OR 3 OR 4";
        }



    // public function getFormatIdByLabel($label)
    // {
    //     $sql = "SELECT id_format FROM format WHERE label = :label";
    //     $result = DAO::select($sql, ['label' => $label]);
    //     $row = $result->fetchColumn();
    //     return $row ? $row['id_format'] : null;
    // }

    public function findInfoById($id)
    {
        $sql = 'SELECT ev.*, t.*, ft.*
        FROM event ev
        JOIN type t ON t.id_type = ev.type_id
        JOIN format ft ON ft.id_format = ev.format_id
        WHERE ev.id_event = :id';
                                            
            return $this->getOneOrNullResult(
                                                DAO::select($sql, ['id' => $id], false) ?: [], 
                                                $this->className
                                            /* * 
                                             * Le ?: signifie que l'on évalue l'expression à gauche, et si elle est équivalente à false, alors on retourne l'expression à droite.
                                             * Dans ce cas-ci, si DAO::select($sql, ['id' => $id], false) ne retourne pas de résultat, alors on retourne un tableau vide []
                                             * Cela permet de s'assurer que la méthode getOneOrNullResult ne recevra jamais un paramètre nul ou indéfini.
                                             * Ce qui pourrait causer une erreur dans le reste du code.
                                             * */

                                            );
    }

    public function findIdByLabel($label)
    {
        $sql = 'SELECT id_format FROM format WHERE label = :label';
        $result = DAO::select($sql, ['label' => $label]);

        if (!$result) 
        {
            return null;
        }

        $row = $result[0];
        return $row['id_format'];
    }



    public function findRallyBySearch($string)
    {
        // if(isset($_GET['search']))
        // {
        //     $events = (String) trim($_GET['search']);
         
            $sql = "SELECT *
            FROM ".$this->tableName."
            WHERE adress LIKE :search ";

            $params = ["search" => '%'.$string.'%'];

            return $this->getMultipleResults
            (
                DAO::select($sql, $params),
                $this->className
            );
        // }
    }
    

    
    public function rallyCount()
    {
        $sql = 'SELECT COUNT(DISTINCT e.adress) AS RallyPoint FROM rally e';

        $result = DAO::select($sql);
    
        return $this->getSingleScalarResult($result);
    }

    public function userCount()
    {
        $sql = 'SELECT COUNT(DISTINCT e.pseudo) AS Planeswalker FROM user e';

        $result = DAO::select($sql);

        return $this->getSingleScalarResult($result);
    }

    public function totalEventCount()
    {
        $sql = 'SELECT COUNT(id_event) AS TotalEvent FROM event';
    
        $result = DAO::select($sql);
    
        return $this->getSingleScalarResult($result);
    }
    
    public function currentEventCount()
    {
        $sql = 'SELECT COUNT(id_event) AS CurrentEvent FROM `event` WHERE eventLocked = 0';
        
        $result = DAO::select($sql);
        
        return $this->getSingleScalarResult($result);
    }

    public function crewMateEventCount()
    {
        $sql = 'SELECT COUNT(DISTINCT user_id) AS `Crewmate` FROM `event`';
        
        $result = DAO::select($sql);
        
        return $this->getSingleScalarResult($result);
    }
    


}
