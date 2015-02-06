<?php
/**
 * Created by PhpStorm.
 * User: Will2
 * Date: 2/5/15
 * Time: 7:01 PM
 */

class Parser{
    private $file;

    private static $rootNodeNames = ['CrackInformation'];

    function __construct($file){
        $this->file = $file;

        $reader = new XMLReader();
        $reader->open($this->file);

        //skip everything till nodes we are interested in
        while($reader->read()){
            while($reader->read() && !in_array($reader->name,self::$rootNodeNames));

            //skip end elements
            if($reader->nodeType == XMLReader::END_ELEMENT) continue;

            $name = trim($reader->name);
            $method = "parse_$name";
            if(method_exists($this,$method))
                call_user_func(array($this,$method),$reader);
            elseif(!empty($name))
                echo "Method does not exist: $method<br/>";
        }


    }

    public function parse_CrackInformation($reader){
        //skip to the Crack node
        while($reader->read() && $reader->name !== 'Crack');

        while($reader->name === 'Crack'){
            $doc = new DOMDocument();
            $node = simplexml_import_dom($doc->importNode($reader->expand(),true));

            $id = $node->CrackID->__toString();
            echo "Crack: $id<br/>";

            $reader->next();//the close element
            $reader->next();//the next open element
        }

        echo "finished reading";
    }
}