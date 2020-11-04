<?php

namespace orhanbhr\IddaaBot\Controllers;

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

    public function index()
    {


        echo '<pre>';
        print_r($this->program(2));
        die;

        return view('iddaabot::matches');
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

            // Change Response Status
            $data['status'] = true;

            foreach ($getList->data->events as $key => $value) {

                // Participants To Array
                $participants = [];

                if (!empty($value->eprt)) {
                    foreach ($value->eprt as $participantKey => $participantValue) {
                        $participants[] = [
                            'short_name' => trim($participantValue->acr),
                            'long_name' => trim($participantValue->pn)
                        ];
                    }
                }

                $data['list'][] = [
                    'id' => $value->eid,
                    'sport_id' => $value->sid,
                    'sport_name' => $this->sports[$value->sid][$this->defaultLanguage],
                    'league_name' => $value->cn,
                    'region_name' => $value->ca,
                    'event_name' => $value->en,
                    'start_date' => $value->ed,
                    'mbs' => $value->mb,
                    'betradar_id' => $value->bid,
                    'is_live' => $value->live == true ? 1 : 0,
                    'market_count' => $value->mc,
                    'participants' => $participants
                ];
            }
        }

        return $this->responseType == 1 ? json_encode($data) : $data;
    }

    /**
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
