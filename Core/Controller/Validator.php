<?php
    namespace Core\Controller;

    class Validator
    {
        public static function filterGet($array) {
            $result = [];

            foreach ($array as $param) {
                
                if (isset($_POST[$param])) {
                    $result['method'] = 'POST';
                    $result[$param] = strtolower(htmlspecialchars($_POST[$param]));
                } elseif (isset($_GET[$param])) {
                    $result['method'] = 'GET';
                    $result[$param] = strtolower(htmlspecialchars($_GET[$param]));
                } elseif (isset($_GET[$param]) || isset($_POST[$param])) {
                    $result['method'] = 'GET|POST';
                }
            }

            return !empty($result) ? $result : false;
        }

        public static function token($token) {
            return !empty($token) ? htmlspecialchars($token) : false;
        }

        public static function class($class) {
            return !empty($class) ? ucfirst(htmlspecialchars($class)) : false;
        }

        public static function method($method) {
            return !empty($method) ? htmlspecialchars($method) : false;
        }

        public static function absNumber($getNumber) {
            $number = abs((int) $getNumber);

            return ($number > 0) ? $number : 0;
        }

        public static function count($getCount) {
            $count = (int) $getCount;

            return ($count > 0 && $count <= 1000) ? $count : 100;
        }

        public static function sort($sort) {
            $result = [];
            $default = ['column' => 'id', 'type' => 'asc'];

            if (!empty($sort) && count($data = explode('.', $sort)) == 2) {
                $result['column'] = $data[0];
                $result['type'] = strtolower($data[1]) == 'desc' ? 'desc' : 'asc';
            }

            return !empty($result) ? $result : $default;
        }
    }