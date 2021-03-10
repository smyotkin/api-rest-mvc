<?php
    namespace Api\Controller;

    use \Core\Controller\Controller as Controller;
    use \Core\Controller\Validator as Validate;
    use \Api\Model\Stats as StatsModel;
    use \Api\Model\Earnings as EarningsModel;
    use \Api\Model\Token as TokenModel;
    use \Api\Model\Users as UsersModel;

    class Stats extends Controller
    {
        private $token;

        public function __construct($token) {
            $this->token = $token;
        }
        
        public function get()
        {
            $query = StatsModel::query();

            $get = Validate::filterGet([
                'date'
            ]);

            $sub_uids = $this->getSubs();
            
            $query = $query->select('TStats.club_id as club_ext_id', 'TStats.club_name', 'TLogin.id as user_id', 'TStats.player_id',  'TStats.rate', 'TStats.application_id', 'TStats.date_imported', 'TClubs.id as club_id')
                ->selectRaw('sum(TStats.win_loss) as win_loss')
                ->selectRaw('sum(TStats.rake) as rake')
                ->selectRaw('sum(TStats.wl_dollars) as wl_dollars')
                ->selectRaw('sum(TStats.rake_dollars) as rake_dollars')
                ->join('TClubs', 'TClubs.external_id', '=', 'TStats.club_id')
                ->join('TAccounts', 'TAccounts.external_id', '=', 'TStats.player_id')
                ->join('TLogin', 'TLogin.id', '=', 'TAccounts.user_id')
                ->where('TAccounts.is_deleted', 0);


            if(isset($get['date'])) {
                $query = $query->where('TStats.date_imported', strtotime($get['date']));
            } else {
                $query = $query->whereRaw('TStats.date_imported =(SELECT MAX(TStats.date_imported) FROM TStats)');
            }

            if(!empty($sub_uids)) {
                $query = $query->whereIn('TLogin.id', $sub_uids);
            }

            $stats = $query->groupBy('TClubs.id')->groupBy('TLogin.id')->get()->toArray();

            foreach ($stats as $key => $value) {

                $earnings = $this->getEarnings(array('club_id' => $value['club_id'], 'user_id' =>$value['user_id']), $get);
                
                list($user_details, $user_details_sums) = $this->getUserDetails(array('club_id' => $value['club_id'], 'user_id' =>$value['user_id']), $get);

                $value['earnings'] = $earnings;
                $value['user_details'] = $user_details;
                $value['user_details_sums'] = $user_details_sums;
                
                $stats[$key] = $value;
            }
                                
            $response = [
                'response' => [
                    'count' => count($stats),
                    'items' => $stats
                ]
            ];
            
            \Flight::json($response, $code = 200, $encode = true, $charset = 'utf-8', $option = JSON_PRETTY_PRINT);
        }

        protected function getUserDetails($args, $get = false) {
            $query = StatsModel::query();

            if(isset($get['date'])) {
                $query = $query->where('TStats.date_imported', strtotime($get['date']));
            } else {
                $query = $query->whereRaw('TStats.date_imported =(SELECT MAX(TStats.date_imported) FROM TStats)');
            }

            $query = $query->select('TAccounts.external_id', 'TAccounts.nickname', 'TStats.win_loss', 'TStats.rake',  'TStats.rate', 'TStats.wl_dollars',  'TStats.rake_dollars')
                    ->join('TAccounts', 'TAccounts.external_id', '=', 'TStats.player_id')
                    ->join('TClubs', 'TClubs.external_id', '=', 'TStats.club_id')
                    ->where('TAccounts.is_deleted', 0)
                    ->where('TClubs.id', $args['club_id'])
                    ->where('TAccounts.user_id', $args['user_id']);

            $user_details = $query->get()->toArray();

            $user_details_sums = $this->getUserDetailsSums($user_details);

            return array($user_details, $user_details_sums);
        }

        protected function getUserDetailsSums($user_details) {
            $sums = [];
            foreach ($user_details as $key => $detail) {
                $sums[$key]['win_loss_sum'] += $detail['win_loss'];
                $sums[$key]['rake_sum'] += $detail['rake'];
                $sums[$key]['wl_dollars_sum'] += $detail['wl_dollars'];
                $sums[$key]['rake_dollars_sum'] += $detail['rake_dollars'];

            }

            return $sums;
        }

        protected function getEarnings($args, $get = false) {
            $query = EarningsModel::query();
            $query = $query->select('our_earnings', 'sub_earnings', 'user_earnings')
                ->where('club_id', $args['club_id'])
                ->where('user_id', $args['user_id']);

            if(isset($get['date'])) {
                $query = $query->where('date_imported', strtotime($get['date']));
            } else {
                $query = $query->whereRaw('date_imported =(SELECT MAX(date_imported) FROM TStats_earnings)');
            }

            $earnings = $query->get()->toArray();

            return $earnings;
        }

        protected function getSubs() {
            
            $query = TokenModel::query();
            $user = $query->select('owner')->where('token', $this->token)->first()->toArray();
            $sub_uids = [];

            if($user['owner'] != 807) {
                $sub_uids = $this->CollectTreeRefs($user['owner']);
                array_push($sub_uids, $user['owner']);
            }

            return $sub_uids;
        }

        protected function CollectTreeRefs($uid) {
            $query = UsersModel::query();
            $flag = true;
            $sub_uids = [];
            while($flag) {
                $suid = $query->select('id')->where('parent', $uid)->first();
                
                if(isset($suid)) {
                    $suid = $suid->toArray();
                    $sub_uids[] = $suid['id'];
                    $uid = $suid['id'];
                } else {
                    $flag = false;
                }
            }

            return $sub_uids;
        }
    }