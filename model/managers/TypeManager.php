<?php

namespace Model\Managers;

//on utilise DAO pour ce connecter a la base de données
use App\DAO;

//on utilise App/manager ca c'est lui qui execute toute les requete sql !
use App\Manager;

class TypeManager extends Manager
{ // On extends Manager pour pouvoir accéder à ses méthodes publiques 

    protected $className = "Model\Entities\Type"; // arborescence des namespaces
    protected $tableName = "type"; // C'est la table à laquelle on veut accéder.  

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


    
    public function typeEventCount()
    {
        $sql = 'SELECT COUNT(id_type) AS `TypeEvent` FROM `type`';
    
        $result = DAO::select($sql);
    
        return $this->getSingleScalarResult($result);
    }
    


}