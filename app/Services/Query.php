<?php

declare(strict_types=1);

namespace App\Services;

/**
 * This will be repository for building queries, it will call itself and allow for a detailed construction of a query
 * we can go along modyfing the query with the help of the methods in this class
 */


class Query
{
    protected \PDO $pdo;
    protected string $query;
    protected array $bindings = [];

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    /**
     * This function will allow us to select columns from a table
     * @param array|string $columns can do either format :
     * apples,bananas,oranges or ['apples', 'bananas', 'oranges']
     */
    public function select(array|string $columns = '*')
    {
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $this->query = "SELECT {$columns} ";
        return $this;
    }

    /**
     * This function will allow us to insert into a table, using a table name
     * @param string $table
     */
    public function insertInto(string $table, array $columns)
    {
        $this->query = "INSERT INTO {$table} ";
        $this->query .= "(" . implode(', ', $columns) . ") ";
        return $this;
    }

    /**
     * This function will allow us to set the values of the columns we are inserting into
     * NOTE: Also sets the binding for later use when executing
     * @param array $values
     */
    public function values(array $values)
    {
        $this->query .= "VALUES (";
        foreach ($values as $key => $value) {
            $this->query .= ":{$key}, ";
            $this->bindings[":{$key}"] = $value; // formatting the bindings for execute() function
            // might be unnecessary to have the colon in the key if only this function existed but it follows the philosphy of
            // placeholder will have ':' and the bindings will have keys that are the placeholders so :key => value
        }
        $this->query = rtrim($this->query, ', ');
        $this->query .= ") ";
        return $this;
    }

    /**
     * This function will allow us enter in a table
     * @param string $table
     */
    public function from(string $table)
    {
        $this->query .= "FROM {$table} ";
        return $this;
    }

    /**
     * This function creates the where clause in the query, be it a singluar (string) or multiple (array) 
     * @param array|string $columns
     * @param string $operator
     * @param string|array $values 
     * 
     * @throws \Exception if columns and values are not of the same type
     * @throws \Exception if inside the columns or values there is an invalid type
     */
    public function where(array|string $columns, string $operator, string|array $values)
    {
        /**
         * Since allowing arrays is risky we will check that the types inside are still valid 
         * and be sure we can still convert them to strings (for the SQL query) 
         */
        $prohibited_types = ['object', 'resource', 'NULL']; // to test later and add more types
        if (in_array(gettype($columns), $prohibited_types) || in_array(gettype($values), $prohibited_types)) {
            throw new \Exception('Invalid type passed');
        }

        match (true) {
            // both arrays
            is_array($columns) && is_array($values)
            => $this->query .= $this->whereColumnsArray(columns: $columns, operator: $operator, values: $values),
            // both strings 
            is_string($columns) && is_string($values)
            => $this->query .= "WHERE {$columns} {$operator} '{$values}' ",
            default => throw new \Exception('columns and values must both either be a string or an array'),
        };

        return $this;
    }



    /**
     * This function will deal with the case in which there are arrays sets as 
     * columns and values in the where clause
     * @param array $columns
     * @param array $values
     * @return string which is the formatted WHERE clause ( WHERE included )
     */
    private function whereColumnsArray(array $columns, string $operator, array $values): string
    {

        // missmatch in columns and values we want to check against
        if (count($columns) !== count($values)) {
            throw new \Exception('Columns and values must be of the same length');
        }
        if (in_array('array', array_map('gettype', $values))) {
            throw new \Exception('Array inside of an array is not allowed');
        }

        $arr_clauses = (array_map(function ($column, $value) use ($operator) {
            $this->bindings[':' . $column] = $value;
            return "{$column} {$operator} :{$column} "; // single arguments
        }, $columns, $values));

        $formatted_where_clauses = implode('AND ', $arr_clauses); // adding AND to the arguments

        return 'WHERE ' . $formatted_where_clauses;
    }

    /**
     * This just adds DELETE to the string, can be used with current functions
     */
    public function delete()
    {
        $this->query = "DELETE ";
        return $this;
    }

    /**
     * This function executes the querys
     * @return array|false returns an array of the results or false if there are none
     * this means that we can check if the query was successful by checking if the result is false
     */
    public function get()
    {
        try {
            $stmt = $this->pdo->prepare($this->query);   
            $stmt->execute($this->bindings);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
