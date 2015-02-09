<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/5/15
 * Time: 7:01 PM
 */
class Crack{
    public $x;
    public $y;
    public $width;
    public $depth;

    function __construct($x,$y,$width,$depth){
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->depth = $depth;
    }
}

class Parser{
    private $file;

    /**
     * Information about this survey
     */
    public $surveyID;
    public $sectionID;
    public $gpsData = array();

    private $cracks = array();

    private static $rootNodeNames = array('RoadSectionInfo','GPSInformation','CrackInformation');

    //image width and height
    private $width;
    private $height;

    //section length, we use this to calculate a factor
    private $length;

    //calculated once RoadSectionInfo is parsed
    private $factor;

    private $offsety = 10;
    private $offsetx = 2;

    function __construct($file, $imageWidth = 1024, $imageHeight = 2500){
        $this->file = $file;
        $this->width  = $imageWidth;
        $this->height = $imageHeight;

        $reader = new XMLReader();
        $reader->open($this->file);

        //skip everything till nodes we are interested in
        while($reader->read()){
            while($reader->read() && !in_array($reader->name,self::$rootNodeNames));

            //skip end elements
            if($reader->nodeType == XMLReader::END_ELEMENT) continue;

            $name = trim($reader->name);
            $method = "parse_$name";
            if(method_exists($this,$method)){
                call_user_func(array($this,$method),$reader);
            }elseif(!empty($name))
                throw new Exception("Missing method '$name'");
        }


    }

    public function getPageWidth(){
        return $this->width;
    }

    public function getPageHeight(){
        return $this->height;
    }

    public function parse_RoadSectionInfo($reader){
        $doc = new DOMDocument();
        $node = simplexml_import_dom($doc->importNode($reader->expand(),true));
        $this->surveyID = $node->SurveyID;
        $this->sectionID = $node->SectionID;
        $this->length = $node->SectionLength_m;

        //now we have enough to calculate a factor
        $this->factor = 1.0/(($this->length / $this->height) * 1000.0); // gives px / mm
    }

    public function parse_GPSInformation($reader){
        $doc = new DOMDocument();
        $node = simplexml_import_dom($doc->importNode($reader->expand(),true));
        foreach($node->GPSCoordinate as $key=>$el){
            array_push($this->gpsData,array($el->Longitude,$el->Latitude));
        }
    }

    public function parse_CrackInformation($reader){
        echo "var cracks = [\n";

        //skip to the Crack node
        while($reader->read() && $reader->name !== 'Crack');

        $comma1 = "\t";
        while($reader->name === 'Crack'){
            $doc = new DOMDocument();
            $node = simplexml_import_dom($doc->importNode($reader->expand(),true));

            $id = $node->CrackID->__toString();
            $depth = $node->WeightedDepth->__toString();
            $width = $node->WeightedWidth->__toString();

            if(count($node->Node) > 0)
                echo "{$comma1}{path:";

            $first = true;
            $key = 0;
            foreach($node->Node as $el){
                if($first){
                    $x = $el->X * $this->factor + $this->offsetx;
                    $y = $this->height - $el->Y * $this->factor - $this->offsety;
                    //$y = $el->Y * $this->factor;

                    echo "\"M $x $y";
                }else{

                    $dx = $el->X * $this->factor + $this->offsetx;
                    $dy = $this->height - $el->Y*$this->factor - $this->offsety;
                    //$dy = $el->Y*$this->factor;

                    echo " L $dx $dy";
                }
                $key++;
                $first = false;
            }

            echo "\",id:\"$id\",depth:$depth,width:$width}";

            if(count($node->Node) > 0)
                echo "\n\t";

            $comma1 = ',';

            //return;

            $reader->next();//the close element
            $reader->next();//the next open element
        }

        //echo "finished reading, made ".count($this->cracks)." elements<br/>";
        echo "\n];";
    }
}