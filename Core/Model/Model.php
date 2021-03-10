<?php
    namespace Core\Model;

    use \Illuminate\Database\Eloquent\Model as Eloquent;

    class Model extends Eloquent
    {
        public function queryBuilder($query, $array) {
            foreach ($array as $name => $method) {
                foreach ($method as $row) {
                    $table = isset($row[0]) ? $row[0] : null;
                    $value = !is_array($row) ? $row : $row[1];
    
                    switch ($name) {
                        case 'where':
                            if (isset($table) && isset($value)) {
                                $query->where($table, $value);
                            }
                            break;
                        case 'whereRaw':
                            if (isset($table) && isset($value)) {
                                $query->whereRaw($table, $value);
                            }
                            break;
                        case 'limit':
                            if (isset($value)) {
                                $query->limit($value);
                            }
                            break;
                        case 'offset':
                            if (isset($value)) {
                               $query->offset($value);
                            }
                            break;
                        default:
                            continue 2;
                            break;
                    }
                }
            }

            return $query;
        }
    }