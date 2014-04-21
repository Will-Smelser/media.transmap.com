<?php
/**
 * Author: Will Smelser
 * Date: 4/20/14
 * Time: 9:29 AM
 * Project: media.transmap.com
 */

class ServiceData {
    private $service;
    private $surveyField = 'Survey';
    private $fields = array();
    private $lastQuery = null;

    public function __construct($arcServiceUrl){
        $this->service = rtrim($arcServiceUrl,'/');
    }

    /**
     * The field list gets passed into here at construction
     * @param $fields
     */
    private function cleanFields(&$fields){
        return;
    }

    private function getJSON($query){
        $this->lastQuery = $this->service . '/0'.$query;

        $content = null;
        try{
            $content = file_get_contents($this->lastQuery);
        }catch(Exception $e){
            return null;
        }

        return json_decode($content,true);
    }

    /**
     * Get back the fields associated with this service
     * @return mixed|null
     */
    public function getFields(){
        if(!empty($this->fields)) return $this->fields;
        $data = $this->getJSON('?f=json');

        foreach($data['fields'] as $entry){
            array_push($this->fields, $entry['name']);
        }
        return $this->fields;
    }

    private function htmlRows(&$arr){
        $html = '';
        $index = 0;
        foreach($arr as $field=>$val){
            $even = ($index%2 == 0) ? 'even' : 'odd';
            $info = $val;
            if(preg_match('@^https?://@i',$val)){
                if(strlen($val) > 80){
                    $info = substr($val,0,45) . '...' . substr($val,-32);
                }
                $info = "<a href='$val' target='_blank'>$info</a>";
            }
            $html .= "\n<tr class='$even'>\n\t<td class='name'>$field</td><td class='value'>$info</td>\n</tr>";
            $index++;
        }
        return $html;
    }

    public function getLastQuery(){
        return $this->lastQuery;
    }

    public function getDataHTML($survey, $uniqueFieldName, $uniqueValue){
        $query = urlencode($this->surveyField . "='$survey' and $uniqueFieldName=$uniqueValue");
        $data = $this->getJSON('/query?f=json&outFields=*&where='.$query);

        //we want to throw an error, but lets make it meaningful
        if($data == null){
            $surveyFieldFound = false;
            $uniqueFieldFound = false;
            $fields = $this->getFields();
            foreach($fields as $key=>$val){
                if($key == $this->surveyField) $surveyFieldFound = true;
                if($key == $uniqueFieldName) $uniqueFieldFound = true;
            }

            if(!$surveyFieldFound)
                throw new HttpQueryStringException("Arc GIS service does not have a '{$this->surveyField}' field.");

            if(!$uniqueFieldFound)
                throw new HttpQueryStringException("Arc GIS service does not the '$uniqueFieldName' unqiue field.");

            throw new Exception("Unknown exception making request to Arc GIS: {$this->lastQuery}");
        }

        //check we did not get an error
        if(isset($data['error']))
            throw new Exception('Arc GIS said: '.$data['error']['message']);

        if(!isset($data['features']))
            throw new Exception("Arc GIS query worked, but no features were returned");

        if(count($data['features']) > 0 && !isset($data['features'][0]['attributes']))
            throw new Exception("Arc GIS query worked, but no attributes were returned");

        $html = '<table><thead><tr><th>Field</th><th>Values</th></tr></thead><tbdoy>';
        if(count($data['features']) < 1){
            $html .= '<tr><td colspan="2">No Data</td></tr>';
        }else{
            foreach($data['features'] as $entry){
                $html .= $this->htmlRows($entry['attributes']);
            }
        }
        return $html .= '</tbody></table>';
    }
} 