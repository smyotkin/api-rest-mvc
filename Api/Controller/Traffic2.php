<?php
    namespace Api\Controller;

    use \Core\Controller\Controller as Controller;

    use \Api\Model\Traffic2 as TrafficModel;

    class Traffic2 extends Controller
    {
        public function get()
        {
            $validated = $this->validateParams($_GET + $_POST, $availableParams = [
                'union'      => ['required', 'numeric', ['min', 0]],
                'date'       => ['required', ['dateFormat', 'd.m.Y'], ['dateAfter', '2000-01-01']],
                'offset'     => ['numeric', ['min', 0]],
                'count'      => ['numeric', ['min', 0], ['max', 1000]],
                'game_type'  => ['numeric', ['min', 0]],
                'chip_limit' => ['numeric', ['min', 0]],
                'report'     => [['in', ['all', 'errors', 'notice', 'params', 'sql']]]
            ], $default = ['offset' => 0, 'count'  => 1000]);

            $model = new TrafficModel();
            $query = $model->getTraffic($validated);
            
            if ($validated['valid']) {
                $result = $query->get()->toArray();
                
                if (!empty($validated['valid_params']) && isset($validated['valid_params']['report']) && in_array($validated['valid_params']['report'], ['all', 'params']))
                    $response['response']['params'] = $validated['valid_params'];
        
                if (!empty($validated['errors']))
                    $response['response']['errors'] = $validated['errors'];
        
                if (!empty($validated['notice']) && isset($validated['valid_params']['report']) && in_array($validated['valid_params']['report'], ['all', 'notice']))
                    $response['response']['notice'] = $validated['notice'];

                if (isset($validated['valid_params']['report']) && in_array($validated['valid_params']['report'], ['all', 'sql']))
                    $response['response']['sql'] = vsprintf(str_replace(array('?'), array('\'%s\''), $query->toSql()), $query->getBindings());
        
                $response['response']['count'] = count($result);
                $response['response']['items'] = $result;
            } else {
                $response['response']['errors'] = $validated['errors'];
            }

            // echo '<pre>';
            // echo json_encode($response, JSON_PRETTY_PRINT);
            // echo '</pre>';
        
            \Flight::json($response, $code = 200, $encode = true, $charset = 'utf-8', $option = JSON_PRETTY_PRINT);
        }
    }