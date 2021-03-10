<?php
    namespace Api\Model;

    use Core\Model\Model;

    use Carbon\Carbon as Carbon;

    class Traffic2 extends Model
    {
        protected $table = 'TRoomsTraffic';
        protected $casts = [
            'union'      => 'int',
            'game_type'  => 'int',
            'chip_limit' => 'int',
            'capacity'   => 'int',
            'date'       => 'int',
            'users'      => 'int',
            'tables'     => 'int',
        ];

        public function getTraffic($validatedParams)
        {
            $date = isset($validatedParams['all_params']['date']) ? Carbon::parse($validatedParams['all_params']['date'])->toDateString() : null;

            $query = self::query();

            $query = $query->join(
                'TChipLimit',
                'TChipLimit.title_en', '=', 'TRoomsTraffic.chip_limits'
            );

            $query->select(
                'TRoomsTraffic.id',
                'TRoomsTraffic.unions AS union',
                'TRoomsTraffic.game_types AS game_type',
                'TChipLimit.id AS chip_limit',
                'TRoomsTraffic.capacity',
                'TRoomsTraffic.date',
                'TRoomsTraffic.users',
                'TRoomsTraffic.tables');

            $query = $this->queryBuilder($query, [
                'where' => [
                    ['TRoomsTraffic.unions', $validatedParams['all_params']['union']],
                    ['TRoomsTraffic.game_types', $validatedParams['all_params']['game_type']],
                    ['TChipLimit.id', $validatedParams['all_params']['chip_limit']],
                ],
                'whereRaw' => [
                    ['DATE(FROM_UNIXTIME(`TRoomsTraffic`.date)) = ?', $date]
                ],
                'limit' => [$validatedParams['all_params']['count']],
                'offset' => [$validatedParams['all_params']['offset']]
            ]);

            return $query;
        }
    }