<?php
namespace App;

abstract class Manager
{

    protected function connect()
    {
        DAO::connect(); // On utilise Dao qui nous permettra de se connecter sans avoir à faire de new pdo 
    }

    /**
     * get all the records of a table, sorted by optionnal field and order
     * 
     * @param array $order an array with field and order option
     * @return Collection a collection of objects hydrated by DAO, which are results of the request sent
     */

    // Le a dans les requêtes SQL fait référence à un alias de table ($this->tableName = a ) 

    public function findAll($order = null)
    {
        $orderQuery = ($order) ?
            "ORDER BY " . $order[0] . " " . $order[1] :
            "";

        $sql = "SELECT *
                    FROM " . $this->tableName . " a
                    " . $orderQuery;

        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }

    public function findOneById($id)
    {
        $sql = "SELECT *
                    FROM " . $this->tableName . " a
                    WHERE a.id_" . $this->tableName . " = :id
                    ";

        return $this->getOneOrNullResult(
            DAO::select($sql, ['id' => $id], false),
            $this->className
        );
    }

    //$data = ['pseudo' => 'Squalli', 'password' => 'dfsyfshfbzeifbqefbq', 'email' => 'sql@gmail.com'];

    public function add($data, $debug = false)
    {

        //$keys = ['pseudo' , 'password', 'email']
        $keys = array_keys($data);
        //$values = ['Squalli', 'dfsyfshfbzeifbqefbq', 'sql@gmail.com']
        $values = array_values($data);
        //"pseudo,password,email"
        $sql = "INSERT INTO `" . $this->tableName . "`
                    (" . implode(',', $keys) . ") 
                    VALUES
                    ('" . implode("','", $values) . "')";
        //"'Squalli', 'dfsyfshfbzeifbqefbq', 'sql@gmail.com'"
        /*
            INSERT INTO user (pseudo,password,email) VALUES ('Squalli', 'dfsyfshfbzeifbqefbq', 'sql@gmail.com') 
        */

        if ($debug) {
            var_dump($sql);
        }

        /* 
            Lors de l'appel de la méthode add, passez en deuxième paramètre true activera le mode debug :

                $eventManager->add($data, true);
                
            Cela affichera la requête SQL générée par la méthode add.
        */

        try {
            return DAO::insert($sql);
        } catch (\PDOException $e) {
            echo $e->getMessage();
            die();
        }
    }



    public function edit($data, $id = null) //  = null, signifie qu'il pourrait être omis lors de l'appel
    {
        // Extraction des clés du tableau $data
        $keys = array_keys($data);
        //$keys = ['username' , 'password', 'email', etc.. ]
    
        // Extraction des valeurs du tableau $data
        $values = array_values($data);
        //$values = ['Squalli', 'dfsyfshfbzeifbqefbq', 'sql@gmail.com', ...]
    
        // var_dump($keys, $values);

        // Construction de la requête SQL d'update
        $sql = "UPDATE " . $this->tableName . " SET " . implode(',', $keys) . " = '" . implode("','", $values) . "' WHERE id_" . $this->tableName . " = :id ";
    
        try 
        {
            // Exécution de la requête SQL
            return DAO::update($sql, ["id" => $id]);
        } 
         catch (\PDOException $e) 
        {
            // En cas d'erreur, affichage du message d'erreur et interruption du script
            echo $e->getMessage();
            die();
        }
    }
    


    public function delete($id)
    {
        $sql = "DELETE FROM " . $this->tableName . "
                    WHERE id_" . $this->tableName . " = :id
                    ";

        return DAO::delete($sql, ['id' => $id]);
    }

    private function generate($rows, $class)
    {
        foreach ($rows as $row) {
            yield new $class($row);
        }
    }

    protected function getMultipleResults($rows, $class)
    {

        if (is_iterable($rows)) {
            return $this->generate($rows, $class);
        } else
            return null;
    }



    protected function getOneOrNullResult($row, $class)
    {

        if ($row != null) {
            return new $class($row);
        }
        return false;
    }



    /**
     * Récupère la valeur scalaire unique à partir du résultat d'une requête SQL
     *
     * @param mixed $row Le résultat de la requête SQL
     * @return mixed La valeur scalaire unique extraite du résultat
     */
    protected function getSingleScalarResult($row)
    {
        // Vérifie si le résultat est un tableau non vide
        if (is_array($row) && count($row) > 0) 
        {
            // Convertit le tableau en un tableau de valeurs
            $value = array_values($row);
            
            // Récupère la première valeur du tableau (valeur scalaire unique)
            return $value[0];
        }
        // Aucun résultat trouvé ou le résultat n'est pas un tableau
        return false;
    }

    


}