<?php

namespace orhanbhr\IddaaBot\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class IddaaBotController
{

    /**
     * @description Default Iddaa.com API Url
     * @var string
     */
    private $apiUrl = 'https://api.iddaa.com/';


    /**
     * @description Default Response Type
     * @descriptipn 0 = Array
     * @description 1 = Json
     * @var int
     */
    private $responseType = 1;

    /**
     * @description Default Language
     * @var string
     */
    private $defaultLanguage = 'en';

    /**
     * @description Default Sport Types
     * @var array
     */
    private $sports = [
        1 => [
            'tr' => 'Futbol',
            'en' => 'Football'
        ],
        2 => [
            'tr' => 'Basketbol',
            'en' => 'Basketball'
        ],
        20 => [
            'tr' => 'Masa Tenisi',
            'en' => 'Table Tennis'
        ],
        4 => [
            'tr' => 'Buz Hokeyi',
            'en' => 'Ice Hockey'
        ],
        6 => [
            'tr' => 'Hentbol',
            'en' => 'Handball'
        ],
        23 => [
            'tr' => 'Voleybol',
            'en' => 'Volleyball'
        ],
        5 => [
            'tr' => 'Tenis',
            'en' => 'Tennis'
        ],
        11 => [
            'tr' => 'Motor Sporları',
            'en' => 'Motor Sports'
        ],
        19 => [
            'tr' => 'Snooker',
            'en' => 'Snooker'
        ]
    ];

    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('iddaabot::matches');
    }

    /**
     * @description Get Match Detail By SportId And EventId
     * @param $sportId
     * @param $eventId
     * @return array|false|string
     */
    public function detail($sportId, $eventId)
    {
        $data = [
            'status' => false,
            'detail' => []
        ];

        // Empty Sport Id
        if (!array_key_exists($sportId, $this->sports))
            return $this->responseType == 1 ? json_encode($data) : $data;

        // Get List
        $getList = $this->getConnect('sportsprogram/markets/' . $sportId . '/' . $eventId);

        if (!empty($getList->isSuccess) && $getList->isSuccess == 1) {

            // Check Empty Data
            if (empty($getList->data))
                return $this->responseType == 1 ? json_encode($data) : $data;

            // Change Response Status
            $data['status'] = true;

            // Set Detail Data
            $data['detail'] = [
                'id' => $getList->data->eid,
                'sport_id' => $getList->data->sid,
                'sport_name' => $this->sports[$getList->data->sid][$this->defaultLanguage],
                'league_name' => trim($getList->data->cn),
                'region_name' => trim($getList->data->ca),
                'event_name' => trim($getList->data->en),
                'start_date' => $getList->data->ed,
                'mbs' => $getList->data->mb,
                'betradar_id' => $getList->data->bid,
                'is_live' => $getList->data->live == true ? 1 : 0,
                'market_count' => $getList->data->mc,
                'participants' => $this->participantsToArray($getList->data->eprt),
                'markets' => $this->marketsToArray($getList->data->m)
            ];
        }

        return $this->responseType == 1 ? json_encode($data) : $data;
    }

    /**
     * @description Get List Match Program
     * @param int $sportId
     * @return array
     */
    public function program($sportId = 1)
    {
        $data = [
            'status' => false,
            'list' => []
        ];

        // Empty Sport Id
        if (!array_key_exists($sportId, $this->sports))
            return $this->responseType == 1 ? json_encode($data) : $data;

        // Get List
        $getList = $this->getConnect('sportsprogram/' . $sportId);

        if (!empty($getList->isSuccess) && $getList->isSuccess == 1) {

            // Check Empty Event List
            if (empty($getList->data->events))
                return $this->responseType == 1 ? json_encode($data) : $data;

            // Change Response Status
            $data['status'] = true;

            foreach ($getList->data->events as $key => $value) {



                $data['list'][] = [
                    'id' => $value->eid,
                    'sport_id' => $value->sid,
                    'sport_name' => $this->sports[$value->sid][$this->defaultLanguage],
                    'league_name' => trim($value->cn),
                    'region_name' => trim($value->ca),
                    'event_name' => trim($value->en),
                    'start_date' => $value->ed,
                    'mbs' => $value->mb,
                    'betradar_id' => $value->bid,
                    'is_live' => $value->live == true ? 1 : 0,
                    'market_count' => $value->mc,
                    'participants' => $this->participantsToArray($value->eprt),
                    'markets' => $this->marketsToArray($value->m)
                ];
            }
        }

        return $this->responseType == 1 ? json_encode($data) : $data;
    }

    /**
     * @description Participants To Array Method
     * @param $data
     * @return array
     */
    private function participantsToArray($data)
    {
        $participants = [];

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $participants[] = [
                    'short_name' => trim($value->acr),
                    'long_name' => trim($value->pn)
                ];
            }
        }

        return $participants;
    }

    /**
     * @param $className
     * @return string
     */
    private function convertRateClass($className)
    {
        switch ($className) {
            case 'd':
                return 'down';

            case 'u':
                return 'up';

            default:
                return 'empty';
        }
    }

    /**
     * @description Rates To Array Method
     * @param $data
     * @return array
     */
    private function ratesToArray($data)
    {
        $rates = [];

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $rates[] = [
                    'id' => (int)trim($value->ov),
                    'name' => (string)trim($value->ona),
                    'no' => (string)trim($value->sono),
                    'rate' => (float)trim($value->odd),
                    'start_rate' => (float)trim($value->sodd),
                    'class' => !empty($value->cs) ? $this->convertRateClass($value->cs) : null
                ];
            }
        }

        return $rates;
    }

    /**
     * @description Markets To Array Method
     * @param $data
     * @return array
     */
    private function marketsToArray($data)
    {
        $markets = [];

        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $markets[] = [
                    'id' => (int)trim($value->mid),
                    'name' => (string)trim($value->mn),
                    'no' => (int)trim($value->mno),
                    'mbs' => (int)trim($value->mbs),
                    'rates' => $this->ratesToArray($value->o)
                ];
            }
        }

        return $markets;
    }

    /**
     * @description Change Lang
     * @param string $lang
     * @return $this
     */
    public function lang($lang = 'en')
    {
        $supportedLang = ['tr', 'en'];

        // Check Supported Languages
        if (in_array($lang, $supportedLang)) {
            $this->defaultLanguage = $lang;
        }

        return $this;
    }

    /**
     * @description Change Response Type
     * @param int $typeId
     * @return $this
     */
    public function responseType($typeId = 1)
    {
        $this->responseType = $typeId;

        return $this;
    }

    /**
     * @description Website Connect GET Methods
     * @param $url
     * @return mixed
     */
    private function getConnect($url)
    {
        $ch = curl_init();

        // CURL options
        $options = array(
            CURLOPT_URL => $this->apiUrl . $url,
            CURLOPT_POST => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HEADER => false
        );
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

}
