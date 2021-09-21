<?php
Class JapaneseHoliday {
  /**
    googleが提供する休日カレンダーを取得するClass
    **/
  public function __construct($calendarID = 'japanese__ja@holiday.calendar.google.com'){
    $this->calendarID = $calendarID;
  }

  private $startDate = null;
  private $endDate = null;
  private $APIKey = null;
  private $max = 0;
  private $url = null;
  private $calendarID = null;
//  private $calendarID = 'japanese__ja@holiday.calendar.google.com';

  const TIME_STRING = '\T00:00:00\Z';

  public function setStartDate($startDate='2020-01-01'){
    $this->startDate = $startDate;
  }
  public function setEndDate($endDate='2099-12-31'){
    $this->endDate = $endDate;
  }
  public function setAPIKey($apikey=null){
    $this->APIKey = $apikey;
  }
  public function setMax($max=99) {
    $this->max = $max;
  }

  /**
   *  入力日付のバリデーションを行う
   */
  private function validateDateFormat($date){
    if(preg_match('/\A[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}\z/', $date) == false) return false;
    list($year, $month, $day) = explode('-', $date);
    if(checkdate($month, $day, $year) == false) return false;
    return true;
  }
  /*
  メイン
  */
  public function getJSON() {
    //バリデーション
    if(is_null($this->APIKey)) return null;
    if(!$this->validateDateFormat($this->startDate)) return null;
    if(!$this->validateDateFormat($this->endDate)) return null;

    //Query building
    $query = [
        'key' => $this->APIKey,
        'timeMin' => date($this->startDate.self::TIME_STRING),
        'timeMax' => date($this->endDate.self::TIME_STRING),
        'maxResults' => 50,
        'orderBy' => 'startTime',
        'singleEvents' => 'true'
    ];

    $calendar = urlencode($this->calendarID);
    $this->url = "https://www.googleapis.com/calendar/v3/calendars/" . $calendar . "/events?";
    $results = [];

    if ($res = @file_get_contents($this->url.http_build_query($query), true)) {
        if(is_null($res) || !$res) return null;

        //$res = json_encode($res);
        foreach ($res->items as $row) {
            $results[$row->start->date] = $row->summary;
        }
        echo '<pre>';
        var_dump($res);
        echo '</pre>';




    }
    //echo '<pre>';
    //var_dump($results);
    //echo '</pre>';


    //return $results;
  }
}
