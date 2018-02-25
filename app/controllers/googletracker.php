<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 require_once('tracker.php');
class GoogleTracker extends Tracker
    {
        function set_baseurl()
        {
            // use "keyword" and "position" to mark the position of the variables in the url
            $baseurl = "http://www.google.com/search?q=keyword&start=position";
            return $baseurl;
        }
 
        function find($html)
        {
			
            // process the html and return either a numeric value of the position of the site in the current page or FALSE
            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $nodes = $dom->getElementsByTagName('cite');
			
            // found is false by default, we will set it to the position of the site in the results if found
            $found = FALSE;
 
            // start counting the results from the first result in the page
            $current = 1;
            foreach($nodes as $node)
            {
 
                $node = $node->nodeValue;
				
                // look for links that look like this: cmsreport.com › Blogs › Bryan's blog
                if(preg_match('/\s/',$node))
                {
                    $site = explode(' ',$node);
                }
                else
                {
                    $site = explode('/',$node);
                }
 
                $urls[$current] = $site[0];
				/*echo $site[0];
				echo "<br>";
				echo $this->site;*/
                if($site[0] == $this->site)
                {
                    $found = TRUE;
                    $place = $current;
                }
                $current++;
            }
			
            if(isset($found) && $found !== FALSE)
            {
                return $place;
            }
            else
            {
                return FALSE;
            }
        }
 
    }
?>
