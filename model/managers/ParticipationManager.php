<?php

namespace Model\Managers;

//on utilise DAO pour ce connecter a la base de données
use App\DAO;

//on utilise App/manager ca c'est lui qui execute toute les requete sql !
use App\Manager;

class ParticipationManager extends Manager
{ // On extends Manager pour pouvoir accéder à ses méthodes publiques 

    protected $className = "Model\Entities\Participation"; // arborescence des namespaces
    protected $tableName = "participation"; // C'est la table à laquelle on veut accéder.  

    /** Pourquoi Protected : 
     * Les propriétés et méthodes définies comme "protected" ne sont accessibles que : 
     * Depuis la classe elle-même et ses sous-classes (héritage)
     * Il est donc courant d'utiliser la portée "protected" : 
     * Pour des propriétés et des méthodes qui doivent être accessibles par les sous-classes du parent,
     * mais qui ne devraient pas être accessibles depuis l'extérieur de la classe ou ses sous-classes. 
     * Cela permet de mieux contrôler l'accès aux données et aux méthodes de la classe.
     * L'utilisation de la portée "protected" peut également faciliter la maintenance du code. 
     * Si une propriété ou une méthode est définie comme "protected" : 
     * Cela signifie que son comportement ne doit pas être modifié à l'extérieur de la classe ou ses sous-classes.
     * Permettant de garantir que le comportement de la classe reste cohérent et prévisible.
     * Et ce, même si des modifications sont apportées à l'implémentation interne de la classe.
     * En résumé, la portée "protected" est utile pour contrôler l'accès aux données et méthodes d'une classe.
     * Et pour faciliter la maintenance du code tout en garantissant la cohérence du comportement de la classe.
     */

    public function __construct()
    {
        parent::connect(); // On utilise le parent pour se connecter grâce à DAO
    }



    public function findOneByPseudo($user, $id)
    {
        $sql = "SELECT * 
        FROM `" . $this->tableName . "` u WHERE u.user_id = ? AND u.event_id = ? ";
        return $this->getOneOrNullResult(
            DAO::select($sql, [$user, $id], false),
            $this->className
        );
    }



    // Retrouver les Participations d'un User via son id 
    public function findParticipationsByUser($id)
    {
        $sql = "SELECT * FROM `" . $this->tableName . "`
                WHERE user_id = :id ";

        return $this->getMultipleResults
        (
            DAO::select($sql, ['id' => $id]),
            $this->className
        );
    }



    // Retrouver les Participations d'un Event via son id 
    public function findParticipationsByEvent($id, $order = null)
    {
        $orderQuery = ($order) ?
            "ORDER BY " . $order[0] . " " . $order[1] :
            "";

        $sql = "SELECT p.event_id, e.name, p.user_id, u.pseudo
                FROM participation p
                LEFT JOIN event e ON p.event_id = e.id_event
                LEFT JOIN user u ON p.user_id = u.id_user
                WHERE p.event_id = :id"
            . $orderQuery;

        return $this->getMultipleResults(
            DAO::select($sql),
            $this->className
        );
    }



    // Pour calculer le nombre de places disponibles : 
    // Il faut d'abord compter le nombre de places maximum, puis de soustraire à ce dernier celui des participants déjà inscrits.

    public function countParticipations($id)
    {
        $sql = "SELECT COUNT(*) FROM `" . $this->tableName . "` p WHERE p.event_id = ?";

        return $this->getSingleScalarResult(DAO::select($sql, [$id], false));
    }



    public function retire($event_id, $user_id)
    {
        $sql = "DELETE FROM " . $this->tableName . "
            WHERE event_id = :event_id AND user_id = :user_id
            ";

        return DAO::delete($sql, ['event_id' => $event_id, 'user_id' => $user_id]);
    }
}