<?php
	#Expiration set to 20 minutes or 1200 seconds.
	#Connection constants
	define('MEMCACHED_HOST', '127.0.0.1');
	define('MEMCACHED_PORT', '11211');
    # Connect to memcache:
    global $memcache;
    $memcache = new Memcache;
	$cacheAvailable = $memcache->connect(MEMCACHED_HOST, MEMCACHED_PORT);
	if ($cacheAvailable != true){
		mail("cj3wilso@gmail.com","Memcache down","Fix it");
	}
	
    # Gets key / value pair into memcache ... called by mysql_query_cache()
    function getCache($key) {
        global $memcache;
        return ($memcache) ? $memcache->get($key) : false;
    }

    # Puts key / value pair into memcache ... called by mysql_query_cache()
    function setCache($key,$object,$timeout = 1200) {
        global $memcache;
        return ($memcache) ? $memcache->set($key,$object,MEMCACHE_COMPRESSED,$timeout) : false;
    }

    # Caching version of mysql_query()
    function mysql_query_cache($sql,$timeout = 1200) {
		global $memcache, $conn;
		$cache = getCache(md5("mysql_query" . $sql));
		if ($cache === false) {
			#mysqli connection
			if(!isset($conn) && get_class($var) != 'mysqli'){
				require_once("mysqli-connect.php");
			}
			$cache = false;
            $r = $conn->query($sql);
			$rows = $r->num_rows;
			if($rows==0){ return NULL; }
			echo '<!-- Calling from database -->';
			if ($r instanceof mysqli_result && $rows !== 0) {
                for ($i=0;$i<$rows;$i++) {
                    $fields = $r->field_count;
                    $row = $r->fetch_array(MYSQLI_BOTH);
                    for ($j=0;$j<$fields;$j++) {
                        if ($i === 0) {
                            $finfo = $r->fetch_field_direct($j);
							$columns[$j] = $finfo->name;
                        }
                        $cache[$i][$columns[$j]] = $row[$j];
                    }
                }
				$setCache = setCache(md5("mysql_query" . $sql),$cache,$timeout);
                if ($setCache === false) {
                    # If we get here, there isn't a memcache daemon running or responding
					mail("cj3wilso@gmail.com","Memcache down","there isn't a memcache daemon running or responding".$sql);
                }
            }
        }
        return $cache;
    }
?>