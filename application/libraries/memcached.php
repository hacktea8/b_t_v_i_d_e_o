<?php
defined('BASEPATH') || exit('Forbidden');
/*
*
*
*/
class Memcached{
  private $_memcached;	// Holds the memcached object
  public $pre = 'btv';
  protected $_memcache_conf = array(
			      'default' => array(
			      'hostname'=> '127.0.0.1',
			      'port'=> 11211,
			      'weight'=> 1
	  	                                )
				    );

// --------------------------------------------------------
  public function __construct($config = array()){
     $this->_memcached = new Memcache();
     foreach($this->_memcache_conf as $cache_server){
     $this->_memcached->addServer(
                     $cache_server['hostname'], $cache_server['port'], $cache_server['weight']);
//echo '<pre>';var_dump($this->_memcached);exit;
     }

  }
  public function getkey($key){
    return substr($key,0,strlen($this->pre)) == $this->pre?$key:$this->pre.$key;
  }
// ------------------------------------------------------------
  /**
   * Fetch from cache
   *
   * @param 	mixed		unique key id
   * @return 	mixed		data on success/false on failure
   */	
  public function get($id){
     $id = $this->getkey($id);
     $data = $this->_memcached->get($id);		
     return (is_array($data)) ? $data[0] : FALSE;
  }

// --------------------------------------------------------------------

  /**
   * Save
   *
   * @param 	string		unique identifier
   * @param 	mixed		data being cached
   * @param 	int			time to live
   * @return 	boolean 	true on success, false on failure
   */
   public function save($id, $data, $ttl = 60){
      $id = $this->getkey($id);
      if (get_class($this->_memcached) == 'Memcached')
      {
        return $this->_memcached->set($id, array($data, time(), $ttl), $ttl);
      }
      else if (get_class($this->_memcached) == 'Memcache')
      {
	return $this->_memcached->set($id, array($data, time(), $ttl), 0, $ttl);
      }
		
      return FALSE;
    }
   public function set($id, $data, $ttl = 60){
     $this->save($id, $data, $ttl);
   }
// --------------------------------------------------------------------
	
  /**
   * Delete from Cache
   *
   * @param 	mixed		key to be deleted.
   * @return 	boolean 	true on success, false on failure
   */
  public function delete($id){
    $id = $this->getkey($id);
    return $this->_memcached->delete($id);
  }

// -----------------------------------------------------------------
	
  /**
   * Clean the Cache
   *
   * @return 	boolean		false on failure/true on success
   */
  public function clean(){
     return $this->_memcached->flush();
  }

// -------------------------------------------------------------

  /**
   * Cache Info
   *
   * @param 	null		type not supported in memcached
   * @return 	mixed 		array on success, false on failure
   */
  public function cache_info($type = NULL){
     return $this->_memcached->getStats();
  }

  // -----------------------------------------------------------------
	
  /**
   * Get Cache Metadata
   *
   * @param 	mixed		key to get cache metadata on
   * @return 	mixed		FALSE on failure, array on success.
   */
  public function get_metadata($id){
     $id = $this->getkey($id);
     $stored = $this->_memcached->get($id);
     if (count($stored) !== 3){
	return FALSE;
     }

     list($data, $time, $ttl) = $stored;

     return array(
		'expire'=> $time + $ttl,
		'mtime'	=> $time,
		'data'	=> $data
		 );
  }

}
