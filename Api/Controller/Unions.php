<?php
    namespace Api\Controller;

    use \Core\Controller\Controller as Controller;
    use \Core\Controller\Validator as Validate;
    use \Api\Model\Unions as UnionsModel;

    class Unions extends Controller
    {
        public function get()
        {
            $query = UnionsModel::query();

            $query = $query->join(
                'TNetworks',
                'TNetworks.id', '=', 'TUnions.networks'
            );

            $query = $query->select(
                'TUnions.id',
                'TUnions.title_en AS title',
                'TNetworks.title_en AS network'
            );

            $query = $query->orderBy('TUnions.id');

            $clubs = $query->get()->toArray();
                                
            $response = [
                'response' => [
                    'count' => count($clubs),
                    'items' => $clubs
                ]
            ];
            
            \Flight::json($response, $code = 200, $encode = true, $charset = 'utf-8', $option = JSON_PRETTY_PRINT);
        }
    }