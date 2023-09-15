<?php

namespace Model\Managers;

//on utilise DAO pour ce connecter a la base de donnée
use App\DAO;

//on utilise App/manager ca c'est lui qui execute toute les requete sql !
use App\Manager;



class UserManager extends Manager
{

    protected $className = "Model\Entities\User";
    protected $tableName ="user";



    public function __construct()
    {
        parent::connect();
    }



    public function findOneByPseudo($data)
    {
        $sql = "SELECT *
        FROM ".$this->tableName." u
        WHERE u.pseudo = :pseudo
        ";
        return $this->getOneOrNullResult
        (
            DAO::select($sql, ['pseudo' => $data], false), 
            $this->className
        );
    }
    
    public function findUserById($id)
    {
        $sql = "SELECT *
        FROM ".$this->tableName." a
        WHERE a.id_".$this->tableName." = :id
        ";
        return $this->getOneOrNullResult
        (
            DAO::select($sql, ['id' => $id], false), 
            $this->className
        );
    }

    public function findUserBySearch($string)
    {
        // if(isset($_GET['user']))
        // {
        //     $user = (String) trim($_GET['user']);
         
            $sql = "SELECT id_user, pseudo, role
            FROM ".$this->tableName."
            WHERE pseudo LIKE :search ";

            $params = ["search" => '%'.$string.'%'];

            return $this->getMultipleResults
            (
                DAO::select($sql, $params),
                $this->className
            );
        // }
    }

    // Trouve tous les events créés par user_id
    public function findEventByUser($id)
    {
        // $orderQuery = ($order) ? "ORDER BY ".$order[0]." ".$order[1] : "";

        $sql = "SELECT * 
                FROM `event` 
                WHERE user_id = :id";
                // .$orderQuery;

        return $this->getMultipleResults(
                                            DAO::select($sql),
                                            $this->className
                                        );
    }

}
