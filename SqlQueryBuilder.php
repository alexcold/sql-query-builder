<?php

/**
 * The simple sql query builder
 * 
 * @author Aleksandr Cerkasov <coldworld@gmail.com>
 */
class SqlQueryBuilder {
    /**
     * @var
     */
    private $_select;

    /**
     * @var
     */
    private $_from;

    /**
     * @var
     */
    private $_where;

    /**
     * @var
     */
    private $_join;

    /**
     * @var
     */
    private $_orderBy;

    /**
     * @var
     */
    private $_groupBy;

    /**
     * @var
     */
    private $_having;

    /**
     * @var
     */
    private $_limit;

    /**
     * @param string $selectQuery
     * @return $this
     */
    public function select ($selectQuery = '*') {
        $this->_select = 'SELECT ' . trim($selectQuery);
        
        return $this;
    }

    /**
     * @param $fromQuery
     * @return $this
     */
    public function from ($fromQuery) {
        if (!empty($fromQuery)) {
            $this->_from = 'FROM ' . trim($fromQuery);
        }
        
        return $this;
    }

    /**
     * @param $whereQuery
     * @param array $whereQueryParams
     * @return $this
     */
    public function where ($whereQuery, $whereQueryParams = []) {
        if (empty($this->_where)) {
            $this->_where = 'WHERE ' . $this->buildWhereQuery(
                $whereQuery, $whereQueryParams
            );
        }
        
        return $this;
    }

    /**
     * @param $andWhereQuery
     * @param array $whereQueryParams
     * @return $this
     */
    public function andWhere ($andWhereQuery, $whereQueryParams = []) {
        $and = ' AND ';
        if (empty($this->_where)) {
            $and = 'WHERE ';
        }
        
        $this->_where .= $and .  $this->buildWhereQuery(
            $andWhereQuery, $whereQueryParams
        );
        
        return $this;
    }

    /**
     * @param $orWhereQuery
     * @param array $whereQueryParams
     * @return $this
     */
    public function orWhere ($orWhereQuery, $whereQueryParams = []) {
        $or = ' OR ';
        if (empty($this->_where)) {
            $or = 'WHERE ';
        }
        
        $this->_where .= $or .  $this->buildWhereQuery(
            $orWhereQuery, $whereQueryParams
        );
        
        return $this;
    }

    /**
     * @param $whereQuery
     * @param array $whereQueryParams
     * @return string
     */
    private function buildWhereQuery ($whereQuery, $whereQueryParams = []) {
        if (count($whereQueryParams) > 0) {
            $this->addParams($whereQuery, $whereQueryParams);
        }
        
        return trim($whereQuery);
    }

    /**
     * @param $joinQuery
     * @return $this
     */
    public function join ($joinQuery) {
        if (!empty($joinQuery)) {
            $this->_join .= trim($joinQuery) . ' ';
        }
        
        return $this;
    }

    /**
     * @param $groupByQuery
     * @return $this
     */
    public function groupBy ($groupByQuery) {
        if (!empty($groupByQuery)) {
            $this->_groupBy = 'GROUP BY ' . trim($groupByQuery);
        }
        
        return $this;
    }

    /**
     * @param $orderByQuery
     * @return $this
     */
    public function orderBy ($orderByQuery) {
        if (!empty($orderByQuery)) {
            $this->_orderBy = 'ORDER BY ' . trim($orderByQuery);
        }
        
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit ($limit) {
        if (!empty($limit)) {
            $this->_limit = 'LIMIT ' . (int) trim($limit);
        }
        
        return $this;
    }

    /**
     * @param $havingQuery
     * @param $havingWhereQueryParams
     * @return $this
     */
    public function having ($havingQuery, $havingWhereQueryParams) {
        if (!empty($havingQuery)) {
            $this->_having = 'HAVING ' . $this->buildWhereQuery(
                $havingQuery, $havingWhereQueryParams    
            );
        }
        
        return $this;
    }

    /**
     * @param $query
     * @param $params
     */
    private function addParams (&$query, $params) {
        foreach ($params as $key => $value) {
            $query = str_replace("{$key}", $this->stringEscape($value), $query);
        }
    }

    /**
     * @param $string
     * @return array|mixed
     */
    public function stringEscape ($string) {
        if(is_array($string)) 
            return array_map(__METHOD__, $string); 

        if(!empty($value) && is_string($string)) { 
            return str_replace(
                array('\\', "\0", "\n", "\r", "'", '"', "\x1a"),
                array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), 
                $string
            ); 
        } 
    
        return $string; 
    }

    /**
     * @return string
     */
    public function getQueryText () {
        return $this->_select
            . ' ' . $this->_from
            . ' ' . $this->_join
            . ' ' . $this->_where
            . ' ' . $this->_groupBy
            . ' ' . $this->_having
            . ' ' . $this->_orderBy
            . ' ' . $this->_limit;
    }
}
?>