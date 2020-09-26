<?php
namespace App\Core\Query;

interface QueryInterface
{
    // insert
    /**
     * Insert Query
     * @param Array|String|Integer
     */
    public function insert(Array $data);

    /**
     * Insert Batch
     * with array has value
     * 
     * @param Array|MultipleArray
     */
    public function insertBatch();

    /**
     * Insert Id
     * Get last Id when store data
     * 
     * @param Array|String|Integer
     * @return id
     */
    public function insertId(Array $data);

    // update
    /**
     * Update Query
     * @param Array|String|Integer
     */
    public function update(Array $data);
    
    /**
     * Update or insert
     * but insert when data hasn't exist same primary
     * 
     * @param Array|String|Integer
     */
    public function updateOrInsert();

    /**
     * Update batch
     * with array has value
     * 
     * @param Array|MultipleArray
     */
    public function updateBatch();

    /**
     * increment & Decrement
     * update when want to increase or decrease values
     * 
     * @return Integer
     */
    public function increment();
    public function decrement();
    
    // delete
    /**
     * Delete Query
     * @param Array|String|Integer
     */
    public function delete();

    /**
     * Empty table & truncate
     * this action will delete all data from the table
     * 
     * @param String
     */
    public function emptyTable();
    public function truncate();

    // join
    public function join(String $Table);

    /**
     * Condition Join
     * use on clause when add join query
     * 
     * @param String|Integer
     */
    public function on($condition1, $condition2);

    /**
     * LeftJoin & RightJoin Query
     * @param TableName
     */
    public function leftJoin();
    public function rightJoin();


    // mysql
    /**
     * Num rows || change to field count
     * return count data from rows table
     * 
     * @param Query
     */
    public function field_count();

    /**
     * Affected rows
     * get data by last action
     * 
     * @param Query
     */
    public function affected_rows();
// -------------------------------------------------------------------------------------------------------
    // where clause
    /**
     * Where
     * 
     * key
     * @param String|Array|Int
     * condition
     * @param Null|String|Int|Operator
     * value
     * @param Null|String|Int
     * 
     */
    public function where($key, $cond, $value);

    /**
     * Where in
     * condition when data was found similarity
     * 
     * Where not in
     * condition when data wasn't found similarity
     */
    public function whereIn();
    public function whereNotIn();

    /**
     * Where between
     * condition when data between the params
     * 
     * Where not between
     * condition data where data isn't not in between 
     */
    public function whereBetween();
    public function whereNotBetween();

    /**
     * Where null
     * condition when data or field is null
     * 
     * Where not null
     * condition data or field isn't null
     */
    public function whereNull();
    public function whereNotNull();

    /**
     * Where date
     * condition check by date
     */
    public function whereDate();
    
    /**
     * Where day
     * condition check by day
     */
    public function whereDay();

    /**
     * Where year
     * condition check by year
     */
    public function whereYear();
    
    /**
     * Where time
     * condition check by time
     */
    public function whereTime();

    /**
     * Where column
     * condition check by column
     */
    public function whereColumn();
    
    /**
     * Where Exist
     * condition check when data is exist
     */
    public function whereExist();

    // like & not like
    public function like();
    public function notLike();

    // having clause
    public function having();
    public function havingIn();
    public function havingNotIn();
    public function havingLike();
    public function notHavingLike();

    // aggregates
    public function min();
    public function max();
    public function average();
    public function sum();
    public function count();

    // ofset & limit
    public function limit();
    public function offset();
    public function latest();
    public function first();

    // order & group
    public function groupBy();
    public function orderBy();

    // free sql
    public function whereRaw();
    public function when();

    // select column
    public function select(Array $data);

    // multiple query
    public function union();

    // run query
    public function get();
}